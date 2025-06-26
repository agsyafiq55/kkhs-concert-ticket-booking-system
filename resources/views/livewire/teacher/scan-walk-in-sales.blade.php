<div>
    <!-- Page Header -->
    <div class="mb-8">
        <flux:heading size="xl" class="mb-2">Walk-in Ticket Sales Scanner</flux:heading>
        <flux:text class="text-zinc-600 dark:text-zinc-400">
            Scan pre-generated walk-in tickets to mark them as sold and collect payment
        </flux:text>
    </div>

    <div class="bg-white dark:bg-zinc-900 overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6">
            <flux:heading size="xl" class="mb-2">Scan Walk-in Tickets for Sale</flux:heading>
            
            <div class="flex items-center mb-6 space-x-2">
                <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"></path>
                </svg>
                <flux:text>Scan a walk-in ticket QR code to collect payment and mark as sold</flux:text>
            </div>

            <!-- QR code scanner -->
            <div class="bg-gray-100 dark:bg-zinc-700 rounded-lg p-6 mb-6" @if($scanStatus) style="display: none;" @endif>
                <div class="text-center mb-4">
                    <flux:text>Walk-in Sales QR Code Scanner</flux:text>
                    <div id="scanner-status" class="text-sm text-gray-500 dark:text-gray-400">
                        Ready to scan walk-in tickets...
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
                        'success' => 'Walk-in Ticket Sold!',
                        'warning' => 'Already Processed',
                        default => 'Sale Failed'
                    };
                @endphp
                
                <div class="mt-8" wire:key="scan-result">
                    <div class="p-4 rounded-lg {{ $containerClasses }}">
                        
                        <!-- Status Icon -->
                        <div class="flex items-center mb-4">
                            @if($scanStatus === 'success')
                                <div class="flex-shrink-0">
                                    <svg class="h-8 w-8 text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                            @elseif($scanStatus === 'warning')
                                <div class="flex-shrink-0">
                                    <svg class="h-8 w-8 text-yellow-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.864-.833-2.634 0L4.232 15.5c-.77.833.192 2.5 1.732 2.5z" />
                                    </svg>
                                </div>
                            @else
                                <div class="flex-shrink-0">
                                    <svg class="h-8 w-8 text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                            @endif
                            
                            <div class="ml-3">
                                <h3 class="text-lg font-medium {{ $titleClasses }}">
                                    {{ $statusTitle }}
                                </h3>
                            </div>
                        </div>

                        <!-- Status Message -->
                        <div class="mb-4">
                            <p class="{{ $messageClasses }}">
                                {{ $scanMessage }}
                            </p>
                        </div>

                        <!-- Ticket Details -->
                        @if($scanResult)
                            <div class="bg-white dark:bg-zinc-800 rounded-lg p-4 mt-4">
                                <h4 class="font-semibold mb-3 text-gray-900 dark:text-gray-100">Ticket Details</h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                                    <div>
                                        <span class="font-medium text-gray-600 dark:text-gray-400">Ticket ID:</span>
                                        <span class="ml-2 text-gray-900 dark:text-gray-100">#{{ $scanResult->id }}</span>
                                    </div>
                                    <div>
                                        <span class="font-medium text-gray-600 dark:text-gray-400">Order ID:</span>
                                        <span class="ml-2 text-gray-900 dark:text-gray-100" style="font-family: 'Courier New', monospace;">{{ $scanResult->formatted_order_id }}</span>
                                    </div>
                                    <div>
                                        <span class="font-medium text-gray-600 dark:text-gray-400">Type:</span>
                                        <span class="ml-2 text-gray-900 dark:text-gray-100">{{ $scanResult->ticket->ticket_type }}</span>
                                    </div>
                                    <div>
                                        <span class="font-medium text-gray-600 dark:text-gray-400">Concert:</span>
                                        <span class="ml-2 text-gray-900 dark:text-gray-100">{{ $scanResult->ticket->concert->title }}</span>
                                    </div>
                                    <div>
                                        <span class="font-medium text-gray-600 dark:text-gray-400">Price:</span>
                                        <span class="ml-2 text-gray-900 dark:text-gray-100">RM{{ number_format($scanResult->ticket->price, 2) }}</span>
                                    </div>
                                    <div>
                                        <span class="font-medium text-gray-600 dark:text-gray-400">Date:</span>
                                        <span class="ml-2 text-gray-900 dark:text-gray-100">{{ $scanResult->ticket->concert->date->format('M d, Y') }}</span>
                                    </div>
                                    <div>
                                        <span class="font-medium text-gray-600 dark:text-gray-400">Time:</span>
                                        <span class="ml-2 text-gray-900 dark:text-gray-100">{{ $scanResult->ticket->concert->start_time->format('g:i A') }}</span>
                                    </div>
                                    <div>
                                        <span class="font-medium text-gray-600 dark:text-gray-400">Status:</span>
                                        <span class="ml-2">
                                            @if($scanResult->status === 'used')
                                                <flux:badge color="zinc">Used</flux:badge>
                                            @elseif($scanResult->is_sold)
                                                <flux:badge color="lime">Sold</flux:badge>
                                            @else
                                                <flux:badge color="amber">Pre-generated</flux:badge>
                                            @endif
                                        </span>
                                    </div>
                                    <div>
                                        <span class="font-medium text-gray-600 dark:text-gray-400">Generated:</span>
                                        <span class="ml-2 text-gray-900 dark:text-gray-100">{{ $scanResult->created_at->format('M d, Y g:i A') }}</span>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Action Button -->
                        <div class="mt-6">
                            <flux:button variant="primary" wire:click="resetScan">
                                Scan Another Ticket
                            </flux:button>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Scan Statistics -->
            @if($scanCount > 0)
                <div class="mt-6 text-center">
                    <flux:text class="text-sm text-gray-500 dark:text-gray-400">
                        Total scans this session: {{ $scanCount }}
                    </flux:text>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- QR Code Scanner Script -->
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
                
                // Create new scanner instance
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
                            resultContainer.innerHTML = `<div class="text-green-600 dark:text-green-400">Processing Walk-in Ticket...</div>`;
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
                    if (statusElement) statusElement.textContent = 'Ready to scan walk-in tickets...';
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
        if (statusElement) statusElement.textContent = 'Ready to scan walk-in tickets...';
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
