<div class="py-6">
    <div class="mx-auto sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-6">
            <div class="flex items-center gap-3 mb-2">
                <flux:button 
                    wire:click="cancel" 
                    variant="primary" 
                    icon="arrow-left" 
                    size="sm"
                >
                    Back to Users
                </flux:button>
            </div>
            
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <flux:heading size="xl" class="flex items-center gap-3">
                        <div class="p-2 bg-blue-100 dark:bg-blue-900/20 rounded-lg">
                            <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        Edit User Role
                    </flux:heading>
                    <flux:text class="mt-1 text-gray-600 dark:text-gray-400">
                        Assign a role to {{ $user->name }}
                    </flux:text>
                </div>
            </div>
        </div>

        <!-- Main Card -->
        <div class="bg-white dark:bg-zinc-800 shadow-xl sm:rounded-xl border border-gray-200 dark:border-zinc-700">
            <!-- User Info Header -->
            <div class="px-6 py-6 border-b border-gray-200 dark:border-zinc-700 bg-gray-50 dark:bg-zinc-900/50">
                <div class="flex items-center gap-4">
                    <div class="flex-shrink-0 h-16 w-16">
                        <div class="h-16 w-16 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center">
                            <span class="text-white font-bold text-lg">
                                {{ strtoupper(substr($user->name, 0, 2)) }}
                            </span>
                        </div>
                    </div>
                    <div>
                        <flux:heading size="lg" class="text-gray-900 dark:text-white">
                            {{ $user->name }}
                        </flux:heading>
                        <flux:text class="text-gray-600 dark:text-gray-400">
                            {{ $user->email }}
                        </flux:text>
                        <div class="flex flex-wrap gap-1 mt-2">
                            @if($selectedRole)
                            <flux:badge 
                                color="{{ $selectedRole === 'super-admin' ? 'red' : ($selectedRole === 'admin' ? 'amber' : 'blue') }}"
                                size="sm"
                            >
                                Current: {{ $selectedRole }}
                            </flux:badge>
                            @else
                            <flux:text class="text-gray-500 dark:text-gray-400 text-sm">No role assigned</flux:text>
                            @endif
                        </div>
                    </div>
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

            <!-- Form Content -->
            <div class="px-6 py-8">
                <div class="max-w-2xl">
                    <div class="mb-6">
                        <flux:heading size="lg" class="mb-2">Select User Role</flux:heading>
                        <flux:text class="text-gray-600 dark:text-gray-400">
                            Choose the single role that should be assigned to this user. Each user can only have one role at a time.
                        </flux:text>
                    </div>

                    <!-- Role Selection -->
                    <flux:radio.group wire:model="selectedRole" label="Available Roles" class="space-y-4">
                        @foreach ($roles as $role)
                        <div class="flex items-start gap-4 p-4 border border-gray-200 dark:border-zinc-700 rounded-lg hover:bg-gray-50 dark:hover:bg-zinc-700/30 transition-colors">
                            <div class="flex-shrink-0 mt-1">
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
                                <flux:text class="text-sm text-gray-600 dark:text-gray-400">
                                    @switch($role->name)
                                        @case('super-admin')
                                            Full system access with all administrative privileges. Can manage all users, roles, and system settings.
                                            @break
                                        @case('admin')
                                            Administrative access to manage users, content, and most system features. Cannot modify super-admin accounts.
                                            @break
                                        @case('teacher')
                                            Access to manage concerts, assign tickets, and view sales reports. Can scan tickets at events.
                                            @break
                                        @case('student')
                                            Basic user access to view and purchase concert tickets. Can view own ticket history.
                                            @break
                                        @default
                                            Standard user role with basic system access.
                                    @endswitch
                                </flux:text>
                            </div>
                        </div>
                        @endforeach
                    </flux:radio.group>

                    <flux:error name="selectedRole" />
                </div>
            </div>

            <!-- Actions -->
            <div class="px-6 py-6 bg-gray-50 dark:bg-zinc-900/50 border-t border-gray-200 dark:border-zinc-700">
                <div class="flex flex-col sm:flex-row sm:justify-end gap-3">
                    <flux:button 
                        wire:click="cancel" 
                        variant="subtle"
                        class="w-full sm:w-auto"
                    >
                        Cancel
                    </flux:button>
                    
                    <flux:button
                        wire:click="updateRoles"
                        variant="primary"
                        :disabled="$isUpdating"
                        class="w-full sm:w-auto"
                    >
                        <span wire:loading.remove wire:target="updateRoles">Save Role Change</span>
                        <span wire:loading wire:target="updateRoles">Saving Changes...</span>
                    </flux:button>
                </div>
            </div>
        </div>
    </div>
</div> 