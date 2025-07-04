<div class="py-6">
    <div class="mx-auto sm:px-6 lg:px-8">
        <!-- Warning Banner -->
        <div class="mb-6" x-data="{ visible: true }" x-show="visible" x-collapse>
            <div x-show="visible" x-transition>
                <flux:callout icon="exclamation-triangle" variant="danger">
                    <flux:callout.heading>Danger Zone!</flux:callout.heading>
                    <flux:callout.text>
                        Managing user roles and permissions can cause serious issues. Only proceed if you know what you are doing!
                    </flux:callout.text>
                    <x-slot name="controls">
                        <flux:button icon="x-mark" variant="ghost" size="sm" x-on:click="visible = false" />
                    </x-slot>
                </flux:callout>
            </div>
        </div>

        <!-- Main Card -->
        <div class="bg-white dark:bg-zinc-800 shadow-xl sm:rounded-xl border border-gray-200 dark:border-zinc-700">
            <!-- Header -->
            <div class="px-6 py-6 border-b border-gray-200 dark:border-zinc-700">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <flux:heading size="xl" class="flex items-center gap-3">
                            <div class="p-2 bg-blue-100 dark:bg-blue-900/20 rounded-lg">
                                <flux:icon.users />
                            </div>
                            User Management
                        </flux:heading>
                        <flux:text class="mt-1 text-gray-600 dark:text-gray-400">
                            Manage user accounts, roles, and permissions
                        </flux:text>
                    </div>
                    
                    <!-- Create User Button -->
                    <flux:modal.trigger name="create-user">
                        <flux:button variant="primary" icon="user-plus" wire:click="resetCreateForm">
                            Create User
                        </flux:button>
                    </flux:modal.trigger>
                </div>
            </div>

            <!-- Status Messages -->
            @if (session('message'))
            <div class="px-6 pt-6">
                <flux:callout icon="check-circle" variant="success">
                    <flux:callout.heading>Success</flux:callout.heading>
                    <flux:callout.text>{{ session('message') }}</flux:callout.text>
                </flux:callout>
            </div>
            @endif

            @if (session('error'))
            <div class="px-6 pt-6">
                <flux:callout icon="exclamation-circle" variant="danger">
                    <flux:callout.heading>Error</flux:callout.heading>
                    <flux:callout.text>{{ session('error') }}</flux:callout.text>
                </flux:callout>
            </div>
            @endif

            <!-- Filters and Search -->
            <div class="px-6 py-6 bg-gray-50 dark:bg-zinc-900/50 border-b border-gray-200 dark:border-zinc-700">
                <div class="grid grid-cols-1 lg:grid-cols-4 gap-4">
                    <!-- Search -->
                    <div class="lg:col-span-2">
                        <flux:field>
                            <flux:label>Search Users</flux:label>
                            <flux:input 
                                icon="magnifying-glass" 
                                wire:model.live.debounce.300ms="search" 
                                placeholder="Search by name or email..." 
                                class="w-full"
                            />
                        </flux:field>
                    </div>

                    <!-- Role Filter -->
                    <div>
                        <flux:field>
                            <flux:label>Filter by Role</flux:label>
                            <flux:select wire:model.live="roleFilter">
                                <flux:select.option value="">All Roles</flux:select.option>
                                <flux:select.option value="admin">Admin</flux:select.option>
                                <flux:select.option value="teacher">Teacher</flux:select.option>
                                <flux:select.option value="student">Student</flux:select.option>
                            </flux:select>
                        </flux:field>
                    </div>

                    <!-- Clear Filters -->
                    <div class="flex items-end">
                        <flux:button 
                            wire:click="clearFilters" 
                            variant="filled" 
                            icon="arrow-path"
                            class="w-full lg:w-auto"
                        >
                            Clear Filters
                        </flux:button>
                    </div>
                </div>

                <!-- Filter Summary -->
                @if($search || $roleFilter)
                <div class="mt-4 flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400">
                    <span>Active filters:</span>
                    @if($search)
                    <flux:badge color="blue">Search: "{{ $search }}"</flux:badge>
                    @endif
                    @if($roleFilter)
                    <flux:badge color="green">Role: {{ ucfirst($roleFilter) }}</flux:badge>
                    @endif
                </div>
                @endif
            </div>

            <!-- Content -->
            <div class="px-6 py-6">
                <!-- Results Header -->
                <div class="mb-6 flex items-center justify-between">
                    <div>
                        <flux:heading size="lg">
                            @if($roleFilter)
                                {{ ucfirst($roleFilter) }} Accounts
                            @else
                                All User Accounts
                            @endif
                        </flux:heading>
                        <flux:text class="mt-1 text-gray-600 dark:text-gray-400">
                            Showing {{ $users->count() }} of {{ $users->total() }} users
                        </flux:text>
                    </div>
                </div>

                <!-- Users Table -->
                @if($users->count() > 0)
                <div class="overflow-hidden rounded-lg border border-gray-200 dark:border-zinc-700">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-zinc-700">
                            <thead class="bg-gray-50 dark:bg-zinc-900">
                                <tr>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                        User
                                    </th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                        Roles
                                    </th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-zinc-800 divide-y divide-gray-200 dark:divide-zinc-700">
                                @foreach ($users as $user)
                                <tr class="hover:bg-gray-50 dark:hover:bg-zinc-700/50 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10">
                                                <div class="h-10 w-10 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center">
                                                    <span class="text-white font-semibold text-sm">
                                                        {{ strtoupper(substr($user->name, 0, 2)) }}
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-semibold text-gray-900 dark:text-white">
                                                    {{ $user->name }}
                                                </div>
                                                <div class="text-sm text-gray-500 dark:text-gray-400">
                                                    {{ $user->email }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex flex-wrap gap-1">
                                            @forelse ($user->roles as $role)
                                            <flux:badge 
                                                color="{{ $role->name === 'super-admin' ? 'red' : ($role->name === 'admin' ? 'amber' : 'blue') }}"
                                                size="sm"
                                            >
                                                {{ $role->name }}
                                            </flux:badge>
                                            @empty
                                            <flux:text class="text-gray-500 dark:text-gray-400 text-sm">No roles</flux:text>
                                            @endforelse
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-2">
                                            <!-- Edit Roles Button -->
                                            <flux:button
                                                href="{{ route('admin.users.edit-roles', $user->id) }}"
                                                variant="primary"
                                                size="sm"
                                                icon="pencil-square"
                                            >
                                                Edit Roles
                                            </flux:button>

                                            <!-- Delete Button -->
                                            @if($this->canDeleteUser($user))
                                            <flux:modal.trigger name="delete-user-{{ $user->id }}">
                                                <flux:button
                                                    wire:click="prepareDeleteUser({{ $user->id }})"
                                                    variant="danger"
                                                    size="sm"
                                                    icon="trash"
                                                >
                                                    Delete
                                                </flux:button>
                                            </flux:modal.trigger>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Pagination -->
                <div class="mt-6">
                    {{ $users->links() }}
                </div>
                @else
                <!-- Empty State -->
                <div class="text-center py-12">
                    <div class="mx-auto h-24 w-24 text-gray-400 dark:text-gray-500 mb-4">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-full h-full">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z" />
                        </svg>
                    </div>
                    <flux:heading size="lg" class="mb-2">No users found</flux:heading>
                    <flux:text class="text-gray-600 dark:text-gray-400 mb-6">
                        @if($search || $roleFilter)
                            No users match your current filters. Try adjusting your search criteria.
                        @else
                            There are no {{ strtolower($roleFilter ?: 'user') }} accounts to display.
                        @endif
                    </flux:text>
                    @if($search || $roleFilter)
                    <flux:button wire:click="clearFilters" variant="subtle">
                        Clear All Filters
                    </flux:button>
                    @endif
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Create User Modal -->
    <flux:modal name="create-user" class="lg:w-[600px]">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">Create New User Account</flux:heading>
                <flux:text class="mt-2 text-gray-600 dark:text-gray-400">
                    Create a new user account and assign appropriate roles.
                </flux:text>
            </div>

            <div class="space-y-4">
                <!-- Name Field -->
                <flux:field>
                    <flux:label>Full Name</flux:label>
                    <flux:input wire:model="name" type="text" placeholder="Enter full name" />
                    <flux:error name="name" />
                </flux:field>

                <!-- Email Field -->
                <flux:field>
                    <flux:label>Email Address</flux:label>
                    <flux:input wire:model="email" type="email" placeholder="user@example.com" />
                    <flux:error name="email" />
                </flux:field>

                <!-- Password Fields -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <flux:field>
                        <flux:label>Password</flux:label>
                        <flux:input wire:model="password" type="password" placeholder="Password" />
                        <flux:error name="password" />
                    </flux:field>

                    <flux:field>
                        <flux:label>Confirm Password</flux:label>
                        <flux:input wire:model="password_confirmation" type="password" placeholder="Confirm password" />
                    </flux:field>
                </div>

                <!-- Role Selection -->
                <div>
                    <flux:label class="mb-3 block">Assign Role</flux:label>
                    <flux:radio.group wire:model="createUserRole" class="space-y-3">
                        @foreach ($roles as $role)
                        <div class="flex items-start gap-3 p-3 border border-gray-200 dark:border-zinc-700 rounded-lg hover:bg-gray-50 dark:hover:bg-zinc-700/30 transition-colors">
                            <div class="flex-shrink-0 mt-0.5">
                                <flux:radio
                                    value="{{ $role->name }}"
                                    class="text-blue-600 focus:ring-blue-500"
                                />
                            </div>
                            <div class="flex-1">
                                <div class="flex items-center gap-2 mb-1">
                                    <flux:badge 
                                        color="{{ $role->name === 'super-admin' ? 'red' : ($role->name === 'admin' ? 'amber' : 'blue') }}"
                                        size="sm"
                                    >
                                        {{ $role->name }}
                                    </flux:badge>
                                </div>
                                <flux:text class="text-xs text-gray-600 dark:text-gray-400">
                                    @switch($role->name)
                                        @case('super-admin')
                                            Full system access with all privileges
                                            @break
                                        @case('admin')
                                            Administrative access to manage users and content
                                            @break
                                        @case('teacher')
                                            Can manage concerts, assign tickets, and scan entries
                                            @break
                                        @case('student')
                                            Basic user access to view and purchase tickets
                                            @break
                                        @default
                                            Standard user role with basic access
                                    @endswitch
                                </flux:text>
                            </div>
                        </div>
                        @endforeach
                    </flux:radio.group>
                    <flux:error name="createUserRole" />
                </div>
            </div>

            <div class="flex justify-end gap-3 pt-4 border-t border-gray-200 dark:border-zinc-700">
                <flux:modal.close>
                    <flux:button variant="subtle">Cancel</flux:button>
                </flux:modal.close>
                
                <flux:button
                    wire:click="createUser"
                    variant="primary"
                    :disabled="$isCreating"
                >
                    <span wire:loading.remove wire:target="createUser">Create User</span>
                    <span wire:loading wire:target="createUser">Creating...</span>
                </flux:button>
            </div>
        </div>
    </flux:modal>

    <!-- Delete User Modals -->
    @foreach ($users as $user)
    @if($this->canDeleteUser($user))
    <flux:modal name="delete-user-{{ $user->id }}" class="md:w-[500px]">
        <div class="space-y-6">
            <div class="flex items-start gap-4">
                <div class="flex-shrink-0 w-12 h-12 bg-red-100 dark:bg-red-900/20 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z" />
                    </svg>
                </div>
                <div>
                    <flux:heading size="lg">Delete User Account</flux:heading>
                    <flux:text class="mt-2 text-gray-600 dark:text-gray-400">
                        Are you sure you want to delete <strong>{{ $user->name }}</strong> ({{ $user->email }})?
                    </flux:text>
                    <div class="mt-3 p-3 bg-red-50 dark:bg-red-900/20 rounded-lg">
                        <flux:text class="text-red-700 dark:text-red-400 text-sm font-medium">
                            ⚠️ This action cannot be undone. All data associated with this user will be permanently deleted.
                        </flux:text>
                    </div>
                </div>
            </div>

            @if($userToDelete && $userToDelete->id === $user->id)
            <div class="flex justify-end gap-3 pt-4 border-t border-gray-200 dark:border-zinc-700">
                <flux:modal.close>
                    <flux:button variant="subtle">Cancel</flux:button>
                </flux:modal.close>
                
                <flux:button
                    wire:click="deleteUser"
                    variant="danger"
                    :disabled="$isDeleting"
                >
                    <span wire:loading.remove wire:target="deleteUser">Delete User</span>
                    <span wire:loading wire:target="deleteUser">Deleting...</span>
                </flux:button>
            </div>
            @endif
        </div>
    </flux:modal>
    @endif
    @endforeach
</div>