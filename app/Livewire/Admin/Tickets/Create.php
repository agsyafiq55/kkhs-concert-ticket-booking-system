<?php

namespace App\Livewire\Admin\Tickets;

use App\Models\Concert;
use App\Models\Ticket;
use Illuminate\Support\Facades\Gate;
use Livewire\Component;

class Create extends Component
{
    public $concert_id = '';

    public $ticket_type = '';

    public $price = '';

    public $quantity_available = 100;

    // New properties for walk-in and VIP tickets
    public $createWalkInTickets = false;

    public $walkInTicketType = 'Walk-in Ticket';

    public $walkInPrice = '';

    public $walkInQuantity = 50;

    public $createVipTickets = false;

    public $vipTicketType = 'VIP Ticket';

    public $vipPrice = '';

    public $vipQuantity = 20;

    protected function rules()
    {
        $rules = [
            'concert_id' => 'required|exists:concerts,id',
            'ticket_type' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'quantity_available' => 'required|integer|min:0',
        ];

        if ($this->createWalkInTickets) {
            $rules['walkInTicketType'] = 'required|string|max:255';
            $rules['walkInPrice'] = 'required|numeric|min:0';
            $rules['walkInQuantity'] = 'required|integer|min:0';
        }

        if ($this->createVipTickets) {
            $rules['vipTicketType'] = 'required|string|max:255';
            $rules['vipPrice'] = 'required|numeric|min:0';
            $rules['vipQuantity'] = 'required|integer|min:0';
        }

        return $rules;
    }

    public function mount()
    {
        // Check if user has permission to create tickets
        if (! Gate::allows('create tickets')) {
            abort(403, 'You do not have permission to create tickets.');
        }
    }

    public function updatedConcertId()
    {
        // Check what ticket types already exist for this concert
        $this->checkExistingTicketTypes();
    }

    public function updatedCreateWalkInTickets()
    {
        if ($this->createWalkInTickets) {
            $this->walkInPrice = $this->walkInPrice ?: $this->price;
            $this->checkExistingTicketTypes();
        } else {
            $this->walkInPrice = '';
        }
    }

    public function updatedCreateVipTickets()
    {
        if ($this->createVipTickets) {
            $this->vipPrice = $this->vipPrice ?: ($this->price ? $this->price * 1.5 : '');
            $this->checkExistingTicketTypes();
        } else {
            $this->vipPrice = '';
        }
    }

    private function checkExistingTicketTypes()
    {
        if (! $this->concert_id) {
            return;
        }

        $existingTypes = Ticket::where('concert_id', $this->concert_id)
            ->pluck('ticket_category')
            ->toArray();

        // Check for existing walk-in tickets
        if ($this->createWalkInTickets && in_array('walk-in', $existingTypes)) {
            $this->addError('createWalkInTickets', 'This concert already has walk-in tickets. Only one walk-in ticket type is allowed per concert.');
            $this->createWalkInTickets = false;
        }

        // Check for existing VIP tickets
        if ($this->createVipTickets && in_array('vip', $existingTypes)) {
            $this->addError('createVipTickets', 'This concert already has VIP tickets. Only one VIP ticket type is allowed per concert.');
            $this->createVipTickets = false;
        }
    }

    public function save()
    {
        // Double-check permission before saving
        if (! Gate::allows('create tickets')) {
            session()->flash('error', 'You do not have permission to create tickets.');

            return;
        }

        $this->validate();

        // Additional validation for existing ticket types
        if ($this->concert_id) {
            $existingTypes = Ticket::where('concert_id', $this->concert_id)
                ->pluck('ticket_category')
                ->toArray();

            if ($this->createWalkInTickets && in_array('walk-in', $existingTypes)) {
                $this->addError('createWalkInTickets', 'This concert already has walk-in tickets.');

                return;
            }

            if ($this->createVipTickets && in_array('vip', $existingTypes)) {
                $this->addError('createVipTickets', 'This concert already has VIP tickets.');

                return;
            }
        }

        $createdTickets = [];

        try {
            // Create regular ticket
            $regularTicket = Ticket::create([
                'concert_id' => $this->concert_id,
                'ticket_type' => $this->ticket_type,
                'ticket_category' => 'regular',
                'price' => $this->price,
                'quantity_available' => $this->quantity_available,
            ]);
            $createdTickets[] = $regularTicket;

            // Create walk-in ticket if requested
            if ($this->createWalkInTickets) {
                $walkInTicket = Ticket::create([
                    'concert_id' => $this->concert_id,
                    'ticket_type' => $this->walkInTicketType,
                    'ticket_category' => 'walk-in',
                    'price' => $this->walkInPrice,
                    'quantity_available' => $this->walkInQuantity,
                ]);
                $createdTickets[] = $walkInTicket;
            }

            // Create VIP ticket if requested
            if ($this->createVipTickets) {
                $vipTicket = Ticket::create([
                    'concert_id' => $this->concert_id,
                    'ticket_type' => $this->vipTicketType,
                    'ticket_category' => 'vip',
                    'price' => $this->vipPrice,
                    'quantity_available' => $this->vipQuantity,
                ]);
                $createdTickets[] = $vipTicket;
            }

            $ticketCount = count($createdTickets);
            session()->flash('message', "Successfully created {$ticketCount} ticket type(s) for this concert.");

            $this->resetForm();

            $this->dispatch('ticketCreated');

            return $this->redirect(route('admin.tickets'));

        } catch (\Exception $e) {
            // Rollback any created tickets on error
            foreach ($createdTickets as $ticket) {
                $ticket->delete();
            }

            session()->flash('error', 'An error occurred while creating tickets. Please try again.');
        }
    }

    public function resetForm()
    {
        $this->reset([
            'ticket_type',
            'price',
            'createWalkInTickets',
            'walkInTicketType',
            'walkInPrice',
            'walkInQuantity',
            'createVipTickets',
            'vipTicketType',
            'vipPrice',
            'vipQuantity',
        ]);
        $this->quantity_available = 100;
        $this->walkInQuantity = 50;
        $this->vipQuantity = 20;
        $this->walkInTicketType = 'Walk-in Ticket';
        $this->vipTicketType = 'VIP Ticket';
    }

    public function render()
    {
        $concerts = Concert::orderBy('title')->get();

        return view('livewire.admin.tickets.create', [
            'concerts' => $concerts,
        ]);
    }
}
