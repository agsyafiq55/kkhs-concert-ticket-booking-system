<div class="space-y-6">
    <div>
        <flux:heading size="lg">Role & Permission Management</flux:heading>
        <flux:text class="mt-2">Manage roles and their associated permissions. Only Super Admins can access this section.</flux:text>
    </div>

    <!-- Flash Messages -->
    @if (session()->has('message'))
        <flux:callout icon="check-circle" variant="success">
            {{ session('message') }}
        </flux:callout>
    @endif

    @if (session()->has('error'))
        <flux:callout icon="exclamation-triangle" variant="danger">
            {{ session('error') }}
        </flux:callout>
    @endif

    <!-- Role Hierarchy Overview -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        @foreach($roles->where('name', '!=', 'super-admin') as $role)
        <div class="border border-zinc-200 dark:border-zinc-700 rounded-lg p-4 hover:bg-zinc-50 dark:hover:bg-zinc-800 transition-colors">
            <div class="flex items-center justify-between mb-3">
                <flux:heading size="sm">{{ ucfirst(str_replace('-', ' ', $role->name)) }}</flux:heading>
                @if($role->name === 'admin')
                    <flux:badge color="blue">Admin</flux:badge>
                @elseif($role->name === 'teacher')
                    <flux:badge color="green">Limited</flux:badge>
                @else
                    <flux:badge color="gray">Basic</flux:badge>
                @endif
            </div>
            
            <flux:text class="text-sm mb-3">{{ $role->permissions->count() }} permissions assigned</flux:text>
            
            <flux:button 
                wire:click="selectRole({{ $role->id }})" 
                variant="filled" 
                size="sm"
                class="w-full"
            >
                Manage Permissions
            </flux:button>
        </div>
        @endforeach
    </div>

    <!-- Permission Management Modal -->
    @if($showPermissionModal && $selectedRole)
    <flux:modal name="permission-modal" class="md:w-2xl" wire:model="showPermissionModal">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">Manage Permissions for {{ ucfirst(str_replace('-', ' ', $selectedRole->name)) }}</flux:heading>
                <flux:text class="mt-2">Select the permissions for this role. Changes will be saved immediately.</flux:text>
            </div>

            <div class="space-y-4">
                @php
                    // Group permissions dynamically based on actual database permissions
                    $permissionGroups = [];
                    
                    foreach($permissions as $permission) {
                        $name = $permission->name;
                        
                        // Skip "manage permissions" - only super admin should have this permanently
                        if ($name === 'manage permissions') {
                            continue;
                        }
                        
                        // Categorize permissions based on their names
                        if (str_contains($name, 'concert')) {
                            $permissionGroups['Concerts'][] = $permission;
                        } elseif (str_contains($name, 'ticket')) {
                            $permissionGroups['Tickets'][] = $permission;
                        } elseif (str_contains($name, 'user') || str_contains($name, 'bulk upload students')) {
                            $permissionGroups['Users'][] = $permission;
                        } elseif (str_contains($name, 'role') || str_contains($name, 'permission')) {
                            $permissionGroups['Roles & Permissions'][] = $permission;
                        } elseif (str_contains($name, 'sale') || str_contains($name, 'report')) {
                            $permissionGroups['Reports & Sales'][] = $permission;
                        } elseif (str_contains($name, 'walk-in')) {
                            $permissionGroups['Walk-in Management'][] = $permission;
                        } elseif (str_contains($name, 'own')) {
                            $permissionGroups['Student Features'][] = $permission;
                        } else {
                            $permissionGroups['Other'][] = $permission;
                        }
                    }
                @endphp

                @foreach($permissionGroups as $groupName => $groupPermissions)
                <div class="border border-zinc-200 dark:border-zinc-700 rounded-lg p-4">
                    <flux:heading size="sm" class="mb-3">{{ $groupName }}</flux:heading>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                        @foreach($groupPermissions as $permission)
                            <flux:field variant="inline">
                                <flux:checkbox 
                                    wire:model="rolePermissions" 
                                    value="{{ $permission->id }}"
                                />
                                <flux:label>{{ ucfirst($permission->name) }}</flux:label>
                            </flux:field>
                        @endforeach
                    </div>
                </div>
                @endforeach

                <!-- Information about Super Admin exclusive permissions -->
                <div class="border border-amber-200 dark:border-amber-700 rounded-lg p-4 bg-amber-50 dark:bg-amber-900/20">
                    <flux:heading size="sm" class="mb-2 text-amber-800 dark:text-amber-200">Super Admin Exclusive</flux:heading>
                    <flux:text class="text-sm text-amber-700 dark:text-amber-300">
                        The "Manage Permissions" permission is permanently reserved for Super Admins only and cannot be assigned to other roles. Super Admins have all access to everything by default.
                    </flux:text>
                </div>
            </div>

            <div class="flex gap-3 justify-end">
                <flux:button wire:click="closeModal" variant="ghost">
                    Cancel
                </flux:button>
                <flux:button wire:click="updateRolePermissions" variant="primary">
                    Save Changes
                </flux:button>
            </div>
        </div>
    </flux:modal>
    @endif

    <!-- Current Role Hierarchy Information -->
    <div class="border border-zinc-200 dark:border-zinc-700 rounded-lg p-6">
        <flux:heading size="md" class="mb-4">Role Hierarchy Overview</flux:heading>
        
        <div class="space-y-4">
            <div class="flex items-start gap-3">
                <flux:badge color="red">Super Admin</flux:badge>
                <div>
                    <flux:text class="font-medium">Super Administrator</flux:text>
                    <flux:text class="text-sm text-zinc-600 dark:text-zinc-400">Has all permissions in the system including user and role management</flux:text>
                </div>
            </div>
            
            <div class="flex items-start gap-3">
                <flux:badge color="blue">Admin</flux:badge>
                <div>
                    <flux:text class="font-medium">Administrator</flux:text>
                    <flux:text class="text-sm text-zinc-600 dark:text-zinc-400">Has all permissions except user roles and permissions management</flux:text>
                </div>
            </div>
            
            <div class="flex items-start gap-3">
                <flux:badge color="green">Teacher</flux:badge>
                <div>
                    <flux:text class="font-medium">Teacher</flux:text>
                    <flux:text class="text-sm text-zinc-600 dark:text-zinc-400">Can scan tickets, assign tickets to students, and bulk upload student accounts</flux:text>
                </div>
            </div>
            
            <div class="flex items-start gap-3">
                <flux:badge color="gray">Student</flux:badge>
                <div>
                    <flux:text class="font-medium">Student</flux:text>
                    <flux:text class="text-sm text-zinc-600 dark:text-zinc-400">Can only view their own tickets</flux:text>
                </div>
            </div>
        </div>
    </div>
</div>
