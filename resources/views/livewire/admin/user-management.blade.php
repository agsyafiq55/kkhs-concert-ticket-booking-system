<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-zinc-700 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <div class="flex justify-between items-center mb-6">
                    <flux:heading size="xl">User Management</flux:heading>
                    <flux:button 
                        wire:click="openCreateUserModal"
                        variant="primary"
                    >
                        Create User
                    </flux:button>
                </div>
                
                @if (session('message'))
                    <flux:callout icon="check-circle" class="mb-4">
                        <flux:callout.heading>Success</flux:callout.heading>
                        <flux:callout.text>{{ session('message') }}</flux:callout.text>
                    </flux:callout>
                @endif
                
                @if (session('error'))
                    <flux:callout icon="exclamation-circle" class="mb-4">
                        <flux:callout.heading>Error</flux:callout.heading>
                        <flux:callout.text>{{ session('error') }}</flux:callout.text>
                    </flux:callout>
                @endif
                
                <!-- Search and Filter Controls -->
                <div class="mb-6 grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Search -->
                    <div>
                        <flux:input icon="magnifying-glass" wire:model.live="search" placeholder="Search users..." />
                    </div>
                    
                    <!-- Role Filter -->
                    <div>
                        <flux:select wire:model.live="roleFilter">
                            <flux:select.option value="admin">Admin</flux:select.option>
                            <flux:select.option value="teacher">Teacher</flux:select.option>
                            <flux:select.option value="student">Student</flux:select.option>
                        </flux:select>
                    </div>
                </div>
                
                <!-- Table Header -->
                <div class="mb-4">
                    <flux:heading size="lg">
                        {{ ucfirst($roleFilter) }} Accounts
                        @if($search)
                            <span class="text-sm font-normal ml-2">(Filtered by: "{{ $search }}")</span>
                        @endif
                    </flux:heading>
                </div>
                
                <!-- Users Table -->
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-zinc-700">
                        <thead class="bg-gray-50 dark:bg-zinc-800">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Name</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Email</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Roles</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-zinc-800/50 divide-y divide-gray-200 dark:divide-zinc-700">
                            @foreach ($users as $user)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $user->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $user->email }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @foreach ($user->roles as $role)
                                            <flux:badge class="mr-1">{{ $role->name }}</flux:badge>
                                        @endforeach
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <flux:button 
                                            wire:click="openEditRolesModal({{ $user->id }})"
                                            variant="primary"
                                        >
                                            Edit Roles
                                        </flux:button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                @if($users->isEmpty())
                <div class="text-center py-4">
                    <flux:text>No {{ strtolower($roleFilter) }} accounts found.</flux:text>
                </div>
                @endif
                
                <div class="mt-4">
                    {{ $users->links() }}
                </div>
            </div>
        </div>
    </div>
    
    <!-- Create User Modal -->
    @if($showCreateModal)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <!-- Background overlay -->
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" wire:click="closeCreateModal"></div>
                
                <!-- Modal panel -->
                <div class="inline-block align-bottom bg-white dark:bg-zinc-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <div class="bg-white dark:bg-zinc-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                                <flux:heading size="lg" class="mb-4">
                                    Create New User Account
                                </flux:heading>
                                <flux:text class="mb-6 text-gray-600 dark:text-gray-300">
                                    Create a new user account and assign roles.
                                </flux:text>
                                
                                <div class="space-y-4">
                                    <!-- Name Field -->
                                    <flux:field>
                                        <flux:label>Name</flux:label>
                                        <flux:input wire:model="name" type="text" placeholder="Full name" />
                                        <flux:error name="name" />
                                    </flux:field>
                                    
                                    <!-- Email Field -->
                                    <flux:field>
                                        <flux:label>Email</flux:label>
                                        <flux:input wire:model="email" type="email" placeholder="email@example.com" />
                                        <flux:error name="email" />
                                    </flux:field>
                                    
                                    <!-- Password Field -->
                                    <flux:field>
                                        <flux:label>Password</flux:label>
                                        <flux:input wire:model="password" type="password" placeholder="Password" />
                                        <flux:error name="password" />
                                    </flux:field>
                                    
                                    <!-- Confirm Password Field -->
                                    <flux:field>
                                        <flux:label>Confirm Password</flux:label>
                                        <flux:input wire:model="password_confirmation" type="password" placeholder="Confirm password" />
                                    </flux:field>
                                    
                                    <!-- Role Selection -->
                                    <div>
                                        <flux:label class="mb-3 block">Assign Roles</flux:label>
                                        <div class="space-y-2">
                                            @foreach ($roles as $role)
                                                <flux:checkbox 
                                                    wire:model="createUserRoles.{{ $role->name }}"
                                                    label="{{ ucfirst($role->name) }}"
                                                />
                                            @endforeach
                                        </div>
                                        <flux:error name="createUserRoles" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-gray-50 dark:bg-zinc-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <flux:button 
                            wire:click="createUser" 
                            wire:loading.attr="disabled" 
                            variant="primary"
                            class="w-full sm:w-auto sm:ml-3"
                        >
                            <span wire:loading.remove wire:target="createUser">Create User</span>
                            <span wire:loading wire:target="createUser">Creating...</span>
                        </flux:button>
                        
                        <flux:button 
                            wire:click="closeCreateModal"
                            variant="subtle"
                            class="mt-3 w-full sm:mt-0 sm:w-auto"
                        >
                            Cancel
                        </flux:button>
                    </div>
                </div>
            </div>
        </div>
    @endif
    
    <!-- Edit Roles Modal -->
    @if($showModal && $editingUser)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <!-- Background overlay -->
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" wire:click="closeModal"></div>
                
                <!-- Modal panel -->
                <div class="inline-block align-bottom bg-white dark:bg-zinc-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <div class="bg-white dark:bg-zinc-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                                <flux:heading size="lg" class="mb-4">
                                    Update User Roles for {{ $editingUser->name }}
                                </flux:heading>
                                <flux:text class="mb-6 text-gray-600 dark:text-gray-300">
                                    Select the roles for this user.
                                </flux:text>
                                
                                <div class="space-y-4">
                                    @foreach ($roles as $role)
                                        <flux:checkbox 
                                            wire:model="selectedRoles.{{ $role->name }}"
                                            label="{{ ucfirst($role->name) }}"
                                        />
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-gray-50 dark:bg-zinc-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <flux:button 
                            wire:click="updateRoles" 
                            wire:loading.attr="disabled" 
                            variant="primary"
                            class="w-full sm:w-auto sm:ml-3"
                        >
                            <span wire:loading.remove wire:target="updateRoles">Save Changes</span>
                            <span wire:loading wire:target="updateRoles">Saving...</span>
                        </flux:button>
                        
                        <flux:button 
                            wire:click="closeModal"
                            variant="subtle"
                            class="mt-3 w-full sm:mt-0 sm:w-auto"
                        >
                            Cancel
                        </flux:button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
