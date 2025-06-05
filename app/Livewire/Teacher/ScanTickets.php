<?php

namespace App\Livewire\Teacher;

use App\Models\TicketPurchase;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\On;
use Livewire\Component;

class ScanTickets extends Component
{
    public $qrCode = '';
    public $scanResult = null;
    public $scanStatus = null; // 'success', 'error', 'warning'
    public $scanMessage = '';
    public $scanCount = 0;
    public $lastScannedAt = null;
    
    // Listen for changes to qrCode property
    public function updatedQrCode()
    {
        // Auto-validate if the QR code is not empty and has sufficient length
        if (!empty($this->qrCode) && strlen($this->qrCode) > 10) {
            $this->validateQrCode();
        }
    }
    
    // Listen for scan-detected event from JavaScript
    #[On('scan-detected')]
    public function handleScanDetected($code = null)
    {
        if (is_array($code) && isset($code['code'])) {
            // If we received an array with a code property
            $this->qrCode = $code['code'];
            $this->validateQrCode();
        } elseif (is_string($code) && !empty($code)) {
            // If we received the code directly as a string
            $this->qrCode = $code;
            $this->validateQrCode();
        }
    }
    
    public function validateQrCode()
    {
        if (empty($this->qrCode)) {
            $this->scanStatus = 'error';
            $this->scanMessage = 'No QR code provided';
            $this->scanResult = null;
            return;
        }
        
        // Prevent duplicate scans in quick succession
        $now = now();
        if ($this->lastScannedAt && $now->diffInSeconds($this->lastScannedAt) < 2) {
            return; // Ignore scans within 2 seconds of last scan
        }
        
        $this->lastScannedAt = $now;
        $this->scanCount++;
        
        try {
            // Find the ticket purchase by QR code with eager loading of relationships
            $ticketPurchase = TicketPurchase::with(['ticket.concert', 'student'])
                ->where('qr_code', $this->qrCode)
                ->first();
            
            if (!$ticketPurchase) {
                $this->scanStatus = 'error';
                $this->scanMessage = 'Invalid ticket: QR code not found';
                $this->scanResult = null;
                $this->js('playSound("error")');
                return;
            }
            
            // Check the ticket status
            if ($ticketPurchase->status === 'cancelled') {
                $this->scanStatus = 'error';
                $this->scanMessage = 'This ticket has been cancelled';
                $this->scanResult = $ticketPurchase;
                $this->js('playSound("error")');
                return;
            }
            
            if ($ticketPurchase->status === 'used') {
                $this->scanStatus = 'warning';
                $usedTime = $ticketPurchase->updated_at->format('M d, Y \a\t g:i A');
                $timeSince = $ticketPurchase->updated_at->diffForHumans();
                
                $this->scanMessage = "⚠️ ALREADY USED! This ticket was already scanned $timeSince ($usedTime). Admission was already granted.";
                $this->scanResult = $ticketPurchase;
                $this->js('playSound("warning")');
                return;
            }
            
            // Valid ticket - mark as used
            $ticketPurchase->status = 'used';
            $ticketPurchase->save();
            
            $this->scanStatus = 'success';
            $this->scanMessage = 'Ticket accepted! Admission granted for ' . $ticketPurchase->student->name;
            $this->scanResult = $ticketPurchase;
            
            // Play a success sound
            $this->js('playSound("success")');
            
        } catch (\Exception $e) {
            Log::error('Error validating ticket: ' . $e->getMessage());
            $this->scanStatus = 'error';
            $this->scanMessage = 'An error occurred while validating the ticket';
            $this->scanResult = null;
            
            // Play an error sound
            $this->js('playSound("error")');
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
