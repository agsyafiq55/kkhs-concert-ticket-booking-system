<?php

namespace App\Jobs;

use App\Mail\Emailer;
use App\Models\TicketPurchase;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendBulkTicketEmails implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 3;

    /**
     * The maximum number of seconds the job can run.
     *
     * @var int
     */
    public $timeout = 30;

    /**
     * The student ID to send email to.
     *
     * @var int
     */
    public $studentId;

    /**
     * The ticket purchase IDs for this student.
     *
     * @var array
     */
    public $ticketPurchaseIds;

    /**
     * Create a new job instance.
     *
     * @param int $studentId
     * @param array $ticketPurchaseIds
     */
    public function __construct(int $studentId, array $ticketPurchaseIds)
    {
        $this->studentId = $studentId;
        $this->ticketPurchaseIds = $ticketPurchaseIds;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(): void
    {
        try {
            // Get the student
            $student = User::find($this->studentId);
            if (!$student) {
                Log::error("Bulk email job failed: Student not found with ID {$this->studentId}");
                return;
            }

            // Get the ticket purchases with relations
            $ticketPurchases = TicketPurchase::with([
                'student',
                'teacher',
                'ticket.concert',
            ])->whereIn('id', $this->ticketPurchaseIds)->get();

            if ($ticketPurchases->isEmpty()) {
                Log::error("Bulk email job failed: No ticket purchases found for student {$student->name} ({$student->email})");
                return;
            }

            // Send the email
            Mail::to($student->email)->send(new Emailer($ticketPurchases));

            Log::info("Bulk email sent successfully to {$student->name} ({$student->email}) - {$ticketPurchases->count()} tickets");

        } catch (\Exception $e) {
            Log::error("Bulk email job failed for student ID {$this->studentId}: " . $e->getMessage());
            
            // Re-throw the exception to trigger retry mechanism
            throw $e;
        }
    }

    /**
     * Handle a job failure.
     *
     * @param  \Throwable  $exception
     * @return void
     */
    public function failed(\Throwable $exception): void
    {
        $student = User::find($this->studentId);
        $studentName = $student ? $student->name : 'Unknown';
        $studentEmail = $student ? $student->email : 'Unknown';
        
        Log::error("Bulk email job permanently failed for {$studentName} ({$studentEmail}) after {$this->tries} attempts: " . $exception->getMessage());
    }
} 