<!-- Bulk Class Purchase Mode -->
@if(!$ticketAssigned)
<!-- Progress Steps -->
<div class="mb-8">
    <div class="flex items-center justify-center">
        <ol class="flex items-center w-full max-w-2xl">
            <!-- Step 1: Select Class -->
            <li class="flex w-full items-center {{ $selectedClassId ? 'text-green-600 dark:text-green-400' : 'text-rose-600 dark:text-rose-400' }}">
                <span class="flex items-center justify-center w-10 h-10 {{ $selectedClassId ? 'bg-green-100 border-green-600 dark:bg-green-800 dark:border-green-400' : 'bg-rose-100 border-rose-600 dark:bg-rose-800 dark:border-rose-400' }} border-2 rounded-full lg:h-12 lg:w-12 shrink-0">
                    @if($selectedClassId)
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                    </svg>
                    @else
                    <span class="text-sm font-bold">1</span>
                    @endif
                </span>
                <span class="text-sm font-medium ml-2 lg:ml-4">Select Class</span>
                <div class="hidden sm:flex w-full bg-gray-200 h-0.5 dark:bg-zinc-700 ml-4"></div>
            </li>

            <!-- Step 2: Select Students -->
            <li class="flex w-full items-center {{ count($selectedStudentIds) > 0 ? 'text-green-600 dark:text-green-400' : ($selectedClassId ? 'text-rose-600 dark:text-rose-400' : 'text-gray-500 dark:text-zinc-400') }}">
                <span class="flex items-center justify-center w-10 h-10 {{ count($selectedStudentIds) > 0 ? 'bg-green-100 border-green-600 dark:bg-green-800 dark:border-green-400' : ($selectedClassId ? 'bg-rose-100 border-rose-600 dark:bg-rose-800 dark:border-rose-400' : 'bg-gray-100 border-gray-300 dark:bg-zinc-700 dark:border-zinc-600') }} border-2 rounded-full lg:h-12 lg:w-12 shrink-0">
                    @if(count($selectedStudentIds) > 0)
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                    </svg>
                    @else
                    <span class="text-sm font-bold">2</span>
                    @endif
                </span>
                <span class="text-sm font-medium ml-2 lg:ml-4">Select Students</span>
                <div class="hidden sm:flex w-full bg-gray-200 h-0.5 dark:bg-zinc-700 ml-4"></div>
            </li>

            <!-- Step 3: Select Ticket -->
            <li class="flex w-full items-center {{ $selectedBulkTicketId ? 'text-green-600 dark:text-green-400' : (count($selectedStudentIds) > 0 ? 'text-rose-600 dark:text-rose-400' : 'text-gray-500 dark:text-zinc-400') }}">
                <span class="flex items-center justify-center w-10 h-10 {{ $selectedBulkTicketId ? 'bg-green-100 border-green-600 dark:bg-green-800 dark:border-green-400' : (count($selectedStudentIds) > 0 ? 'bg-rose-100 border-rose-600 dark:bg-rose-800 dark:border-rose-400' : 'bg-gray-100 border-gray-300 dark:bg-zinc-700 dark:border-zinc-600') }} border-2 rounded-full lg:h-12 lg:w-12 shrink-0">
                    @if($selectedBulkTicketId)
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                    </svg>
                    @else
                    <span class="text-sm font-bold">3</span>
                    @endif
                </span>
                <span class="text-sm font-medium ml-2 lg:ml-4">Select Ticket</span>
                <div class="hidden sm:flex w-full bg-gray-200 h-0.5 dark:bg-zinc-700 ml-4"></div>
            </li>

            <!-- Step 4: Payment & Assign -->
            <li class="flex items-center {{ count($selectedStudentIds) > 0 && $selectedBulkTicketId ? 'text-rose-600 dark:text-rose-400' : 'text-gray-500 dark:text-zinc-400' }}">
                <span class="flex items-center justify-center w-10 h-10 {{ count($selectedStudentIds) > 0 && $selectedBulkTicketId ? 'bg-rose-100 border-rose-600 dark:bg-rose-800 dark:border-rose-400' : 'bg-gray-100 border-gray-300 dark:bg-zinc-700 dark:border-zinc-600' }} border-2 rounded-full lg:h-12 lg:w-12 shrink-0">
                    <span class="text-sm font-bold">4</span>
                </span>
                <span class="text-sm font-medium ml-2 lg:ml-4">Complete</span>
            </li>
        </ol>
    </div>
</div>
@endif

