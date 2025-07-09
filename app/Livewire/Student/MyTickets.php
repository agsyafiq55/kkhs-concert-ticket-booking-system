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

    public $showQrModal = false;

    public $selectedTicketForQr = null;

    public function mount()
    {
        // Check if user has permission to view their own tickets
        if (! Gate::allows('view own tickets')) {
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

    public function enlargeQrCode($ticketId)
    {
        $this->selectedTicketForQr = collect($this->tickets)->firstWhere('id', $ticketId);
        $this->showQrModal = true;
    }

    public function closeQrModal()
    {
        $this->showQrModal = false;
        $this->selectedTicketForQr = null;
    }

    public function downloadTicket($ticketId)
    {
        // Find the ticket purchase
        $purchase = TicketPurchase::findOrFail($ticketId);

        // Verify this ticket belongs to the current user
        if ($purchase->student_id !== Auth::id()) {
            session()->flash('error', 'You do not have permission to download this ticket.');

            return;
        }

        // Generate secure token for the printable ticket URL
        $token = hash('sha256', $purchase->id.$purchase->qr_code.config('app.key'));

        // Redirect to the printable ticket view
        return $this->redirect(route('ticket.printable', [
            'id' => $purchase->id,
            'token' => $token,
        ]), navigate: false);
    }

    public function render()
    {
        return view('livewire.student.my-tickets');
    }
}
