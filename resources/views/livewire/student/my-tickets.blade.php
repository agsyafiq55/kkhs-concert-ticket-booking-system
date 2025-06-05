<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <flux:heading size="xl" class="mb-6">My Tickets</flux:heading>
                
                @if(count($tickets) > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($tickets as $ticket)
                            <div class="border dark:border-gray-700 rounded-lg overflow-hidden shadow-sm hover:shadow-md transition">
                                <div class="bg-gray-50 dark:bg-gray-700 p-4">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <h3 class="font-bold text-lg">{{ $ticket->ticket->concert->title }}</h3>
                                            <p class="text-sm text-gray-600 dark:text-gray-400">{{ $ticket->ticket->ticket_type }}</p>
                                        </div>
                                        @if($ticket->status === 'used')
                                            <flux:badge variant="filled">Used</flux:badge>
                                        @elseif($ticket->status === 'cancelled')
                                            <flux:badge variant="danger">Cancelled</flux:badge>
                                        @else
                                            <flux:badge variant="success">Valid</flux:badge>
                                        @endif
                                    </div>
                                </div>
                                
                                <div class="p-4">
                                    <div class="mb-4">
                                        <div class="text-sm text-gray-500 dark:text-gray-400">Date & Time</div>
                                        <div>{{ $ticket->ticket->concert->date->format('M d, Y') }} at {{ $ticket->ticket->concert->start_time->format('g:i A') }}</div>
                                    </div>
                                    
                                    <div class="mb-4">
                                        <div class="text-sm text-gray-500 dark:text-gray-400">Venue</div>
                                        <div>{{ $ticket->ticket->concert->venue }}</div>
                                    </div>
                                    
                                    <div class="mb-4">
                                        <div class="text-sm text-gray-500 dark:text-gray-400">Purchase Date</div>
                                        <div>{{ $ticket->purchase_date->format('M d, Y g:i A') }}</div>
                                    </div>
                                    
                                    <div class="mb-4">
                                        <div class="text-sm text-gray-500 dark:text-gray-400 mb-2">Ticket QR Code</div>
                                        <div class="flex justify-center bg-white p-2 rounded-md">
                                            @if(isset($qrCodes[$ticket->id]))
                                                <img src="data:image/svg+xml;base64,{{ $qrCodes[$ticket->id] }}" alt="Ticket QR Code" class="w-40 h-40">
                                            @else
                                                <div class="border-2 border-gray-300 p-4 text-center bg-white w-40 h-40 flex items-center justify-center">
                                                    <div class="text-sm text-gray-500">
                                                        QR code not available
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    
                                    <div class="text-xs text-gray-500 dark:text-gray-400 mb-4">
                                        Present this QR code at the entrance for admission
                                    </div>
                                    
                                    <div class="flex justify-end">
                                        <flux:button size="sm" wire:click="downloadTicket({{ $ticket->id }})" variant="filled">
                                            Download Ticket
                                        </flux:button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-12">
                        <div class="text-gray-500 dark:text-gray-400 mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
                            </svg>
                        </div>
                        <flux:heading size="lg" class="mb-2">No Tickets Found</flux:heading>
                        <flux:text>You haven't purchased any tickets yet.</flux:text>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
