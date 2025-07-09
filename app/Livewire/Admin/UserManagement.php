<?php

namespace App\Livewire\Admin;

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Role;

class UserManagement extends Component
{
    use WithPagination;

    public $search = '';

    public $roleFilter = 'admin'; // Default filter for admin accounts

    public $userToDelete = null;

    // User creation form properties
    public $name = '';

    public $email = '';

    public $password = '';

    public $password_confirmation = '';

    public $createUserRole = '';

    // UI state
    public $isCreating = false;

    public $isDeleting = false;

    protected $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|string|lowercase|email|max:255|unique:users',
        'password' => 'required|string|confirmed|min:8',
        'createUserRole' => 'required|string|exists:roles,name',
    ];

    protected $messages = [
        'createUserRole.required' => 'Please select a role for the user.',
        'createUserRole.exists' => 'The selected role is invalid.',
    ];

    // Define which properties should be refreshed when updated
    protected $queryString = [
        'search' => ['except' => ''],
        'roleFilter' => ['except' => 'admin'],
    ];

    public function mount()
    {
        // Check if user has permission to manage roles
        if (! Gate::allows('manage roles')) {
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

    public function resetCreateForm()
    {
        $this->name = '';
        $this->email = '';
        $this->password = '';
        $this->password_confirmation = '';
        $this->createUserRole = '';
        $this->resetErrorBag();
    }

    public function createUser()
    {
        $this->isCreating = true;

        $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|lowercase|email|max:255|unique:users',
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
            'createUserRole' => 'required|string|exists:roles,name',
        ]);

        try {
            // Create the user
            $user = User::create([
                'name' => $this->name,
                'email' => $this->email,
                'password' => Hash::make($this->password),
            ]);

            // Assign the selected role to the user
            $user->assignRole($this->createUserRole);

            // Fire the registered event (for email verification if needed)
            event(new Registered($user));

            session()->flash('message', 'User created successfully: '.$user->name);

            $this->resetCreateForm();
            $this->dispatch('close-modal', 'create-user');

        } catch (\Exception $e) {
            session()->flash('error', 'An error occurred while creating the user: '.$e->getMessage());
        } finally {
            $this->isCreating = false;
        }
    }

    public function prepareDeleteUser($userId)
    {
        // Check if user has permission to delete users
        if (! Gate::allows('delete users')) {
            session()->flash('error', 'You do not have permission to delete users.');

            return;
        }

        try {
            $user = User::find($userId);

            if (! $user) {
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
            if ($user->roles->contains('name', 'super-admin') && ! $currentUser->roles->contains('name', 'super-admin')) {
                session()->flash('error', 'You do not have permission to delete super-admin accounts.');

                return;
            }

            $this->userToDelete = $user;

        } catch (\Exception $e) {
            session()->flash('error', 'An error occurred: '.$e->getMessage());
        }
    }

    public function deleteUser()
    {
        $this->isDeleting = true;

        // Double-check permissions
        if (! Gate::allows('delete users')) {
            session()->flash('error', 'You do not have permission to delete users.');
            $this->dispatch('close-modal', 'delete-user');
            $this->isDeleting = false;

            return;
        }

        if (! $this->userToDelete) {
            session()->flash('error', 'No user selected for deletion.');
            $this->dispatch('close-modal', 'delete-user');
            $this->isDeleting = false;

            return;
        }

        try {
            $userName = $this->userToDelete->name;

            // Additional safety checks
            if ($this->userToDelete->id === Auth::id()) {
                session()->flash('error', 'You cannot delete your own account.');
                $this->dispatch('close-modal', 'delete-user');

                return;
            }

            $currentUser = Auth::user();
            if ($this->userToDelete->roles->contains('name', 'super-admin') && ! $currentUser->roles->contains('name', 'super-admin')) {
                session()->flash('error', 'You do not have permission to delete super-admin accounts.');
                $this->dispatch('close-modal', 'delete-user');

                return;
            }

            // Delete the user
            $this->userToDelete->delete();

            session()->flash('message', 'User "'.$userName.'" has been deleted successfully.');

            $this->userToDelete = null;
            $this->dispatch('close-modal', 'delete-user');

        } catch (\Exception $e) {
            session()->flash('error', 'An error occurred while deleting the user: '.$e->getMessage());
            $this->dispatch('close-modal', 'delete-user');
        } finally {
            $this->isDeleting = false;
        }
    }

    public function canDeleteUser($user)
    {
        // Check if current user has delete users permission
        if (! Gate::allows('delete users')) {
            return false;
        }

        // Cannot delete yourself
        if ($user->id === Auth::id()) {
            return false;
        }

        // Only super-admins can delete other super-admins
        $currentUser = Auth::user();
        if ($user->roles->contains('name', 'super-admin') && ! $currentUser->roles->contains('name', 'super-admin')) {
            return false;
        }

        return true;
    }

    public function clearFilters()
    {
        $this->search = '';
        $this->roleFilter = 'admin';
        $this->resetPage();
    }

    public function render()
    {
        $query = User::with('roles')
            ->where(function ($query) {
                $query->where('name', 'like', '%'.$this->search.'%')
                    ->orWhere('email', 'like', '%'.$this->search.'%');
            });

        // Filter by role if a role filter is selected
        if ($this->roleFilter) {
            $query->whereHas('roles', function ($q) {
                $q->where('name', $this->roleFilter);
            });
        }

        $users = $query->orderBy('name')->paginate(10);
        $roles = Role::orderBy('name')->get();

        return view('livewire.admin.user-management', [
            'users' => $users,
            'roles' => $roles,
        ]);
    }
}
