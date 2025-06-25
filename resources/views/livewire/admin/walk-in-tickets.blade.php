<div>
    <!-- Page Header -->
    <div class="mb-8">
        <flux:heading size="2xl" class="mb-2">Walk-in Ticket Management</flux:heading>
        <flux:text class="text-zinc-600 dark:text-zinc-400">
            Pre-generate physical tickets for walk-in buyers. These tickets will be printed and handed out during the concert day.
        </flux:text>
    </div>

    <!-- Success/Error Messages -->
    @if (session()->has('success'))
        <div class="mb-6 p-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg">
            <flux:text class="text-green-800 dark:text-green-200">{{ session('success') }}</flux:text>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="mb-6 p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg">
            <flux:text class="text-red-800 dark:text-red-200">{{ session('error') }}</flux:text>
        </div>
    @endif

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
        <!-- Generation Panel -->
        <div class="xl:col-span-1">
            <div class="bg-white dark:bg-zinc-900 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <flux:heading size="lg" class="mb-6">Generate Walk-in Tickets</flux:heading>

                    @error('general')
                        <div class="mb-4 p-3 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg">
                            <flux:text class="text-red-800 dark:text-red-200">{{ $message }}</flux:text>
                        </div>
                    @enderror

                    <!-- Concert Filter -->
                    <div class="mb-6">
                        <flux:field>
                            <flux:label>Concert</flux:label>
                            <flux:select wire:model.live="concertFilter" placeholder="Select a concert...">
                                <flux:select.option value="">All Concerts</flux:select.option>
                                @foreach($concerts as $concert)
                                    <flux:select.option value="{{ $concert->id }}">
                                        {{ $concert->title }} - {{ $concert->date->format('M d, Y') }}
                                    </flux:select.option>
                                @endforeach
                            </flux:select>
                        </flux:field>
                    </div>

                    <!-- Ticket Selection -->
                    @if(count($tickets) > 0)
                        <div class="mb-6">
                            <flux:heading size="md" class="mb-4">Available Tickets</flux:heading>
                            <div class="space-y-3">
                                @foreach($tickets as $ticket)
                                    <div class="border border-zinc-200 dark:border-zinc-700 rounded-lg p-4 cursor-pointer hover:bg-zinc-50 dark:hover:bg-zinc-800 {{ $selectedTicketId == $ticket->id ? 'ring-2 ring-blue-500 bg-blue-50 dark:bg-blue-900/20' : '' }}"
                                         wire:click="selectTicket({{ $ticket->id }})">
                                        <div class="flex justify-between items-start">
                                            <div>
                                                <flux:text class="font-semibold">{{ $ticket->ticket_type }}</flux:text>
                                                <flux:text class="text-sm text-zinc-600 dark:text-zinc-400 block">
                                                    {{ $ticket->concert->title }}
                                                </flux:text>
                                                <flux:text class="text-sm text-zinc-500 dark:text-zinc-500 block">
                                                    {{ $ticket->concert->date->format('M d, Y') }} at {{ $ticket->concert->start_time->format('g:i A') }}
                                                </flux:text>
                                            </div>
                                            <div class="text-right">
                                                <flux:text class="font-bold text-lg">RM{{ number_format($ticket->price, 2) }}</flux:text>
                                                <flux:text class="text-sm text-zinc-600 dark:text-zinc-400 block">
                                                    {{ $ticket->remaining_tickets }} available
                                                </flux:text>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Quantity and Generate -->
                        @if($selectedTicketId)
                            <div class="mb-6">
                                <flux:field>
                                    <flux:label>Quantity to Generate</flux:label>
                                    <flux:input wire:model.live="quantity" type="number" min="1" max="50" />
                                    <flux:error name="quantity" />
                                </flux:field>
                            </div>

                            <div class="mb-6">
                                <flux:button
                                    variant="primary"
                                    wire:click="generateWalkInTickets"
                                    wire:loading.attr="disabled"
                                    class="w-full">
                                    <span wire:loading.remove wire:target="generateWalkInTickets">Generate {{ $quantity }} Walk-in Ticket{{ $quantity != 1 ? 's' : '' }}</span>
                                    <span wire:loading wire:target="generateWalkInTickets">Generating...</span>
                                </flux:button>
                            </div>
                        @endif
                    @else
                        <div class="p-8 text-center text-zinc-500 dark:text-zinc-400">
                            <svg class="w-12 h-12 mx-auto mb-3 text-zinc-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a1 1 0 001 1h1a1 1 0 001-1V7a2 2 0 00-2-2H5zM5 14a2 2 0 00-2 2v3a1 1 0 001 1h1a1 1 0 001-1v-3a2 2 0 00-2-2H5z"></path>
                            </svg>
                            <flux:text>No tickets available for generation.</flux:text>
                            @if($concertFilter)
                                <flux:text class="text-sm block mt-2">Try selecting a different concert.</flux:text>
                            @endif
                        </div>
                    @endif

                    <!-- Success State -->
                    @if($ticketsGenerated && count($lastGeneratedTickets) > 0)
                        <div class="mt-6 p-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg">
                            <div class="text-center">
                                <div class="w-12 h-12 bg-green-100 dark:bg-green-800 rounded-full flex items-center justify-center mx-auto mb-3">
                                    <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                                <flux:heading size="md" class="text-green-800 dark:text-green-200 mb-2">
                                    {{ count($lastGeneratedTickets) }} Walk-in Ticket{{ count($lastGeneratedTickets) != 1 ? 's' : '' }} Generated!
                                </flux:heading>
                                <flux:text class="text-green-600 dark:text-green-400">
                                    Tickets are ready for printing and distribution.
                                </flux:text>
                            </div>

                            <div class="mt-4">
                                <flux:button variant="filled" wire:click="resetForm" class="w-full">
                                    Generate More Tickets
                                </flux:button>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Tickets List Panel -->
        <div class="xl:col-span-2">
            <div class="bg-white dark:bg-zinc-900 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <!-- Print Section -->
                    @if($walkInTicketsByConcert->count() > 0)
                        <div class="mb-8 p-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg">
                            <flux:heading size="lg" class="mb-4 text-blue-800 dark:text-blue-200">🖨️ Print Walk-in Tickets</flux:heading>
                            <flux:text class="text-blue-700 dark:text-blue-300 mb-4 block">
                                Print physical tickets for each concert to prepare for walk-in sales.
                            </flux:text>
                            
                            <div class="space-y-3">
                                @foreach($walkInTicketsByConcert as $concertData)
                                    <div class="flex items-center justify-between p-3 bg-white dark:bg-zinc-800 rounded-lg border border-blue-200 dark:border-blue-700">
                                        <div class="flex-grow">
                                            <flux:text class="font-semibold">{{ $concertData['concert']->title }}</flux:text>
                                            <flux:text class="text-sm text-zinc-600 dark:text-zinc-400 block">
                                                {{ $concertData['concert']->date->format('M d, Y') }} at {{ $concertData['concert']->start_time->format('g:i A') }}
                                            </flux:text>
                                            <flux:text class="text-sm text-zinc-500 dark:text-zinc-500 block">
                                                {{ $concertData['count'] }} tickets • RM{{ number_format($concertData['total_value'], 2) }} total value
                                            </flux:text>
                                        </div>
                                        <div class="flex items-center space-x-2">
                                            <flux:badge color="blue">{{ $concertData['count'] }} ready</flux:badge>
                                            <a href="{{ route('walk-in-tickets.bulk-print', $concertData['concert']->id) }}" 
                                               target="_blank"
                                               class="inline-flex items-center">
                                                <flux:button size="sm" variant="primary">
                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                                                    </svg>
                                                    Print All
                                                </flux:button>
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <div class="flex justify-between items-center mb-6">
                        <flux:heading size="lg">Walk-in Tickets</flux:heading>
                        <div class="flex space-x-4">
                            <!-- Status Filter -->
                            <flux:select wire:model.live="statusFilter" class="min-w-40">
                                <flux:select.option value="all">All Status</flux:select.option>
                                <flux:select.option value="pre-generated">Pre-generated</flux:select.option>
                                <flux:select.option value="sold">Sold</flux:select.option>
                                <flux:select.option value="used">Used</flux:select.option>
                            </flux:select>
                        </div>
                    </div>

                    <!-- Tickets Table -->
                    @if($walkInTickets->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-700">
                                <thead class="bg-zinc-50 dark:bg-zinc-800">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">
                                            Ticket Details
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">
                                            Concert
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">
                                            Status
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">
                                            Generated By
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">
                                            Actions
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-zinc-900 divide-y divide-zinc-200 dark:divide-zinc-700">
                                    @foreach($walkInTickets as $ticket)
                                        <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-800">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <div>
                                                        <flux:text class="font-medium">{{ $ticket->ticket->ticket_type }}</flux:text>
                                                        <flux:text class="text-sm text-zinc-500 dark:text-zinc-400 block">
                                                            ID: #{{ $ticket->id }}
                                                        </flux:text>
                                                        <flux:text class="text-sm text-zinc-500 dark:text-zinc-400 block">
                                                            RM{{ number_format($ticket->ticket->price, 2) }}
                                                        </flux:text>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <flux:text class="font-medium">{{ $ticket->ticket->concert->title }}</flux:text>
                                                <flux:text class="text-sm text-zinc-500 dark:text-zinc-400 block">
                                                    {{ $ticket->ticket->concert->date->format('M d, Y') }}
                                                </flux:text>
                                                <flux:text class="text-sm text-zinc-500 dark:text-zinc-400 block">
                                                    {{ $ticket->ticket->concert->start_time->format('g:i A') }}
                                                </flux:text>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @if($ticket->status === 'used')
                                                    <flux:badge color="zinc">Used</flux:badge>
                                                @elseif($ticket->is_sold)
                                                    <flux:badge color="lime">Sold</flux:badge>
                                                @else
                                                    <flux:badge color="amber">Pre-generated</flux:badge>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <flux:text class="text-sm">{{ $ticket->teacher->name }}</flux:text>
                                                <flux:text class="text-sm text-zinc-500 dark:text-zinc-400 block">
                                                    {{ $ticket->created_at->format('M d, Y g:i A') }}
                                                </flux:text>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                @if(!$ticket->is_sold && $ticket->status === 'valid')
                                                    <flux:button
                                                        size="sm"
                                                        variant="danger"
                                                        wire:click="deleteWalkInTicket({{ $ticket->id }})"
                                                        wire:confirm="Are you sure you want to delete this walk-in ticket? This action cannot be undone.">
                                                        Delete
                                                    </flux:button>
                                                @else
                                                    <flux:text class="text-zinc-400">No actions</flux:text>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="mt-6">
                            {{ $walkInTickets->links() }}
                        </div>
                    @else
                        <div class="p-8 text-center text-zinc-500 dark:text-zinc-400">
                            <svg class="w-12 h-12 mx-auto mb-3 text-zinc-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                            <flux:text>No walk-in tickets found.</flux:text>
                            @if($statusFilter !== 'all')
                                <flux:text class="text-sm block mt-2">Try changing the status filter.</flux:text>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
