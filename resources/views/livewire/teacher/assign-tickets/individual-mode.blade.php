<!-- Individual Assignment Mode -->
<!-- Progress Steps -->
<div class="mb-8">
    <div class="flex items-center justify-center">
        <ol class="flex items-center w-full max-w-2xl">
            <!-- Step 1: Select Student -->
            <li class="flex w-full items-center {{ $selectedStudentId ? 'text-green-600 dark:text-green-400' : 'text-rose-600 dark:text-rose-400' }}">
                <span class="flex items-center justify-center w-10 h-10 {{ $selectedStudentId ? 'bg-green-100 border-green-600 dark:bg-green-800 dark:border-green-400' : 'bg-rose-100 border-rose-600 dark:bg-rose-800 dark:border-rose-400' }} border-2 rounded-full lg:h-12 lg:w-12 shrink-0">
                    @if($selectedStudentId)
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                    </svg>
                    @else
                    <span class="text-sm font-bold">1</span>
                    @endif
                </span>
                <span class="text-sm font-medium ml-2 lg:ml-4">Select Student</span>
                <div class="hidden sm:flex w-full bg-gray-200 h-0.5 dark:bg-zinc-700 ml-4"></div>
            </li>

            <!-- Step 2: Add Tickets to Cart -->
            <li class="flex w-full items-center {{ count($cart) > 0 ? 'text-green-600 dark:text-green-400' : ($selectedStudentId ? 'text-rose-600 dark:text-rose-400' : 'text-gray-500 dark:text-zinc-400') }}">
                <span class="flex items-center justify-center w-10 h-10 {{ count($cart) > 0 ? 'bg-green-100 border-green-600 dark:bg-green-800 dark:border-green-400' : ($selectedStudentId ? 'bg-rose-100 border-rose-600 dark:bg-rose-800 dark:border-rose-400' : 'bg-gray-100 border-gray-300 dark:bg-zinc-700 dark:border-zinc-600') }} border-2 rounded-full lg:h-12 lg:w-12 shrink-0">
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

            <!-- Step 3: Confirm Assignment -->
            <li class="flex items-center {{ $ticketAssigned ? 'text-green-600 dark:text-green-400' : ($selectedStudentId && count($cart) > 0 ? 'text-rose-600 dark:text-rose-400' : 'text-gray-500 dark:text-zinc-400') }}">
                <span class="flex items-center justify-center w-10 h-10 {{ $ticketAssigned ? 'bg-green-100 border-green-600 dark:bg-green-800 dark:border-green-400' : ($selectedStudentId && count($cart) > 0 ? 'bg-rose-100 border-rose-600 dark:bg-rose-800 dark:border-rose-400' : 'bg-gray-100 border-gray-300 dark:bg-zinc-700 dark:border-zinc-600') }} border-2 rounded-full lg:h-12 lg:w-12 shrink-0">
                    @if($ticketAssigned)
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
<div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
    <!-- Main Assignment Panel -->
    <div class="xl:col-span-2">
        <div class="bg-white dark:bg-zinc-900 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
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

                <!-- Step 1: Select Student -->
                <div class="mb-8">
                    <div class="flex items-center mb-4">
                        <div class="flex items-center justify-center w-8 h-8 {{ $selectedStudentId ? 'bg-green-100 text-green-600 dark:bg-green-800 dark:text-green-400' : 'bg-rose-100 text-rose-600 dark:bg-rose-800 dark:text-rose-400' }} rounded-full mr-3">
                            <span class="text-sm font-bold">1</span>
                        </div>
                        <flux:heading size="lg">Select Student</flux:heading>
                    </div>

                    @if($selectedStudentId)
                    <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg p-4">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-green-100 dark:bg-green-800 rounded-full flex items-center justify-center">
                                    <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                                <div>
                                    <flux:text class="font-semibold text-green-800 dark:text-green-200">{{ $selectedStudent->name }}</flux:text>
                                    <flux:text class="text-sm text-green-600 dark:text-green-400">{{ $selectedStudent->email }}</flux:text>
                                </div>
                            </div>
                            <flux:button size="sm" variant="ghost" wire:click="resetForm">
                                Change
                            </flux:button>
                        </div>
                    </div>
                    @else
                    <div class="space-y-4">
                        <flux:field>
                            <flux:input
                                icon="magnifying-glass"
                                wire:model.live="search"
                                placeholder="Search students by name or email..."
                                class="w-full" />
                        </flux:field>

                        @if(strlen($search) >= 2)
                        <div class="border border-gray-200 dark:border-zinc-700 rounded-lg overflow-hidden">
                            @forelse ($students as $student)
                            <div class="flex items-center justify-between p-4 hover:bg-gray-50 dark:hover:bg-zinc-700 border-b border-gray-200 dark:border-zinc-700 last:border-b-0">
                                <div class="flex items-center space-x-3">
                                    <div class="w-8 h-8 bg-gray-100 dark:bg-zinc-600 rounded-full flex items-center justify-center">
                                        <svg class="w-4 h-4 text-gray-600 dark:text-zinc-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="font-medium">{{ $student->name }}</div>
                                        <div class="text-sm text-gray-500 dark:text-zinc-400">{{ $student->email }}</div>
                                        @if($student->schoolClass)
                                            <div class="text-xs text-rose-600 dark:text-rose-400 font-medium">{{ $student->schoolClass->display_name }}</div>
                                        @else
                                            <div class="text-xs text-gray-400">No Class Assigned</div>
                                        @endif
                                    </div>
                                </div>
                                <flux:button size="sm" variant="primary" wire:click="selectStudent({{ $student->id }})">
                                    Select
                                </flux:button>
                            </div>
                            @empty
                            <div class="p-8 text-center text-gray-500 dark:text-zinc-400">
                                <svg class="w-12 h-12 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                                </svg>
                                No students found matching your search.
                            </div>
                            @endforelse
                        </div>

                        @if($students->hasPages())
                        <div class="mt-4">
                            {{ $students->links() }}
                        </div>
                        @endif
                        @else
                        <div class="p-8 text-center text-gray-500 dark:text-zinc-400">
                            <svg class="w-12 h-12 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            Start typing to search for students
                        </div>
                        @endif
                    </div>
                    @endif
                </div>

                <!-- Cart Display -->
                @if($selectedStudentId && count($cart) > 0 && !$ticketAssigned)
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

                    <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg overflow-hidden">
                        @foreach($cart as $index => $item)
                        <div class="p-4 {{ !$loop->last ? 'border-b border-blue-200 dark:border-blue-700' : '' }}">
                            <div class="flex items-center justify-between">
                                <div class="flex-1">
                                    <flux:text class="font-semibold text-blue-800 dark:text-blue-200">{{ $item['ticket_type'] }}</flux:text>
                                    <flux:text class="text-sm text-blue-600 dark:text-blue-400 block">{{ $item['concert_title'] }}</flux:text>
                                    <flux:text class="text-sm text-blue-600 dark:text-blue-400 block">{{ $item['concert_date'] }} at {{ $item['concert_time'] }}</flux:text>
                                    <flux:text class="text-sm text-blue-600 dark:text-blue-400 block">RM{{ number_format($item['price'], 2) }} each</flux:text>
                                </div>
                                <div class="flex items-center space-x-3">
                                    <div class="flex items-center space-x-2">
                                        <flux:text class="text-sm text-blue-600 dark:text-blue-400">Qty:</flux:text>
                                        <flux:select wire:change="updateCartQuantity({{ $index }}, $event.target.value)" class="w-20">
                                            @for ($i = 1; $i <= min(10, $item['available_tickets']); $i++)
                                                <option value="{{ $i }}" {{ $item['quantity'] == $i ? 'selected' : '' }}>{{ $i }}</option>
                                                @endfor
                                        </flux:select>
                                    </div>
                                    <div class="text-right">
                                        <flux:text class="font-bold text-blue-800 dark:text-blue-200">RM{{ number_format($item['subtotal'], 2) }}</flux:text>
                                    </div>
                                    <flux:button size="sm" variant="ghost" wire:click="removeFromCart({{ $index }})">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
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
                @if($selectedStudentId && !$ticketAssigned)
                <div class="mb-8">
                    <div class="flex items-center mb-4">
                        <div class="flex items-center justify-center w-8 h-8 {{ count($cart) > 0 ? 'bg-green-100 text-green-600 dark:bg-green-800 dark:text-green-400' : 'bg-rose-100 text-rose-600 dark:bg-rose-800 dark:text-rose-400' }} rounded-full mr-3">
                            <span class="text-sm font-bold">2</span>
                        </div>
                        <flux:heading size="lg">Add Concert Tickets</flux:heading>
                    </div>

                    <div class="space-y-4">
                        <flux:field>
                            <flux:label>Select Concert</flux:label>
                            <flux:select wire:model.live="concertFilter" placeholder="Choose a concert...">
                                @foreach ($concerts as $concert)
                                <flux:select.option value="{{ $concert->id }}">
                                    {{ $concert->title }} - {{ $concert->date->format('M d, Y') }}
                                </flux:select.option>
                                @endforeach
                            </flux:select>
                        </flux:field>

                        @if($concertFilter)
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            @forelse ($tickets as $ticket)
                            <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4 hover:border-rose-300 dark:hover:border-rose-600 transition">
                                <div class="flex justify-between items-start mb-2">
                                    <flux:text class="font-semibold">{{ $ticket->ticket_type }}</flux:text>
                                    <flux:badge color="lime">{{ $ticket->remaining_tickets }} left</flux:badge>
                                </div>
                                <flux:text class="text-sm text-gray-600 dark:text-zinc-400 mb-1">{{ $ticket->concert->title }}</flux:text>
                                <flux:text class="text-sm text-gray-600 dark:text-zinc-400 mb-2">{{ $ticket->concert->date->format('M d, Y') }} at {{ $ticket->concert->start_time->format('g:i A') }}</flux:text>
                                <div class="flex justify-between items-end">
                                    <div>
                                        <flux:text class="font-bold text-lg">RM{{ number_format($ticket->price, 2) }}</flux:text>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <flux:select wire:model.live="quantity" class="w-16">
                                            @for ($i = 1; $i <= min(10, $ticket->remaining_tickets); $i++)
                                                <option value="{{ $i }}">{{ $i }}</option>
                                                @endfor
                                        </flux:select>
                                        <flux:button size="sm" variant="primary" wire:click="addToCart({{ $ticket->id }})">
                                            Add to Cart
                                        </flux:button>
                                    </div>
                                </div>
                            </div>
                            @empty
                            <div class="col-span-2 p-8 text-center text-gray-500 dark:text-zinc-400">
                                <svg class="w-12 h-12 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a1 1 0 001 1h1a1 1 0 001-1V7a2 2 0 00-2-2H5zM5 14a2 2 0 00-2 2v3a1 1 0 001 1h1a1 1 0 001-1v-3a2 2 0 00-2-2H5z"></path>
                                </svg>
                                No tickets available for this concert.
                            </div>
                            @endforelse
                        </div>
                        @else
                        <div class="p-8 text-center text-gray-500 dark:text-zinc-400">
                            Please select a concert to view available tickets.
                        </div>
                        @endif
                    </div>
                </div>
                @endif

                <!-- Step 3: Payment & Confirmation -->
                @if($selectedStudentId && count($cart) > 0 && !$ticketAssigned)
                <div class="mb-8">
                    <div class="flex items-center mb-4">
                        <div class="flex items-center justify-center w-8 h-8 bg-rose-100 text-rose-600 dark:bg-rose-800 dark:text-rose-400 rounded-full mr-3">
                            <span class="text-sm font-bold">3</span>
                        </div>
                        <flux:heading size="lg">Payment & Confirmation</flux:heading>
                    </div>

                    <div class="bg-rose-50 dark:bg-rose-900/20 border border-rose-200 dark:border-rose-800 rounded-lg p-6">
                        <flux:heading size="md" class="mb-4">Purchase Summary</flux:heading>
                        <div class="space-y-3 mb-6">
                            <div class="flex justify-between">
                                <flux:text>Student:</flux:text>
                                <flux:text class="font-semibold">{{ $selectedStudent->name }}</flux:text>
                            </div>

                            <!-- Cart Summary -->
                            <div class="border-t border-rose-300 dark:border-rose-600 pt-3">
                                <flux:text class="font-semibold mb-2 block">Tickets:</flux:text>
                                @foreach($cart as $item)
                                <div class="flex justify-between text-sm mb-1">
                                    <flux:text>{{ $item['ticket_type'] }} ({{ $item['concert_title'] }}) × {{ $item['quantity'] }}</flux:text>
                                    <flux:text class="font-semibold">RM{{ number_format($item['subtotal'], 2) }}</flux:text>
                                </div>
                                @endforeach
                            </div>

                            <flux:separator />

                            <!-- Payment Amount - Highlighted -->
                            <div class="bg-white dark:bg-zinc-900 rounded-lg p-4 border-2 border-green-300 dark:border-green-600">
                                <div class="flex justify-between items-center">
                                    <div>
                                        <flux:text class="text-lg font-bold text-green-800 dark:text-green-200">Total Amount to Collect:</flux:text>
                                        <flux:text class="text-sm text-green-600 dark:text-green-400">Cash payment from student</flux:text>
                                    </div>
                                    <div class="text-right">
                                        <div class="text-3xl font-bold text-green-800 dark:text-green-200">RM{{ number_format($this->cartTotal, 2) }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Payment Confirmation -->
                        <div class="mb-6">
                            <flux:field variant="inline">
                                <flux:checkbox wire:model.live="paymentReceived" />
                                <flux:label class="ml-2">
                                    I have received RM{{ number_format($this->cartTotal, 2) }} cash payment from {{ $selectedStudent->name }} for {{ $this->cartItemCount }} ticket{{ $this->cartItemCount != 1 ? 's' : '' }}
                                </flux:label>
                            </flux:field>

                            @if($errors->has('paymentReceived'))
                            <flux:error>{{ $errors->first('paymentReceived') }}</flux:error>
                            @endif

                            @if(!$paymentReceived)
                            <div class="mt-3 p-3 bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 rounded-lg">
                                <div class="flex items-center space-x-2">
                                    <svg class="w-5 h-5 text-amber-600 dark:text-amber-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                    </svg>
                                    <flux:text class="text-sm text-amber-800 dark:text-amber-200 font-medium">
                                        Please confirm that you have received the cash payment before proceeding.
                                    </flux:text>
                                </div>
                            </div>
                            @endif
                        </div>

                        <div class="flex justify-end">
                            <flux:button
                                variant="primary"
                                wire:click="assignTicket"
                                :disabled="!$paymentReceived"
                                class="{{ !$paymentReceived ? 'opacity-50 cursor-not-allowed' : '' }}">
                                <span wire:loading.remove wire:target="assignTicket">
                                    @if(!$paymentReceived)
                                    Confirm Payment First
                                    @else
                                    Complete Purchase & Assign Tickets
                                    @endif
                                </span>
                            </flux:button>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Student's Current Tickets Sidebar -->
    @if($selectedStudentId && count($studentTickets) > 0)
    <div class="xl:col-span-1">
        <div class="bg-white dark:bg-zinc-900 overflow-hidden shadow-sm sm:rounded-lg sticky top-8">
            <div class="p-6">
                <flux:heading size="lg" class="mb-4">{{ $selectedStudent->name }}'s Tickets</flux:heading>

                <div class="space-y-3">
                    @foreach ($studentTickets->take(5) as $purchase)
                    <div class="border border-gray-200 dark:border-zinc-700 rounded-lg p-3">
                        <flux:text class="font-semibold text-sm">{{ $purchase->ticket->concert->title }}</flux:text>
                        <flux:text class="text-xs text-gray-600 dark:text-zinc-400 block">{{ $purchase->ticket->ticket_type }}</flux:text>
                        <flux:text class="text-xs text-gray-500 dark:text-zinc-500 block">{{ $purchase->purchase_date->format('M d, Y') }}</flux:text>
                        <div class="mt-2">
                            @if($purchase->status === 'valid')
                            <flux:badge color="lime">Valid</flux:badge>
                            @elseif($purchase->status === 'used')
                            <flux:badge>Used</flux:badge>
                            @else
                            <flux:badge>Cancelled</flux:badge>
                            @endif
                        </div>
                    </div>
                    @endforeach

                    @if($studentTickets->count() > 5)
                    <flux:text class="text-xs text-gray-500 text-center">
                        And {{ $studentTickets->count() - 5 }} more tickets...
                    </flux:text>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endif
</div> 