<?php

namespace App\Livewire\Teacher;

use App\Models\TicketPurchase;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\On;
use Livewire\Component;

class ScanWalkInSales extends Component
{
    public $qrCode = '';
    public $scanResult = null;
    public $scanStatus = null; // 'success', 'error', 'warning'
    public $scanMessage = '';
    public $scanCount = 0;
    public $lastScannedAt = null;
    
    public function mount()
    {
        // Check if user has permission to scan walk-in sales
        if (!Gate::allows('scan walk-in sales')) {
            abort(403, 'You do not have permission to scan walk-in sales.');
        }
    }
    
    // Listen for scan-detected event from JavaScript
    #[On('scan-detected')]
    public function handleScanDetected($code)
    {
        Log::info('Walk-in sale scan detected event received', ['code' => $code]);
        
        if (is_array($code) && isset($code['code'])) {
            // If we received an array with a code property
            $this->qrCode = $code['code'];
        } elseif (is_string($code) && !empty($code)) {
            // If we received the code directly as a string
            $this->qrCode = $code;
        } else {
            Log::error('Invalid QR code format received for walk-in sale', ['code' => $code]);
            $this->scanStatus = 'error';
            $this->scanMessage = 'Invalid QR code format';
            $this->scanResult = null;
            $this->js('playSound("error")');
            return;
        }
        
        $this->validateWalkInTicket();
    }
    
    public function validateWalkInTicket()
    {
        Log::info('Validating walk-in ticket QR code', ['qrCode' => $this->qrCode]);
        
        if (empty($this->qrCode)) {
            $this->scanStatus = 'error';
            $this->scanMessage = 'No QR code provided';
            $this->scanResult = null;
            return;
        }
        
        // Prevent duplicate scans in quick succession
        $now = now();
        if ($this->lastScannedAt && $now->diffInSeconds($this->lastScannedAt) < 2) {
            Log::info('Duplicate walk-in scan prevented', ['timeDiff' => $now->diffInSeconds($this->lastScannedAt)]);
            return; // Ignore scans within 2 seconds of last scan
        }
        
        $this->lastScannedAt = $now;
        $this->scanCount++;
        
        try {
            // Find the walk-in ticket purchase by QR code with eager loading of relationships
            $ticketPurchase = TicketPurchase::with(['ticket.concert'])
                ->where('qr_code', $this->qrCode)
                ->where('is_walk_in', true) // Only walk-in tickets
                ->first();
            
            Log::info('Walk-in ticket purchase lookup result', ['found' => (bool)$ticketPurchase]);
            
            if (!$ticketPurchase) {
                $this->scanStatus = 'error';
                $this->scanMessage = 'Invalid walk-in ticket: QR code not found or not a walk-in ticket';
                $this->scanResult = null;
                $this->js('playSound("error")');
                return;
            }
            
            // Check the ticket status
            if ($ticketPurchase->status === 'cancelled') {
                $this->scanStatus = 'error';
                $this->scanMessage = 'This walk-in ticket has been cancelled';
                $this->scanResult = $ticketPurchase;
                $this->js('playSound("error")');
                return;
            }
            
            if ($ticketPurchase->status === 'used') {
                $this->scanStatus = 'error';
                $this->scanMessage = 'This walk-in ticket has already been used for entry. Cannot process sale.';
                $this->scanResult = $ticketPurchase;
                $this->js('playSound("error")');
                return;
            }
            
            if ($ticketPurchase->is_sold) {
                $this->scanStatus = 'warning';
                $soldTime = $ticketPurchase->updated_at->format('M d, Y \a\t g:i A');
                $timeSince = $ticketPurchase->updated_at->diffForHumans();
                
                $this->scanMessage = "ALREADY SOLD! This walk-in ticket was already sold $timeSince ($soldTime). Payment already collected.";
                $this->scanResult = $ticketPurchase;
                $this->js('playSound("warning")');
                return;
            }
            
            // Valid walk-in ticket - mark as sold
            $ticketPurchase->is_sold = true;
            $ticketPurchase->save();
            
            $this->scanStatus = 'success';
            $this->scanMessage = 'Walk-in ticket sold! Payment collected for ' . $ticketPurchase->ticket->ticket_type . ' - RM' . number_format($ticketPurchase->ticket->price, 2);
            $this->scanResult = $ticketPurchase;
            
            // Play a success sound
            $this->js('playSound("success")');
            
        } catch (\Exception $e) {
            Log::error('Error validating walk-in ticket: ' . $e->getMessage(), [
                'qrCode' => $this->qrCode,
                'exception' => $e
            ]);
            $this->scanStatus = 'error';
            $this->scanMessage = 'An error occurred while processing the walk-in ticket sale';
            $this->scanResult = null;
            
            // Play an error sound
            $this->js('playSound("error")');
        }
    }
    
    public function resetScan()
    {
        Log::info('Resetting walk-in sale scan by refreshing page');
        
        // Simply redirect to the same page to refresh everything
        return $this->redirect(route('teacher.scan-walk-in-sales'), navigate: true);
    }
    
    public function render()
    {
        return view('livewire.teacher.scan-walk-in-sales');
    }
}
