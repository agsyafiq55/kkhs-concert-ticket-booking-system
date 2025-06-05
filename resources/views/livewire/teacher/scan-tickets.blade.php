<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <flux:heading size="xl" class="mb-6">Scan Tickets</flux:heading>
                
                <div class="mb-8">
                    <flux:heading size="lg" class="mb-3">Scan QR Code</flux:heading>
                    
                    <div class="mb-4">
                        <flux:text>Enter the QR code from the ticket or scan it with a QR code scanner</flux:text>
                    </div>
                    
                    <div class="mb-6">
                        <div class="flex space-x-2">
                            <div class="flex-1">
                                <flux:input wire:model="qrCode" placeholder="Enter QR code" autofocus />
                            </div>
                            <flux:button wire:click="validateQrCode" wire:loading.attr="disabled">
                                <span wire:loading.remove wire:target="validateQrCode">Validate</span>
                                <span wire:loading wire:target="validateQrCode">Processing...</span>
                            </flux:button>
                        </div>
                    </div>
                    
                    <!-- This would be where the QR code scanner would be integrated in a real app -->
                    <div class="bg-gray-100 dark:bg-gray-700 rounded-lg p-6 mb-6 text-center">
                        <flux:text>QR Code Scanner</flux:text>
                        <div class="my-4 border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg p-8">
                            <flux:text class="text-gray-500 dark:text-gray-400">
                                (In a production environment, this would contain a camera-based QR code scanner)
                            </flux:text>
                        </div>
                        <div class="text-sm text-gray-500 dark:text-gray-400">
                            For now, please manually enter the QR code in the field above
                        </div>
                    </div>
                    
                    <!-- Scan Result -->
                    @if($scanStatus)
                        <div class="mt-8">
                            <div class="p-4 rounded-lg @if($scanStatus === 'success') bg-green-50 dark:bg-green-900 @elseif($scanStatus === 'warning') bg-yellow-50 dark:bg-yellow-900 @else bg-red-50 dark:bg-red-900 @endif">
                                <div class="flex items-start">
                                    <div class="flex-shrink-0">
                                        @if($scanStatus === 'success')
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600 dark:text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                            </svg>
                                        @elseif($scanStatus === 'warning')
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-yellow-600 dark:text-yellow-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                            </svg>
                                        @else
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-600 dark:text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        @endif
                                    </div>
                                    <div class="ml-3">
                                        <h3 class="text-lg font-medium @if($scanStatus === 'success') text-green-800 dark:text-green-200 @elseif($scanStatus === 'warning') text-yellow-800 dark:text-yellow-200 @else text-red-800 dark:text-red-200 @endif">
                                            {{ $scanStatus === 'success' ? 'Valid Ticket' : ($scanStatus === 'warning' ? 'Warning' : 'Invalid Ticket') }}
                                        </h3>
                                        <div class="mt-2 @if($scanStatus === 'success') text-green-700 dark:text-green-300 @elseif($scanStatus === 'warning') text-yellow-700 dark:text-yellow-300 @else text-red-700 dark:text-red-300 @endif">
                                            {{ $scanMessage }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            @if($scanResult)
                                <div class="mt-4 p-4 bg-white dark:bg-gray-700 rounded-lg shadow">
                                    <flux:heading size="md" class="mb-2">Ticket Details</flux:heading>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <div class="text-sm text-gray-500 dark:text-gray-400">Student</div>
                                            <div class="font-semibold">{{ $scanResult->student->name }}</div>
                                        </div>
                                        <div>
                                            <div class="text-sm text-gray-500 dark:text-gray-400">Email</div>
                                            <div>{{ $scanResult->student->email }}</div>
                                        </div>
                                        <div>
                                            <div class="text-sm text-gray-500 dark:text-gray-400">Concert</div>
                                            <div class="font-semibold">{{ $scanResult->ticket->concert->title }}</div>
                                        </div>
                                        <div>
                                            <div class="text-sm text-gray-500 dark:text-gray-400">Ticket Type</div>
                                            <div>{{ $scanResult->ticket->ticket_type }}</div>
                                        </div>
                                        <div>
                                            <div class="text-sm text-gray-500 dark:text-gray-400">Date & Time</div>
                                            <div>{{ $scanResult->ticket->concert->date->format('M d, Y') }} at {{ $scanResult->ticket->concert->start_time->format('g:i A') }}</div>
                                        </div>
                                        <div>
                                            <div class="text-sm text-gray-500 dark:text-gray-400">Status</div>
                                            <div>
                                                @if($scanResult->status === 'valid')
                                                    <flux:badge variant="success">Valid</flux:badge>
                                                @elseif($scanResult->status === 'used')
                                                    <flux:badge variant="filled">Used</flux:badge>
                                                @else
                                                    <flux:badge variant="danger">Cancelled</flux:badge>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            
                            <div class="mt-4 flex justify-end">
                                <flux:button variant="filled" wire:click="resetScan">
                                    Scan Another Ticket
                                </flux:button>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
