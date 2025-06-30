<?php

namespace App\Livewire\Admin\Tickets;

use App\Models\Concert;
use App\Models\Ticket;
use Livewire\Component;
use Illuminate\Support\Facades\Gate;

class Edit extends Component
{
    public $ticketId;
    public $concert_id;
    public $ticket_type;
    public $price;
    public $quantity_available;
    
    protected $rules = [
        'concert_id' => 'required|exists:concerts,id',
        'ticket_type' => 'required|string|max:255',
        'price' => 'required|numeric|min:0',
        'quantity_available' => 'required|integer|min:0',
    ];
    
    public function mount($id)
    {
        // Check if user has permission to edit tickets
        if (!Gate::allows('edit tickets')) {
            abort(403, 'You do not have permission to edit tickets.');
        }
        
        $this->ticketId = $id;
        $ticket = Ticket::findOrFail($id);
        
        $this->concert_id = $ticket->concert_id;
        $this->ticket_type = $ticket->ticket_type;
        $this->price = $ticket->price;
        $this->quantity_available = $ticket->quantity_available;
    }
    
    public function update()
    {
        // Double-check permission before saving
        if (!Gate::allows('edit tickets')) {
            session()->flash('error', 'You do not have permission to edit tickets.');
            return;
        }
        
        $this->validate();
        
        $ticket = Ticket::findOrFail($this->ticketId);
        
        $ticket->update([
            'concert_id' => $this->concert_id,
            'ticket_type' => $this->ticket_type,
            'price' => $this->price,
            'quantity_available' => $this->quantity_available,
        ]);
        
        session()->flash('message', 'Ticket successfully updated.');
        
        $this->dispatch('ticketUpdated');
        
        return $this->redirect(route('admin.tickets'));
    }
    
    public function render()
    {
        $concerts = Concert::orderBy('title')->get();
        
        return view('livewire.admin.tickets.edit', [
            'concerts' => $concerts,
        ]);
    }
}
