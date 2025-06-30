<?php

namespace App\Livewire\Admin\Concerts;

use App\Models\Concert;
use Livewire\Component;
use Illuminate\Support\Facades\Gate;

class Edit extends Component
{
    public $concert;
    public $title = '';
    public $description = '';
    public $venue = '';
    public $date = '';
    public $start_time = '';
    public $end_time = '';
    
    protected $rules = [
        'title' => 'required|string|max:255',
        'description' => 'required|string',
        'venue' => 'required|string|max:255',
        'date' => 'required|date',
        'start_time' => 'required',
        'end_time' => 'required|after:start_time',
    ];
    
    public function mount(Concert $concert)
    {
        // Check if user has permission to edit concerts
        if (!Gate::allows('edit concerts')) {
            abort(403, 'You do not have permission to edit concerts.');
        }
        
        $this->concert = $concert;
        $this->title = $concert->title;
        $this->description = $concert->description;
        $this->venue = $concert->venue;
        $this->date = $concert->date;
        $this->start_time = $concert->start_time;
        $this->end_time = $concert->end_time;
    }
    
    public function save()
    {
        // Double-check permission before saving
        if (!Gate::allows('edit concerts')) {
            session()->flash('error', 'You do not have permission to edit concerts.');
            return;
        }
        
        $this->validate();
        
        $this->concert->update([
            'title' => $this->title,
            'description' => $this->description,
            'venue' => $this->venue,
            'date' => $this->date,
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
        ]);
        
        session()->flash('message', 'Concert successfully updated.');
        
        $this->dispatch('concertUpdated');
        
        return $this->redirect(route('admin.concerts'));
    }
    
    public function render()
    {
        return view('livewire.admin.concerts.edit');
    }
}
