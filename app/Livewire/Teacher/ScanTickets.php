<?php

namespace App\Livewire\Teacher;

use App\Models\TicketPurchase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\On;
use Livewire\Component;
use Illuminate\Support\Facades\DB;

class ScanTickets extends Component
{
    public $qrCode = '';
    public $scanResult = null;
    public $scanStatus = null; // 'success', 'error', 'warning'
    public $scanMessage = '';
    public $scanCount = 0;
    public $lastScannedAt = null;
    public $originalStatus = null; // Store the original status before updating to "used"
    
    public function mount()
    {
        // Check if user has permission to scan tickets
        if (!Gate::allows('scan tickets')) {
            abort(403, 'You do not have permission to scan tickets.');
        }
    }
    
    // Listen for scan-detected event from JavaScript
    #[On('scan-detected')]
    public function handleScanDetected($code)
    {
        Log::info('Scan detected event received', ['code' => $code]);
        
        if (is_array($code) && isset($code['code'])) {
            // If we received an array with a code property
            $this->qrCode = $code['code'];
        } elseif (is_string($code) && !empty($code)) {
            // If we received the code directly as a string
            $this->qrCode = $code;
        } else {
            Log::error('Invalid QR code format received', ['code' => $code]);
            $this->scanStatus = 'error';
            $this->scanMessage = 'Invalid QR code format';
            $this->scanResult = null;
            $this->js('playSound("error")');
            return;
        }
        
        $this->validateQrCode();
    }
    
    public function validateQrCode()
    {
        Log::info('Validating QR code', ['qrCode' => $this->qrCode]);
        
        if (empty($this->qrCode)) {
            $this->scanStatus = 'error';
            $this->scanMessage = 'No QR code provided';
            $this->scanResult = null;
            return;
        }
        
        // Prevent duplicate scans in quick succession
        $now = now();
        if ($this->lastScannedAt && $now->diffInSeconds($this->lastScannedAt) < 2) {
            Log::info('Duplicate scan prevented', ['timeDiff' => $now->diffInSeconds($this->lastScannedAt)]);
            return; // Ignore scans within 2 seconds of last scan
        }
        
        $this->lastScannedAt = $now;
        $this->scanCount++;
        
        try {
            // Use database transaction with row locking for concurrency safety
            $result = DB::transaction(function () {
                // Find and lock the ticket purchase record to prevent concurrent access
                $ticketPurchase = TicketPurchase::with(['ticket.concert', 'student', 'teacher'])
                    ->where('qr_code', $this->qrCode)
                    ->lockForUpdate() // This prevents other transactions from modifying this row
                    ->first();
                
                Log::info('Ticket purchase lookup result', ['found' => (bool)$ticketPurchase]);
                
                if (!$ticketPurchase) {
                    return [
                        'status' => 'error',
                        'message' => 'Invalid ticket: QR code not found',
                        'ticket' => null,
                        'originalStatus' => null
                    ];
                }
                
                // Store the original status before any modifications
                $originalStatus = $ticketPurchase->status;
                
                // Check the ticket status
                if ($ticketPurchase->status === 'cancelled') {
                    return [
                        'status' => 'error',
                        'message' => 'This ticket has been cancelled',
                        'ticket' => $ticketPurchase,
                        'originalStatus' => $originalStatus
                    ];
                }
                
                if ($ticketPurchase->status === 'used') {
                    $usedTime = $ticketPurchase->updated_at->format('M d, Y \a\t g:i A');
                    $timeSince = $ticketPurchase->updated_at->diffForHumans();
                    
                    return [
                        'status' => 'warning',
                        'message' => "ALREADY USED! This ticket was already scanned $timeSince ($usedTime). Admission was already granted.",
                        'ticket' => $ticketPurchase,
                        'originalStatus' => $originalStatus
                    ];
                }
                
                // Special handling for walk-in tickets
                if ($ticketPurchase->isWalkIn() && !$ticketPurchase->is_sold) {
                    return [
                        'status' => 'error',
                        'message' => 'WALK-IN TICKET NOT SOLD! This walk-in ticket has not been sold yet. Please use the Walk-in Sales Scanner to collect payment first.',
                        'ticket' => $ticketPurchase,
                        'originalStatus' => $originalStatus
                    ];
                }
                
                // Valid ticket - mark as used (this update is now safely locked)
                $ticketPurchase->status = 'used';
                $ticketPurchase->save();
                
                // Different success message for walk-in vs regular tickets
                if ($ticketPurchase->isWalkIn()) {
                    $message = 'Walk-in ticket accepted! Admission granted for walk-in customer';
                } else {
                    $message = 'Ticket accepted! Admission granted for ' . $ticketPurchase->student->name;
                }
                
                return [
                    'status' => 'success',
                    'message' => $message,
                    'ticket' => $ticketPurchase,
                    'originalStatus' => $originalStatus
                ];
            }, 3); // Retry up to 3 times if deadlock occurs
            
            // Apply the results from the transaction
            $this->scanStatus = $result['status'];
            $this->scanMessage = $result['message'];
            $this->scanResult = $result['ticket'];
            $this->originalStatus = $result['originalStatus'];
            
            // Play appropriate sound
            $this->js('playSound("' . $result['status'] . '")');
            
        } catch (\Illuminate\Database\QueryException $e) {
            // Handle database-specific errors (deadlocks, etc.)
            Log::error('Database error during ticket validation: ' . $e->getMessage(), [
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
                $this->scanMessage = 'Database error occurred while validating the ticket';
            }
            
            $this->scanResult = null;
            $this->originalStatus = null;
            $this->js('playSound("error")');
            
        } catch (\Exception $e) {
            Log::error('Error validating ticket: ' . $e->getMessage(), [
                'qrCode' => $this->qrCode,
                'exception' => $e
            ]);
            $this->scanStatus = 'error';
            $this->scanMessage = 'An error occurred while validating the ticket';
            $this->scanResult = null;
            $this->originalStatus = null;
            
            // Play an error sound
            $this->js('playSound("error")');
        }
    }
    
    public function resetScan()
    {
        Log::info('Resetting scan by refreshing page');
        
        // Simply redirect to the same page to refresh everything
        return $this->redirect(route('teacher.scan-tickets'), navigate: true);
    }
    
    public function render()
    {
        return view('livewire.teacher.scan-tickets');
    }
}
