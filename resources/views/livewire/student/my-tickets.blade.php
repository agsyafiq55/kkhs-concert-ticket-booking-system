<div class="py-6 sm:py-12">
    <div class="mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Page Header -->
        <div class="mb-6 sm:mb-10">
            <div class="flex flex-col sm:flex-row sm:items-center mb-4">
                <div class="w-12 h-12 bg-gradient-to-br from-rose-500 to-rose-600 rounded-xl flex items-center justify-center mb-3 sm:mb-0 sm:mr-4">
                    <flux:icon.ticket variant="solid" class="w-7 h-7 text-white" />
                </div>
                <div>
                    <flux:heading size="xl">My Tickets</flux:heading>
                    <flux:text class="text-zinc-600 dark:text-zinc-400">
                        View and manage your purchased tickets.
                    </flux:text>
                    @if(auth()->user()->hasClass())
                        <div class="mt-2">
                            <flux:badge color="rose">{{ auth()->user()->class_display_name }}</flux:badge>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <div class="bg-white dark:bg-zinc-700 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-4 sm:p-6">
                @if(count($tickets) > 0)
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6">
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

                    <!-- Mobile Layout -->
                    <div class="relative rounded-lg overflow-hidden shadow-xl border border-gray-200 md:hidden">
                        <!-- Used ticket overlay -->
                        @if($ticket->status === 'used')
                        <div class="absolute inset-0 z-10 pointer-events-none">
                            <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 rotate-12">
                                <div class="bg-red-600 text-white px-6 py-3 rounded-lg border-4 border-red-700 shadow-2xl opacity-90">
                                    <div class="text-2xl font-black uppercase tracking-wider text-center">USED</div>
                                    <div class="text-xs text-center mt-1 opacity-80">{{ $ticket->updated_at->format('M d, Y') }}</div>
                                </div>
                            </div>
                        </div>
                        @endif

                        <!-- Mobile ticket content -->
                        <div class="bg-white">
                            <!-- Header with colored strip -->
                            <div class="{{ $bgColor }} p-3">
                                <div class="flex items-center justify-between text-white">
                                    <div class="text-sm font-bold uppercase">Concert Ticket</div>
                                    <div class="text-xs">{{ ($ticket->formatted_order_id) }}</div>
                                </div>
                            </div>
                            
                            <!-- Main content -->
                            <div class="p-4">
                                <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
                                    <div class="flex-grow">
                                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 mb-3">
                                            <h3 class="text-black text-lg font-bold">{{ $ticket->ticket->concert->title }}</h3>
                                            <flux:badge variant="solid" color="{{ $ticketColor }}">{{ $ticket->ticket->ticket_type }}</flux:badge>
                                        </div>
                                        <div class="text-xs text-red-500 mb-3">Please present this ticket at entry</div>
                                        <div class="text-gray-700 text-sm space-y-1">
                                            <div><strong>Date:</strong> {{ $ticket->ticket->concert->date->format('d M Y') }}</div>
                                            <div><strong>Time:</strong> {{ $ticket->ticket->concert->start_time->format('g:i A') }} - {{ $ticket->ticket->concert->end_time->format('g:i A') }}</div>
                                            <div><strong>Venue:</strong> {{ $ticket->ticket->concert->venue }}</div>
                                            <div><strong>Price:</strong> RM{{ number_format($ticket->ticket->price, 2) }}</div>
                                        </div>
                                    </div>
                                    
                                    <!-- QR Code for mobile -->
                                    <div class="flex justify-center sm:justify-end">
                                        @if(isset($qrCodes[$ticket->id]))
                                        <div class="bg-white border border-gray-200 p-2 rounded">
                                            <img src="data:image/svg+xml;base64,{{ $qrCodes[$ticket->id] }}" alt="Ticket QR Code" class="w-20 h-20">
                                        </div>
                                        @else
                                        <div class="border-2 border-gray-300 p-2 text-center bg-white w-20 h-20 flex items-center justify-center rounded">
                                            <div class="text-xs text-gray-500">QR code not available</div>
                                        </div>
                                        @endif
                                    </div>
                                </div>

                                <!-- Action buttons -->
                                @if($ticket->status === 'valid')
                                <div class="flex flex-col sm:flex-row gap-2 mt-4 pt-4 border-t border-gray-100">
                                    <flux:button icon="qr-code" size="sm" variant="primary" wire:click="enlargeQrCode({{ $ticket->id }})" class="w-full sm:w-auto">Enlarge QR</flux:button>
                                    <flux:button icon="arrow-down-tray" size="sm" wire:click="downloadTicket({{ $ticket->id }})" variant="primary" class="w-full sm:w-auto">
                                        Download PDF
                                    </flux:button>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Desktop Layout (md and up) -->
                    <div class="relative rounded-lg overflow-hidden shadow-xl border border-gray-200 hidden md:flex flex-row">
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
                                    <div class="flex flex-row items-center justify-between gap-4">
                                        <h3 class="text-black text-xl font-bold">{{ $ticket->ticket->concert->title }}</h3>
                                        <div class="flex flex-wrap gap-2">
                                            <flux:badge variant="solid" color="{{ $ticketColor }}">{{ $ticket->ticket->ticket_type }}</flux:badge>
                                        </div>
                                    </div>
                                    <div class="text-xs text-red-500">Please present this ticket at entry</div>
                                    <div class="mt-2">
                                        <div class="text-gray-700">
                                            <div><strong>Date:</strong> {{ $ticket->ticket->concert->date->format('d M Y') }}</div>
                                            <div><strong>Time:</strong> {{ $ticket->ticket->concert->start_time->format('g:i A') }} - {{ $ticket->ticket->concert->end_time->format('g:i A') }}</div>
                                            <div><strong>Venue:</strong> {{ $ticket->ticket->concert->venue }}</div>
                                            <div><strong>Price:</strong> RM{{ number_format($ticket->ticket->price, 2) }}</div>
                                        </div>
                                    </div>
                                </div>

                                <div class="flex justify-start mt-4 pt-4 border-t border-gray-100">
                                    @if($ticket->status === 'valid')
                                    <flux:button icon="qr-code" class="mr-2" size="sm" variant="primary" wire:click="enlargeQrCode({{ $ticket->id }})">Enlarge</flux:button>
                                    <flux:button icon="arrow-down-tray" size="sm" wire:click="downloadTicket({{ $ticket->id }})" variant="primary">
                                        Download as PDF
                                    </flux:button>
                                    @endif
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
                                        <div class="w-2 h-2 bg-white rounded-full">
                                </div>
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
            <div class="text-center py-8 sm:py-12">
                <flux:icon.face-frown class="w-12 h-12 sm:w-16 sm:h-16 mx-auto" />
                <flux:heading size="lg" class="mb-2">No Tickets Found</flux:heading>
                <flux:text>You haven't purchased any tickets yet.</flux:text>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- QR Code Modal -->
@if($showQrModal && $selectedTicketForQr)
<flux:modal name="qr-modal" wire:model="showQrModal" class="w-full max-w-sm sm:max-w-md">
    <div class="space-y-6">
        <flux:text class="mt-2 text-center font-bold text-sm sm:text-base">{{ $selectedTicketForQr->ticket->concert->title }}</flux:text>
        <div class="flex justify-center">
            @if(isset($qrCodes[$selectedTicketForQr->id]))
            <div class="bg-white p-4 rounded-lg">
                <img src="data:image/svg+xml;base64,{{ $qrCodes[$selectedTicketForQr->id] }}" alt="Enlarged Ticket QR Code" class="w-48 h-48 sm:w-64 sm:h-64">
            </div>
            @else
            <div class="border-2 border-gray-300 p-8 text-center bg-white w-48 h-48 sm:w-64 sm:h-64 flex items-center justify-center rounded-lg">
                <div class="text-gray-500">
                    QR code not available
                </div>
            </div>
            @endif
        </div>

        <div class="text-center">
            <flux:text class="font-bold text-sm sm:text-base">
                {{ $selectedTicketForQr->formatted_order_id }}
            </flux:text>
        </div>
    </div>
</flux:modal>
@endif
</div>