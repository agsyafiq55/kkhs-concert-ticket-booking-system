<div class="py-12">
    <div class="mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-zinc-700 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <div class="flex justify-between items-center mb-6">
                    <div class="flex items-center mb-4">
                        <div class="w-12 h-12 bg-gradient-to-br from-rose-500 to-rose-600 rounded-xl flex items-center justify-center mr-4">
                            <flux:icon.ticket variant="solid" class="w-7 h-7 text-white" />
                        </div>
                        <div>
                            <flux:heading size="xl">Manage Tickets</flux:heading>
                            <flux:text class="text-zinc-600 dark:text-zinc-400">
                                Manage tickets and their details.
                            </flux:text>
                        </div>
                    </div>
                    @can('create tickets')
                    <flux:button variant="primary" icon="plus" :href="route('admin.tickets.create')" wire:navigate>
                        Add Ticket
                    </flux:button>
                    @endcan
                </div>

                @if (session('message'))
                <div x-data="{ visible: true }" x-show="visible" x-collapse>
                    <div x-show="visible" x-transition>
                        <flux:callout class="mb-4" icon="check-circle" variant="success">
                            <flux:callout.heading>Success</flux:callout.heading>
                            <flux:callout.text>{{ session('message') }}</flux:callout.text>
                            <x-slot name="controls">
                                <flux:button icon="x-mark" variant="ghost" x-on:click="visible = false" />
                            </x-slot>
                        </flux:callout>
                    </div>
                </div>
                @endif

                @if (session('deleted'))
                <div x-data="{ visible: true }" x-show="visible" x-collapse>
                    <div x-show="visible" x-transition>
                        <flux:callout class="mb-4" icon="check-circle" variant="success">
                            <flux:callout.heading>Ticket Deleted Successfully!</flux:callout.heading>
                            <flux:callout.text>{{ session('deleted') }}</flux:callout.text>
                            <x-slot name="controls">
                                <flux:button icon="x-mark" variant="ghost" x-on:click="visible = false" />
                            </x-slot>
                        </flux:callout>
                    </div>
                </div>
                @endif

                @if (session('error'))
                <flux:callout icon="exclamation-triangle" variant="danger" class="mb-4">
                    <flux:callout.heading>Error</flux:callout.heading>
                    <flux:callout.text>{{ session('error') }}</flux:callout.text>
                </flux:callout>
                @endif

                <!-- Search and Filters -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <flux:input icon="magnifying-glass" wire:model.live="search" placeholder="Search tickets..." />
                    </div>
                    <div>
                        <flux:select wire:model.live="concertFilter" placeholder="Filter by Concert">
                            <option value="">All Concerts</option>
                            @foreach ($concerts as $concert)
                            <option value="{{ $concert->id }}">{{ $concert->title }}</option>
                            @endforeach
                        </flux:select>
                    </div>
                </div>

                <!-- Tickets Table -->
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-zinc-700">
                        <thead class="bg-gray-50 dark:bg-zinc-800">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Concert</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Ticket Type</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Price</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Available</th>
                                @if(auth()->user()->can('edit tickets') || auth()->user()->can('delete tickets'))
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-zinc-800/50 divide-y divide-gray-200 dark:divide-zinc-700">
                            @forelse ($tickets as $ticket)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $ticket->concert->title }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $ticket->ticket_type }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">RM{{ number_format($ticket->price, 2) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $ticket->quantity_available }}</td>
                                @if(auth()->user()->can('edit tickets') || auth()->user()->can('delete tickets'))
                                <td class="px-6 py-4 whitespace-nowrap space-x-2">
                                    @can('edit tickets')
                                    <flux:button size="sm" variant="filled" :href="route('admin.tickets.edit', $ticket->id)" wire:navigate>
                                        Edit
                                    </flux:button>
                                    @endcan
                                    @can('delete tickets')
                                    <flux:modal.trigger name="delete-confirmation">
                                        <flux:button size="sm" variant="danger" wire:click="confirmDelete({{ $ticket->id }})">
                                            Delete
                                        </flux:button>
                                    </flux:modal.trigger>
                                    @endcan
                                </td>
                                @endif
                            </tr>
                            @empty
                            <tr>
                                <td colspan="{{ (auth()->user()->can('edit tickets') || auth()->user()->can('delete tickets')) ? '5' : '4' }}" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                    @can('create tickets')
                                    No tickets found. <a href="{{ route('admin.tickets.create') }}" wire:navigate class="text-blue-600 dark:text-blue-400 hover:underline">Create one to get started.</a>
                                    @else
                                    No tickets found.
                                    @endcan
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $tickets->links() }}
                </div>

                <!-- Delete Confirmation Modal -->
                <flux:modal name="delete-confirmation" class="md:w-96">
                    <div class="space-y-6">
                        <div>
                            <flux:heading size="lg">Confirm Deletion</flux:heading>
                            <flux:text class="mt-2">Are you sure you want to delete this ticket? This action cannot be undone.</flux:text>
                        </div>

                        <div class="flex justify-end space-x-2">
                            <flux:modal.close>
                                <flux:button variant="filled" wire:click="cancelDelete">Cancel</flux:button>
                            </flux:modal.close>
                            <flux:modal.close>
                                <flux:button variant="danger" wire:click="deleteTicket">Delete</flux:button>
                            </flux:modal.close>
                        </div>
                    </div>
                </flux:modal>
            </div>
        </div>
    </div>
</div>