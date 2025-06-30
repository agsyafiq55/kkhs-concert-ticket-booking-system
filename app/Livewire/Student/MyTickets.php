<?php

namespace App\Livewire\Student;

use App\Models\TicketPurchase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Livewire\Component;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class MyTickets extends Component
{
    public $tickets = [];
    public $qrCodes = [];
    
    public function mount()
    {
        // Check if user has permission to view their own tickets  
        if (!Gate::allows('view own tickets')) {
            abort(403, 'You do not have permission to view tickets.');
        }
        
        $this->loadTickets();
    }
    
    public function loadTickets()
    {
        // Get all tickets purchased by the current student
        $ticketPurchases = TicketPurchase::with(['ticket.concert'])
            ->where('student_id', Auth::id())
            ->orderBy('purchase_date', 'desc')
            ->get();
        
        $this->tickets = $ticketPurchases;
        
        // Generate QR codes for each ticket
        foreach ($ticketPurchases as $purchase) {
            try {
                $this->qrCodes[$purchase->id] = base64_encode(QrCode::format('svg')
                    ->size(200)
                    ->errorCorrection('H')
                    ->generate($purchase->qr_code));
            } catch (\Exception $e) {
                // If QR code generation fails, set to null
                $this->qrCodes[$purchase->id] = null;
            }
        }
    }
    
    public function downloadTicket($ticketId)
    {
        // In a real application, this would generate a PDF ticket for download
        // For now, we'll just refresh the ticket data
        $this->loadTickets();
    }
    
    public function render()
    {
        return view('livewire.student.my-tickets');
    }
}
