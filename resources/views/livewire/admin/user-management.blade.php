<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6" x-data="{ selectedUser: null }">
                <flux:heading size="xl" class="mb-6">User Management</flux:heading>
                
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
                        <flux:input wire:model.live="search" placeholder="Search users..." />
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
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Name</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Email</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Roles</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
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
                                        <flux:modal.trigger name="edit-user-roles">
                                            <flux:button 
                                                wire:click="prepareRoleUpdate({{ $user->id }})"
                                                variant="primary"
                                            >
                                                Edit Roles
                                            </flux:button>
                                        </flux:modal.trigger>
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
                
                <!-- Edit Roles Modal -->
                <flux:modal name="edit-user-roles" class="md:w-96">
                    @if($editingUser)
                        <div class="space-y-6">
                            <div>
                                <flux:heading size="lg">Update User Roles for {{ $editingUser->name }}</flux:heading>
                                <flux:text class="mt-2">Select the roles for this user.</flux:text>
                            </div>
                            
                            <div class="space-y-4">
                                @foreach ($roles as $role)
                                    <flux:checkbox 
                                        wire:model="selectedRoles.{{ $role->name }}"
                                        label="{{ ucfirst($role->name) }}"
                                    />
                                @endforeach
                            </div>
                            
                            <div class="flex justify-end space-x-3 mt-6">
                                <flux:button 
                                    wire:click="closeModal" 
                                    variant="subtle"
                                >
                                    Cancel
                                </flux:button>
                                <flux:button 
                                    wire:click="updateRoles" 
                                    wire:loading.attr="disabled" 
                                    variant="primary"
                                >
                                    <span wire:loading.remove wire:target="updateRoles">Save Changes</span>
                                    <span wire:loading wire:target="updateRoles">Saving...</span>
                                </flux:button>
                            </div>
                        </div>
                    @else
                        <div class="p-4 text-center">
                            <p>Loading...</p>
                        </div>
                    @endif
                </flux:modal>
            </div>
        </div>
    </div>
</div>
