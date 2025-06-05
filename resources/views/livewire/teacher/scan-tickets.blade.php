<div class="py-12">
    <!-- Add styles for the pulsing animation -->
    <style>
        @keyframes pulse-warning {
            0% { opacity: 1; }
            50% { opacity: 0.7; }
            100% { opacity: 1; }
        }
        
        .pulse-warning {
            animation: pulse-warning 1.5s infinite;
        }
    </style>
    
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <flux:heading size="xl" class="mb-6">Scan Tickets</flux:heading>
                
                <div class="mb-8">
                    <flux:heading size="lg" class="mb-3">Scan QR Code</flux:heading>
                    
                    <div class="mb-4">
                        <flux:text>Scan a ticket QR code with your camera or enter the code manually</flux:text>
                    </div>
                    
                    <div class="mb-6">
                        <div class="flex space-x-2">
                            <div class="flex-1">
                                <flux:input wire:model.live="qrCode" placeholder="Enter QR code" autofocus />
                            </div>
                            <flux:button wire:click="validateQrCode" wire:loading.attr="disabled">
                                <span wire:loading.remove wire:target="validateQrCode">Validate</span>
                                <span wire:loading wire:target="validateQrCode">Processing...</span>
                            </flux:button>
                        </div>
                    </div>
                    
                    <!-- QR code scanner -->
                    <div class="bg-gray-100 dark:bg-gray-700 rounded-lg p-6 mb-6">
                        <div class="flex justify-between items-center mb-4">
                            <flux:text>QR Code Scanner</flux:text>
                            <div>
                                <flux:button id="startScanButton" size="sm">
                                    Start Camera
                                </flux:button>
                                <flux:button id="stopScanButton" size="sm" variant="filled" class="hidden">
                                    Stop Camera
                                </flux:button>
                            </div>
                        </div>
                        
                        <div id="qr-reader" class="w-full max-w-md mx-auto overflow-hidden"></div>
                        <div id="qr-reader-results" class="mt-2 text-center text-sm text-gray-500 dark:text-gray-400"></div>
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
                                    
                                    @if($scanResult->status === 'used')
                                        <div class="mb-4 p-3 bg-yellow-100 dark:bg-yellow-800 border-l-4 border-yellow-500 text-yellow-800 dark:text-yellow-200 pulse-warning">
                                            <div class="flex items-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                <div>
                                                    <p class="font-bold">Used Ticket</p>
                                                    <p>This ticket has been used and should not be admitted again.</p>
                                                    <p class="text-sm mt-1">Used on: {{ $scanResult->updated_at->format('M d, Y \a\t g:i A') }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                    
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
                                                    <flux:badge variant="filled" class="bg-yellow-500">
                                                        <div class="flex items-center">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                            </svg>
                                                            USED
                                                        </div>
                                                    </flux:badge>
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

<!-- Add the HTML5 QR Code Scanner Script -->
<script src="https://unpkg.com/html5-qrcode"></script>

<script>
    // Define sound functions
    function playSuccessSound() {
        const audio = new Audio('https://assets.mixkit.co/active_storage/sfx/2865/2865-preview.mp3');
        audio.play().catch(e => console.log('Error playing sound'));
    }
    
    function playErrorSound() {
        const audio = new Audio('https://assets.mixkit.co/active_storage/sfx/209/209-preview.mp3');
        audio.play().catch(e => console.log('Error playing sound'));
    }
    
    function playWarningSound() {
        const audio = new Audio('https://assets.mixkit.co/active_storage/sfx/2869/2869-preview.mp3');
        audio.play().catch(e => console.log('Error playing sound'));
    }
    
    // Global function to be called from Livewire
    function playSound(type) {
        if (type === 'success') {
            playSuccessSound();
        } else if (type === 'error') {
            playErrorSound();
        } else if (type === 'warning') {
            playWarningSound();
        }
    }
    
    document.addEventListener('DOMContentLoaded', function() {
        let html5QrCode;
        const qrReader = document.getElementById('qr-reader');
        const startButton = document.getElementById('startScanButton');
        const stopButton = document.getElementById('stopScanButton');
        const resultContainer = document.getElementById('qr-reader-results');
        
        function onScanSuccess(decodedText, decodedResult) {
            // Handle the scanned code as you like
            resultContainer.innerHTML = `<div class="text-green-600 dark:text-green-400">QR Code detected!</div>`;
            
            // Update the input field directly
            const qrInput = document.querySelector('[wire\\:model\\.live="qrCode"]');
            if (qrInput) {
                qrInput.value = decodedText;
                // Dispatch an input event to trigger Livewire's wire:model behavior
                qrInput.dispatchEvent(new Event('input', { bubbles: true }));
                
                // Also click the validate button to trigger immediate validation
                setTimeout(() => {
                    const validateButton = document.querySelector('[wire\\:click="validateQrCode"]');
                    if (validateButton) {
                        validateButton.click();
                    }
                }, 100);
            }
        }
        
        function onScanFailure(error) {
            // Handle scan failure silently
        }
        
        function toggleScanButtons(isScanning) {
            if (isScanning) {
                startButton.classList.add('hidden');
                stopButton.classList.remove('hidden');
            } else {
                startButton.classList.remove('hidden');
                stopButton.classList.add('hidden');
            }
        }
        
        function startScanner() {
            html5QrCode = new Html5Qrcode("qr-reader");
            const config = { fps: 10, qrbox: { width: 250, height: 250 } };
            
            html5QrCode.start(
                { facingMode: "environment" }, // Use the back camera
                config,
                onScanSuccess,
                onScanFailure
            ).then(() => {
                toggleScanButtons(true);
                resultContainer.innerHTML = '<div>Scanner started. Point camera at a QR code.</div>';
            }).catch((err) => {
                resultContainer.innerHTML = `<div class="text-red-600 dark:text-red-400">Error starting scanner: ${err}</div>`;
            });
        }
        
        function stopScanner() {
            if (html5QrCode) {
                html5QrCode.stop().then(() => {
                    toggleScanButtons(false);
                    resultContainer.innerHTML = '<div>Scanner stopped.</div>';
                }).catch((err) => {
                    console.error('Error stopping scanner:', err);
                });
            }
        }
        
        // Event listeners for buttons
        startButton.addEventListener('click', startScanner);
        stopButton.addEventListener('click', stopScanner);
        
        // Clean up when page changes
        window.addEventListener('beforeunload', stopScanner);
    });
</script>
