<?php

namespace App\Livewire\Admin\Tickets;

use App\Models\Ticket;
use Illuminate\Support\Facades\Gate;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $search = '';

    public $ticketIdToDelete = null;

    public $concertFilter = '';

    protected $listeners = [
        'ticketCreated' => '$refresh',
        'ticketUpdated' => '$refresh',
    ];

    public function mount()
    {
        // Check if user has permission to view tickets
        if (! Gate::allows('view tickets')) {
            abort(403, 'You do not have permission to view tickets.');
        }
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingConcertFilter()
    {
        $this->resetPage();
    }

    public function confirmDelete($ticketId)
    {
        // Check if user has permission to delete tickets
        if (! Gate::allows('delete tickets')) {
            session()->flash('error', 'You do not have permission to delete tickets.');

            return;
        }

        $this->ticketIdToDelete = $ticketId;
    }

    public function deleteTicket()
    {
        // Double-check permission before actual deletion
        if (! Gate::allows('delete tickets')) {
            session()->flash('error', 'You do not have permission to delete tickets.');

            return;
        }

        if ($this->ticketIdToDelete) {
            $ticket = Ticket::find($this->ticketIdToDelete);

            if ($ticket) {
                $ticket->delete();
                session()->flash('deleted', 'Ticket successfully deleted.');
            }

            $this->ticketIdToDelete = null;
        }
    }

    public function cancelDelete()
    {
        $this->ticketIdToDelete = null;
    }

    public function render()
    {
        $ticketsQuery = Ticket::query()
            ->with('concert')
            ->when($this->search, function ($query) {
                return $query->where('ticket_type', 'like', '%'.$this->search.'%')
                    ->orWhereHas('concert', function ($q) {
                        $q->where('title', 'like', '%'.$this->search.'%');
                    });
            })
            ->when($this->concertFilter, function ($query) {
                return $query->where('concert_id', $this->concertFilter);
            });

        $tickets = $ticketsQuery->orderBy('concert_id')->paginate(10);

        // Get list of concerts for filter dropdown
        $concerts = \App\Models\Concert::orderBy('title')->get();

        return view('livewire.admin.tickets.index', [
            'tickets' => $tickets,
            'concerts' => $concerts,
        ]);
    }
}
