<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-zinc-700 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <flux:heading size="xl" class="mb-6">My Tickets</flux:heading>
                
                @if(count($tickets) > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @foreach($tickets as $ticket)
                            @php
                                // Generate a dynamic color based on the concert ID
                                $colors = ['orange', 'emerald', 'sky', 'purple', 'amber', 'pink'];
                                $colorIndex = $ticket->ticket->concert_id % count($colors);
                                $ticketColor = $colors[$colorIndex];
                                $colorClasses = [
                                    'emerald' => 'bg-emerald-500',
                                    'orange' => 'bg-orange-500',
                                    'sky' => 'bg-sky-500',
                                    'purple' => 'bg-purple-500',
                                    'amber' => 'bg-amber-500',
                                    'pink' => 'bg-pink-500',
                                ];
                                $bgColor = $colorClasses[$ticketColor];
                            @endphp
                            
                            <div class="relative rounded-lg overflow-hidden shadow-xl border border-gray-200 flex flex-row">
                                <!-- Used ticket overlay -->
                                @if($ticket->status === 'used')
                                    <div class="absolute inset-0 z-10 pointer-events-none">
                                        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 rotate-12">
                                            <div class="bg-red-600 text-white px-8 py-4 rounded-lg border-4 border-red-700 shadow-2xl opacity-90">
                                                <div class="text-3xl font-black uppercase tracking-wider text-center">USED</div>
                                                <div class="text-xs text-center mt-1 opacity-80">{{ $ticket->updated_at->format('M d, Y') }}</div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                
                                <!-- Main ticket section -->
                                <div class="bg-white p-6 flex-grow">
                                    <div class="flex flex-col justify-between h-full">
                                        <div>
                                            <div class="uppercase text-gray-500 font-bold text-sm">CONCERT EVENT TICKET</div>
                                            <div class="text-xs text-red-500">Please present this ticket at entry</div>
                                            
                                            <div class="mt-4">
                                                <h3 class="text-black text-xl font-bold">{{ $ticket->ticket->concert->title }}</h3>
                                                <div class="text-gray-700">
                                                    <div>{{ $ticket->ticket->concert->date->format('d M Y') }}</div>
                                                    <div>{{ $ticket->ticket->concert->start_time->format('g:i A') }} - {{ $ticket->ticket->concert->end_time->format('g:i A') }}</div>
                                                    <div>{{ $ticket->ticket->concert->venue }}</div>
                                                </div>
                                            </div>
                                            
                                            <div class="flex flex-wrap gap-2 mt-4">
                                                <flux:badge variant="solid" color="{{ $ticketColor }}">{{ $ticket->ticket->ticket_type }}</flux:badge>
                                                @if($ticket->status === 'used')
                                                    <flux:badge variant="solid" color="zinc">Used</flux:badge>
                                                @elseif($ticket->status === 'cancelled')
                                                    <flux:badge variant="solid" color="red">Cancelled</flux:badge>
                                                @else
                                                    <flux:badge variant="solid" color="green">Valid</flux:badge>
                                                @endif
                                            </div>
                                            <div class="flex justify-between">
                                                <span class="text-gray-600 dark:text-gray-400">Price:</span>
                                                <span class="font-semibold text-gray-800 dark:text-gray-200">RM{{ number_format($ticket->ticket->price, 2) }}</span>
                                            </div>
                                        </div>
                                        
                                        <div class="flex justify-end mt-4">
                                            <flux:button size="sm" wire:click="downloadTicket({{ $ticket->id }})" variant="filled">
                                                Download Ticket
                                            </flux:button>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- QR code section -->
                                <div class="bg-white border-l border-gray-100 flex items-center justify-center px-4">
                                    <div>
                                        @if(isset($qrCodes[$ticket->id]))
                                            <div class="bg-white">
                                                <img src="data:image/svg+xml;base64,{{ $qrCodes[$ticket->id] }}" alt="Ticket QR Code" class="w-32 h-32">
                                            </div>
                                        @else
                                            <div class="border-2 border-gray-300 p-4 text-center bg-white w-32 h-32 flex items-center justify-center">
                                                <div class="text-sm text-gray-500">
                                                    QR code not available
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                
                                <!-- Colored ticket stub -->
                                <div class="relative {{ $bgColor }} text-white p-4" style="width: 80px;">
                                    <div class="absolute top-0 left-0 bottom-0 w-3 flex items-center justify-center">
                                        <div class="h-full flex flex-col justify-between py-4 overflow-hidden">
                                            @for ($i = 0; $i < 15; $i++)
                                                <div class="w-2 h-2 bg-white rounded-full"></div>
                                            @endfor
                                        </div>
                                    </div>
                                    
                                    <div class="rotate-90 absolute top-1/2 left-1/2 transform -translate-y-1/2 -translate-x-1/2 whitespace-nowrap text-center origin-center" style="width: 180px;">
                                        <div class="uppercase font-bold text-sm">CONCERT TICKET</div>
                                        <div class="text-xs">{{ ($ticket->formatted_order_id) }}</div>
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
