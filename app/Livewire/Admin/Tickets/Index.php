<?php

namespace App\Livewire\Admin\Tickets;

use App\Models\Ticket;
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
        $this->ticketIdToDelete = $ticketId;
    }
    
    public function deleteTicket()
    {
        if ($this->ticketIdToDelete) {
            $ticket = Ticket::find($this->ticketIdToDelete);
            
            if ($ticket) {
                $ticket->delete();
                session()->flash('message', 'Ticket successfully deleted.');
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
                return $query->where('ticket_type', 'like', '%' . $this->search . '%')
                    ->orWhereHas('concert', function ($q) {
                        $q->where('title', 'like', '%' . $this->search . '%');
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
