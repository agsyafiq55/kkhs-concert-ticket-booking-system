<?php

namespace App\Livewire\Admin\Concerts;

use App\Models\Concert;
use Livewire\Component;

class Edit extends Component
{
    public $concertId;
    public $title;
    public $description;
    public $venue;
    public $date;
    public $start_time;
    public $end_time;
    
    protected $rules = [
        'title' => 'required|string|max:255',
        'description' => 'required|string',
        'venue' => 'required|string|max:255',
        'date' => 'required|date',
        'start_time' => 'required',
        'end_time' => 'required|after:start_time',
    ];
    
    public function mount($id)
    {
        $this->concertId = $id;
        $concert = Concert::findOrFail($id);
        
        $this->title = $concert->title;
        $this->description = $concert->description;
        $this->venue = $concert->venue;
        $this->date = $concert->date->format('Y-m-d');
        $this->start_time = $concert->start_time->format('H:i');
        $this->end_time = $concert->end_time->format('H:i');
    }
    
    public function update()
    {
        $this->validate();
        
        $concert = Concert::findOrFail($this->concertId);
        
        $concert->update([
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
