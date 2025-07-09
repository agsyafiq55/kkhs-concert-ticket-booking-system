<?php

namespace App\Livewire\Admin;

use App\Mail\Emailer;
use App\Models\Concert;
use App\Models\Ticket;
use App\Models\TicketPurchase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithPagination;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class VipTicketSales extends Component
{
    use WithPagination;

    public $vipName = '';

    public $vipEmail = '';

    public $vipPhone = '';

    public $selectedTicketId = null;

    public $quantity = 1;

    public $concertFilter = '';

    public $ticketsSold = false;

    public $lastSoldTickets = [];

    public $lastQrCodeImages = [];

    public $lastSoldQuantity = 0;

    public $paymentReceived = false;

    // Cart system properties
    public $cart = [];

    public $showCart = false;

    protected $rules = [
        'vipName' => 'required|string|max:255',
        'vipEmail' => 'required|email|max:255',
        'vipPhone' => 'required|string|max:20',
        'paymentReceived' => 'required|accepted',
    ];

    public function mount()
    {
        // Check if user has permission to sell VIP tickets
        if (! Gate::allows('sell vip tickets')) {
            abort(403, 'You do not have permission to sell VIP tickets.');
        }
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
        if (! $ticket) {
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
                $this->addError('cart', 'Cannot add more tickets. Only '.$ticket->remaining_tickets.' tickets available.');

                return;
            }
        } else {
            // Add new item to cart
            if ($quantity <= $ticket->remaining_tickets) {
                $this->cart[] = [
                    'ticket_id' => $ticketId,
                    'ticket_type' => $ticket->ticket_type,
                    'concert_title' => $ticket->concert->title,
                    'concert_date' => $ticket->concert->date->format('M d, Y'),
                    'price' => $ticket->price,
                    'quantity' => $quantity,
                    'subtotal' => $quantity * $ticket->price,
                ];
                $this->showCart = true;
            } else {
                $this->addError('cart', 'Cannot add tickets. Only '.$ticket->remaining_tickets.' tickets available.');

                return;
            }
        }

        session()->flash('cart-message', "Added {$quantity} {$ticket->ticket_type} ticket(s) to cart");
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
        // Only reset paymentReceived if we're not in success state
        if (! $this->ticketsSold) {
            $this->paymentReceived = false;
        }
    }

    public function getCartTotalProperty()
    {
        return collect($this->cart)->sum('subtotal');
    }

    public function getCartItemCountProperty()
    {
        return collect($this->cart)->sum('quantity');
    }

    public function sellVipTickets()
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
                        'student_id' => null, // No student for VIP tickets
                        'teacher_id' => Auth::id(),
                        'purchase_date' => now(),
                        'qr_code' => $qrCodeData,
                        'status' => 'valid',
                        // Removed is_walk_in and is_vip - ticket type determined by ticket relationship
                        'is_sold' => true, // VIP tickets are always sold
                        'vip_name' => $this->vipName,
                        'vip_email' => $this->vipEmail,
                        'vip_phone' => $this->vipPhone,
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

            // Send email notification to VIP customer
            try {
                $ticketPurchasesWithRelations = TicketPurchase::with([
                    'teacher',
                    'ticket.concert',
                ])->whereIn('id', collect($createdPurchases)->pluck('id'))->get();

                Mail::to($this->vipEmail)->send(new Emailer($ticketPurchasesWithRelations));
            } catch (\Exception $e) {
                // Log error but don't stop the process
                Log::error('VIP email sending failed: '.$e->getMessage());
            }

            // Clear cart first, then set success state
            $this->clearCart();

            // Set success state
            $this->lastSoldTickets = $createdPurchases;
            $this->lastSoldQuantity = $totalQuantity;
            $this->ticketsSold = true;

            // Reset form fields (but preserve success state)
            $this->resetFormFields();
            $this->resetPage();

        } catch (\Exception $e) {
            // Rollback any created purchases
            foreach ($createdPurchases as $purchase) {
                $purchase->delete();
            }

            $this->addError('general', 'An error occurred while selling VIP tickets. Please try again.');
        }
    }

    /**
     * Reset only the form fields without clearing success state
     */
    public function resetFormFields()
    {
        $this->vipName = '';
        $this->vipEmail = '';
        $this->vipPhone = '';
        $this->selectedTicketId = null;
        $this->quantity = 1;
        $this->paymentReceived = false;
        $this->resetValidation();
    }

    public function resetForm()
    {
        $this->resetFormFields();
        $this->ticketsSold = false;
        $this->lastSoldTickets = [];
        $this->lastQrCodeImages = [];
        $this->lastSoldQuantity = 0;
        $this->clearCart();
    }

    /**
     * Generate a unique QR code string for the VIP ticket
     */
    protected function generateQrCodeData(Ticket $ticket, int $sequenceNumber = 1): string
    {
        $uniqueId = (string) Str::uuid();
        $timestamp = now()->timestamp;
        $ticketId = $ticket->id;
        $teacherId = Auth::id();

        $qrData = "KKHS-VIP-CONCERT-{$uniqueId}-{$timestamp}-{$ticketId}-{$teacherId}-{$sequenceNumber}";

        return $qrData;
    }

    public function render()
    {
        // Get tickets available for VIP sales (only VIP tickets)
        $ticketsQuery = Ticket::query()
            ->with('concert')
            ->vip() // Only show VIP tickets
            ->when($this->concertFilter, function ($query) {
                return $query->where('concert_id', $this->concertFilter);
            })
            ->whereRaw('quantity_available > (SELECT COUNT(*) FROM ticket_purchases WHERE ticket_id = tickets.id AND status != "cancelled")');

        $tickets = $ticketsQuery->get();

        // Get concerts for filter dropdown
        $concerts = Concert::orderBy('date')->get();

        return view('livewire.admin.vip-ticket-sales', [
            'tickets' => $tickets,
            'concerts' => $concerts,
        ]);
    }
}
