<?php

namespace App\Livewire\Admin\Concerts;

use App\Models\Concert;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Gate;

class Index extends Component
{
    use WithPagination;
    
    public $search = '';
    public $concertIdToDelete = null;
    
    protected $listeners = [
        'concertCreated' => '$refresh',
        'concertUpdated' => '$refresh',
    ];
    
    public function mount()
    {
        // Check if user has permission to view concerts
        if (!Gate::allows('view concerts')) {
            abort(403, 'You do not have permission to view concerts.');
        }
    }
    
    public function updatingSearch()
    {
        $this->resetPage();
    }
    
    public function confirmDelete($concertId)
    {
        // Check if user has permission to delete concerts
        if (!Gate::allows('delete concerts')) {
            session()->flash('error', 'You do not have permission to delete concerts.');
            return;
        }
        
        $this->concertIdToDelete = $concertId;
    }
    
    public function deleteConcert()
    {
        // Double-check permission before actual deletion
        if (!Gate::allows('delete concerts')) {
            session()->flash('error', 'You do not have permission to delete concerts.');
            return;
        }
        
        if ($this->concertIdToDelete) {
            $concert = Concert::find($this->concertIdToDelete);
            
            if ($concert) {
                $concert->delete();
                session()->flash('message', 'Concert successfully deleted.');
            }
            
            $this->concertIdToDelete = null;
        }
    }
    
    public function cancelDelete()
    {
        $this->concertIdToDelete = null;
    }
    
    public function render()
    {
        $concerts = Concert::where('title', 'like', '%' . $this->search . '%')
                        ->orWhere('venue', 'like', '%' . $this->search . '%')
                        ->orderBy('date', 'desc')
                        ->paginate(10);
                        
        return view('livewire.admin.concerts.index', [
            'concerts' => $concerts,
        ]);
    }
}
