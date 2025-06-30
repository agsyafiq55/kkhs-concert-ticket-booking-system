<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;

class RolePermissionManagement extends Component
{
    public $roles = [];
    public $permissions = [];
    public $selectedRole = null;
    public $rolePermissions = [];
    public $showPermissionModal = false;
    
    public function mount()
    {
        // Check if user has permission to manage roles
        if (!Gate::allows('manage roles')) {
            abort(403, 'You do not have permission to manage roles and permissions.');
        }
        
        $this->loadData();
    }
    
    public function loadData()
    {
        $this->roles = Role::with('permissions')->get();
        $this->permissions = Permission::all();
    }
    
    public function selectRole($roleId)
    {
        $this->selectedRole = Role::with('permissions')->find($roleId);
        $this->rolePermissions = $this->selectedRole->permissions->pluck('id')->toArray();
        $this->showPermissionModal = true;
    }
    
    public function updateRolePermissions()
    {
        if (!$this->selectedRole) {
            session()->flash('error', 'No role selected.');
            return;
        }
        
        try {
            // Filter out any invalid permission IDs
            $validPermissionIds = Permission::whereIn('id', $this->rolePermissions)->pluck('id')->toArray();
            
            if (count($validPermissionIds) !== count($this->rolePermissions)) {
                $invalidIds = array_diff($this->rolePermissions, $validPermissionIds);
                Log::warning('Invalid permission IDs found: ' . implode(', ', $invalidIds));
            }
            
            // Sync permissions for the selected role using valid IDs only
            $this->selectedRole->syncPermissions($validPermissionIds);
            
            session()->flash('message', 'Permissions updated successfully for ' . $this->selectedRole->name . ' role.');
            
            $this->closeModal();
            $this->loadData();
            
        } catch (\Exception $e) {
            Log::error('Permission update error: ' . $e->getMessage());
            session()->flash('error', 'An error occurred: ' . $e->getMessage());
        }
    }
    
    public function closeModal()
    {
        $this->showPermissionModal = false;
        $this->selectedRole = null;
        $this->rolePermissions = [];
    }
    
    public function render()
    {
        return view('livewire.admin.role-permission-management');
    }
}
