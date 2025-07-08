<div class="py-8">
    <div class="mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Progress Steps -->
        <div class="mb-8">
            <div class="flex items-center justify-center">
                <ol class="flex items-center w-full max-w-2xl">
                    <!-- Step 1: Enter VIP Details -->
                    <li class="flex w-full items-center {{ $vipName && $vipEmail && $vipPhone ? 'text-green-600 dark:text-green-400' : 'text-rose-600 dark:text-rose-400' }}">
                        <span class="flex items-center justify-center w-10 h-10 {{ $vipName && $vipEmail && $vipPhone ? 'bg-green-100 border-green-600 dark:bg-green-800 dark:border-green-400' : 'bg-rose-100 border-rose-600 dark:bg-rose-800 dark:border-rose-400' }} border-2 rounded-full lg:h-12 lg:w-12 shrink-0">
                            @if($vipName && $vipEmail && $vipPhone)
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            @else
                            <span class="text-sm font-bold">1</span>
                            @endif
                        </span>
                        <span class="text-sm font-medium ml-2 lg:ml-4">VIP Details</span>
                        <div class="hidden sm:flex w-full bg-gray-200 h-0.5 dark:bg-zinc-700 ml-4"></div>
                    </li>

                    <!-- Step 2: Add Tickets to Cart -->
                    <li class="flex w-full items-center {{ count($cart) > 0 ? 'text-green-600 dark:text-green-400' : (($vipName && $vipEmail && $vipPhone) ? 'text-rose-600 dark:text-rose-400' : 'text-gray-500 dark:text-zinc-400') }}">
                        <span class="flex items-center justify-center w-10 h-10 {{ count($cart) > 0 ? 'bg-green-100 border-green-600 dark:bg-green-800 dark:border-green-400' : (($vipName && $vipEmail && $vipPhone) ? 'bg-rose-100 border-rose-600 dark:bg-rose-800 dark:border-rose-400' : 'bg-gray-100 border-gray-300 dark:bg-zinc-700 dark:border-zinc-600') }} border-2 rounded-full lg:h-12 lg:w-12 shrink-0">
                            @if(count($cart) > 0)
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            @else
                            <span class="text-sm font-bold">2</span>
                            @endif
                        </span>
                        <span class="text-sm font-medium ml-2 lg:ml-4">Add Tickets</span>
                        <div class="hidden sm:flex w-full bg-gray-200 h-0.5 dark:bg-zinc-700 ml-4"></div>
                    </li>

                    <!-- Step 3: Complete Sale -->
                    <li class="flex items-center {{ $ticketsSold ? 'text-green-600 dark:text-green-400' : (($vipName && $vipEmail && $vipPhone && count($cart) > 0) ? 'text-rose-600 dark:text-rose-400' : 'text-gray-500 dark:text-zinc-400') }}">
                        <span class="flex items-center justify-center w-10 h-10 {{ $ticketsSold ? 'bg-green-100 border-green-600 dark:bg-green-800 dark:border-green-400' : (($vipName && $vipEmail && $vipPhone && count($cart) > 0) ? 'bg-rose-100 border-rose-600 dark:bg-rose-800 dark:border-rose-400' : 'bg-gray-100 border-gray-300 dark:bg-zinc-700 dark:border-zinc-600') }} border-2 rounded-full lg:h-12 lg:w-12 shrink-0">
                            @if($ticketsSold)
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            @else
                            <span class="text-sm font-bold">3</span>
                            @endif
                        </span>
                        <span class="text-sm font-medium ml-2 lg:ml-4">Complete</span>
                    </li>
                </ol>
            </div>
        </div>

        <!-- Main Content -->
        <div class="bg-white dark:bg-zinc-900 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <flux:heading size="xl" class="mb-6 text-center">Sell VIP Tickets</flux:heading>

                <!-- Cart Success Message -->
                @if (session()->has('cart-message'))
                <div class="mb-4 p-3 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg">
                    <flux:text class="text-green-800 dark:text-green-200">{{ session('cart-message') }}</flux:text>
                </div>
                @endif

                <!-- Cart Errors -->
                @error('cart')
                <div class="mb-4 p-3 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg">
                    <flux:text class="text-red-800 dark:text-red-200">{{ $message }}</flux:text>
                </div>
                @enderror

                <!-- General Errors -->
                @error('general')
                <div class="mb-4 p-3 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg">
                    <flux:text class="text-red-800 dark:text-red-200">{{ $message }}</flux:text>
                </div>
                @enderror

                <!-- Step 1: Enter VIP Details -->
                @if(!$ticketsSold)
                <div class="mb-8">
                    <div class="flex items-center mb-4">
                        <div class="flex items-center justify-center w-8 h-8 {{ $vipName && $vipEmail && $vipPhone ? 'bg-green-100 text-green-600 dark:bg-green-800 dark:text-green-400' : 'bg-rose-100 text-rose-600 dark:bg-rose-800 dark:text-rose-400' }} rounded-full mr-3">
                            <span class="text-sm font-bold">1</span>
                        </div>
                        <flux:heading size="lg">VIP Customer Details</flux:heading>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                        <flux:field>
                            <flux:label>VIP Name</flux:label>
                            <flux:input 
                                wire:model.live="vipName" 
                                placeholder="Enter VIP's full name"
                                class="w-full" />
                            <flux:error name="vipName" />
                        </flux:field>

                        <flux:field>
                            <flux:label>VIP Email</flux:label>
                            <flux:input 
                                type="email"
                                wire:model.live="vipEmail" 
                                placeholder="Enter VIP's email address"
                                class="w-full" />
                            <flux:error name="vipEmail" />
                        </flux:field>

                        <flux:field>
                            <flux:label>VIP Phone</flux:label>
                            <flux:input 
                                wire:model.live="vipPhone" 
                                placeholder="Enter VIP's phone number"
                                class="w-full" />
                            <flux:error name="vipPhone" />
                        </flux:field>
                    </div>

                    @if($vipName && $vipEmail && $vipPhone)
                    <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg p-4">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-green-100 dark:bg-green-800 rounded-full flex items-center justify-center">
                                <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <div class="flex-grow">
                                <flux:text class="font-semibold text-green-800 dark:text-green-200">{{ $vipName }}</flux:text>
                                <flux:text class="text-sm text-green-600 dark:text-green-400 block">{{ $vipEmail }}</flux:text>
                                <flux:text class="text-sm text-green-600 dark:text-green-400 block">{{ $vipPhone }}</flux:text>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
                @endif

                <!-- Cart Display -->
                @if($vipName && $vipEmail && $vipPhone && count($cart) > 0 && !$ticketsSold)
                <div class="mb-8">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center">
                            <div class="flex items-center justify-center w-8 h-8 bg-green-100 text-green-600 dark:bg-green-800 dark:text-green-400 rounded-full mr-3">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3zM16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z"></path>
                                </svg>
                            </div>
                            <flux:heading size="lg">Cart ({{ $this->cartItemCount }} ticket{{ $this->cartItemCount != 1 ? 's' : '' }})</flux:heading>
                        </div>
                        <flux:button size="sm" variant="ghost" wire:click="clearCart">
                            Clear All
                        </flux:button>
                    </div>

                    <div class="border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden">
                        @foreach($cart as $index => $item)
                        <div class="p-4 border-b border-gray-200 dark:border-gray-700 last:border-b-0">
                            <div class="flex justify-between items-start">
                                <div class="flex-grow">
                                    <flux:text class="font-semibold">{{ $item['ticket_type'] }}</flux:text>
                                    <flux:text class="text-sm text-gray-600 dark:text-zinc-400 block">{{ $item['concert_title'] }}</flux:text>
                                    <flux:text class="text-sm text-gray-600 dark:text-zinc-400 block">{{ $item['concert_date'] }}</flux:text>
                                    <div class="flex items-center space-x-4 mt-2">
                                        <flux:text class="text-sm">Quantity: {{ $item['quantity'] }}</flux:text>
                                        <flux:text class="text-sm">RM{{ number_format($item['price'], 2) }} each</flux:text>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-3">
                                    <flux:text class="font-bold text-lg">RM{{ number_format($item['subtotal'], 2) }}</flux:text>
                                    <flux:button size="sm" variant="ghost" wire:click="removeFromCart({{ $index }})">
                                        Remove
                                    </flux:button>
                                </div>
                            </div>
                        </div>
                        @endforeach

                        <!-- Cart Total -->
                        <div class="p-4 bg-blue-100 dark:bg-blue-900/40">
                            <div class="flex justify-between items-center">
                                <flux:text class="text-lg font-bold text-blue-800 dark:text-blue-200">Total:</flux:text>
                                <flux:text class="text-xl font-bold text-blue-800 dark:text-blue-200">RM{{ number_format($this->cartTotal, 2) }}</flux:text>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Step 2: Select Tickets -->
                @if($vipName && $vipEmail && $vipPhone && !$ticketsSold)
                <div class="mb-8">
                    <div class="flex items-center mb-4">
                        <div class="flex items-center justify-center w-8 h-8 {{ count($cart) > 0 ? 'bg-green-100 text-green-600 dark:bg-green-800 dark:text-green-400' : 'bg-rose-100 text-rose-600 dark:bg-rose-800 dark:text-rose-400' }} rounded-full mr-3">
                            <span class="text-sm font-bold">2</span>
                        </div>
                        <flux:heading size="lg">Select VIP Tickets</flux:heading>
                    </div>

                    <!-- Concert Filter -->
                    <div class="mb-4">
                        <flux:field>
                            <flux:label>Filter by Concert</flux:label>
                            <flux:select wire:model.live="concertFilter" placeholder="All concerts">
                                @foreach($concerts as $concert)
                                <flux:select.option value="{{ $concert->id }}">{{ $concert->title }} - {{ $concert->date->format('M d, Y') }}</flux:select.option>
                                @endforeach
                            </flux:select>
                        </flux:field>
                    </div>

                    @if(count($tickets) > 0)
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            @foreach ($tickets as $ticket)
                            <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4 hover:border-rose-300 dark:hover:border-rose-600 transition">
                                <div class="flex justify-between items-start mb-2">
                                    <flux:text class="font-semibold">{{ $ticket->ticket_type }}</flux:text>
                                    <flux:badge color="lime">{{ $ticket->remaining_tickets }} left</flux:badge>
                                </div>
                                <flux:text class="text-sm text-gray-600 dark:text-zinc-400 mb-1">{{ $ticket->concert->title }}</flux:text>
                                <flux:text class="text-sm text-gray-600 dark:text-zinc-400 mb-2">{{ $ticket->concert->date->format('M d, Y') }} at {{ $ticket->concert->start_time->format('g:i A') }}</flux:text>
                                
                                <div class="flex justify-between items-center mb-3">
                                    <flux:text class="text-lg font-bold text-green-600 dark:text-green-400">RM{{ number_format($ticket->price, 2) }}</flux:text>
                                </div>

                                @if($selectedTicketId == $ticket->id)
                                <div class="mb-3 p-3 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded">
                                    <flux:field>
                                        <flux:label>Quantity</flux:label>
                                        <flux:input 
                                            type="number" 
                                            wire:model.live="quantity" 
                                            min="1" 
                                            max="{{ $ticket->remaining_tickets }}"
                                            class="w-full" />
                                    </flux:field>
                                    
                                    <div class="flex justify-between items-center mt-3">
                                        <flux:text class="font-semibold">Total: RM{{ number_format($ticket->price * $quantity, 2) }}</flux:text>
                                        <div class="flex space-x-2">
                                            <flux:button size="sm" variant="ghost" wire:click="selectTicket(null)">
                                                Cancel
                                            </flux:button>
                                            <flux:button size="sm" variant="primary" wire:click="addToCart({{ $ticket->id }})">
                                                Add to Cart
                                            </flux:button>
                                        </div>
                                    </div>
                                </div>
                                @else
                                <flux:button 
                                    size="sm" 
                                    variant="filled" 
                                    wire:click="selectTicket({{ $ticket->id }})"
                                    class="w-full">
                                    Select Ticket
                                </flux:button>
                                @endif
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="p-8 text-center text-gray-500 dark:text-zinc-400">
                            <svg class="w-12 h-12 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path>
                            </svg>
                            <flux:text>No tickets available for VIP sales</flux:text>
                            @if($concertFilter)
                            <flux:button size="sm" variant="ghost" wire:click="$set('concertFilter', '')" class="mt-2">
                                View all concerts
                            </flux:button>
                            @endif
                        </div>
                    @endif
                </div>
                @endif

                <!-- Step 3: Complete Sale -->
                @if($vipName && $vipEmail && $vipPhone && count($cart) > 0 && !$ticketsSold)
                <div class="mb-8">
                    <div class="flex items-center mb-4">
                        <div class="flex items-center justify-center w-8 h-8 bg-rose-100 text-rose-600 dark:bg-rose-800 dark:text-rose-400 rounded-full mr-3">
                            <span class="text-sm font-bold">3</span>
                        </div>
                        <flux:heading size="lg">Complete VIP Sale</flux:heading>
                    </div>

                    <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg p-4 mb-4">
                        <div class="flex items-start space-x-3">
                            <svg class="w-5 h-5 text-yellow-600 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                            </svg>
                            <div>
                                <flux:text class="font-semibold text-yellow-800 dark:text-yellow-200">Important Notice</flux:text>
                                <flux:text class="text-sm text-yellow-700 dark:text-yellow-300 block mt-1">
                                    Please ensure payment has been received before completing this VIP sale. An email confirmation will be sent to the VIP customer at {{ $vipEmail }}.
                                </flux:text>
                            </div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <flux:field variant="inline">
                            <flux:label>Payment received from VIP customer</flux:label>
                            <flux:checkbox wire:model.live="paymentReceived" />
                            <flux:error name="paymentReceived" />
                        </flux:field>
                    </div>

                    <div class="flex justify-end">
                        <flux:button
                            variant="primary"
                            wire:click="sellVipTickets"
                            :disabled="!$paymentReceived"
                            class="{{ !$paymentReceived ? 'opacity-50 cursor-not-allowed' : '' }}">
                            <span wire:loading.remove wire:target="sellVipTickets">
                                @if(!$paymentReceived)
                                    Confirm Payment First
                                @else
                                    Complete VIP Sale & Send Email
                                @endif
                            </span>
                            <span wire:loading wire:target="sellVipTickets">
                                Processing...
                            </span>
                        </flux:button>
                    </div>
                </div>
                @endif

                <!-- Success State -->
                @if($ticketsSold)
                <div class="mb-8">
                    <!-- Celebration Header -->
                    <div class="text-center mb-8">
                        <div class="w-20 h-20 bg-gradient-to-br from-green-400 to-green-600 rounded-full flex items-center justify-center mx-auto mb-6 shadow-lg">
                            <svg class="w-10 h-10 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        
                        <div class="mb-4">
                            <flux:heading size="xl" class="text-green-800 dark:text-green-200 mb-2">🎉 VIP Sale Completed Successfully! 🎉</flux:heading>
                            <flux:text class="text-lg text-green-600 dark:text-green-400">
                                {{ $lastSoldQuantity }} VIP ticket{{ $lastSoldQuantity > 1 ? 's have' : ' has' }} been sold to <strong>{{ $lastSoldTickets[0]->vip_name ?? 'VIP customer' }}</strong>
                            </flux:text>
                        </div>
                    </div>

                    <!-- Transaction Summary -->
                    @if(count($lastSoldTickets) > 0)
                    <div class="bg-gradient-to-r from-green-50 to-emerald-50 dark:from-green-900/20 dark:to-emerald-900/20 border border-green-200 dark:border-green-800 rounded-xl p-6 mb-6">
                        <flux:heading size="lg" class="text-green-800 dark:text-green-200 mb-4 text-center">📋 Purchase Summary</flux:heading>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Customer Details -->
                            <div class="space-y-3">
                                <flux:text class="font-semibold text-green-700 dark:text-green-300 text-sm uppercase tracking-wide">Customer Information</flux:text>
                                <div class="space-y-2">
                                    <div class="flex items-center space-x-2">
                                        <svg class="w-4 h-4 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                                        </svg>
                                        <flux:text class="text-green-800 dark:text-green-200">{{ $lastSoldTickets[0]->vip_name }}</flux:text>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <svg class="w-4 h-4 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"></path>
                                            <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"></path>
                                        </svg>
                                        <flux:text class="text-green-800 dark:text-green-200">{{ $lastSoldTickets[0]->vip_email }}</flux:text>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <svg class="w-4 h-4 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M2 3.5A1.5 1.5 0 013.5 2h1.148a1.5 1.5 0 011.465 1.175l.716 3.223a1.5 1.5 0 01-1.052 1.767l-.933.267c-.41.117-.643.555-.48.95a11.542 11.542 0 006.254 6.254c.395.163.833-.07.95-.48l.267-.933a1.5 1.5 0 011.767-1.052l3.223.716A1.5 1.5 0 0118 15.352V16.5a1.5 1.5 0 01-1.5 1.5H15c-1.149 0-2.263-.15-3.326-.43A13.022 13.022 0 012.43 8.326 13.019 13.019 0 012 5V3.5z" clip-rule="evenodd"></path>
                                        </svg>
                                        <flux:text class="text-green-800 dark:text-green-200">{{ $lastSoldTickets[0]->vip_phone }}</flux:text>
                                    </div>
                                </div>
                            </div>

                            <!-- Transaction Details -->
                            <div class="space-y-3">
                                <flux:text class="font-semibold text-green-700 dark:text-green-300 text-sm uppercase tracking-wide">Transaction Details</flux:text>
                                <div class="space-y-2">
                                    <div class="flex justify-between">
                                        <flux:text class="text-green-700 dark:text-green-300">Order ID:</flux:text>
                                        <flux:text class="font-mono text-green-800 dark:text-green-200">{{ $lastSoldTickets[0]->formatted_order_id }}</flux:text>
                                    </div>
                                    <div class="flex justify-between">
                                        <flux:text class="text-green-700 dark:text-green-300">Tickets Sold:</flux:text>
                                        <flux:text class="font-semibold text-green-800 dark:text-green-200">{{ $lastSoldQuantity }}</flux:text>
                                    </div>
                                    <div class="flex justify-between">
                                        <flux:text class="text-green-700 dark:text-green-300">Total Amount:</flux:text>
                                        <flux:text class="font-bold text-xl text-green-800 dark:text-green-200">
                                            RM{{ number_format(collect($lastSoldTickets)->sum(function($ticket) { return $ticket->ticket->price; }), 2) }}
                                        </flux:text>
                                    </div>
                                    <div class="flex justify-between">
                                        <flux:text class="text-green-700 dark:text-green-300">Sale Date:</flux:text>
                                        <flux:text class="text-green-800 dark:text-green-200">{{ now()->format('M d, Y \a\t g:i A') }}</flux:text>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Tickets Breakdown -->
                        @php
                            $ticketGroups = collect($lastSoldTickets)->groupBy(function($ticket) {
                                return $ticket->ticket->ticket_type . ' - ' . $ticket->ticket->concert->title;
                            });
                        @endphp
                        
                        @if($ticketGroups->count() > 1)
                        <div class="mt-6 pt-4 border-t border-green-200 dark:border-green-700">
                            <flux:text class="font-semibold text-green-700 dark:text-green-300 text-sm uppercase tracking-wide block mb-3">Ticket Breakdown</flux:text>
                            <div class="space-y-2">
                                @foreach($ticketGroups as $ticketType => $tickets)
                                <div class="flex justify-between items-center py-2 px-3 bg-white dark:bg-zinc-800 rounded-lg">
                                    <flux:text class="text-green-800 dark:text-green-200">{{ $ticketType }} × {{ $tickets->count() }}</flux:text>
                                    <flux:text class="font-semibold text-green-800 dark:text-green-200">
                                        RM{{ number_format($tickets->sum(function($t) { return $t->ticket->price; }), 2) }}
                                    </flux:text>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endif
                    </div>
                    @endif

                    <!-- Email Confirmation Notice -->
                    <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4 mb-6">
                        <div class="flex items-start space-x-3">
                            <div class="w-6 h-6 bg-blue-100 dark:bg-blue-800 rounded-full flex items-center justify-center mt-0.5">
                                <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"></path>
                                    <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"></path>
                                </svg>
                            </div>
                            <div class="flex-grow">
                                <flux:text class="font-semibold text-blue-800 dark:text-blue-200">Email Confirmation Sent!</flux:text>
                                <flux:text class="text-sm text-blue-700 dark:text-blue-300 block mt-1">
                                    A detailed ticket confirmation email with QR codes has been sent to <strong>{{ $lastSoldTickets[0]->vip_email ?? 'VIP email' }}</strong>. 
                                    The VIP customer can print their tickets or save them on their phone for entry.
                                </flux:text>
                            </div>
                        </div>
                    </div>

                    <!-- Success Actions -->
                    <div class="flex flex-col sm:flex-row justify-center items-center space-y-3 sm:space-y-0 sm:space-x-4">
                        <flux:button variant="filled" wire:click="resetForm" class="w-full sm:w-auto">
                            Sell More VIP Tickets
                        </flux:button>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
