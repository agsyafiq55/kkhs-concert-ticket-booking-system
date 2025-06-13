<div class="py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Progress Steps -->
        <div class="mb-8">
            <div class="flex items-center justify-center">
                <ol class="flex items-center w-full max-w-2xl">
                    <!-- Step 1: Select Student -->
                    <li class="flex w-full items-center {{ $selectedStudentId ? 'text-green-600 dark:text-green-400' : 'text-blue-600 dark:text-blue-400' }}">
                        <span class="flex items-center justify-center w-10 h-10 {{ $selectedStudentId ? 'bg-green-100 border-green-600 dark:bg-green-800 dark:border-green-400' : 'bg-blue-100 border-blue-600 dark:bg-blue-800 dark:border-blue-400' }} border-2 rounded-full lg:h-12 lg:w-12 shrink-0">
                            @if($selectedStudentId)
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            @else
                            <span class="text-sm font-bold">1</span>
                            @endif
                        </span>
                        <span class="text-sm font-medium ml-2 lg:ml-4">Select Student</span>
                        <div class="hidden sm:flex w-full bg-gray-200 h-0.5 dark:bg-gray-700 ml-4"></div>
                    </li>

                    <!-- Step 2: Choose Ticket -->
                    <li class="flex w-full items-center {{ $selectedTicketId ? 'text-green-600 dark:text-green-400' : ($selectedStudentId ? 'text-blue-600 dark:text-blue-400' : 'text-gray-500 dark:text-gray-400') }}">
                        <span class="flex items-center justify-center w-10 h-10 {{ $selectedTicketId ? 'bg-green-100 border-green-600 dark:bg-green-800 dark:border-green-400' : ($selectedStudentId ? 'bg-blue-100 border-blue-600 dark:bg-blue-800 dark:border-blue-400' : 'bg-gray-100 border-gray-300 dark:bg-gray-700 dark:border-gray-600') }} border-2 rounded-full lg:h-12 lg:w-12 shrink-0">
                            @if($selectedTicketId)
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            @else
                            <span class="text-sm font-bold">2</span>
                            @endif
                        </span>
                        <span class="text-sm font-medium ml-2 lg:ml-4">Choose Ticket</span>
                        <div class="hidden sm:flex w-full bg-gray-200 h-0.5 dark:bg-gray-700 ml-4"></div>
                    </li>

                    <!-- Step 3: Confirm Assignment -->
                    <li class="flex items-center {{ $ticketAssigned ? 'text-green-600 dark:text-green-400' : ($selectedStudentId && $selectedTicketId ? 'text-blue-600 dark:text-blue-400' : 'text-gray-500 dark:text-gray-400') }}">
                        <span class="flex items-center justify-center w-10 h-10 {{ $ticketAssigned ? 'bg-green-100 border-green-600 dark:bg-green-800 dark:border-green-400' : ($selectedStudentId && $selectedTicketId ? 'bg-blue-100 border-blue-600 dark:bg-blue-800 dark:border-blue-400' : 'bg-gray-100 border-gray-300 dark:bg-gray-700 dark:border-gray-600') }} border-2 rounded-full lg:h-12 lg:w-12 shrink-0">
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
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <flux:heading size="xl" class="mb-6 text-center">Assign Concert Tickets</flux:heading>

                        <!-- Step 1: Select Student -->
                        <div class="mb-8">
                            <div class="flex items-center mb-4">
                                <div class="flex items-center justify-center w-8 h-8 {{ $selectedStudentId ? 'bg-green-100 text-green-600 dark:bg-green-800 dark:text-green-400' : 'bg-blue-100 text-blue-600 dark:bg-blue-800 dark:text-blue-400' }} rounded-full mr-3">
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
                                        wire:model.live="search"
                                        placeholder="Search students by name or email..."
                                        class="w-full" />
                                </flux:field>

                                @if(strlen($search) >= 2)
                                <div class="border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden">
                                    @forelse ($students as $student)
                                    <div class="flex items-center justify-between p-4 hover:bg-gray-50 dark:hover:bg-gray-700 border-b border-gray-200 dark:border-gray-700 last:border-b-0">
                                        <div class="flex items-center space-x-3">
                                            <div class="w-8 h-8 bg-gray-100 dark:bg-gray-600 rounded-full flex items-center justify-center">
                                                <svg class="w-4 h-4 text-gray-600 dark:text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                                                </svg>
                                            </div>
                                            <div>
                                                <div class="font-medium">{{ $student->name }}</div>
                                                <div class="text-sm text-gray-500 dark:text-gray-400">{{ $student->email }}</div>
                                            </div>
                                        </div>
                                        <flux:button size="sm" variant="primary" wire:click="selectStudent({{ $student->id }})">
                                            Select
                                        </flux:button>
                                    </div>
                                    @empty
                                    <div class="p-8 text-center text-gray-500 dark:text-gray-400">
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
                                <div class="p-8 text-center text-gray-500 dark:text-gray-400">
                                    <svg class="w-12 h-12 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                    </svg>
                                    Start typing to search for students
                                </div>
                                @endif
                            </div>
                            @endif
                        </div>

                        <!-- Step 2: Select Ticket -->
                        @if($selectedStudentId)
                        <div class="mb-8">
                            <div class="flex items-center mb-4">
                                <div class="flex items-center justify-center w-8 h-8 {{ $selectedTicketId ? 'bg-green-100 text-green-600 dark:bg-green-800 dark:text-green-400' : 'bg-blue-100 text-blue-600 dark:bg-blue-800 dark:text-blue-400' }} rounded-full mr-3">
                                    <span class="text-sm font-bold">2</span>
                                </div>
                                <flux:heading size="lg">Choose Concert & Ticket</flux:heading>
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

                                @if($selectedTicketId)
                                @php $selectedTicket = $tickets->firstWhere('id', $selectedTicketId); @endphp
                                <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg p-4">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center space-x-3">
                                            <div class="w-10 h-10 bg-green-100 dark:bg-green-800 rounded-full flex items-center justify-center">
                                                <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M2 6a2 2 0 012-2h12a2 2 0 012 2v8a2 2 0 01-2 2H4a2 2 0 01-2-2V6zM4 8v6h12V8H4z"></path>
                                                </svg>
                                            </div>
                                            <div>
                                                <flux:text class="font-semibold text-green-800 dark:text-green-200">{{ $selectedTicket->ticket_type }}</flux:text>
                                                <flux:text class="text-sm text-green-600 dark:text-green-400">{{ $selectedTicket->concert->title }} - RM{{ number_format($selectedTicket->price, 2) }}</flux:text>
                                            </div>
                                        </div>
                                        <flux:button size="sm" variant="ghost" wire:click="$set('selectedTicketId', null)">
                                            Change
                                        </flux:button>
                                    </div>
                                </div>
                                @elseif($concertFilter)
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    @forelse ($tickets as $ticket)
                                    <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4 hover:border-blue-300 dark:hover:border-blue-600 transition cursor-pointer" wire:click="selectTicket({{ $ticket->id }})">
                                        <div class="flex justify-between items-start mb-2">
                                            <flux:text class="font-semibold">{{ $ticket->ticket_type }}</flux:text>
                                            <flux:badge color="lime">{{ $ticket->remaining_tickets }} left</flux:badge>
                                        </div>
                                        <flux:text class="text-sm text-gray-600 dark:text-gray-400 mb-1">{{ $ticket->concert->title }}</flux:text>
                                        <flux:text class="text-sm text-gray-600 dark:text-gray-400 mb-2">{{ $ticket->concert->date->format('M d, Y') }} at {{ $ticket->concert->start_time->format('g:i A') }}</flux:text>
                                        <div class="flex justify-between items-center">
                                            <flux:text class="font-bold text-lg">RM{{ number_format($ticket->price, 2) }}</flux:text>
                                            <flux:button size="sm" variant="primary">
                                                Select
                                            </flux:button>
                                        </div>
                                    </div>
                                    @empty
                                    <div class="col-span-2 p-8 text-center text-gray-500 dark:text-gray-400">
                                        <svg class="w-12 h-12 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a1 1 0 001 1h1a1 1 0 001-1V7a2 2 0 00-2-2H5zM5 14a2 2 0 00-2 2v3a1 1 0 001 1h1a1 1 0 001-1v-3a2 2 0 00-2-2H5z"></path>
                                        </svg>
                                        No tickets available for this concert.
                                    </div>
                                    @endforelse
                                </div>
                                @else
                                <div class="p-8 text-center text-gray-500 dark:text-gray-400">
                                    <svg class="w-12 h-12 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a4 4 0 118 0v4m-4 8a4 4 0 11-8 0v4a4 4 0 118 0z"></path>
                                    </svg>
                                    Please select a concert to view available tickets.
                                </div>
                                @endif
                            </div>
                        </div>
                        @endif

                        <!-- Step 3: Payment & Confirmation -->
                        @if($selectedStudentId && $selectedTicketId)
                        <div class="mb-8">
                            <div class="flex items-center mb-4">
                                <div class="flex items-center justify-center w-8 h-8 bg-blue-100 text-blue-600 dark:bg-blue-800 dark:text-blue-400 rounded-full mr-3">
                                    <span class="text-sm font-bold">3</span>
                                </div>
                                <flux:heading size="lg">Payment & Confirmation</flux:heading>
                            </div>

                            @php $selectedTicket = $tickets->firstWhere('id', $selectedTicketId); @endphp
                            <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-6">
                                <flux:heading size="md" class="mb-4">Ticket Purchase Summary</flux:heading>
                                <div class="space-y-3 mb-6">
                                    <div class="flex justify-between">
                                        <flux:text>Student:</flux:text>
                                        <flux:text class="font-semibold">{{ $selectedStudent->name }}</flux:text>
                                    </div>
                                    <div class="flex justify-between">
                                        <flux:text>Concert:</flux:text>
                                        <flux:text class="font-semibold">{{ $selectedTicket->concert->title }}</flux:text>
                                    </div>
                                    <div class="flex justify-between">
                                        <flux:text>Ticket Type:</flux:text>
                                        <flux:text class="font-semibold">{{ $selectedTicket->ticket_type }}</flux:text>
                                    </div>
                                    <div class="flex justify-between">
                                        <flux:text>Date & Time:</flux:text>
                                        <flux:text class="font-semibold">{{ $selectedTicket->concert->date->format('M d, Y') }} at {{ $selectedTicket->concert->start_time->format('g:i A') }}</flux:text>
                                    </div>

                                    <flux:separator />

                                    <!-- Payment Amount - Highlighted -->
                                    <div class="bg-white dark:bg-gray-800 rounded-lg p-4 border-2 border-green-300 dark:border-green-600">
                                        <div class="flex justify-between items-center">
                                            <div>
                                                <flux:text class="text-lg font-bold text-green-800 dark:text-green-200">Amount to Collect:</flux:text>
                                                <flux:text class="text-sm text-green-600 dark:text-green-400">Cash payment from student</flux:text>
                                            </div>
                                            <div class="text-right">
                                                <div class="text-3xl font-bold text-green-800 dark:text-green-200">RM{{ number_format($selectedTicket->price, 2) }}</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Payment Confirmation -->
                                <div class="mb-6">
                                    <flux:field variant="inline">
                                        <flux:checkbox wire:model.live="paymentReceived" />
                                        <flux:label class="ml-2">
                                            I have received <strong>RM{{ number_format($selectedTicket->price, 2) }}</strong> cash payment from {{ $selectedStudent->name }}
                                        </flux:label>
                                    </flux:field>
                                    @error('paymentReceived') <flux:error>{{ $message }}</flux:error> @enderror
                                </div>

                                <div class="flex justify-end">
                                    <flux:button
                                        variant="primary"
                                        wire:click="assignTicket"
                                        wire:loading.attr="disabled"
                                        :disabled="!$paymentReceived">
                                        <span wire:loading.remove wire:target="assignTicket">Complete Purchase & Assign Ticket</span>
                                        <span wire:loading wire:target="assignTicket">Processing...</span>
                                    </flux:button>
                                </div>
                            </div>
                        </div>
                        @endif

                        <!-- Success State -->
                        @if($ticketAssigned)
                        <div class="mb-8">
                            <div class="text-center mb-6">
                                <div class="w-16 h-16 bg-green-100 dark:bg-green-800 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <svg class="w-8 h-8 text-green-600 dark:text-green-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                                <flux:heading size="lg" class="text-green-800 dark:text-green-200 mb-2">Ticket Successfully Assigned!</flux:heading>
                                <flux:text class="text-green-600 dark:text-green-400">The ticket has been assigned to {{ $selectedStudent->name }}</flux:text>
                            </div>

                            <!-- Simple Ticket Display -->
                            @php
                            $lastPurchase = $studentTickets->first();
                            $colors = ['emerald', 'orange', 'sky', 'purple', 'amber', 'pink'];
                            $ticketColor = 'emerald';
                            if ($lastPurchase && $lastPurchase->ticket && $lastPurchase->ticket->concert) {
                            $colorIndex = $lastPurchase->ticket->concert->id % count($colors);
                            $ticketColor = $colors[$colorIndex];
                            }
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

                            <div class="max-w-md mx-auto bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-hidden">
                                <div class="{{ $bgColor }} h-2"></div>
                                <div class="p-6">
                                    <div class="text-center mb-4">
                                        <flux:text class="text-xs uppercase tracking-wide text-gray-500">Concert Ticket</flux:text>
                                        @if($lastPurchase && $lastPurchase->ticket && $lastPurchase->ticket->concert)
                                        <flux:heading size="md" class="mt-1">{{ $lastPurchase->ticket->concert->title }}</flux:heading>
                                        <flux:text class="text-sm text-gray-600 dark:text-gray-400">{{ $lastPurchase->ticket->concert->date->format('M d, Y g:i A') }}</flux:text>
                                        @endif
                                    </div>

                                    <div class="flex justify-center mb-4">
                                        @if($lastQrCodeImage)
                                        <img src="data:image/svg+xml;base64,{{ $lastQrCodeImage }}" alt="QR Code" class="w-24 h-24">
                                        @else
                                        <div class="w-24 h-24 bg-gray-100 dark:bg-gray-700 rounded-lg flex items-center justify-center">
                                            <flux:text class="text-xs text-center">QR Code</flux:text>
                                        </div>
                                        @endif
                                    </div>

                                    <div class="text-center">
                                        <flux:text class="text-sm">Student: {{ $selectedStudent->name }}</flux:text>
                                        @if($lastPurchase && $lastPurchase->ticket)
                                        <div class="mt-2">
                                            <flux:badge class="{{ $bgColor }} text-white">{{ $lastPurchase->ticket->ticket_type }}</flux:badge>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="text-center mt-6">
                                <flux:button variant="filled" wire:click="resetForm">
                                    Assign Another Ticket
                                </flux:button>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Student's Current Tickets Sidebar -->
            @if($selectedStudentId && count($studentTickets) > 0)
            <div class="xl:col-span-1">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg sticky top-8">
                    <div class="p-6">
                        <flux:heading size="lg" class="mb-4">{{ $selectedStudent->name }}'s Tickets</flux:heading>

                        <div class="space-y-3">
                            @foreach ($studentTickets->take(5) as $purchase)
                            <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-3">
                                <flux:text class="font-semibold text-sm">{{ $purchase->ticket->concert->title }}</flux:text>
                                <flux:text class="text-xs text-gray-600 dark:text-gray-400 block">{{ $purchase->ticket->ticket_type }}</flux:text>
                                <flux:text class="text-xs text-gray-500 dark:text-gray-500 block">{{ $purchase->purchase_date->format('M d, Y') }}</flux:text>
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
    </div>
</div>