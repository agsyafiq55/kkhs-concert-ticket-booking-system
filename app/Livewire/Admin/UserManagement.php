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
    public $modalName = null;
    
    protected $rules = [
        'selectedRoles' => 'array',
    ];
    
    // Define which properties should be refreshed when updated
    protected $queryString = [
        'search' => ['except' => ''],
        'roleFilter' => ['except' => 'admin'],
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
            // Only load new user data if it's a different user
            if ($this->userId != $userId) {
                // Clear previous selections first
                $this->selectedRoles = [];
                $this->editingUser = null;
                
                // Set the user ID and find the user
                $this->userId = $userId;
                $user = User::with('roles')->find($userId);
                
                if (!$user) {
                    session()->flash('error', 'User not found.');
                    return;
                }
                
                $this->editingUser = $user;
                
                // Generate a unique modal name for this user
                $this->modalName = 'edit-roles-' . $userId;
                
                // Get all available roles
                $allRoles = Role::all();
                
                // Create an array of role names that the user has
                $userRoleNames = $user->roles->pluck('name')->toArray();
                
                // For each available role, check if the user has it
                foreach ($allRoles as $role) {
                    $this->selectedRoles[$role->name] = in_array($role->name, $userRoleNames);
                }
            }
        } catch (\Exception $e) {
            session()->flash('error', 'An error occurred: ' . $e->getMessage());
            $this->clearModalState();
        }
    }
    
    public function clearModalState()
    {
        $modalName = $this->modalName;
        $this->reset(['userId', 'selectedRoles', 'editingUser', 'modalName']);
        
        // Force close any open modals using JavaScript
        $this->dispatch('force-close-modal', ['modalName' => $modalName]);
    }
    
    public function updateRoles()
    {
        try {
            // Save the current modal name before validation potentially wipes it
            $modalName = $this->modalName;
            
            $this->validate();
            
            if (!$this->userId) {
                session()->flash('error', 'No user selected.');
                $this->clearModalState();
                return;
            }
            
            $user = User::find($this->userId);
            
            if (!$user) {
                session()->flash('error', 'User not found.');
                $this->clearModalState();
                return;
            }
            
            // Get selected role names (where value is true)
            $rolesToSync = array_keys(array_filter($this->selectedRoles));
            
            // Sync the user's roles
            $user->syncRoles($rolesToSync);
            
            session()->flash('message', 'User roles updated successfully.');
            
            // Reset state first
            $this->reset(['userId', 'selectedRoles', 'editingUser', 'modalName']);
            
            // Then forcefully close the modal
            $this->dispatch('force-close-modal', ['modalName' => $modalName]);
            
        } catch (\Exception $e) {
            session()->flash('error', 'An error occurred: ' . $e->getMessage());
            $this->clearModalState();
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
