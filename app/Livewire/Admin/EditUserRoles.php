<?php

namespace App\Livewire\Admin;

use App\Models\User;
use Livewire\Component;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Gate;

class EditUserRoles extends Component
{
    public $user;
    public $selectedRole = '';
    public $isUpdating = false;
    
    protected $rules = [
        'selectedRole' => 'required|string|exists:roles,name',
    ];

    protected $messages = [
        'selectedRole.required' => 'Please select a role for the user.',
        'selectedRole.exists' => 'The selected role is invalid.',
    ];
    
    public function mount($userId)
    {
        // Check if user has permission to manage roles
        if (!Gate::allows('manage roles')) {
            abort(403, 'You do not have permission to manage user roles.');
        }
        
        try {
            $this->user = User::with('roles')->findOrFail($userId);
            
            // Set the current role (get the first role if user has multiple)
            $currentRole = $this->user->roles->first();
            $this->selectedRole = $currentRole ? $currentRole->name : '';
            
        } catch (\Exception $e) {
            session()->flash('error', 'User not found.');
            return redirect()->route('admin.users');
        }
    }
    
    public function updateRoles()
    {
        $this->isUpdating = true;
        
        try {
            $this->validate();
            
            // Remove all current roles and assign the selected one
            $this->user->syncRoles([$this->selectedRole]);
            
            session()->flash('message', 'User role updated successfully for ' . $this->user->name . '.');
            
            return redirect()->route('admin.users');
            
        } catch (\Exception $e) {
            session()->flash('error', 'An error occurred: ' . $e->getMessage());
        } finally {
            $this->isUpdating = false;
        }
    }
    
    public function cancel()
    {
        return redirect()->route('admin.users');
    }
    
    public function render()
    {
        $roles = Role::orderBy('name')->get();
        
        return view('livewire.admin.edit-user-roles', [
            'roles' => $roles
        ]);
    }
} 