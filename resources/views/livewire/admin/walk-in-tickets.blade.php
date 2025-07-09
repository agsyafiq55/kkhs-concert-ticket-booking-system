<div class="px-6 py-8">
    <!-- Page Header -->
    <div class="mb-10">
        <div class="flex items-center mb-4">
            <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center mr-4">
                <flux:icon.ticket variant="solid" class="w-7 h-7 text-white" />
            </div>
            <div>
                <flux:heading size="xl" class="mb-2">Walk-in Ticket Management</flux:heading>
                <flux:text class="text-zinc-600 dark:text-zinc-400">
                    Generate and manage physical tickets for walk-in buyers on concert day.
                </flux:text>
            </div>
        </div>
    </div>

    <!-- Alert Messages -->
    @if (session()->has('success'))
        <div class="mb-8 p-4 bg-gradient-to-r from-green-50 to-emerald-50 dark:from-green-900/20 dark:to-emerald-900/20 border border-green-200 dark:border-green-800 rounded-xl shadow-sm">
            <div class="flex items-center">
                <div class="w-8 h-8 bg-green-100 dark:bg-green-800 rounded-full flex items-center justify-center mr-3">
                    <flux:icon.check class="w-4 h-4 text-green-600 dark:text-green-400" />
                </div>
                <flux:text class="text-green-800 dark:text-green-200 font-medium">{{ session('success') }}</flux:text>
            </div>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="mb-8 p-4 bg-gradient-to-r from-red-50 to-pink-50 dark:from-red-900/20 dark:to-pink-900/20 border border-red-200 dark:border-red-800 rounded-xl shadow-sm">
            <div class="flex items-center">
                <div class="w-8 h-8 bg-red-100 dark:bg-red-800 rounded-full flex items-center justify-center mr-3">
                    <flux:icon.exclamation-triangle class="w-4 h-4 text-red-600 dark:text-red-400" />
                </div>
                <flux:text class="text-red-800 dark:text-red-200 font-medium">{{ session('error') }}</flux:text>
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
        <!-- Generation Panel -->
        <div class="xl:col-span-1">
            <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-2xl shadow-sm overflow-hidden">
                <div class="bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 px-6 py-4 border-b border-zinc-200 dark:border-zinc-700">
                    <div class="flex items-center">
                        <flux:icon.plus class="w-5 h-5 text-blue-600 dark:text-blue-400 mr-2" />
                        <flux:heading size="lg" class="text-blue-900 dark:text-blue-100">Generate Walk-in Tickets</flux:heading>
                    </div>
                </div>

                <div class="p-6">
                    @error('general')
                        <div class="mb-6 p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-xl">
                            <div class="flex items-center">
                                <flux:icon.exclamation-triangle class="w-5 h-5 text-red-500 mr-2" />
                                <flux:text class="text-red-800 dark:text-red-200 font-medium">{{ $message }}</flux:text>
                            </div>
                        </div>
                    @enderror

                    <!-- Concert Filter -->
                    <div class="mb-6">
                        <flux:field>
                            <flux:label class="font-semibold">Concert</flux:label>
                            <flux:select wire:model.live="concertFilter" placeholder="Select a concert..." class="mt-2">
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
                            <flux:heading size="md" class="mb-4 font-semibold">Available Ticket Types</flux:heading>
                            <div class="space-y-3 max-h-96 overflow-y-auto">
                                @foreach($tickets as $ticket)
                                    <div class="group border border-zinc-200 dark:border-zinc-700 rounded-xl p-4 cursor-pointer transition-all duration-200 hover:border-blue-300 dark:hover:border-blue-600 hover:shadow-md {{ $selectedTicketId == $ticket->id ? 'ring-2 ring-blue-500 border-blue-300 dark:border-blue-600 bg-blue-50 dark:bg-blue-900/20 shadow-md' : 'hover:bg-zinc-50 dark:hover:bg-zinc-800' }}"
                                         wire:click="selectTicket({{ $ticket->id }})">
                                        <div class="flex justify-between items-start">
                                            <div class="flex-grow">
                                                <div class="flex items-center mb-2">
                                                    <flux:text class="font-semibold text-lg">{{ $ticket->ticket_type }}</flux:text>
                                                    @if($selectedTicketId == $ticket->id)
                                                        <div class="ml-2 w-5 h-5 bg-blue-500 rounded-full flex items-center justify-center">
                                                            <flux:icon.check class="w-3 h-3 text-white" />
                                                        </div>
                                                    @endif
                                                </div>
                                                <flux:text class="text-sm text-zinc-600 dark:text-zinc-400 block mb-1">
                                                    {{ $ticket->concert->title }}
                                                </flux:text>
                                                <flux:text class="text-sm text-zinc-500 dark:text-zinc-500 block">
                                                    {{ $ticket->concert->date->format('M d, Y') }} at {{ $ticket->concert->start_time->format('g:i A') }}
                                                </flux:text>
                                            </div>
                                            <div class="text-right ml-4">
                                                <flux:text class="font-bold text-xl text-green-600 dark:text-green-400">RM{{ number_format($ticket->price, 2) }}</flux:text>
                                                <div class="flex items-center justify-end mt-1">
                                                    <div class="w-2 h-2 rounded-full {{ $ticket->remaining_tickets > 10 ? 'bg-green-400' : ($ticket->remaining_tickets > 0 ? 'bg-amber-400' : 'bg-red-400') }} mr-2"></div>
                                                    <flux:text class="text-sm text-zinc-600 dark:text-zinc-400">
                                                        {{ $ticket->remaining_tickets }} available
                                                    </flux:text>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Generate Button -->
                        @if($selectedTicketId)
                            @php
                                $selectedTicket = $tickets->firstWhere('id', $selectedTicketId);
                            @endphp
                            
                            <div class="mb-6">
                                <flux:button
                                    variant="primary"
                                    wire:click="generateWalkInTickets"
                                    wire:loading.attr="disabled"
                                    class="w-full py-3 text-base font-semibold">
                                    <span wire:loading.remove wire:target="generateWalkInTickets" class="flex items-center justify-center">
                                        <flux:icon.plus class="w-5 h-5 mr-2" />
                                        Generate All Tickets ({{ $selectedTicket ? $selectedTicket->remaining_tickets : 0 }})
                                    </span>
                                    <span wire:loading wire:target="generateWalkInTickets" class="flex items-center justify-center">
                                        <flux:icon.loading class="w-5 h-5 mr-2" />
                                        Generating...
                                    </span>
                                </flux:button>
                            </div>
                        @endif
                    @else
                        <div class="py-12 text-center">
                            <div class="w-16 h-16 bg-zinc-100 dark:bg-zinc-800 rounded-full flex items-center justify-center mx-auto mb-4">
                                <flux:icon.ticket class="w-8 h-8 text-zinc-400" />
                            </div>
                            <flux:heading size="md" class="text-zinc-600 dark:text-zinc-400 mb-2">No Tickets Available</flux:heading>
                            <flux:text class="text-zinc-500 dark:text-zinc-500 mb-4">
                                Create walk-in ticket types first to generate physical tickets.
                            </flux:text>
                            <a href="{{ route('admin.tickets') }}" class="inline-flex items-center px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors">
                                <flux:icon.plus class="w-4 h-4 mr-2" />
                                Manage Ticket Types
                            </a>
                            @if($concertFilter)
                                <flux:text class="text-sm block mt-3 text-zinc-400">Or try selecting a different concert above</flux:text>
                            @endif
                        </div>
                    @endif

                    <!-- Success State -->
                    @if($ticketsGenerated && count($lastGeneratedTickets) > 0)
                        <div class="mt-6 p-6 bg-gradient-to-r from-green-50 to-emerald-50 dark:from-green-900/20 dark:to-emerald-900/20 border border-green-200 dark:border-green-800 rounded-xl shadow-sm">
                            <div class="text-center">
                                <div class="w-16 h-16 bg-gradient-to-br from-green-400 to-emerald-500 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <flux:icon.check class="w-8 h-8 text-white" />
                                </div>
                                <flux:heading size="lg" class="text-green-800 dark:text-green-200 mb-2">
                                    Success! {{ count($lastGeneratedTickets) }} Ticket{{ count($lastGeneratedTickets) != 1 ? 's' : '' }} Generated
                                </flux:heading>
                                <flux:text class="text-green-600 dark:text-green-400 mb-6">
                                    All available tickets for this type are now ready for printing and distribution.
                                </flux:text>
                                <flux:button variant="filled" wire:click="resetForm" class="w-full">
                                    <flux:icon.plus class="w-4 h-4 mr-2" />
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
            <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-2xl shadow-sm overflow-hidden">
                <!-- Print Section -->
                @if($walkInTicketsByConcert->count() > 0)
                    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 border-b border-zinc-200 dark:border-zinc-700 p-6">
                        <div class="flex items-center mb-4">
                            <div class="w-10 h-10 bg-blue-100 dark:bg-blue-800 rounded-xl flex items-center justify-center mr-3">
                                <flux:icon.printer class="w-5 h-5 text-blue-600 dark:text-blue-400" />
                            </div>
                            <flux:heading size="lg" class="text-blue-900 dark:text-blue-100">Print Walk-in Tickets</flux:heading>
                        </div>
                        <flux:text class="text-blue-700 dark:text-blue-300 mb-6">
                            Print physical tickets for each concert to prepare for walk-in sales.
                        </flux:text>
                        
                        <div class="grid gap-4 md:grid-cols-2">
                            @foreach($walkInTicketsByConcert as $concertData)
                                <div class="bg-white dark:bg-zinc-800 rounded-xl p-4 border border-blue-200 dark:border-blue-700 shadow-sm">
                                    <div class="flex justify-between items-start mb-4">
                                        <div class="flex-grow">
                                            <flux:heading size="md" class="mb-1">{{ $concertData['concert']->title }}</flux:heading>
                                            <flux:text class="text-sm text-zinc-600 dark:text-zinc-400 block">
                                                {{ $concertData['concert']->date->format('M d, Y') }} at {{ $concertData['concert']->start_time->format('g:i A') }}
                                            </flux:text>
                                        </div>
                                        <flux:badge color="blue" class="ml-2">{{ $concertData['count'] }} ready</flux:badge>
                                    </div>
                                    
                                    <div class="flex items-center justify-between">
                                        <div class="text-sm">
                                            <flux:text class="text-zinc-600 dark:text-zinc-400">Total Value: </flux:text>
                                            <flux:text class="font-semibold text-green-600 dark:text-green-400">RM{{ number_format($concertData['total_value'], 2) }}</flux:text>
                                        </div>
                                        <a href="{{ route('walk-in-tickets.bulk-print', $concertData['concert']->id) }}" 
                                           target="_blank"
                                           class="inline-flex items-center">
                                            <flux:button size="sm" variant="primary">
                                                <flux:icon.printer class="w-4 h-4 mr-1" />
                                                Print All
                                            </flux:button>
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <div class="p-6">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-6 gap-4">
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-zinc-100 dark:bg-zinc-800 rounded-lg flex items-center justify-center mr-3">
                                <flux:icon.clipboard class="w-4 h-4 text-zinc-600 dark:text-zinc-400" />
                            </div>
                            <flux:heading size="lg">Walk-in Tickets</flux:heading>
                        </div>
                        <div class="flex space-x-3">
                            <!-- Status Filter -->
                            <flux:select wire:model.live="statusFilter" class="min-w-40">
                                <flux:select.option value="all">All Status</flux:select.option>
                                <flux:select.option value="pre-generated">Unsold</flux:select.option>
                                <flux:select.option value="sold">Sold</flux:select.option>
                                <flux:select.option value="used">Used</flux:select.option>
                            </flux:select>
                        </div>
                    </div>

                    <!-- Bulk Actions -->
                    @if(count($selectedTickets) > 0)
                        <div class="mb-6 p-4 bg-gradient-to-r from-amber-50 to-orange-50 dark:from-amber-900/20 dark:to-orange-900/20 border border-amber-200 dark:border-amber-800 rounded-xl shadow-sm">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 bg-amber-100 dark:bg-amber-800 rounded-full flex items-center justify-center mr-3">
                                        <flux:icon.check class="w-4 h-4 text-amber-600 dark:text-amber-400" />
                                    </div>
                                    <flux:text class="text-amber-800 dark:text-amber-200 font-semibold">
                                        {{ count($selectedTickets) }} ticket{{ count($selectedTickets) !== 1 ? 's' : '' }} selected
                                    </flux:text>
                                </div>
                                <div class="flex space-x-2">
                                    <flux:button 
                                        variant="ghost" 
                                        size="sm"
                                        wire:click="clearSelection">
                                        Clear Selection
                                    </flux:button>
                                    <flux:button 
                                        variant="danger" 
                                        size="sm"
                                        wire:click="bulkDeleteTickets"
                                        wire:confirm="Are you sure you want to delete {{ count($selectedTickets) }} selected ticket{{ count($selectedTickets) !== 1 ? 's' : '' }}? This action cannot be undone.">
                                        Delete Selected
                                    </flux:button>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Tickets Table -->
                    @if($walkInTickets->count() > 0)
                        <div class="border border-zinc-200 dark:border-zinc-700 rounded-xl overflow-hidden">
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-700">
                                    <thead class="bg-zinc-50 dark:bg-zinc-800">
                                        <tr>
                                            <th class="px-6 py-4 text-left text-xs font-semibold text-zinc-600 dark:text-zinc-300 uppercase tracking-wider">
                                                <flux:checkbox 
                                                    wire:model.live="selectAll" 
                                                    wire:click="toggleSelectAll" />
                                            </th>
                                            <th class="px-6 py-4 text-left text-xs font-semibold text-zinc-600 dark:text-zinc-300 uppercase tracking-wider">
                                                Ticket Details
                                            </th>
                                            <th class="px-6 py-4 text-left text-xs font-semibold text-zinc-600 dark:text-zinc-300 uppercase tracking-wider">
                                                Concert
                                            </th>
                                            <th class="px-6 py-4 text-left text-xs font-semibold text-zinc-600 dark:text-zinc-300 uppercase tracking-wider">
                                                Status
                                            </th>
                                            <th class="px-6 py-4 text-left text-xs font-semibold text-zinc-600 dark:text-zinc-300 uppercase tracking-wider">
                                                Generated By
                                            </th>
                                            <th class="px-6 py-4 text-left text-xs font-semibold text-zinc-600 dark:text-zinc-300 uppercase tracking-wider">
                                                Actions
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white dark:bg-zinc-900 divide-y divide-zinc-200 dark:divide-zinc-700">
                                        @foreach($walkInTickets as $ticket)
                                            <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-800 transition-colors duration-150">
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    @if(!$ticket->is_sold && $ticket->status === 'valid')
                                                        <flux:checkbox 
                                                            wire:model.live="selectedTickets" 
                                                            value="{{ $ticket->id }}"
                                                            wire:click="toggleTicketSelection({{ $ticket->id }})" />
                                                    @else
                                                        <div class="w-4 h-4"></div>
                                                    @endif
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="flex items-center">
                                                        <div>
                                                            <flux:text class="font-semibold text-lg">{{ $ticket->ticket->ticket_type }}</flux:text>
                                                            <flux:text class="text-sm text-zinc-500 dark:text-zinc-400 block">
                                                                ID: {{ $ticket->order_id }}
                                                            </flux:text>
                                                            <flux:text class="text-sm font-medium text-green-600 dark:text-green-400 block">
                                                                RM{{ number_format($ticket->ticket->price, 2) }}
                                                            </flux:text>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <flux:text class="font-semibold">{{ $ticket->ticket->concert->title }}</flux:text>
                                                    <flux:text class="text-sm text-zinc-500 dark:text-zinc-400 block">
                                                        {{ $ticket->ticket->concert->date->format('M d, Y') }}
                                                    </flux:text>
                                                    <flux:text class="text-sm text-zinc-500 dark:text-zinc-400 block">
                                                        {{ $ticket->ticket->concert->start_time->format('g:i A') }}
                                                    </flux:text>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    @if($ticket->status === 'used')
                                                        <flux:badge color="zinc">
                                                            <flux:icon.check class="w-3 h-3 mr-1" />
                                                            Used
                                                        </flux:badge>
                                                    @elseif($ticket->is_sold)
                                                        <flux:badge color="lime">
                                                            <flux:icon.banknotes class="w-3 h-3 mr-1" />
                                                            Sold
                                                        </flux:badge>
                                                    @else
                                                        <flux:badge color="amber">
                                                            <flux:icon.clock class="w-3 h-3 mr-1" />
                                                            Unsold
                                                        </flux:badge>
                                                    @endif
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <flux:text class="text-sm font-medium">{{ $ticket->teacher->name }}</flux:text>
                                                    <flux:text class="text-sm text-zinc-500 dark:text-zinc-400 block">
                                                        {{ $ticket->created_at->format('M d, Y') }}
                                                    </flux:text>
                                                    <flux:text class="text-sm text-zinc-500 dark:text-zinc-400 block">
                                                        {{ $ticket->created_at->format('g:i A') }}
                                                    </flux:text>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                    @if(!$ticket->is_sold && $ticket->status === 'valid')
                                                        <flux:button
                                                            size="sm"
                                                            variant="danger"
                                                            wire:click="deleteWalkInTicket({{ $ticket->id }})"
                                                            wire:confirm="Are you sure you want to delete this walk-in ticket? This action cannot be undone.">
                                                            <flux:icon.trash variant="solid" class="w-4 h-4" />
                                                        </flux:button>
                                                    @else
                                                        <flux:text class="text-zinc-400">No actions available</flux:text>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Pagination -->
                        <div class="mt-6">
                            {{ $walkInTickets->links() }}
                        </div>
                    @else
                        <div class="py-16 text-center">
                            <div class="w-20 h-20 bg-zinc-100 dark:bg-zinc-800 rounded-full flex items-center justify-center mx-auto mb-6">
                                <flux:icon.clipboard class="w-10 h-10 text-zinc-400" />
                            </div>
                            <flux:heading size="lg" class="text-zinc-600 dark:text-zinc-400 mb-3">No Walk-in Tickets Found</flux:heading>
                            <flux:text class="text-zinc-500 dark:text-zinc-500 max-w-md mx-auto">
                                @if($statusFilter !== 'all')
                                    No tickets found with the current status filter. Try selecting a different status or generate some tickets first.
                                @else
                                    Generate some walk-in tickets using the panel on the left to get started.
                                @endif
                            </flux:text>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
