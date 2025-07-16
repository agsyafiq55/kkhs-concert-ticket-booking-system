<div class="py-8">
    <div class="mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Page Header -->
        <div class="mb-10">
            <div class="flex items-center mb-4">
                <div class="w-12 h-12 bg-gradient-to-br from-rose-500 to-rose-600 rounded-xl flex items-center justify-center mr-4">
                    <flux:icon.user-plus variant="solid" class="w-7 h-7 text-white" />
                </div>
                <div>
                    <flux:heading size="xl">Sell Concert Tickets</flux:heading>
                    <flux:text class="text-zinc-600 dark:text-zinc-400">
                        Sell concert tickets to students individually or in bulk by class.
                    </flux:text>
                </div>
            </div>
        </div>

        <!-- Purchase Mode Selection -->
        <div class="mb-8">
            <div class="bg-white dark:bg-zinc-900 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <flux:heading size="lg" class="mb-4">Select Purchase Mode</flux:heading>
                    <div class="flex justify-center bg-zinc-100 dark:bg-zinc-800 rounded-lg p-4">
                        <div class="flex flex-col sm:flex-row gap-4">
                            <flux:button 
                                wire:click="setPurchaseMode('individual')" 
                                variant="{{ $purchaseMode === 'individual' ? 'primary' : 'filled' }}"
                                class="flex-1 justify-center">
                                <div class="flex items-center">
                                    <flux:icon.user class="w-5 h-5 mr-2" />
                                    Individual Purchase
                                </div>
                            </flux:button>
                            <flux:separator orientation="vertical" class="h-10" />
                            <flux:button 
                                wire:click="setPurchaseMode('bulk')" 
                                variant="{{ $purchaseMode === 'bulk' ? 'primary' : 'filled' }}"
                                class="flex-1 justify-center">
                                <div class="flex items-center">
                                    <flux:icon.user-group class="w-5 h-5 mr-2" />
                                    Bulk Class Purchase
                                </div>
                            </flux:button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if($purchaseMode === 'individual')
            <!-- Individual Assignment Mode -->
            @include('livewire.teacher.assign-tickets.individual-mode')
        @else
            <!-- Bulk Class Purchase Mode -->
            @include('livewire.teacher.assign-tickets.bulk-mode')
        @endif

        <!-- Success State (shared between modes) -->
        @if($ticketAssigned)
        <div class="mb-8">
            <div class="bg-white dark:bg-zinc-900 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="text-center mb-6">
                        <div class="w-16 h-16 bg-green-100 dark:bg-green-800 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-green-600 dark:text-green-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <flux:heading size="lg" class="text-green-800 dark:text-green-200 mb-2">
                            @if($purchaseMode === 'bulk')
                                Bulk Tickets Successfully Assigned!
                            @else
                                Ticket{{ $lastPurchasedQuantity > 1 ? 's' : '' }} Successfully Assigned!
                            @endif
                        </flux:heading>
                        <flux:text class="text-green-600 dark:text-green-400">
                            @if($purchaseMode === 'bulk')
                                {{ $lastPurchasedQuantity }} ticket{{ $lastPurchasedQuantity > 1 ? 's have' : ' has' }} been assigned to {{ $lastPurchasedQuantity }} selected students from {{ $selectedClass->display_name ?? 'the selected class' }}
                            @else
                                {{ $lastPurchasedQuantity }} ticket{{ $lastPurchasedQuantity > 1 ? 's have' : ' has' }} been assigned to {{ $selectedStudent->name ?? 'the selected student' }}
                            @endif
                        </flux:text>
                        <div class="mt-3 p-3 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg">
                            <div class="flex items-center space-x-2">
                                <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"></path>
                                    <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"></path>
                                </svg>
                                <flux:text class="text-sm text-blue-800 dark:text-blue-200">
                                    @if($purchaseMode === 'bulk')
                                        Ticket confirmation emails are being sent to {{ $lastPurchasedQuantity }} selected students in the background - this may take a few minutes
                                    @else
                                        Ticket confirmation email sent to {{ $selectedStudent->email ?? 'the student' }}
                                    @endif
                                </flux:text>
                            </div>
                            @if($purchaseMode === 'bulk')
                            <div class="mt-2 ml-7">
                                <flux:text class="text-xs text-blue-700 dark:text-blue-300">
                                    Students will receive their tickets via email shortly. Check server logs if students report not receiving emails.
                                </flux:text>
                            </div>
                            @endif
                        </div>
                    </div>

                    @if(count($lastPurchases) > 0)
                    <!-- Purchase Summary -->
                    <div class="mb-6 p-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg">
                        <flux:heading size="md" class="text-green-800 dark:text-green-200 mb-3">Purchase Summary</flux:heading>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                            <div>
                                <flux:text class="font-semibold text-green-700 dark:text-green-300">Total Tickets:</flux:text>
                                <flux:text class="text-green-800 dark:text-green-200">{{ count($lastPurchases) }}</flux:text>
                            </div>
                            <div>
                                <flux:text class="font-semibold text-green-700 dark:text-green-300">Total Amount:</flux:text>
                                <flux:text class="text-green-800 dark:text-green-200">RM{{ number_format(collect($lastPurchases)->sum(function($p) { return $p->ticket->price; }), 2) }}</flux:text>
                            </div>
                            <div>
                                <flux:text class="font-semibold text-green-700 dark:text-green-300">
                                    @if($purchaseMode === 'bulk')
                                        Students:
                                    @else
                                        Ticket Types:
                                    @endif
                                </flux:text>
                                <flux:text class="text-green-800 dark:text-green-200">
                                    @if($purchaseMode === 'bulk')
                                        {{ collect($lastPurchases)->unique('student_id')->count() }} students
                                    @else
                                        {{ collect($lastPurchases)->groupBy(function($p) { return $p->ticket->ticket_type . '_' . $p->ticket->concert_id; })->count() }} different type{{ collect($lastPurchases)->groupBy(function($p) { return $p->ticket->ticket_type . '_' . $p->ticket->concert_id; })->count() != 1 ? 's' : '' }}
                                    @endif
                                </flux:text>
                            </div>
                        </div>
                    </div>

                    <!-- Tickets Grid Display -->
                    @php
                    $colors = ['emerald', 'orange', 'sky', 'purple', 'amber', 'pink', 'rose', 'indigo', 'teal', 'cyan'];
                    $colorClasses = [
                        'emerald' => ['bg' => 'bg-emerald-500', 'text' => 'text-emerald-700', 'border' => 'border-emerald-200', 'bg-light' => 'bg-emerald-50'],
                        'orange' => ['bg' => 'bg-orange-500', 'text' => 'text-orange-700', 'border' => 'border-orange-200', 'bg-light' => 'bg-orange-50'],
                        'sky' => ['bg' => 'bg-sky-500', 'text' => 'text-sky-700', 'border' => 'border-sky-200', 'bg-light' => 'bg-sky-50'],
                        'purple' => ['bg' => 'bg-purple-500', 'text' => 'text-purple-700', 'border' => 'border-purple-200', 'bg-light' => 'bg-purple-50'],
                        'amber' => ['bg' => 'bg-amber-500', 'text' => 'text-amber-700', 'border' => 'border-amber-200', 'bg-light' => 'bg-amber-50'],
                        'pink' => ['bg' => 'bg-pink-500', 'text' => 'text-pink-700', 'border' => 'border-pink-200', 'bg-light' => 'bg-pink-50'],
                        'rose' => ['bg' => 'bg-rose-500', 'text' => 'text-rose-700', 'border' => 'border-rose-200', 'bg-light' => 'bg-rose-50'],
                        'indigo' => ['bg' => 'bg-indigo-500', 'text' => 'text-indigo-700', 'border' => 'border-indigo-200', 'bg-light' => 'bg-indigo-50'],
                        'teal' => ['bg' => 'bg-teal-500', 'text' => 'text-teal-700', 'border' => 'border-teal-200', 'bg-light' => 'bg-teal-50'],
                        'cyan' => ['bg' => 'bg-cyan-500', 'text' => 'text-cyan-700', 'border' => 'border-cyan-200', 'bg-light' => 'bg-cyan-50'],
                    ];

                    // Group tickets by type for better color assignment
                    $ticketsByType = collect($lastPurchases)->groupBy(function($purchase) {
                        return $purchase->ticket->ticket_type . '_' . $purchase->ticket->concert_id;
                    });

                    $typeColorMap = [];
                    $colorIndex = 0;
                    foreach($ticketsByType->keys() as $ticketTypeKey) {
                        $typeColorMap[$ticketTypeKey] = $colors[$colorIndex % count($colors)];
                        $colorIndex++;
                    }
                    @endphp

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 max-w-7xl mx-auto">
                        @foreach($lastPurchases as $index => $purchase)
                        @php
                        $ticketTypeKey = $purchase->ticket->ticket_type . '_' . $purchase->ticket->concert_id;
                        $colorKey = $typeColorMap[$ticketTypeKey];
                        $colorSet = $colorClasses[$colorKey];
                        @endphp
                        <div class="bg-white dark:bg-zinc-700 rounded-lg shadow-lg overflow-hidden border-2 {{ $colorSet['border'] }} dark:border-zinc-600">
                            <div class="{{ $colorSet['bg'] }} h-3"></div>
                            <div class="p-6">
                                <div class="text-center mb-4">
                                    @if($purchase->ticket && $purchase->ticket->concert)
                                    <flux:heading size="sm" class="mt-1 text-gray-800 dark:text-gray-200">{{ $purchase->ticket->concert->title }}</flux:heading>
                                    <flux:text class="text-xs text-gray-500 dark:text-gray-400">{{ $purchase->ticket->concert->date->format('M d, Y') }} at {{ $purchase->ticket->concert->start_time->format('g:i A') }}</flux:text>
                                    @endif
                                </div>

                                <div class="space-y-2 mb-4 text-sm">
                                    <div class="flex justify-between">
                                        <span class="text-gray-600 dark:text-gray-400">Type:</span>
                                        <span class="font-semibold text-gray-800 dark:text-gray-200">{{ $purchase->ticket->ticket_type }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600 dark:text-gray-400">Order ID:</span>
                                        <span class="font-semibold text-gray-800 dark:text-gray-200" style="font-family: 'Courier New', monospace;">{{ $purchase->formatted_order_id }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600 dark:text-gray-400">Price:</span>
                                        <span class="font-semibold text-gray-800 dark:text-gray-200">RM{{ number_format($purchase->ticket->price, 2) }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600 dark:text-gray-400">Venue:</span>
                                        <span class="font-semibold text-gray-800 dark:text-gray-200">{{ $purchase->ticket->concert->venue ?? 'TBA' }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600 dark:text-gray-400">Student:</span>
                                        <span class="font-semibold text-gray-800 dark:text-gray-200">{{ $purchase->student->name }}</span>
                                    </div>
                                </div>

                                <div class="flex justify-center mb-4">
                                    @if(isset($lastQrCodeImages[$index]) && $lastQrCodeImages[$index])
                                    <div class="p-3 bg-white rounded-lg shadow-inner">
                                        <img src="data:image/svg+xml;base64,{{ $lastQrCodeImages[$index] }}" alt="QR Code {{ $index + 1 }}" class="w-24 h-24">
                                    </div>
                                    @else
                                    <div class="w-24 h-24 bg-gray-100 dark:bg-zinc-600 rounded-lg flex items-center justify-center">
                                        <flux:text class="text-xs text-center text-gray-500">QR Code</flux:text>
                                    </div>
                                    @endif
                                </div>
                                <div class="text-center">
                                    <div class="mt-2">
                                        <span class="{{ $colorSet['bg'] }} text-white text-xs px-3 py-1 rounded-full font-semibold">{{ $purchase->ticket->ticket_type }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @endif

                    <div class="text-center mt-6">
                        <flux:button variant="filled" wire:click="resetForm">
                            @if($purchaseMode === 'bulk')
                                Assign More Bulk Tickets
                            @else
                                Assign More Tickets
                            @endif
                        </flux:button>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>