<?php

namespace App\Livewire\Admin\Concerts;

use App\Models\Concert;
use Livewire\Component;

class Create extends Component
{
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
        'date' => 'required|date|after_or_equal:today',
        'start_time' => 'required',
        'end_time' => 'required|after:start_time',
    ];
    
    public function mount()
    {
        // Set default date to today
        $this->date = date('Y-m-d');
        
        // Set default times
        $this->start_time = '18:00';
        $this->end_time = '21:00';
    }
    
    public function save()
    {
        $this->validate();
        
        Concert::create([
            'title' => $this->title,
            'description' => $this->description,
            'venue' => $this->venue,
            'date' => $this->date,
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
        ]);
        
        session()->flash('message', 'Concert successfully created.');
        
        $this->reset(['title', 'description', 'venue']);
        $this->date = date('Y-m-d');
        $this->start_time = '18:00';
        $this->end_time = '21:00';
        
        $this->dispatch('concertCreated');
        
        return $this->redirect(route('admin.concerts'));
    }
    
    public function render()
    {
        return view('livewire.admin.concerts.create');
    }
}
