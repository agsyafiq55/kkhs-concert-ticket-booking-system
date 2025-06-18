<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-zinc-700 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <div class="flex justify-between items-center mb-6">
                    <flux:heading size="xl">Manage Tickets</flux:heading>
                    <flux:button variant="primary" icon="plus" :href="route('admin.tickets.create')" wire:navigate>
                        Add Ticket
                    </flux:button>
                </div>
                
                @if (session('message'))
                    <flux:callout icon="check-circle" class="mb-4">
                        <flux:callout.heading>Success</flux:callout.heading>
                        <flux:callout.text>{{ session('message') }}</flux:callout.text>
                    </flux:callout>
                @endif
                
                <!-- Search and Filters -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <flux:input icon="magnifying-glass" wire:model.live="search" label="Search Tickets" placeholder="Search tickets..." />
                    </div>
                    <div>
                        <flux:select wire:model.live="concertFilter" label="Filter by Concert">
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
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-zinc-800/50 divide-y divide-gray-200 dark:divide-zinc-700">
                            @forelse ($tickets as $ticket)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $ticket->concert->title }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $ticket->ticket_type }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">RM{{ number_format($ticket->price, 2) }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $ticket->quantity_available }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap space-x-2">
                                        <flux:button size="sm" variant="filled" :href="route('admin.tickets.edit', $ticket->id)" wire:navigate>
                                            Edit
                                        </flux:button>
                                        <flux:button size="sm" variant="danger" wire:click="confirmDelete({{ $ticket->id }})">
                                            Delete
                                        </flux:button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                        No tickets found. Create one to get started.
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
                @if ($ticketIdToDelete)
                    <flux:modal name="delete-confirmation" class="md:w-96">
                        <div class="space-y-6">
                            <div>
                                <flux:heading size="lg">Confirm Deletion</flux:heading>
                                <flux:text class="mt-2">Are you sure you want to delete this ticket? This action cannot be undone.</flux:text>
                            </div>
                            
                            <div class="flex justify-end space-x-2">
                                <flux:button variant="filled" wire:click="cancelDelete">Cancel</flux:button>
                                <flux:button variant="danger" wire:click="deleteTicket">Delete</flux:button>
                            </div>
                        </div>
                    </flux:modal>
                @endif
            </div>
        </div>
    </div>
</div>
