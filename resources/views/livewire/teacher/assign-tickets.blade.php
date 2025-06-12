<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <flux:heading size="xl" class="mb-6">Assign Tickets to Students</flux:heading>
                
                <!-- Step 1: Search for and select a student -->
                <div class="mb-8">
                    <flux:heading size="lg" class="mb-3">Step 1: Select a Student</flux:heading>
                    
                    <div class="mb-4">
                        <flux:input wire:model.live="search" placeholder="Search students by name or email..." />
                    </div>
                    
                    @if($selectedStudentId)
                        <div class="mb-4 p-4 bg-blue-50 dark:bg-blue-900 rounded-lg">
                            <div class="flex items-center justify-between">
                                <div>
                                    <flux:heading size="md">Selected Student: {{ $selectedStudent->name }}</flux:heading>
                                    <flux:text>{{ $selectedStudent->email }}</flux:text>
                                </div>
                                <flux:button size="sm" variant="filled" wire:click="resetForm">
                                    Change Student
                                </flux:button>
                            </div>
                        </div>
                    @elseif(strlen($search) >= 2)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Name</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Email</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @forelse ($students as $student)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">{{ $student->name }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">{{ $student->email }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <flux:button size="sm" variant="primary" wire:click="selectStudent({{ $student->id }})">
                                                    Select
                                                </flux:button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                                No students found matching your search.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="mt-4">
                            {{ $students->links() }}
                        </div>
                    @else
                        <div class="p-4 text-center text-gray-500 dark:text-gray-400">
                            Start typing on searchbar to see search results.
                        </div>
                    @endif
                </div>
                
                <!-- Step 2: Select a ticket (only shown if student is selected) -->
                @if($selectedStudentId)
                    <div class="mb-8">
                        <flux:heading size="lg" class="mb-3">Step 2: Select a Ticket</flux:heading>
                        
                        <div class="mb-4">
                            <flux:select wire:model.live="concertFilter" label="Filter by Concert">
                                <option value="">Select a Concert</option>
                                @foreach ($concerts as $concert)
                                    <option value="{{ $concert->id }}">{{ $concert->title }} ({{ $concert->date->format('M d, Y') }})</option>
                                @endforeach
                            </flux:select>
                        </div>
                        
                        @if($selectedTicketId)
                            <div class="mb-4 p-4 bg-green-50 dark:bg-green-900 rounded-lg">
                                @php
                                    $selectedTicket = $tickets->firstWhere('id', $selectedTicketId);
                                @endphp
                                <div class="flex items-center justify-between">
                                    <div>
                                        <flux:heading size="md">Selected Ticket: {{ $selectedTicket->ticket_type }}</flux:heading>
                                        <flux:text>{{ $selectedTicket->concert->title }} - ${{ number_format($selectedTicket->price, 2) }}</flux:text>
                                    </div>
                                    <flux:button size="sm" variant="filled" wire:click="$set('selectedTicketId', null)">
                                        Change Ticket
                                    </flux:button>
                                </div>
                            </div>
                        @elseif($concertFilter)
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                @forelse ($tickets as $ticket)
                                    <div class="border dark:border-gray-700 rounded-lg p-4 hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                                        <div class="font-bold text-lg">{{ $ticket->ticket_type }}</div>
                                        <div class="text-sm text-gray-600 dark:text-gray-400">{{ $ticket->concert->title }}</div>
                                        <div class="text-sm text-gray-600 dark:text-gray-400">Date: {{ $ticket->concert->date->format('M d, Y') }}</div>
                                        <div class="text-sm text-gray-600 dark:text-gray-400">Time: {{ $ticket->concert->start_time->format('g:i A') }}</div>
                                        <div class="flex justify-between items-center mt-2">
                                            <div class="font-bold">RM{{ number_format($ticket->price, 2) }}</div>
                                            <div class="text-sm text-gray-500 dark:text-gray-400">{{ $ticket->remaining_tickets }} available</div>
                                        </div>
                                        <flux:button class="w-full mt-2" wire:click="selectTicket({{ $ticket->id }})">
                                            Select Ticket
                                        </flux:button>
                                    </div>
                                @empty
                                    <div class="col-span-3 p-4 text-center text-gray-500 dark:text-gray-400">
                                        No tickets available for the selected concert.
                                    </div>
                                @endforelse
                            </div>
                        @else
                            <div class="p-4 text-center text-gray-500 dark:text-gray-400">
                                Please select a concert to view available tickets.
                            </div>
                        @endif
                    </div>
                @endif
                
                <!-- Step 3: Confirm and assign ticket -->
                @if($selectedStudentId && $selectedTicketId)
                    <div class="mb-8">
                        <flux:heading size="lg" class="mb-3">Step 3: Confirm and Assign Ticket</flux:heading>
                        
                        <div class="flex justify-end">
                            <flux:button variant="primary" wire:click="assignTicket" wire:loading.attr="disabled">
                                <span wire:loading.remove wire:target="assignTicket">Assign Ticket to Student</span>
                                <span wire:loading wire:target="assignTicket">Processing...</span>
                            </flux:button>
                        </div>
                    </div>
                @endif
                
                <!-- Success message and QR code -->
                @if($ticketAssigned)
                    <div class="mb-8">
                        <flux:heading size="lg" class="mb-3 text-green-800 dark:text-green-200">Ticket Successfully Assigned!</flux:heading>
                        
                        <!-- Ticket design -->
                        <div class="max-w-2xl mx-auto">
                            @php
                                // Generate a dynamic color based on the concert ID or use predefined colors
                                $colors = ['emerald', 'orange', 'sky', 'purple', 'amber', 'pink'];
                                
                                // Get the last assigned ticket from the student's tickets
                                $lastPurchase = $studentTickets->first();
                                $ticketColor = 'emerald'; // Default color
                                
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
                            
                            <div class="relative rounded-lg overflow-hidden shadow-xl border border-gray-200 flex flex-col md:flex-row">
                                <!-- Main ticket section -->
                                <div class="bg-white p-6 flex-grow">
                                    <div class="flex flex-col md:flex-row justify-between">
                                        <div>
                                            <div class="uppercase text-sm font-bold text-gray-500">CONCERT EVENT TICKET</div>
                                            <div class="text-xs text-red-500">Please present this ticket at entry</div>
                                            <div class="mt-6 mb-6">
                                                @if($lastPurchase && $lastPurchase->ticket && $lastPurchase->ticket->concert)
                                                    <h3 class="text-xl font-bold">{{ $lastPurchase->ticket->concert->title }}</h3>
                                                    <div class="text-sm text-gray-600">
                                                        <div>Date: {{ $lastPurchase->ticket->concert->date->format('M d, Y') }}</div>
                                                        <div>Time: {{ $lastPurchase->ticket->concert->start_time->format('g:i A') }}</div>
                                                        <div>Location: {{ $lastPurchase->ticket->concert->venue }}</div>
                                                    </div>
                                                @else
                                                    <h3 class="text-xl font-bold">Concert Ticket</h3>
                                                    <div class="text-sm text-gray-600">
                                                        <div>Ticket successfully assigned</div>
                                                    </div>
                                                @endif
                                            </div>
                                            
                                            <div class="flex flex-wrap gap-2 my-4">
                                                <div class="bg-gray-200 text-gray-800 text-xs font-bold px-3 py-1 rounded">
                                                    {{ $selectedStudent->id }}
                                                </div>
                                                @if($lastPurchase && $lastPurchase->ticket)
                                                    <div class="{{ $bgColor }} text-white text-xs font-bold px-3 py-1 rounded">
                                                        {{ $lastPurchase->ticket->ticket_type }}
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                        
                                        <div class="mt-4 md:mt-0">
                                            @if($lastQrCodeImage)
                                                <div class="bg-white p-1 border border-gray-200 rounded-md">
                                                    <img src="data:image/svg+xml;base64,{{ $lastQrCodeImage }}" alt="Ticket QR Code" class="w-32 h-32">
                                                </div>
                                            @else
                                                <div class="border-2 border-gray-300 p-4 text-center bg-white w-32 h-32">
                                                    <div class="text-xs overflow-hidden text-ellipsis">
                                                        {{ $lastAssignedQrCode }}
                                                    </div>
                                                    <div class="text-xs text-gray-500 mt-2">
                                                        (This would be a QR code)
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Colored ticket stub -->
                                <div class="relative {{ $bgColor }} text-white p-4 md:w-1/5">
                                    <div class="absolute top-0 left-0 bottom-0 md:left-[-12px] md:w-6 flex items-center justify-center">
                                        <div class="h-full flex flex-col justify-between py-4 overflow-hidden">
                                            @for ($i = 0; $i < 15; $i++)
                                                <div class="w-3 h-2 bg-white rounded-full"></div>
                                            @endfor
                                        </div>
                                    </div>
                                    
                                    <div class="md:rotate-90 md:absolute md:top-1/2 md:left-1/2 md:transform md:-translate-y-1/2 md:-translate-x-1/2 md:whitespace-nowrap text-center">
                                        <div class="uppercase font-bold">CONCERT TICKET</div>
                                        <div class="text-xs">ID: {{ substr($lastAssignedQrCode ?? '', -8) }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="flex justify-end mt-6">
                            <flux:button variant="filled" wire:click="resetForm">
                                Assign Another Ticket
                            </flux:button>
                        </div>
                    </div>
                @endif
                
                <!-- Student's current tickets (shown if student is selected) -->
                @if($selectedStudentId && count($studentTickets) > 0)
                    <div class="mt-8">
                        <flux:heading size="lg" class="mb-3">{{ $selectedStudent->name }}'s Current Tickets</flux:heading>
                        
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Concert</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Ticket Type</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Purchase Date</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach ($studentTickets as $purchase)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">{{ $purchase->ticket->concert->title }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">{{ $purchase->ticket->ticket_type }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">{{ $purchase->purchase_date->format('M d, Y g:i A') }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @if($purchase->status === 'valid')
                                                    <flux:badge variant="success">Valid</flux:badge>
                                                @elseif($purchase->status === 'used')
                                                    <flux:badge variant="filled">Used</flux:badge>
                                                @else
                                                    <flux:badge variant="danger">Cancelled</flux:badge>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
