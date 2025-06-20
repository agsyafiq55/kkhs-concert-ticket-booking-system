<?php

namespace App\Livewire\Teacher;

use App\Mail\Emailer;
use App\Models\Concert;
use App\Models\Ticket;
use App\Models\TicketPurchase;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
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
    
    protected $rules = [
        'selectedStudentId' => 'required|exists:users,id',
        'selectedTicketId' => 'required|exists:tickets,id',
        'quantity' => 'required|integer|min:1',
        'paymentReceived' => 'required|accepted',
    ];
    
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
        $this->paymentReceived = false; // Reset payment confirmation when changing ticket
        $this->resetValidation(['selectedTicketId', 'paymentReceived', 'quantity']);
    }
    
    public function getSubtotalProperty()
    {
        if ($this->selectedTicketId && $this->quantity) {
            $ticket = Ticket::find($this->selectedTicketId);
            if ($ticket) {
                return $ticket->price * $this->quantity;
            }
        }
        return 0;
    }
    
    public function assignTicket()
    {
        // Add custom validation for quantity vs available tickets
        $ticket = Ticket::findOrFail($this->selectedTicketId);
        $this->rules['quantity'] = 'required|integer|min:1|max:' . $ticket->remaining_tickets;
        
        $this->validate();
        
        // Double-check if the selected ticket still has enough available quantity
        if ($ticket->remaining_tickets < $this->quantity) {
            $this->addError('quantity', 'Only ' . $ticket->remaining_tickets . ' tickets are available.');
            return;
        }
        
        $createdPurchases = [];
        $qrCodeImages = [];
        
        try {
            // Create multiple ticket purchase records (one for each ticket)
            for ($i = 0; $i < $this->quantity; $i++) {
                // Generate unique QR code data for each ticket
                $qrCodeData = $this->generateQrCodeData($ticket, $i + 1);
                
                // Create the ticket purchase record
                $ticketPurchase = TicketPurchase::create([
                    'ticket_id' => $this->selectedTicketId,
                    'student_id' => $this->selectedStudentId,
                    'teacher_id' => Auth::id(),
                    'purchase_date' => now(),
                    'qr_code' => $qrCodeData,
                    'status' => 'valid',
                ]);
                
                $createdPurchases[] = $ticketPurchase;
                
                // Generate QR code image for each ticket
                try {
                    $qrCodeImages[] = base64_encode(QrCode::format('svg')
                        ->size(200)
                        ->errorCorrection('H')
                        ->generate($qrCodeData));
                } catch (\Exception $e) {
                    // Fallback if QR code generation fails
                    $qrCodeImages[] = null;
                }
            }
            
            // Send email notification to the student with all purchased tickets
            try {
                // Load all ticket purchases with all necessary relationships for the email
                $ticketPurchasesWithRelations = TicketPurchase::with([
                    'student', 
                    'teacher', 
                    'ticket.concert'
                ])->whereIn('id', collect($createdPurchases)->pluck('id'))->get();
                
                // Send email with all purchase details
                Mail::to($ticketPurchasesWithRelations->first()->student->email)->send(new Emailer($ticketPurchasesWithRelations));
            } catch (\Exception $e) {
                // Log the error but don't stop the process
                // You can add logging here if needed
                // Log::error('Failed to send ticket email: ' . $e->getMessage());
            }
            
            // Reset selections and show success message
            $this->lastAssignedQrCode = $createdPurchases[0]->qr_code;
            $this->lastQrCodeImages = $qrCodeImages;
            $this->lastPurchases = $createdPurchases;
            $this->lastPurchasedQuantity = $this->quantity;
            $this->ticketAssigned = true;
            $this->selectedTicketId = null;
            $this->quantity = 1;
            $this->paymentReceived = false;
            
            // Reset pagination to show the student's updated tickets
            $this->resetPage();
            
        } catch (\Exception $e) {
            // If something went wrong, rollback any created purchases
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
        $this->resetValidation();
    }
    
    /**
     * Generate a unique QR code string for the ticket
     */
    protected function generateQrCodeData(Ticket $ticket, int $sequenceNumber = 1): string
    {
        // Create a unique string that can be verified later
        $uniqueId = (string) Str::uuid();
        $timestamp = now()->timestamp;
        $ticketId = $ticket->id;
        $studentId = $this->selectedStudentId;
        $teacherId = Auth::id();
        
        // Include sequence number for multiple tickets
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
        
        // Get tickets available for assignment
        $ticketsQuery = Ticket::query()
            ->with('concert')
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
