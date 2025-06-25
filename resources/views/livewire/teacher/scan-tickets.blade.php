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
        <div class="bg-white dark:bg-zinc-900 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <flux:heading size="xl" class="mb-2">Scan Tickets</flux:heading>
                
                <div class="mb-8">
                    <div class="mb-4">
                        <flux:text>Scan a ticket QR code with your camera</flux:text>
                    </div>
                    
                    <!-- QR code scanner -->
                    <div class="bg-gray-100 dark:bg-zinc-700 rounded-lg p-6 mb-6" @if($scanStatus) style="display: none;" @endif>
                        <div class="flex justify-between items-center mb-4">
                            <flux:text>QR Code Scanner</flux:text>
                            <div id="scanner-status" class="text-sm text-gray-500 dark:text-gray-400">
                                Camera activating...
                            </div>
                        </div>
                        
                        <div id="qr-reader" class="w-full max-w-md mx-auto overflow-hidden" style="min-height: 300px;"></div>
                        <div id="qr-reader-results" class="mt-2 text-center text-sm text-gray-500 dark:text-gray-400"></div>
                    </div>
                    
                    <!-- Scan Result -->
                    @if($scanStatus)
                        @php
                            // Define classes based on scan status to avoid long conditionals
                            $containerClasses = match($scanStatus) {
                                'success' => 'bg-green-50 dark:bg-green-900',
                                'warning' => 'bg-yellow-50 dark:bg-yellow-900',
                                default => 'bg-red-50 dark:bg-red-900'
                            };
                            
                            $iconClasses = match($scanStatus) {
                                'success' => 'text-green-600 dark:text-green-400',
                                'warning' => 'text-yellow-600 dark:text-yellow-400',
                                default => 'text-red-600 dark:text-red-400'
                            };
                            
                            $titleClasses = match($scanStatus) {
                                'success' => 'text-green-800 dark:text-green-200',
                                'warning' => 'text-yellow-800 dark:text-yellow-200',
                                default => 'text-red-800 dark:text-red-200'
                            };
                            
                            $messageClasses = match($scanStatus) {
                                'success' => 'text-green-700 dark:text-green-300',
                                'warning' => 'text-yellow-700 dark:text-yellow-300',
                                default => 'text-red-700 dark:text-red-300'
                            };
                            
                            $statusTitle = match($scanStatus) {
                                'success' => 'Valid Ticket',
                                'warning' => 'Warning',
                                default => 'Invalid Ticket'
                            };
                        @endphp
                        
                        <div class="mt-8" wire:key="scan-result">
                            <div class="p-4 rounded-lg {{ $containerClasses }}">
                                <div class="flex items-start">
                                    <div class="flex-shrink-0">
                                        @if($scanStatus === 'success')
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 {{ $iconClasses }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                            </svg>
                                        @elseif($scanStatus === 'warning')
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 {{ $iconClasses }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                            </svg>
                                        @else
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 {{ $iconClasses }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        @endif
                                    </div>
                                    <div class="ml-3">
                                        <h3 class="text-lg font-medium {{ $titleClasses }}">
                                            {{ $statusTitle }}
                                        </h3>
                                        <div class="mt-2 {{ $messageClasses }}">
                                            {{ $scanMessage }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            @if($scanResult)
                                <div class="mt-4 p-4 bg-white dark:bg-zinc-700 rounded-lg shadow">
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
                                            <div class="text-sm text-gray-500 dark:text-gray-400">
                                                {{ $scanResult->is_walk_in ? 'Customer' : 'Student' }}
                                            </div>
                                            <div class="font-semibold">
                                                @if($scanResult->is_walk_in)
                                                    Walk-in Customer
                                                @else
                                                    {{ $scanResult->student->name }}
                                                @endif
                                            </div>
                                        </div>
                                        <div>
                                            <div class="text-sm text-gray-500 dark:text-gray-400">
                                                {{ $scanResult->is_walk_in ? 'Type' : 'Email' }}
                                            </div>
                                            <div>
                                                @if($scanResult->is_walk_in)
                                                    <flux:badge color="orange">Walk-in Ticket</flux:badge>
                                                @else
                                                    {{ $scanResult->student->email }}
                                                @endif
                                            </div>
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
                                        
                                        @if($scanResult->is_walk_in)
                                            <div>
                                                <div class="text-sm text-gray-500 dark:text-gray-400">Price Paid</div>
                                                <div class="font-semibold text-green-600">RM{{ number_format($scanResult->ticket->price, 2) }}</div>
                                            </div>
                                            <div>
                                                <div class="text-sm text-gray-500 dark:text-gray-400">Generated By</div>
                                                <div>{{ $scanResult->teacher ? $scanResult->teacher->name : 'Unknown' }}</div>
                                            </div>
                                        @endif
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
<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>

<script>
    // Sound functions
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
    
    function playSound(type) {
        if (type === 'success') playSuccessSound();
        else if (type === 'error') playErrorSound();
        else if (type === 'warning') playWarningSound();
    }
    
    // Global scanner variables
    let html5QrCode = null;
    let scannerActive = false;
    let processingQR = false;
    let initializationInProgress = false;
    
    // Simple function to create and start scanner
    function createAndStartScanner() {
        return new Promise((resolve, reject) => {
            const qrReader = document.getElementById('qr-reader');
            const resultContainer = document.getElementById('qr-reader-results');
            const statusElement = document.getElementById('scanner-status');
            
            if (!qrReader) {
                reject('QR reader element not found');
                return;
            }
            
            try {
                // Clear existing content
                qrReader.innerHTML = '';
                
                // Create new scanner instance with unique ID
                const readerId = 'qr-reader';
                html5QrCode = new Html5Qrcode(readerId);
                
                // Configure the scanner
                const config = { 
                    fps: 10, 
                    qrbox: { width: 250, height: 250 },
                    aspectRatio: 1.0
                };
                
                // Start the scanner
                html5QrCode.start(
                    { facingMode: "environment" },
                    config,
                    (decodedText) => {
                        // Prevent multiple processing of same scan
                        if (processingQR) {
                            return;
                        }
                        
                        processingQR = true;
                        
                        // Update UI immediately
                        if (resultContainer) {
                            resultContainer.innerHTML = `<div class="text-green-600 dark:text-green-400">Processing QR Code...</div>`;
                        }
                        
                        // Stop scanner and send to Livewire
                        stopScanner().then(() => {
                            if (typeof window.Livewire !== 'undefined') {
                                window.Livewire.dispatch('scan-detected', { code: decodedText });
                            } else {
                                console.error("Livewire not available");
                                processingQR = false;
                            }
                        });
                    },
                    (error) => {
                        // Handle errors silently for continuous scanning
                    }
                ).then(() => {
                    scannerActive = true;
                    if (statusElement) statusElement.textContent = 'Camera active - Point at QR code';
                    if (resultContainer) resultContainer.innerHTML = '<div>Scanner ready. Point camera at a QR code.</div>';
                    resolve();
                }).catch((err) => {
                    console.error("Error starting camera:", err);
                    if (statusElement) statusElement.textContent = 'Camera error';
                    if (resultContainer) resultContainer.innerHTML = `<div class="text-red-600 dark:text-red-400">Error: ${err}</div>`;
                    reject(err);
                });
            } catch (err) {
                console.error("Error creating scanner:", err);
                if (statusElement) statusElement.textContent = 'Initialization error';
                reject(err);
            }
        });
    }
    
    // Start the scanner
    function startScanner() {
        // Wait for DOM elements to be ready
        if (!document.getElementById('qr-reader')) {
            setTimeout(startScanner, 100);
            return;
        }
        
        // Prevent multiple simultaneous initializations
        if (initializationInProgress || scannerActive) {
            return;
        }
        
        initializationInProgress = true;
        processingQR = false;
        
        createAndStartScanner()
            .then(() => {
                initializationInProgress = false;
            })
            .catch((err) => {
                console.error("Failed to start scanner:", err);
                initializationInProgress = false;
                scannerActive = false;
            });
    }
    
    // Stop the scanner
    function stopScanner() {
        return new Promise((resolve) => {
            // Force reset all state immediately
            processingQR = false;
            
            if (html5QrCode) {
                // Try to stop the scanner
                const stopPromise = scannerActive ? html5QrCode.stop() : Promise.resolve();
                
                stopPromise
                    .then(() => {
                        return html5QrCode.clear();
                    })
                    .catch((err) => {
                        // Continue with cleanup even if stop failed
                        return html5QrCode.clear().catch(() => {
                            console.warn("Scanner cleanup failed, forcing reset");
                        });
                    })
                    .finally(() => {
                        // Always reset everything regardless of errors
                        cleanupScanner();
                        resolve();
                    });
            } else {
                cleanupScanner();
                resolve();
            }
        });
    }
    
    // Clean up all scanner state and DOM
    function cleanupScanner() {
        // Reset all state variables
        scannerActive = false;
        initializationInProgress = false;
        html5QrCode = null;
        processingQR = false;
        
        // Clear DOM elements
        const qrReader = document.getElementById('qr-reader');
        if (qrReader) {
            qrReader.innerHTML = '';
        }
        
        // Reset status elements
        const statusElement = document.getElementById('scanner-status');
        const resultContainer = document.getElementById('qr-reader-results');
        if (statusElement) statusElement.textContent = 'Camera activating...';
        if (resultContainer) resultContainer.innerHTML = '';
    }
    
    // Initialize scanner when page loads
    function initializeScanner() {
        setTimeout(() => {
            if (!scannerActive && !initializationInProgress) {
                startScanner();
            }
        }, 1000);
    }
    
    // Handle Livewire events
    document.addEventListener('livewire:initialized', function() {
        initializeScanner();
    });
    
    // Handle page navigation
    document.addEventListener('livewire:navigating', function() {
        stopScanner();
    });
    
    document.addEventListener('livewire:navigated', function() {
        initializeScanner();
    });
    
    // DOM ready fallback
    document.addEventListener('DOMContentLoaded', function() {
        setTimeout(() => {
            if (!scannerActive && !initializationInProgress) {
                initializeScanner();
            }
        }, 2000);
    });
    
    // Handle page unload
    window.addEventListener('beforeunload', function() {
        stopScanner();
    });
</script>