<!-- Main Content -->
<div class="bg-white dark:bg-zinc-900 overflow-hidden shadow-sm sm:rounded-lg relative">


    <div class="p-6">
        <!-- Bulk Errors -->
        @error('bulk')
        <div class="mb-4 p-3 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg">
            <flux:text class="text-red-800 dark:text-red-200">{{ $message }}</flux:text>
        </div>
        @enderror

        @if(!$ticketAssigned)
        <!-- Step 1: Select Class -->
        <div class="mb-8">
            <div class="flex items-center mb-4">
                <div class="flex items-center justify-center w-8 h-8 {{ $selectedClassId ? 'bg-green-100 text-green-600 dark:bg-green-800 dark:text-green-400' : 'bg-rose-100 text-rose-600 dark:bg-rose-800 dark:text-rose-400' }} rounded-full mr-3">
                    <span class="text-sm font-bold">1</span>
                </div>
                <flux:heading size="lg">Select Your Class</flux:heading>
            </div>

            @if($selectedClassId)
            <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg p-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-green-100 dark:bg-green-800 rounded-full flex items-center justify-center">
                            <flux:icon.users class="w-5 h-5 text-green-600 dark:text-green-400" />
                        </div>
                        <div>
                            <flux:text class="font-semibold text-green-800 dark:text-green-200">{{ $selectedClass->display_name }}</flux:text>
                            <flux:text class="text-sm text-green-600 dark:text-green-400">{{ $this->bulkStudentsCount }} students</flux:text>
                        </div>
                    </div>
                    <flux:button size="sm" variant="ghost" wire:click="resetForm">
                        Change
                    </flux:button>
                </div>
            </div>
            @else
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                @forelse ($teacherClasses as $class)
                <div class="border border-gray-200 dark:border-zinc-700 rounded-lg p-4 hover:border-rose-300 dark:hover:border-rose-600 transition cursor-pointer" wire:click="selectClass({{ $class->id }})">
                    <div class="flex items-center justify-between mb-2">
                        <flux:text class="font-semibold">{{ $class->display_name }}</flux:text>
                        <flux:badge color="lime">{{ $class->students_count }} students</flux:badge>
                    </div>
                    <flux:text class="text-sm text-gray-600 dark:text-zinc-400 mb-3">{{ $class->description ?? 'No description' }}</flux:text>
                    <flux:button size="sm" variant="primary" wire:click="selectClass({{ $class->id }})">
                        Select Class
                    </flux:button>
                </div>
                @empty
                <div class="col-span-3 p-8 text-center text-gray-500 dark:text-zinc-400">
                    <flux:icon.exclamation-triangle class="w-12 h-12 mx-auto mb-3 text-gray-300" />
                    <flux:text>No classes assigned to you. Please contact an administrator.</flux:text>
                </div>
                @endforelse
            </div>
            @endif
        </div>

        <!-- Step 2: Select Students -->
        @if($selectedClassId)
        <div class="mb-8">
            <div class="flex items-center mb-4">
                <div class="flex items-center justify-center w-8 h-8 {{ count($selectedStudentIds) > 0 ? 'bg-green-100 text-green-600 dark:bg-green-800 dark:text-green-400' : 'bg-rose-100 text-rose-600 dark:bg-rose-800 dark:text-rose-400' }} rounded-full mr-3">
                    <span class="text-sm font-bold">2</span>
                </div>
                <flux:heading size="lg">Select Students</flux:heading>
            </div>

            <div class="bg-white dark:bg-zinc-800 border border-gray-200 dark:border-zinc-700 rounded-lg p-6">
                <div class="mb-4">
                    <flux:text class="font-semibold">Students in {{ $selectedClass->display_name }}</flux:text>
                </div>

                <div class="mb-4 p-3 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg">
                    <flux:text class="text-sm text-blue-800 dark:text-blue-200">
                        <strong>{{ count($selectedStudentIds) }} of {{ count($classStudents) }} students selected</strong> - Choose which students to assign tickets to.
                    </flux:text>
                </div>

                @if(count($classStudents) > 0)
                <div class="space-y-3 max-h-96 overflow-y-auto p-4">
                    @foreach($classStudents as $student)
                    <div class="flex items-center space-x-3 p-3 bg-gray-50 dark:bg-zinc-700 rounded-lg">
                        <flux:checkbox 
                            wire:click="toggleStudentSelection({{ $student->id }})" 
                            :checked="in_array($student->id, $selectedStudentIds)"
                            class="text-blue-600" />
                        <div class="flex-1 min-w-0">
                            <flux:text class="font-medium text-sm truncate">{{ $student->name }}</flux:text>
                            <flux:text class="text-xs text-gray-500 dark:text-zinc-400 truncate">{{ $student->email }}</flux:text>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="p-8 text-center text-gray-500 dark:text-zinc-400">
                    <flux:icon.users class="w-12 h-12 mx-auto mb-3 text-gray-300" />
                    <flux:text>No students found in this class.</flux:text>
                </div>
                @endif
            </div>
        </div>
        @endif

        <!-- Step 3: Select Ticket -->
        @if($selectedClassId && count($selectedStudentIds) > 0)
        <div class="mb-8">
            <div class="flex items-center mb-4">
                <div class="flex items-center justify-center w-8 h-8 {{ $selectedBulkTicketId ? 'bg-green-100 text-green-600 dark:bg-green-800 dark:text-green-400' : 'bg-rose-100 text-rose-600 dark:bg-rose-800 dark:text-rose-400' }} rounded-full mr-3">
                    <span class="text-sm font-bold">3</span>
                </div>
                <flux:heading size="lg">Select Concert Ticket</flux:heading>
            </div>

            @if($selectedBulkTicketId)
            <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg p-4 mb-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-green-100 dark:bg-green-800 rounded-full flex items-center justify-center">
                            <flux:icon.ticket class="w-5 h-5 text-green-600 dark:text-green-400" />
                        </div>
                        <div>
                            <flux:text class="font-semibold text-green-800 dark:text-green-200">{{ $selectedBulkTicket->ticket_type }}</flux:text>
                            <flux:text class="text-sm text-green-600 dark:text-green-400">{{ $selectedBulkTicket->concert->title }} - RM{{ number_format($selectedBulkTicket->price, 2) }}</flux:text>
                        </div>
                    </div>
                    <flux:button size="sm" variant="ghost" wire:click="selectBulkTicket(null)">
                        Change
                    </flux:button>
                </div>
            </div>
            @else
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
                    <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4 hover:border-rose-300 dark:hover:border-rose-600 transition cursor-pointer" wire:click="selectBulkTicket({{ $ticket->id }})">
                        <div class="flex justify-between items-start mb-2">
                            <flux:text class="font-semibold">{{ $ticket->ticket_type }}</flux:text>
                            <flux:badge color="lime">{{ $ticket->remaining_tickets }} left</flux:badge>
                        </div>
                        <flux:text class="text-sm text-gray-600 dark:text-zinc-400 mb-1">{{ $ticket->concert->title }}</flux:text>
                        <flux:text class="text-sm text-gray-600 dark:text-zinc-400 mb-2">{{ $ticket->concert->date->format('M d, Y') }} at {{ $ticket->concert->start_time->format('g:i A') }}</flux:text>
                        <div class="flex justify-between items-end">
                            <div>
                                <flux:text class="font-bold text-lg">RM{{ number_format($ticket->price, 2) }}</flux:text>
                                <flux:text class="text-sm text-gray-500">per ticket</flux:text>
                            </div>
                            <flux:button size="sm" variant="primary" wire:click="selectBulkTicket({{ $ticket->id }})">
                                Select
                            </flux:button>
                        </div>
                    </div>
                    @empty
                    <div class="col-span-2 p-8 text-center text-gray-500 dark:text-zinc-400">
                        <flux:icon.exclamation-triangle class="w-12 h-12 mx-auto mb-3 text-gray-300" />
                        <flux:text>No tickets available for this concert.</flux:text>
                    </div>
                    @endforelse
                </div>
                @else
                <div class="p-8 text-center text-gray-500 dark:text-zinc-400">
                    <flux:icon.musical-note class="w-12 h-12 mx-auto mb-3 text-gray-300" />
                    <flux:text>Please select a concert to view available tickets.</flux:text>
                </div>
                @endif
            </div>
            @endif
        </div>
        @endif

        <!-- Step 4: Payment & Confirmation -->
        @if($selectedClassId && count($selectedStudentIds) > 0 && $selectedBulkTicketId)
        <div class="mb-8">
            <div class="flex items-center mb-4">
                <div class="flex items-center justify-center w-8 h-8 bg-rose-100 text-rose-600 dark:bg-rose-800 dark:text-rose-400 rounded-full mr-3">
                    <span class="text-sm font-bold">4</span>
                </div>
                <flux:heading size="lg">Bulk Payment & Confirmation</flux:heading>
            </div>

            <div class="bg-rose-50 dark:bg-rose-900/20 border border-rose-200 dark:border-rose-800 rounded-lg p-6">
                <flux:heading size="md" class="mb-4">Bulk Purchase Summary</flux:heading>
                <div class="space-y-3 mb-6">
                    <div class="flex justify-between">
                        <flux:text>Class:</flux:text>
                        <flux:text class="font-semibold">{{ $selectedClass->display_name }}</flux:text>
                    </div>
                    <div class="flex justify-between">
                        <flux:text>Selected Students:</flux:text>
                        <flux:text class="font-semibold">{{ $this->bulkStudentsCount }} of {{ count($classStudents) }}</flux:text>
                    </div>
                    <div class="flex justify-between">
                        <flux:text>Ticket Type:</flux:text>
                        <flux:text class="font-semibold">{{ $selectedBulkTicket->ticket_type }}</flux:text>
                    </div>
                    <div class="flex justify-between">
                        <flux:text>Concert:</flux:text>
                        <flux:text class="font-semibold">{{ $selectedBulkTicket->concert->title }}</flux:text>
                    </div>
                    <div class="flex justify-between">
                        <flux:text>Price per ticket:</flux:text>
                        <flux:text class="font-semibold">RM{{ number_format($selectedBulkTicket->price, 2) }}</flux:text>
                    </div>

                    <flux:separator />

                    <!-- Payment Amount - Highlighted -->
                    <div class="bg-white dark:bg-zinc-900 rounded-lg p-4 border-2 border-green-300 dark:border-green-600">
                        <div class="flex justify-between items-center">
                            <div>
                                <flux:text class="text-lg font-bold text-green-800 dark:text-green-200">Total Amount to Collect:</flux:text>
                                <flux:text class="text-sm text-green-600 dark:text-green-400">{{ $this->bulkStudentsCount }} tickets × RM{{ number_format($selectedBulkTicket->price, 2) }}</flux:text>
                            </div>
                            <div class="text-right">
                                <div class="text-3xl font-bold text-green-800 dark:text-green-200">RM{{ number_format($this->bulkTotal, 2) }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- Availability Check -->
                    @if($selectedBulkTicket->remaining_tickets < $this->bulkStudentsCount)
                    <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-4">
                        <div class="flex items-center space-x-2">
                            <flux:icon.exclamation-triangle class="w-5 h-5 text-red-600 dark:text-red-400" />
                            <flux:text class="text-sm text-red-800 dark:text-red-200 font-medium">
                                Insufficient tickets available! Only {{ $selectedBulkTicket->remaining_tickets }} tickets remaining, but {{ $this->bulkStudentsCount }} students selected.
                            </flux:text>
                        </div>
                    </div>
                    @endif
                </div>

                <!-- Payment Confirmation -->
                <div class="mb-6">
                    <flux:field variant="inline">
                        <flux:checkbox wire:model.live="bulkPaymentReceived" />
                        <flux:label class="ml-2">
                            I have received RM{{ number_format($this->bulkTotal, 2) }} cash payment for {{ $this->bulkStudentsCount }} tickets for {{ $this->bulkStudentsCount }} selected students from {{ $selectedClass->display_name }}
                        </flux:label>
                    </flux:field>

                    @if($errors->has('bulkPaymentReceived'))
                    <flux:error>{{ $errors->first('bulkPaymentReceived') }}</flux:error>
                    @endif

                    @if(!$bulkPaymentReceived)
                    <div class="mt-3 p-3 bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 rounded-lg">
                        <div class="flex items-center space-x-2">
                            <flux:icon.exclamation-triangle class="w-5 h-5 text-amber-600 dark:text-amber-400" />
                            <flux:text class="text-sm text-amber-800 dark:text-amber-200 font-medium">
                                Please confirm that you have received the cash payment before proceeding.
                            </flux:text>
                        </div>
                    </div>
                    @endif
                </div>

                @if($bulkPaymentReceived && $selectedBulkTicket->remaining_tickets < $this->bulkStudentsCount)
                    <div class="mb-4 p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg">
                        <div class="flex items-center space-x-2">
                            <svg class="w-5 h-5 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                            </svg>
                            <flux:text class="text-sm font-medium text-red-800 dark:text-red-200">
                                Insufficient Tickets Available
                            </flux:text>
                        </div>
                        <flux:text class="text-sm text-red-600 dark:text-red-400 mt-1">
                            Only {{ $selectedBulkTicket->remaining_tickets }} tickets remaining, but {{ $this->bulkStudentsCount }} students selected.
                        </flux:text>
                    </div>
                @endif

                <div class="flex justify-end">
                    <flux:button
                        variant="primary"
                        wire:click="assignBulkTickets"
                        :disabled="!$bulkPaymentReceived || $selectedBulkTicket->remaining_tickets < $this->bulkStudentsCount"
                        class="{{ (!$bulkPaymentReceived || $selectedBulkTicket->remaining_tickets < $this->bulkStudentsCount) ? 'opacity-50 cursor-not-allowed' : '' }}">
                        @if(!$bulkPaymentReceived)
                            Confirm Payment First
                        @elseif($selectedBulkTicket->remaining_tickets < $this->bulkStudentsCount)
                            Insufficient Tickets Available
                        @else
                            Assign Tickets to Selected Students
                        @endif
                    </flux:button>
                </div>
            </div>
        </div>
        @endif
        @endif
    </div>
</div> 