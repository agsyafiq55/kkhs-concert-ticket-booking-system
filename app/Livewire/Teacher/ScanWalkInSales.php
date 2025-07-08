<?php

namespace App\Livewire\Teacher;

use App\Models\TicketPurchase;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\On;
use Livewire\Component;
use Illuminate\Support\Facades\DB;

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
            // Use database transaction with row locking for concurrency safety
            $result = DB::transaction(function () {
                // Find and lock the walk-in ticket purchase record to prevent concurrent access
                $ticketPurchase = TicketPurchase::with(['ticket.concert'])
                    ->where('qr_code', $this->qrCode)
                    ->whereHas('ticket', function($query) {
                        $query->where('ticket_category', 'walk-in');
                    }) // Only walk-in tickets
                    ->lockForUpdate() // This prevents other transactions from modifying this row
                    ->first();
                
                Log::info('Walk-in ticket purchase lookup result', ['found' => (bool)$ticketPurchase]);
                
                if (!$ticketPurchase) {
                    return [
                        'status' => 'error',
                        'message' => 'Invalid walk-in ticket: QR code not found or not a walk-in ticket',
                        'ticket' => null
                    ];
                }
                
                // Check the ticket status
                if ($ticketPurchase->status === 'cancelled') {
                    return [
                        'status' => 'error',
                        'message' => 'This walk-in ticket has been cancelled',
                        'ticket' => $ticketPurchase
                    ];
                }
                
                if ($ticketPurchase->status === 'used') {
                    return [
                        'status' => 'error',
                        'message' => 'This walk-in ticket has already been used for entry. Cannot process sale.',
                        'ticket' => $ticketPurchase
                    ];
                }
                
                if ($ticketPurchase->is_sold) {
                    $soldTime = $ticketPurchase->updated_at->format('M d, Y \a\t g:i A');
                    $timeSince = $ticketPurchase->updated_at->diffForHumans();
                    
                    return [
                        'status' => 'warning',
                        'message' => "ALREADY SOLD! This walk-in ticket was already sold $timeSince ($soldTime). Payment already collected.",
                        'ticket' => $ticketPurchase
                    ];
                }
                
                // Valid walk-in ticket - mark as sold (this update is now safely locked)
                $ticketPurchase->is_sold = true;
                $ticketPurchase->save();
                
                return [
                    'status' => 'success',
                    'message' => 'Walk-in ticket sold! Payment collected for ' . $ticketPurchase->ticket->ticket_type . ' - RM' . number_format($ticketPurchase->ticket->price, 2),
                    'ticket' => $ticketPurchase
                ];
            }, 3); // Retry up to 3 times if deadlock occurs
            
            // Apply the results from the transaction
            $this->scanStatus = $result['status'];
            $this->scanMessage = $result['message'];
            $this->scanResult = $result['ticket'];
            
            // Play appropriate sound
            $this->js('playSound("' . $result['status'] . '")');
            
        } catch (\Illuminate\Database\QueryException $e) {
            // Handle database-specific errors (deadlocks, etc.)
            Log::error('Database error during walk-in ticket validation: ' . $e->getMessage(), [
                'qrCode' => $this->qrCode,
                'sqlState' => $e->errorInfo[0] ?? null,
                'errorCode' => $e->errorInfo[1] ?? null,
                'exception' => $e
            ]);
            
            if ($e->errorInfo[0] === '40001') { // Deadlock
                $this->scanStatus = 'error';
                $this->scanMessage = 'System busy, please try scanning again';
            } else {
                $this->scanStatus = 'error';
                $this->scanMessage = 'Database error occurred while processing the walk-in ticket sale';
            }
            
            $this->scanResult = null;
            $this->js('playSound("error")');
            
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
