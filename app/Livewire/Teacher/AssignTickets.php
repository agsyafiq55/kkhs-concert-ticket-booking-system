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
    public $concertFilter = '';
    public $ticketAssigned = false;
    public $lastAssignedQrCode = null;
    public $lastQrCodeImage = null;
    public $paymentReceived = false;
    
    protected $rules = [
        'selectedStudentId' => 'required|exists:users,id',
        'selectedTicketId' => 'required|exists:tickets,id',
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
    }
    
    public function selectStudent($studentId)
    {
        $this->selectedStudentId = $studentId;
        $this->resetValidation('selectedStudentId');
    }
    
    public function selectTicket($ticketId)
    {
        $this->selectedTicketId = $ticketId;
        $this->paymentReceived = false; // Reset payment confirmation when changing ticket
        $this->resetValidation(['selectedTicketId', 'paymentReceived']);
    }
    
    public function assignTicket()
    {
        $this->validate();
        
        // Check if the selected ticket still has available quantity
        $ticket = Ticket::findOrFail($this->selectedTicketId);
        
        if ($ticket->remaining_tickets <= 0) {
            $this->addError('selectedTicketId', 'This ticket type is no longer available.');
            return;
        }
        
        // Generate QR code data (unique identifier)
        $qrCodeData = $this->generateQrCodeData($ticket);
        
        try {
            // Generate QR code image (SVG)
            $this->lastQrCodeImage = base64_encode(QrCode::format('svg')
                ->size(200)
                ->errorCorrection('H')
                ->generate($qrCodeData));
        } catch (\Exception $e) {
            // Fallback if QR code generation fails
            $this->lastQrCodeImage = null;
        }
        
        // Create the ticket purchase record
        $ticketPurchase = TicketPurchase::create([
            'ticket_id' => $this->selectedTicketId,
            'student_id' => $this->selectedStudentId,
            'teacher_id' => Auth::id(),
            'purchase_date' => now(),
            'qr_code' => $qrCodeData,
            'status' => 'valid',
        ]);
        
        // Send email notification to the student
        try {
            // Load the ticket purchase with all necessary relationships for the email
            $ticketPurchaseWithRelations = TicketPurchase::with([
                'student', 
                'teacher', 
                'ticket.concert'
            ])->find($ticketPurchase->id);
            
            Mail::to($ticketPurchaseWithRelations->student->email)->send(new Emailer($ticketPurchaseWithRelations));
        } catch (\Exception $e) {
            // Log the error but don't stop the process
            // You can add logging here if needed
            // Log::error('Failed to send ticket email: ' . $e->getMessage());
        }
        
        // Reset selections and show success message
        $this->lastAssignedQrCode = $qrCodeData;
        $this->ticketAssigned = true;
        $this->selectedTicketId = null;
        $this->paymentReceived = false;
        
        // Reset pagination to show the student's updated tickets
        $this->resetPage();
    }
    
    public function resetForm()
    {
        $this->selectedStudentId = null;
        $this->selectedTicketId = null;
        $this->ticketAssigned = false;
        $this->lastAssignedQrCode = null;
        $this->lastQrCodeImage = null;
        $this->paymentReceived = false;
        $this->resetValidation();
    }
    
    /**
     * Generate a unique QR code string for the ticket
     */
    protected function generateQrCodeData(Ticket $ticket): string
    {
        // Create a unique string that can be verified later
        $uniqueId = (string) Str::uuid();
        $timestamp = now()->timestamp;
        $ticketId = $ticket->id;
        $studentId = $this->selectedStudentId;
        $teacherId = Auth::id();
        
        // Combine all data into a single string
        $qrData = "KKHS-CONCERT-{$uniqueId}-{$timestamp}-{$ticketId}-{$studentId}-{$teacherId}";
        
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
