<?php

namespace App\Livewire\Admin;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Auth\Events\Registered;

class UserManagement extends Component
{
    use WithPagination;
    
    public $selectedRoles = [];
    public $search = '';
    public $roleFilter = 'admin'; // Default filter for admin accounts
    public $editingUser = null;
    public $showModal = false;
    public $showCreateModal = false;
    public $showDeleteModal = false;
    public $userToDelete = null;
    
    // User creation form properties
    public $name = '';
    public $email = '';
    public $password = '';
    public $password_confirmation = '';
    public $createUserRoles = [];
    
    protected $rules = [
        'selectedRoles' => 'array',
        'name' => 'required|string|max:255',
        'email' => 'required|string|lowercase|email|max:255|unique:users',
        'password' => 'required|string|confirmed|min:8',
        'createUserRoles' => 'required|array|min:1',
    ];

    protected $messages = [
        'createUserRoles.required' => 'Please select at least one role for the user.',
        'createUserRoles.min' => 'Please select at least one role for the user.',
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

    public function openCreateUserModal()
    {
        $this->resetCreateForm();
        $this->showCreateModal = true;
    }

    public function closeCreateModal()
    {
        $this->showCreateModal = false;
        $this->resetCreateForm();
    }

    public function resetCreateForm()
    {
        $this->name = '';
        $this->email = '';
        $this->password = '';
        $this->password_confirmation = '';
        $this->createUserRoles = [];
        $this->resetErrorBag();
    }

    public function createUser()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|lowercase|email|max:255|unique:users',
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
            'createUserRoles' => 'required|array|min:1',
        ]);

        try {
            // Create the user
            $user = User::create([
                'name' => $this->name,
                'email' => $this->email,
                'password' => Hash::make($this->password),
            ]);

            // Assign roles to the user
            $roleNames = array_keys(array_filter($this->createUserRoles));
            $user->assignRole($roleNames);

            // Fire the registered event (for email verification if needed)
            event(new Registered($user));

            session()->flash('message', 'User created successfully: ' . $user->name);
            
            $this->closeCreateModal();
            
        } catch (\Exception $e) {
            session()->flash('error', 'An error occurred while creating the user: ' . $e->getMessage());
        }
    }

    public function openDeleteModal($userId)
    {
        // Check if user has permission to delete users
        if (!Gate::allows('delete users')) {
            session()->flash('error', 'You do not have permission to delete users.');
            return;
        }

        try {
            $user = User::find($userId);
            
            if (!$user) {
                session()->flash('error', 'User not found.');
                return;
            }

            // Prevent deleting the current logged-in user
            if ($user->id === Auth::id()) {
                session()->flash('error', 'You cannot delete your own account.');
                return;
            }

            // Prevent deleting super-admins unless current user is also super-admin
            $currentUser = Auth::user();
            if ($user->roles->contains('name', 'super-admin') && !$currentUser->roles->contains('name', 'super-admin')) {
                session()->flash('error', 'You do not have permission to delete super-admin accounts.');
                return;
            }

            $this->userToDelete = $user;
            $this->showDeleteModal = true;
            
        } catch (\Exception $e) {
            session()->flash('error', 'An error occurred: ' . $e->getMessage());
        }
    }

    public function closeDeleteModal()
    {
        $this->showDeleteModal = false;
        $this->userToDelete = null;
    }

    public function deleteUser()
    {
        // Double-check permissions
        if (!Gate::allows('delete users')) {
            session()->flash('error', 'You do not have permission to delete users.');
            $this->closeDeleteModal();
            return;
        }

        if (!$this->userToDelete) {
            session()->flash('error', 'No user selected for deletion.');
            $this->closeDeleteModal();
            return;
        }

        try {
            $userName = $this->userToDelete->name;
            
            // Additional safety checks
            if ($this->userToDelete->id === Auth::id()) {
                session()->flash('error', 'You cannot delete your own account.');
                $this->closeDeleteModal();
                return;
            }

            $currentUser = Auth::user();
            if ($this->userToDelete->roles->contains('name', 'super-admin') && !$currentUser->roles->contains('name', 'super-admin')) {
                session()->flash('error', 'You do not have permission to delete super-admin accounts.');
                $this->closeDeleteModal();
                return;
            }

            // Delete the user
            $this->userToDelete->delete();

            session()->flash('message', 'User "' . $userName . '" has been deleted successfully.');
            
            $this->closeDeleteModal();
            
        } catch (\Exception $e) {
            session()->flash('error', 'An error occurred while deleting the user: ' . $e->getMessage());
            $this->closeDeleteModal();
        }
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
            $this->validate([
                'selectedRoles' => 'array',
            ]);
            
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

    public function canDeleteUser($user)
    {
        // Check if current user has delete users permission
        if (!Gate::allows('delete users')) {
            return false;
        }

        // Cannot delete yourself
        if ($user->id === Auth::id()) {
            return false;
        }

        // Only super-admins can delete other super-admins
        $currentUser = Auth::user();
        if ($user->roles->contains('name', 'super-admin') && !$currentUser->roles->contains('name', 'super-admin')) {
            return false;
        }

        return true;
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
