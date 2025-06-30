<?php

namespace App\Livewire\Admin\Tickets;

use App\Models\Concert;
use App\Models\Ticket;
use Livewire\Component;
use Illuminate\Support\Facades\Gate;

class Create extends Component
{
    public $concert_id = '';
    public $ticket_type = '';
    public $price = '';
    public $quantity_available = 100;
    
    protected $rules = [
        'concert_id' => 'required|exists:concerts,id',
        'ticket_type' => 'required|string|max:255',
        'price' => 'required|numeric|min:0',
        'quantity_available' => 'required|integer|min:0',
    ];
    
    public function mount()
    {
        // Check if user has permission to create tickets
        if (!Gate::allows('create tickets')) {
            abort(403, 'You do not have permission to create tickets.');
        }
    }
    
    public function save()
    {
        // Double-check permission before saving
        if (!Gate::allows('create tickets')) {
            session()->flash('error', 'You do not have permission to create tickets.');
            return;
        }
        
        $this->validate();
        
        Ticket::create([
            'concert_id' => $this->concert_id,
            'ticket_type' => $this->ticket_type,
            'price' => $this->price,
            'quantity_available' => $this->quantity_available,
        ]);
        
        session()->flash('message', 'Ticket successfully created.');
        
        $this->reset(['ticket_type', 'price']);
        $this->quantity_available = 100;
        
        $this->dispatch('ticketCreated');
        
        return $this->redirect(route('admin.tickets'));
    }
    
    public function render()
    {
        $concerts = Concert::orderBy('title')->get();
        
        return view('livewire.admin.tickets.create', [
            'concerts' => $concerts,
        ]);
    }
}
