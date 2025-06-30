<?php

namespace App\Livewire\Admin;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;

class UserManagement extends Component
{
    use WithPagination;
    
    public $selectedRoles = [];
    public $search = '';
    public $roleFilter = 'admin'; // Default filter for admin accounts
    public $editingUser = null;
    public $showModal = false;
    
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
        // Check if user has permission to manage roles
        if (!Gate::allows('manage roles')) {
            abort(403, 'You do not have permission to manage user roles.');
        }
        
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
    
    public function openEditRolesModal($userId)
    {
        try {
            $user = User::with('roles')->find($userId);
            
            if (!$user) {
                session()->flash('error', 'User not found.');
                return;
            }
            
            $this->editingUser = $user;
            
            // Reset selected roles array
            $this->selectedRoles = [];
            
            // Get all available roles and set selected ones
            $roles = Role::all();
            $userRoleNames = $user->roles->pluck('name')->toArray();
            
            foreach ($roles as $role) {
                $this->selectedRoles[$role->name] = in_array($role->name, $userRoleNames);
            }
            
            $this->showModal = true;
            
        } catch (\Exception $e) {
            session()->flash('error', 'An error occurred: ' . $e->getMessage());
        }
    }
    
    public function closeModal()
    {
        $this->showModal = false;
        $this->editingUser = null;
        $this->selectedRoles = [];
    }
    
    public function updateRoles()
    {
        try {
            $this->validate();
            
            if (!$this->editingUser) {
                session()->flash('error', 'No user selected.');
                $this->closeModal();
                return;
            }
            
            // Get selected role names (where value is true)
            $rolesToSync = array_keys(array_filter($this->selectedRoles));
            
            // Sync the user's roles
            $this->editingUser->syncRoles($rolesToSync);
            
            session()->flash('message', 'User roles updated successfully for ' . $this->editingUser->name . '.');
            
            $this->closeModal();
            
        } catch (\Exception $e) {
            session()->flash('error', 'An error occurred: ' . $e->getMessage());
            $this->closeModal();
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
