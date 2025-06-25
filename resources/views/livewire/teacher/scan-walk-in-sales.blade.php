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
                <div class="mt-8" wire:key="scan-result">
                    <div class="p-4 rounded-lg @if($scanStatus === 'success') bg-green-50 dark:bg-green-900 @elseif($scanStatus === 'warning') bg-yellow-50 dark:bg-yellow-900 @else bg-red-50 dark:bg-red-900 @endif">
                        
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
                                <h3 class="text-lg font-medium @if($scanStatus === 'success') text-green-800 dark:text-green-200 @elseif($scanStatus === 'warning') text-yellow-800 dark:text-yellow-200 @else text-red-800 dark:text-red-200 @endif">
                                    @if($scanStatus === 'success')
                                        Walk-in Ticket Sold!
                                    @elseif($scanStatus === 'warning')
                                        Already Processed
                                    @else
                                        Sale Failed
                                    @endif
                                </h3>
                            </div>
                        </div>

                        <!-- Status Message -->
                        <div class="mb-4">
                            <p class="@if($scanStatus === 'success') text-green-700 dark:text-green-300 @elseif($scanStatus === 'warning') text-yellow-700 dark:text-yellow-300 @else text-red-700 dark:text-red-300 @endif">
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

<!-- Include the same QR scanner script as the regular ticket scanner -->
<script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>

<script>
let html5QrcodeScanner;
let isScanning = false;

document.addEventListener('DOMContentLoaded', function() {
    initializeScanner();
});

// Listen for Livewire events
document.addEventListener('livewire:initialized', () => {
    Livewire.on('scanReset', () => {
        console.log('Received scanReset event');
        setTimeout(() => {
            initializeScanner();
        }, 500);
    });
});

function initializeScanner() {
    console.log('Initializing walk-in sales scanner...');
    
    if (isScanning) {
        console.log('Scanner already running, stopping first...');
        try {
            html5QrcodeScanner.clear();
        } catch (e) {
            console.log('Error stopping scanner:', e);
        }
        isScanning = false;
    }

    // Configure the scanner
    const config = {
        fps: 10,
        qrbox: { width: 250, height: 250 },
        aspectRatio: 1.0,
        rememberLastUsedCamera: true
    };

    html5QrcodeScanner = new Html5QrcodeScanner("qr-reader", config, /* verbose= */ false);
    
    function onScanSuccess(decodedText, decodedResult) {
        console.log(`Walk-in sales QR code scanned: ${decodedText}`);
        
        // Update status
        document.getElementById('scanner-status').textContent = 'Processing walk-in ticket...';
        
        // Send to Livewire component
        Livewire.dispatch('scan-detected', { code: decodedText });
        
        // Stop the scanner
        html5QrcodeScanner.clear();
        isScanning = false;
    }

    function onScanFailure(error) {
        // Handle scan failure - usually just means no QR code detected
        // We don't need to log this as it happens frequently
    }

    // Start scanning
    try {
        html5QrcodeScanner.render(onScanSuccess, onScanFailure);
        isScanning = true;
        document.getElementById('scanner-status').textContent = 'Ready to scan walk-in tickets...';
    } catch (error) {
        console.error('Error starting walk-in sales scanner:', error);
        document.getElementById('scanner-status').textContent = 'Error starting scanner';
    }
}

// Sound feedback functions (reuse from main scanner)
function playSound(type) {
    // Create audio context for sound feedback
    if (typeof AudioContext !== 'undefined' || typeof webkitAudioContext !== 'undefined') {
        const audioContext = new (window.AudioContext || window.webkitAudioContext)();
        
        let frequency;
        let duration;
        
        switch(type) {
            case 'success':
                frequency = 800; // Higher pitch for success
                duration = 200;
                break;
            case 'warning':
                frequency = 600; // Medium pitch for warning
                duration = 300;
                break;
            case 'error':
                frequency = 300; // Lower pitch for error
                duration = 500;
                break;
            default:
                frequency = 500;
                duration = 200;
        }
        
        const oscillator = audioContext.createOscillator();
        const gainNode = audioContext.createGain();
        
        oscillator.connect(gainNode);
        gainNode.connect(audioContext.destination);
        
        oscillator.frequency.setValueAtTime(frequency, audioContext.currentTime);
        oscillator.type = 'sine';
        
        gainNode.gain.setValueAtTime(0.3, audioContext.currentTime);
        gainNode.gain.exponentialRampToValueAtTime(0.01, audioContext.currentTime + duration / 1000);
        
        oscillator.start(audioContext.currentTime);
        oscillator.stop(audioContext.currentTime + duration / 1000);
    }
}

// Cleanup on page unload
window.addEventListener('beforeunload', function() {
    if (isScanning && html5QrcodeScanner) {
        try {
            html5QrcodeScanner.clear();
        } catch (e) {
            console.log('Error during cleanup:', e);
        }
    }
});
</script>
