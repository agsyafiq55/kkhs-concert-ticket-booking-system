<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <flux:heading size="xl" class="mb-6">User Management</flux:heading>
                
                @if (session('message'))
                    <flux:callout icon="check-circle" class="mb-4">
                        <flux:callout.heading>Success</flux:callout.heading>
                        <flux:callout.text>{{ session('message') }}</flux:callout.text>
                    </flux:callout>
                @endif
                
                <!-- Search -->
                <div class="mb-4">
                    <flux:input wire:model.live="search" placeholder="Search users..." />
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
                                        <flux:modal.trigger name="edit-roles-{{ $user->id }}">
                                            <flux:button wire:click="editUserRoles({{ $user->id }})" variant="primary">Edit Roles</flux:button>
                                        </flux:modal.trigger>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <div class="mt-4">
                    {{ $users->links() }}
                </div>
                
                <!-- Edit Roles Modal -->
                @if ($userId)
                    <flux:modal name="edit-roles-{{ $userId }}" class="md:w-96">
                        <div class="space-y-6">
                            <div>
                                <flux:heading size="lg">Update User Roles</flux:heading>
                                <flux:text class="mt-2">Select the roles for this user.</flux:text>
                            </div>
                            
                            <div class="space-y-4">
                                @foreach ($roles as $role)
                                    <flux:checkbox 
                                        wire:model="selectedRoles" 
                                        value="{{ $role->name }}"
                                        label="{{ ucfirst($role->name) }}"
                                    />
                                @endforeach
                            </div>
                            
                            <div class="flex justify-end space-x-2">
                                <flux:button wire:click="updateRoles" variant="primary">Save Changes</flux:button>
                            </div>
                        </div>
                    </flux:modal>
                @endif
            </div>
        </div>
    </div>
</div>
