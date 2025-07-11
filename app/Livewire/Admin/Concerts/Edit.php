<?php

namespace App\Livewire\Admin\Concerts;

use App\Models\Concert;
use Illuminate\Support\Facades\Gate;
use Livewire\Component;

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

    public function mount($id)
    {
        // Check if user has permission to edit concerts
        if (! Gate::allows('edit concerts')) {
            abort(403, 'You do not have permission to edit concerts.');
        }

        $this->concert = Concert::findOrFail($id);
        $this->title = $this->concert->title;
        $this->description = $this->concert->description;
        $this->venue = $this->concert->venue;
        $this->date = $this->concert->date ? $this->concert->date->format('Y-m-d') : '';
        $this->start_time = $this->concert->start_time ? $this->concert->start_time->format('H:i') : '';
        $this->end_time = $this->concert->end_time ? $this->concert->end_time->format('H:i') : '';
    }

    public function save()
    {
        // Double-check permission before saving
        if (! Gate::allows('edit concerts')) {
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
