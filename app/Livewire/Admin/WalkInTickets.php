<?php

namespace App\Livewire\Admin;

use App\Models\Concert;
use App\Models\Ticket;
use App\Models\TicketPurchase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithPagination;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class WalkInTickets extends Component
{
    use WithPagination;

    public $selectedTicketId = null;

    public $concertFilter = '';

    public $statusFilter = 'all'; // all, pre-generated, sold, used

    public $ticketsGenerated = false;

    public $lastGeneratedTickets = [];

    public $lastQrCodeImages = [];

    // Bulk selection properties
    public $selectedTickets = [];
    public $selectAll = false;

    protected $rules = [
        'selectedTicketId' => 'required|exists:tickets,id',
    ];

    public function mount()
    {
        // Check if user has permission to manage walk-in tickets
        if (! Gate::allows('manage walk-in tickets')) {
            abort(403, 'You do not have permission to manage walk-in tickets.');
        }
    }

    public function updatingConcertFilter()
    {
        $this->resetPage();
        $this->selectedTicketId = null;
        $this->clearSelection();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
        $this->clearSelection();
    }

    public function selectTicket($ticketId)
    {
        $this->selectedTicketId = $ticketId;
        $this->resetValidation();
    }

    public function toggleTicketSelection($ticketId)
    {
        if (in_array($ticketId, $this->selectedTickets)) {
            $this->selectedTickets = array_diff($this->selectedTickets, [$ticketId]);
        } else {
            $this->selectedTickets[] = $ticketId;
        }
        
        // Update select all state
        $this->updateSelectAllState();
    }

    public function toggleSelectAll()
    {
        if ($this->selectAll) {
            // Select all deletable tickets on current page
            $this->selectedTickets = $this->getDeletableTicketIds();
        } else {
            // Deselect all
            $this->selectedTickets = [];
        }
    }

    public function clearSelection()
    {
        $this->selectedTickets = [];
        $this->selectAll = false;
    }

    private function updateSelectAllState()
    {
        $deletableTicketIds = $this->getDeletableTicketIds();
        $this->selectAll = count($deletableTicketIds) > 0 && 
                          count(array_intersect($this->selectedTickets, $deletableTicketIds)) === count($deletableTicketIds);
    }

    private function getDeletableTicketIds()
    {
        return TicketPurchase::query()
            ->whereHas('ticket', function ($q) {
                $q->where('ticket_category', 'walk-in');
            })
            ->when($this->statusFilter !== 'all', function ($query) {
                switch ($this->statusFilter) {
                    case 'pre-generated':
                        return $query->where('is_sold', false)->where('status', 'valid');
                    case 'sold':
                        return $query->where('is_sold', true)->where('status', 'valid');
                    case 'used':
                        return $query->where('status', 'used');
                }
            })
            ->when($this->concertFilter, function ($query) {
                return $query->whereHas('ticket', function ($q) {
                    $q->where('concert_id', $this->concertFilter);
                });
            })
            ->where('is_sold', false) // Only unsold tickets can be deleted
            ->where('status', 'valid')
            ->pluck('id')
            ->toArray();
    }

    public function bulkDeleteTickets()
    {
        if (empty($this->selectedTickets)) {
            session()->flash('error', 'No tickets selected for deletion.');
            return;
        }

        $ticketsToDelete = TicketPurchase::whereIn('id', $this->selectedTickets)
            ->whereHas('ticket', function ($q) {
                $q->where('ticket_category', 'walk-in');
            })
            ->where('is_sold', false) // Only allow deletion of unsold tickets
            ->where('status', 'valid')
            ->get();

        if ($ticketsToDelete->isEmpty()) {
            session()->flash('error', 'No valid tickets found for deletion.');
            return;
        }

        $deletedCount = $ticketsToDelete->count();
        
        foreach ($ticketsToDelete as $ticket) {
            $ticket->delete();
        }

        $this->clearSelection();
        $this->resetPage();
        
        session()->flash('success', "Successfully deleted {$deletedCount} walk-in ticket" . ($deletedCount !== 1 ? 's' : '') . ".");
    }

    public function generateWalkInTickets()
    {
        $this->validate();

        $ticket = Ticket::findOrFail($this->selectedTicketId);

        // Get the number of available tickets to generate
        $quantityToGenerate = $ticket->remaining_tickets;

        // Check if there are any tickets available
        if ($quantityToGenerate <= 0) {
            $this->addError('general', "No tickets available for generation for this ticket type.");
            return;
        }

        $createdTickets = [];
        $qrImages = [];

        try {
            // Generate all available walk-in tickets
            for ($i = 0; $i < $quantityToGenerate; $i++) {
                $qrCodeData = $this->generateQrCodeData($ticket, $i + 1);

                $ticketPurchase = TicketPurchase::create([
                    'ticket_id' => $this->selectedTicketId,
                    'student_id' => null, // No student for walk-in tickets
                    'teacher_id' => Auth::id(),
                    'purchase_date' => now(),
                    'qr_code' => $qrCodeData,
                    'status' => 'valid',
                    // Removed is_walk_in - ticket type determined by ticket relationship
                    'is_sold' => false, // Not sold yet - will be marked when payment received
                ]);

                $createdTickets[] = $ticketPurchase;

                // Generate QR code image for display
                try {
                    $qrImages[] = base64_encode(QrCode::format('svg')
                        ->size(200)
                        ->errorCorrection('H')
                        ->generate($qrCodeData));
                } catch (\Exception $e) {
                    $qrImages[] = null;
                }
            }

            // Set success state
            $this->lastGeneratedTickets = $createdTickets;
            $this->lastQrCodeImages = $qrImages;
            $this->ticketsGenerated = true;

            // Reset form
            $this->selectedTicketId = null;
            $this->resetPage();

            session()->flash('success', "Successfully generated {$quantityToGenerate} walk-in tickets!");

        } catch (\Exception $e) {
            // Cleanup any created tickets on error
            foreach ($createdTickets as $ticket) {
                $ticket->delete();
            }

            $this->addError('general', 'An error occurred while generating walk-in tickets. Please try again.');
        }
    }

    public function resetForm()
    {
        $this->selectedTicketId = null;
        $this->ticketsGenerated = false;
        $this->lastGeneratedTickets = [];
        $this->lastQrCodeImages = [];
        $this->clearSelection();
        $this->resetValidation();
    }

    /**
     * Generate a unique QR code string for the walk-in ticket
     */
    protected function generateQrCodeData(Ticket $ticket, int $sequenceNumber = 1): string
    {
        $uniqueId = (string) Str::uuid();
        $timestamp = now()->timestamp;
        $ticketId = $ticket->id;
        $teacherId = Auth::id();

        $qrData = "KKHS-WALKIN-{$uniqueId}-{$timestamp}-{$ticketId}-{$teacherId}-{$sequenceNumber}";

        return $qrData;
    }

    public function deleteWalkInTicket($ticketId)
    {
        $ticket = TicketPurchase::whereHas('ticket', function ($q) {
            $q->where('ticket_category', 'walk-in');
        })
            ->where('id', $ticketId)
            ->where('is_sold', false) // Only allow deletion of unsold tickets
            ->first();

        if ($ticket) {
            $ticket->delete();
            session()->flash('success', 'Walk-in ticket deleted successfully.');
            $this->resetPage();
        } else {
            session()->flash('error', 'Cannot delete this ticket. It may already be sold or not found.');
        }
    }

    public function render()
    {
        // Get tickets available for walk-in generation (only walk-in tickets)
        $ticketsQuery = Ticket::query()
            ->with('concert')
            ->walkIn() // Only show walk-in tickets
            ->when($this->concertFilter, function ($query) {
                return $query->where('concert_id', $this->concertFilter);
            })
            ->whereRaw('quantity_available > (SELECT COUNT(*) FROM ticket_purchases WHERE ticket_id = tickets.id AND status != "cancelled")');

        $tickets = $ticketsQuery->get();

        // Get concerts for filter dropdown
        $concerts = Concert::orderBy('date')->get();

        // Get existing walk-in tickets based on filter
        $walkInTicketsQuery = TicketPurchase::query()
            ->with(['ticket.concert', 'teacher'])
            ->whereHas('ticket', function ($q) {
                $q->where('ticket_category', 'walk-in');
            })
            ->when($this->statusFilter !== 'all', function ($query) {
                switch ($this->statusFilter) {
                    case 'pre-generated':
                        return $query->where('is_sold', false)->where('status', 'valid');
                    case 'sold':
                        return $query->where('is_sold', true)->where('status', 'valid');
                    case 'used':
                        return $query->where('status', 'used');
                }
            })
            ->when($this->concertFilter, function ($query) {
                return $query->whereHas('ticket', function ($q) {
                    $q->where('concert_id', $this->concertFilter);
                });
            })
            ->orderBy('created_at', 'desc');

        $walkInTickets = $walkInTicketsQuery->paginate(20);

        // Get walk-in tickets grouped by concert for printing
        $walkInTicketsByConcert = TicketPurchase::query()
            ->with(['ticket.concert', 'teacher'])
            ->walkIn() // Use the new walk-in scope for relationship-based filtering
            ->where('is_sold', false) // Only pre-generated tickets
            ->where('status', 'valid')
            ->get()
            ->groupBy(function ($ticket) {
                return $ticket->ticket->concert->id;
            })
            ->map(function ($tickets, $concertId) {
                $concert = $tickets->first()->ticket->concert;

                return [
                    'concert' => $concert,
                    'tickets' => $tickets,
                    'count' => $tickets->count(),
                    'total_value' => $tickets->sum(function ($ticket) {
                        return $ticket->ticket->price;
                    }),
                ];
            });

        return view('livewire.admin.walk-in-tickets', [
            'tickets' => $tickets,
            'concerts' => $concerts,
            'walkInTickets' => $walkInTickets,
            'walkInTicketsByConcert' => $walkInTicketsByConcert,
        ]);
    }
}
