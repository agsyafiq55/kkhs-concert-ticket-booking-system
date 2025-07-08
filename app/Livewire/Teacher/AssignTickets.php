<?php

namespace App\Livewire\Teacher;

use App\Mail\Emailer;
use App\Models\Concert;
use App\Models\Ticket;
use App\Models\TicketPurchase;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithPagination;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class AssignTickets extends Component
{
    use WithPagination;
    
    public $search = '';
    public $selectedStudentId = null;
    public $selectedTicketId = null;
    public $quantity = 1;
    public $concertFilter = '';
    public $ticketAssigned = false;
    public $lastAssignedQrCode = null;
    public $lastQrCodeImages = [];
    public $lastPurchasedQuantity = 0;
    public $lastPurchases = [];
    public $paymentReceived = false;
    
    // Cart system properties
    public $cart = [];
    public $showCart = false;
    
    protected $rules = [
        'selectedStudentId' => 'required|exists:users,id',
        'paymentReceived' => 'required|accepted',
    ];
    
    public function mount()
    {
        // Check if user has permission to assign tickets
        if (!Gate::allows('assign tickets')) {
            abort(403, 'You do not have permission to assign tickets.');
        }
    }
    
    public function updatingSearch()
    {
        $this->resetPage();
    }
    
    public function updatingConcertFilter()
    {
        $this->resetPage();
        $this->selectedTicketId = null;
        $this->quantity = 1;
    }
    
    public function updatingSelectedTicketId()
    {
        $this->quantity = 1;
        $this->paymentReceived = false;
    }
    
    public function selectStudent($studentId)
    {
        $this->selectedStudentId = $studentId;
        $this->resetValidation('selectedStudentId');
    }
    
    public function selectTicket($ticketId)
    {
        $this->selectedTicketId = $ticketId;
        $this->quantity = 1;
        $this->paymentReceived = false;
        $this->resetValidation(['selectedTicketId', 'paymentReceived', 'quantity']);
    }
    
    public function addToCart($ticketId, $quantity = null)
    {
        $quantity = $quantity ?? $this->quantity;
        
        if ($quantity <= 0) {
            return;
        }
        
        $ticket = Ticket::with('concert')->find($ticketId);
        if (!$ticket) {
            return;
        }
        
        // Check if ticket already exists in cart
        $existingIndex = null;
        foreach ($this->cart as $index => $item) {
            if ($item['ticket_id'] == $ticketId) {
                $existingIndex = $index;
                break;
            }
        }
        
        if ($existingIndex !== null) {
            // Update existing item quantity
            $newQuantity = $this->cart[$existingIndex]['quantity'] + $quantity;
            if ($newQuantity <= $ticket->remaining_tickets) {
                $this->cart[$existingIndex]['quantity'] = $newQuantity;
                $this->cart[$existingIndex]['subtotal'] = $newQuantity * $ticket->price;
            } else {
                $this->addError('cart', 'Cannot add more tickets. Only ' . $ticket->remaining_tickets . ' tickets available.');
                return;
            }
        } else {
            // Add new item to cart
            if ($quantity <= $ticket->remaining_tickets) {
                $this->cart[] = [
                    'ticket_id' => $ticket->id,
                    'ticket_type' => $ticket->ticket_type,
                    'concert_title' => $ticket->concert->title,
                    'concert_date' => $ticket->concert->date->format('M d, Y'),
                    'concert_time' => $ticket->concert->start_time->format('g:i A'),
                    'price' => $ticket->price,
                    'quantity' => $quantity,
                    'subtotal' => $quantity * $ticket->price,
                    'available_tickets' => $ticket->remaining_tickets,
                ];
            } else {
                $this->addError('cart', 'Cannot add tickets. Only ' . $ticket->remaining_tickets . ' tickets available.');
                return;
            }
        }
        
        // Reset the selection after adding to cart
        $this->selectedTicketId = null;
        $this->quantity = 1;
        $this->showCart = true;
        
        session()->flash('cart-message', 'Ticket added to cart!');
    }
    
    public function updateCartQuantity($index, $newQuantity)
    {
        if ($newQuantity <= 0) {
            $this->removeFromCart($index);
            return;
        }
        
        $ticket = Ticket::find($this->cart[$index]['ticket_id']);
        if (!$ticket) {
            $this->removeFromCart($index);
            return;
        }
        
        if ($newQuantity <= $ticket->remaining_tickets) {
            $this->cart[$index]['quantity'] = $newQuantity;
            $this->cart[$index]['subtotal'] = $newQuantity * $this->cart[$index]['price'];
            $this->cart[$index]['available_tickets'] = $ticket->remaining_tickets;
        } else {
            $this->addError('cart', 'Cannot update quantity. Only ' . $ticket->remaining_tickets . ' tickets available.');
        }
    }
    
    public function removeFromCart($index)
    {
        unset($this->cart[$index]);
        $this->cart = array_values($this->cart); // Reindex array
        
        if (empty($this->cart)) {
            $this->showCart = false;
        }
    }
    
    public function clearCart()
    {
        $this->cart = [];
        $this->showCart = false;
        $this->paymentReceived = false;
    }
    
    public function getCartTotalProperty()
    {
        return collect($this->cart)->sum('subtotal');
    }
    
    public function getCartItemCountProperty()
    {
        return collect($this->cart)->sum('quantity');
    }
    
    public function getSubtotalProperty()
    {
        // For backwards compatibility with single ticket selection
        if ($this->selectedTicketId && $this->quantity) {
            $ticket = Ticket::find($this->selectedTicketId);
            if ($ticket) {
                return $ticket->price * $this->quantity;
            }
        }
        return $this->cartTotal;
    }
    

    
    public function assignTicket()
    {
        $this->validate();
        
        if (empty($this->cart)) {
            $this->addError('cart', 'Cart is empty. Please add tickets to cart first.');
            return;
        }
        
        $createdPurchases = [];
        $totalQuantity = 0;
        
        try {
            // Process each item in the cart
            foreach ($this->cart as $cartItem) {
                $ticket = Ticket::findOrFail($cartItem['ticket_id']);
                
                // Double-check availability
                if ($ticket->remaining_tickets < $cartItem['quantity']) {
                    $this->addError('cart', "Not enough tickets available for {$cartItem['ticket_type']}. Only {$ticket->remaining_tickets} remaining.");
                    return;
                }
                
                // Create multiple ticket purchase records for this ticket type
                for ($i = 0; $i < $cartItem['quantity']; $i++) {
                    $qrCodeData = $this->generateQrCodeData($ticket, $totalQuantity + $i + 1);
                    
                    $ticketPurchase = TicketPurchase::create([
                        'ticket_id' => $cartItem['ticket_id'],
                        'student_id' => $this->selectedStudentId,
                        'teacher_id' => Auth::id(),
                        'purchase_date' => now(),
                        'qr_code' => $qrCodeData,
                        'status' => 'valid',
                    ]);
                    
                    $createdPurchases[] = $ticketPurchase;
                    
                    // For display purposes, generate base64 SVG (kept for UI display)
                    try {
                        $this->lastQrCodeImages[] = base64_encode(QrCode::format('svg')
                            ->size(200)
                            ->errorCorrection('H')
                            ->generate($qrCodeData));
                    } catch (\Exception $e) {
                        $this->lastQrCodeImages[] = null;
                    }
                }
                
                $totalQuantity += $cartItem['quantity'];
            }
            
            // Send email notification
            try {
                $ticketPurchasesWithRelations = TicketPurchase::with([
                    'student', 
                    'teacher', 
                    'ticket.concert'
                ])->whereIn('id', collect($createdPurchases)->pluck('id'))->get();
                
                Mail::to($ticketPurchasesWithRelations->first()->student->email)->send(new Emailer($ticketPurchasesWithRelations));
            } catch (\Exception $e) {
                // Log error but don't stop the process
                Log::error('Email sending failed: ' . $e->getMessage());
            }
            
            // Set success state
            $this->lastAssignedQrCode = $createdPurchases[0]->qr_code ?? null;
            $this->lastPurchases = $createdPurchases;
            $this->lastPurchasedQuantity = $totalQuantity;
            $this->ticketAssigned = true;
            
            // Clear cart and reset form
            $this->clearCart();
            $this->selectedTicketId = null;
            $this->quantity = 1;
            $this->paymentReceived = false;
            $this->resetPage();
            
        } catch (\Exception $e) {
            // Rollback any created purchases
            foreach ($createdPurchases as $purchase) {
                $purchase->delete();
            }
            
            $this->addError('general', 'An error occurred while assigning tickets. Please try again.');
        }
    }
    
    public function resetForm()
    {
        $this->selectedStudentId = null;
        $this->selectedTicketId = null;
        $this->quantity = 1;
        $this->ticketAssigned = false;
        $this->lastAssignedQrCode = null;
        $this->lastQrCodeImages = [];
        $this->lastPurchases = [];
        $this->lastPurchasedQuantity = 0;
        $this->paymentReceived = false;
        $this->clearCart();
        $this->resetValidation();
    }
    
    /**
     * Generate a unique QR code string for the ticket
     */
    protected function generateQrCodeData(Ticket $ticket, int $sequenceNumber = 1): string
    {
        $uniqueId = (string) Str::uuid();
        $timestamp = now()->timestamp;
        $ticketId = $ticket->id;
        $studentId = $this->selectedStudentId;
        $teacherId = Auth::id();
        
        $qrData = "KKHS-CONCERT-{$uniqueId}-{$timestamp}-{$ticketId}-{$studentId}-{$teacherId}-{$sequenceNumber}";
        
        return $qrData;
    }
    
    public function render()
    {
        // Get students with the 'student' role
        $students = User::role('student')
            ->where('name', 'like', '%' . $this->search . '%')
            ->orWhere('email', 'like', '%' . $this->search . '%')
            ->paginate(10);
        
        // Get tickets available for assignment (only regular tickets)
        $ticketsQuery = Ticket::query()
            ->with('concert')
            ->regular() // Only show regular tickets for teacher assignment
            ->when($this->concertFilter, function ($query) {
                return $query->where('concert_id', $this->concertFilter);
            })
            ->whereRaw('quantity_available > (SELECT COUNT(*) FROM ticket_purchases WHERE ticket_id = tickets.id AND status != "cancelled")');
        
        $tickets = $ticketsQuery->get();
        
        // Get concerts for filter dropdown
        $concerts = Concert::orderBy('date')->get();
        
        // Get selected student's tickets if a student is selected
        $studentTickets = [];
        if ($this->selectedStudentId) {
            $studentTickets = TicketPurchase::with(['ticket.concert'])
                ->where('student_id', $this->selectedStudentId)
                ->orderBy('purchase_date', 'desc')
                ->get();
        }
        
        return view('livewire.teacher.assign-tickets', [
            'students' => $students,
            'tickets' => $tickets,
            'concerts' => $concerts,
            'studentTickets' => $studentTickets,
            'selectedStudent' => $this->selectedStudentId ? User::find($this->selectedStudentId) : null,
        ]);
    }
}
