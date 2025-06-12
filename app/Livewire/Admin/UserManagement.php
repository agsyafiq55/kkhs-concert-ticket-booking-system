<?php

namespace App\Livewire\Admin;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Role;

class UserManagement extends Component
{
    use WithPagination;
    
    public $userId;
    public $selectedRoles = [];
    public $search = '';
    public $roleFilter = 'admin'; // Default filter for admin accounts
    
    protected $rules = [
        'selectedRoles' => 'required|array|min:1',
        'userId' => 'required|exists:users,id'
    ];
    
    public function mount()
    {
        $this->resetPage();
    }
    
    public function updatingSearch()
    {
        $this->resetPage();
    }
    
    public function updatingRoleFilter()
    {
        $this->resetPage();
    }
    
    public function editUserRoles($userId)
    {
        $this->userId = $userId;
        $user = User::find($userId);
        $this->selectedRoles = $user->roles->pluck('name')->toArray();
    }
    
    public function updateRoles()
    {
        $this->validate();
        
        $user = User::find($this->userId);
        $user->syncRoles($this->selectedRoles);
        
        session()->flash('message', 'User roles updated successfully.');
        $this->reset('userId', 'selectedRoles');
    }
    
    public function render()
    {
        $query = User::query()
            ->where(function($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('email', 'like', '%' . $this->search . '%');
            });
            
        // Filter by role if a role filter is selected
        if ($this->roleFilter) {
            $query->whereHas('roles', function($q) {
                $q->where('name', $this->roleFilter);
            });
        }
        
        $users = $query->paginate(10);
        $roles = Role::all();
        
        return view('livewire.admin.user-management', [
            'users' => $users,
            'roles' => $roles
        ]);
    }
}
