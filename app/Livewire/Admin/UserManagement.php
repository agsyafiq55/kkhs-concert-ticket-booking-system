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
    public $editingUser = null;
    
    protected $rules = [
        'selectedRoles' => 'array',
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
    
    public function prepareRoleUpdate($userId)
    {
        try {
            $this->userId = $userId;
            $user = User::with('roles')->find($userId);
            
            if (!$user) {
                session()->flash('error', 'User not found.');
                return;
            }
            
            $this->editingUser = $user;
            
            // Reset selected roles
            $this->selectedRoles = [];
            
            // Get all available roles
            $allRoles = Role::all();
            
            // Create an array of role names that the user has
            $userRoleNames = $user->roles->pluck('name')->toArray();
            
            // For each available role, check if the user has it
            foreach ($allRoles as $role) {
                $this->selectedRoles[$role->name] = in_array($role->name, $userRoleNames);
            }
        } catch (\Exception $e) {
            session()->flash('error', 'An error occurred: ' . $e->getMessage());
        }
    }
    
    public function closeModal()
    {
        $this->reset(['userId', 'selectedRoles', 'editingUser']);
    }
    
    public function updateRoles()
    {
        try {
            $this->validate();
            
            if (!$this->userId) {
                session()->flash('error', 'No user selected.');
                return;
            }
            
            $user = User::find($this->userId);
            
            if (!$user) {
                session()->flash('error', 'User not found.');
                return;
            }
            
            // Get selected role names (where value is true)
            $rolesToSync = array_keys(array_filter($this->selectedRoles));
            
            // Sync the user's roles
            $user->syncRoles($rolesToSync);
            
            session()->flash('message', 'User roles updated successfully.');
            $this->closeModal();
            
        } catch (\Exception $e) {
            session()->flash('error', 'An error occurred: ' . $e->getMessage());
        }
    }
    
    public function render()
    {
        $query = User::with('roles')
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
