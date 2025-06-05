<?php

namespace App\Livewire\Admin\Concerts;

use App\Models\Concert;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;
    
    public $search = '';
    public $concertIdToDelete = null;
    
    protected $listeners = [
        'concertCreated' => '$refresh',
        'concertUpdated' => '$refresh',
    ];
    
    public function updatingSearch()
    {
        $this->resetPage();
    }
    
    public function confirmDelete($concertId)
    {
        $this->concertIdToDelete = $concertId;
    }
    
    public function deleteConcert()
    {
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
