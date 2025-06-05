<?php

namespace App\Livewire\Teacher;

use App\Models\TicketPurchase;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class ScanTickets extends Component
{
    public $qrCode = '';
    public $scanResult = null;
    public $scanStatus = null; // 'success', 'error', 'warning'
    public $scanMessage = '';
    
    public function validateQrCode()
    {
        if (empty($this->qrCode)) {
            $this->scanStatus = 'error';
            $this->scanMessage = 'No QR code provided';
            $this->scanResult = null;
            return;
        }
        
        try {
            // Find the ticket purchase by QR code
            $ticketPurchase = TicketPurchase::where('qr_code', $this->qrCode)->first();
            
            if (!$ticketPurchase) {
                $this->scanStatus = 'error';
                $this->scanMessage = 'Invalid ticket: QR code not found';
                $this->scanResult = null;
                return;
            }
            
            // Check the ticket status
            if ($ticketPurchase->status === 'cancelled') {
                $this->scanStatus = 'error';
                $this->scanMessage = 'This ticket has been cancelled';
                $this->scanResult = $ticketPurchase;
                return;
            }
            
            if ($ticketPurchase->status === 'used') {
                $this->scanStatus = 'warning';
                $this->scanMessage = 'This ticket has already been used on ' . 
                    $ticketPurchase->updated_at->format('M d, Y \a\t g:i A');
                $this->scanResult = $ticketPurchase;
                return;
            }
            
            // Valid ticket - mark as used
            $ticketPurchase->status = 'used';
            $ticketPurchase->save();
            
            // Load relations for display
            $ticketPurchase->load(['ticket.concert', 'student']);
            
            $this->scanStatus = 'success';
            $this->scanMessage = 'Ticket is valid and has been marked as used';
            $this->scanResult = $ticketPurchase;
            
        } catch (\Exception $e) {
            Log::error('Error validating ticket: ' . $e->getMessage());
            $this->scanStatus = 'error';
            $this->scanMessage = 'An error occurred while validating the ticket';
            $this->scanResult = null;
        }
    }
    
    public function resetScan()
    {
        $this->qrCode = '';
        $this->scanResult = null;
        $this->scanStatus = null;
        $this->scanMessage = '';
    }
    
    public function render()
    {
        return view('livewire.teacher.scan-tickets');
    }
}
