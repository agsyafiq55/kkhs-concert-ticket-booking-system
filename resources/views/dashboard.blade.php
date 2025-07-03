@php
    // Calculate dashboard statistics
    $totalUsers = \App\Models\User::count();
    $activeConcerts = \App\Models\Concert::where('date', '>=', now())->count();
    
    // Total revenue: only count sold tickets (regular tickets or walk-in tickets that are sold)
    $totalRevenue = \App\Models\TicketPurchase::join('tickets', 'ticket_purchases.ticket_id', '=', 'tickets.id')
        ->whereIn('ticket_purchases.status', ['valid', 'used'])
        ->where(function($query) {
            $query->where('ticket_purchases.is_walk_in', false) // Regular tickets (not walk-in)
                  ->orWhere('ticket_purchases.is_sold', true);    // Or walk-in tickets that are sold
        })
        ->sum('tickets.price');
    
    // Total tickets sold: only count sold tickets (regular tickets or walk-in tickets that are sold)
    $totalTicketsSold = \App\Models\TicketPurchase::whereIn('status', ['valid', 'used'])
        ->where(function($query) {
            $query->where('is_walk_in', false)  // Regular tickets (not walk-in)
                  ->orWhere('is_sold', true);   // Or walk-in tickets that are sold
        })
        ->count();
    
    // Monthly revenue: only count sold tickets (regular tickets or walk-in tickets that are sold)
    $monthlyRevenue = \App\Models\TicketPurchase::join('tickets', 'ticket_purchases.ticket_id', '=', 'tickets.id')
        ->whereIn('ticket_purchases.status', ['valid', 'used'])
        ->where(function($query) {
            $query->where('ticket_purchases.is_walk_in', false) // Regular tickets (not walk-in)
                  ->orWhere('ticket_purchases.is_sold', true);    // Or walk-in tickets that are sold
        })
        ->whereMonth('ticket_purchases.created_at', now()->month)
        ->sum('tickets.price');
    
    // Pending tickets: valid tickets that are ready for scanning (regular tickets or sold walk-in tickets)
    $pendingTickets = \App\Models\TicketPurchase::where('status', 'valid')
        ->where(function($query) {
            $query->where('is_walk_in', false)  // Regular tickets (not walk-in)
                  ->orWhere('is_sold', true);   // Or walk-in tickets that are sold
        })
        ->count();
    
    // Teacher tickets sold: only count sold tickets (regular tickets or walk-in tickets that are sold)
    $teacherTicketsSold = auth()->user()->assignedTickets()
        ->whereIn('status', ['valid', 'used'])
        ->where(function($query) {
            $query->where('is_walk_in', false)  // Regular tickets (not walk-in)
                  ->orWhere('is_sold', true);   // Or walk-in tickets that are sold
        })
        ->count();
    
    // Teacher revenue: only count sold tickets (regular tickets or walk-in tickets that are sold)
    $teacherRevenue = auth()->user()->assignedTickets()
        ->join('tickets', 'ticket_purchases.ticket_id', '=', 'tickets.id')
        ->whereIn('ticket_purchases.status', ['valid', 'used'])
        ->where(function($query) {
            $query->where('ticket_purchases.is_walk_in', false) // Regular tickets (not walk-in)
                  ->orWhere('ticket_purchases.is_sold', true);    // Or walk-in tickets that are sold
        })
        ->sum('tickets.price');
    
    // Student tickets are not affected by walk-in logic since students don't get walk-in tickets
    $studentActiveTickets = auth()->user()->ticketPurchases()->where('status', 'valid')->count();
    $studentUsedTickets = auth()->user()->ticketPurchases()->where('status', 'used')->count();
@endphp

<x-layouts.app :title="__('Dashboard')">
    <div class="py-12">
        <div class="mx-auto sm:px-6 lg:px-8 space-y-8">
            <!-- Welcome Header -->
            <div class="bg-white dark:bg-zinc-700 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <flux:heading size="xl">Welcome back, {{ auth()->user()->name }}!</flux:heading>
                            <flux:text class="mt-2 text-zinc-600 dark:text-zinc-400">
                                @if(auth()->user()->hasRole('super-admin'))
                                    System Administrator Dashboard
                                @elseif(auth()->user()->hasRole('admin'))
                                    Event Management Dashboard
                                @elseif(auth()->user()->hasRole('teacher'))
                                    Ticket Sales Dashboard
                                @else
                                    My Tickets Dashboard
                                @endif
                            </flux:text>
                        </div>
                        <div class="mt-4 sm:mt-0">
                            <flux:badge color="blue">{{ ucfirst(auth()->user()->roles->first()->name ?? 'User') }}</flux:badge>
                        </div>
                    </div>
                </div>
            </div>

            @if(auth()->user()->hasRole('super-admin'))
                <!-- Super Admin Dashboard -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <div class="bg-white dark:bg-zinc-700 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="w-8 h-8 bg-blue-500 rounded-md flex items-center justify-center">
                                        <flux:icon.user variant="solid" class="w-5 h-5"/>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <flux:heading class="text-gray-500 dark:text-gray-400">Total Users</flux:heading>
                                    <flux:heading size="lg" class="text-blue-600 dark:text-blue-400">{{ $totalUsers }}</flux:heading>
                                    <flux:text class="text-xs text-gray-500 dark:text-gray-400">Registered accounts</flux:text>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white dark:bg-zinc-700 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="w-8 h-8 bg-purple-500 rounded-md flex items-center justify-center">
                                        <flux:icon.musical-note variant="solid" class="w-5 h-5"/>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <flux:text class="text-sm text-gray-500 dark:text-gray-400">Active Concerts</flux:text>
                                    <flux:heading size="lg" class="text-purple-600 dark:text-purple-400">{{ $activeConcerts }}</flux:heading>
                                    <flux:text class="text-xs text-gray-500 dark:text-gray-400">Upcoming events</flux:text>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white dark:bg-zinc-700 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="w-8 h-8 bg-green-500 rounded-md flex items-center justify-center">
                                        <flux:icon.currency-dollar variant="solid" class="w-5 h-5"/>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <flux:text class="text-sm text-gray-500 dark:text-gray-400">Total Revenue</flux:text>
                                    <flux:heading size="lg" class="text-green-600 dark:text-green-400">RM {{ number_format($totalRevenue, 2) }}</flux:heading>
                                    <flux:text class="text-xs text-gray-500 dark:text-gray-400">All time sales</flux:text>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white dark:bg-zinc-700 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="w-8 h-8 bg-orange-500 rounded-md flex items-center justify-center">
                                        <flux:icon.ticket variant="solid" class="w-5 h-5"/>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <flux:text class="text-sm text-gray-500 dark:text-gray-400">Tickets Sold</flux:text>
                                    <flux:heading size="lg" class="text-orange-600 dark:text-orange-400">{{ $totalTicketsSold }}</flux:heading>
                                    <flux:text class="text-xs text-gray-500 dark:text-gray-400">Total sales</flux:text>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            @elseif(auth()->user()->hasRole('admin'))
                <!-- Admin Dashboard -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="bg-white dark:bg-zinc-700 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="w-8 h-8 bg-purple-500 rounded-md flex items-center justify-center">
                                        <flux:icon.musical-note variant="solid" class="w-5 h-5"/>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <flux:text class="text-sm text-gray-500 dark:text-gray-400">Active Concerts</flux:text>
                                    <flux:heading size="lg" class="text-purple-600 dark:text-purple-400">{{ $activeConcerts }}</flux:heading>
                                    <flux:text class="text-xs text-gray-500 dark:text-gray-400">Events this month</flux:text>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white dark:bg-zinc-700 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="w-8 h-8 bg-green-500 rounded-md flex items-center justify-center">
                                        <flux:icon.currency-dollar variant="solid" class="w-5 h-5"/>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <flux:text class="text-sm text-gray-500 dark:text-gray-400">Monthly Revenue</flux:text>
                                    <flux:heading size="lg" class="text-green-600 dark:text-green-400">RM {{ number_format($monthlyRevenue, 2) }}</flux:heading>
                                    <flux:text class="text-xs text-gray-500 dark:text-gray-400">This month</flux:text>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white dark:bg-zinc-700 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="w-8 h-8 bg-amber-500 rounded-md flex items-center justify-center">
                                        <flux:icon.ticket variant="solid" class="w-5 h-5"/>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <flux:text class="text-sm text-gray-500 dark:text-gray-400">Pending Tickets</flux:text>
                                    <flux:heading size="lg" class="text-amber-600 dark:text-amber-400">{{ $pendingTickets }}</flux:heading>
                                    <flux:text class="text-xs text-gray-500 dark:text-gray-400">Ready for scanning</flux:text>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            @elseif(auth()->user()->hasRole('teacher'))
                <!-- Teacher Dashboard -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="bg-white dark:bg-zinc-700 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="w-8 h-8 bg-orange-500 rounded-md flex items-center justify-center">
                                        <flux:icon.ticket variant="solid" class="w-5 h-5"/>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <flux:text class="text-sm text-gray-500 dark:text-gray-400">Tickets You've Sold</flux:text>
                                    <flux:heading size="lg" class="text-orange-600 dark:text-orange-400">{{ $teacherTicketsSold }}</flux:heading>
                                    <flux:text class="text-xs text-gray-500 dark:text-gray-400">Students purchase with you</flux:text>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white dark:bg-zinc-700 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="w-8 h-8 bg-green-500 rounded-md flex items-center justify-center">
                                        <flux:icon.currency-dollar variant="solid" class="w-5 h-5"/>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <flux:text class="text-sm text-gray-500 dark:text-gray-400">Your Sales Revenue</flux:text>
                                    <flux:heading size="lg" class="text-green-600 dark:text-green-400">RM {{ number_format($teacherRevenue, 2) }}</flux:heading>
                                    <flux:text class="text-xs text-gray-500 dark:text-gray-400">Total generated</flux:text>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white dark:bg-zinc-700 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="w-8 h-8 bg-purple-500 rounded-md flex items-center justify-center">
                                        <flux:icon.calendar variant="solid" class="w-5 h-5"/>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <flux:text class="text-sm text-gray-500 dark:text-gray-400">Available Concerts</flux:text>
                                    <flux:heading size="lg" class="text-purple-600 dark:text-purple-400">{{ $activeConcerts }}</flux:heading>
                                    <flux:text class="text-xs text-gray-500 dark:text-gray-400">Events to sell</flux:text>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            @else
                <!-- Student Dashboard -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="bg-white dark:bg-zinc-700 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="w-8 h-8 bg-orange-500 rounded-md flex items-center justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
                                        </svg>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <flux:text class="text-sm text-gray-500 dark:text-gray-400">My Active Tickets</flux:text>
                                    <flux:heading size="lg" class="text-orange-600 dark:text-orange-400">{{ $studentActiveTickets }}</flux:heading>
                                    <flux:text class="text-xs text-gray-500 dark:text-gray-400">Ready to use</flux:text>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white dark:bg-zinc-700 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="w-8 h-8 bg-emerald-500 rounded-md flex items-center justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <flux:text class="text-sm text-gray-500 dark:text-gray-400">Events Attended</flux:text>
                                    <flux:heading size="lg" class="text-emerald-600 dark:text-emerald-400">{{ $studentUsedTickets }}</flux:heading>
                                    <flux:text class="text-xs text-gray-500 dark:text-gray-400">Concerts enjoyed</flux:text>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Recent Activity & Upcoming Events -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Recent Activity -->
                <div class="bg-white dark:bg-zinc-700 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-8 h-8 bg-blue-500 rounded-md flex items-center justify-center">
                                <flux:icon.clock variant="solid" class="w-5 h-5"/>
                            </div>
                            <flux:heading size="lg">Recent Activity</flux:heading>
                        </div>
                        <flux:text class="mb-4 text-zinc-600 dark:text-zinc-400">View your recent ticket purchases, sales, and activity.</flux:text>
                        
                        <div class="overflow-x-auto">
                            <div class="space-y-4">
                                @if(auth()->user()->hasRole(['super-admin', 'admin']))
                                    @php
                                        $recentPurchases = \App\Models\TicketPurchase::with(['student', 'teacher', 'ticket.concert'])
                                            ->where(function($query) {
                                                $query->where('is_walk_in', false)  // Regular tickets (not walk-in)
                                                      ->orWhere('is_sold', true);   // Or walk-in tickets that are sold
                                            })
                                            ->latest()
                                            ->take(5)
                                            ->get();
                                    @endphp
                                    @forelse($recentPurchases as $purchase)
                                        <div class="flex items-center justify-between py-3 border-b border-zinc-100 dark:border-zinc-600 last:border-b-0">
                                            <div class="flex-1">
                                                <flux:text class="font-medium">{{ $purchase->student->name ?? 'Walk-in Customer' }}</flux:text>
                                                <flux:text class="text-sm text-zinc-500 dark:text-zinc-400">
                                                    Purchased {{ $purchase->ticket->ticket_type }} for {{ $purchase->ticket->concert->title }}
                                                </flux:text>
                                            </div>
                                            <div class="text-right ml-4">
                                                <flux:text class="text-sm font-semibold text-green-600 dark:text-green-400">RM {{ number_format($purchase->ticket->price, 2) }}</flux:text>
                                                <flux:text class="text-xs text-zinc-500 dark:text-zinc-400">{{ $purchase->created_at?->diffForHumans() }}</flux:text>
                                            </div>
                                        </div>
                                    @empty
                                        <div class="text-center py-8">
                                            <flux:text class="text-zinc-500 dark:text-zinc-400">No recent ticket purchases</flux:text>
                                        </div>
                                    @endforelse
                                @elseif(auth()->user()->hasRole('teacher'))
                                    @php
                                        $myRecentSales = auth()->user()->assignedTickets()
                                            ->with(['student', 'ticket.concert'])
                                            ->where(function($query) {
                                                $query->where('is_walk_in', false)  // Regular tickets (not walk-in)
                                                      ->orWhere('is_sold', true);   // Or walk-in tickets that are sold
                                            })
                                            ->latest()
                                            ->take(5)
                                            ->get();
                                    @endphp
                                    @forelse($myRecentSales as $sale)
                                        <div class="flex items-center justify-between py-3 border-b border-zinc-100 dark:border-zinc-600 last:border-b-0">
                                            <div class="flex-1">
                                                <flux:text class="font-medium">{{ $sale->student->name ?? 'Walk-in Customer' }}</flux:text>
                                                <flux:text class="text-sm text-zinc-500 dark:text-zinc-400">
                                                    {{ $sale->ticket->ticket_type }} for {{ $sale->ticket->concert->title }}
                                                </flux:text>
                                            </div>
                                            <div class="text-right ml-4">
                                                <flux:badge color="green" size="sm">Sold</flux:badge>
                                                <flux:text class="text-xs text-zinc-500 dark:text-zinc-400 block mt-1">{{ $sale->created_at?->diffForHumans() }}</flux:text>
                                            </div>
                                        </div>
                                    @empty
                                        <div class="text-center py-8">
                                            <flux:text class="text-zinc-500 dark:text-zinc-400">No recent ticket sales</flux:text>
                                        </div>
                                    @endforelse
                                @else
                                    @php
                                        $myRecentTickets = auth()->user()->ticketPurchases()
                                            ->with(['ticket.concert'])
                                            ->latest()
                                            ->take(5)
                                            ->get();
                                    @endphp
                                    @forelse($myRecentTickets as $ticket)
                                        <div class="flex items-center justify-between py-3 border-b border-zinc-100 dark:border-zinc-600 last:border-b-0">
                                            <div class="flex-1">
                                                <flux:text class="font-medium">{{ $ticket->ticket->concert->title }}</flux:text>
                                                <flux:text class="text-sm text-zinc-500 dark:text-zinc-400">
                                                    {{ $ticket->ticket->ticket_type }} • {{ $ticket->ticket->concert->date?->format('M j, Y') }}
                                                </flux:text>
                                            </div>
                                            <div class="text-right ml-4">
                                                <flux:badge 
                                                    color="{{ $ticket->status === 'valid' ? 'green' : ($ticket->status === 'used' ? 'blue' : 'gray') }}" 
                                                    size="sm"
                                                >
                                                    {{ ucfirst($ticket->status) }}
                                                </flux:badge>
                                                <flux:text class="text-xs text-zinc-500 dark:text-zinc-400 block mt-1">{{ $ticket->created_at?->diffForHumans() }}</flux:text>
                                            </div>
                                        </div>
                                    @empty
                                        <div class="text-center py-8">
                                            <flux:text class="text-zinc-500 dark:text-zinc-400">No tickets yet</flux:text>
                                        </div>
                                    @endforelse
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Upcoming Events -->
                <div class="bg-white dark:bg-zinc-700 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-8 h-8 bg-purple-500 rounded-md flex items-center justify-center">
                               <flux:icon.calendar variant="solid" class="w-5 h-5"/>
                            </div>
                            <flux:heading size="lg">Upcoming Concerts</flux:heading>
                        </div>
                        
                        <div class="space-y-4">
                            @php
                                $upcomingConcerts = \App\Models\Concert::with(['tickets'])
                                    ->where('date', '>=', now())
                                    ->orderBy('date')
                                    ->take(5)
                                    ->get();
                            @endphp
                            @forelse($upcomingConcerts as $concert)
                                <div class="py-3 border-b border-zinc-100 dark:border-zinc-600 last:border-b-0">
                                    <div class="flex items-start justify-between">
                                        <div class="flex-1">
                                            <flux:text class="font-medium">{{ $concert->title }}</flux:text>
                                            <flux:text class="text-sm text-zinc-500 dark:text-zinc-400">{{ $concert->venue }}</flux:text>
                                            <flux:text class="text-xs text-zinc-400 dark:text-zinc-500">
                                                {{ $concert->date?->format('M j, Y') }} at {{ $concert->start_time?->format('g:i A') }}
                                            </flux:text>
                                        </div>
                                        <div class="text-right ml-4">
                                            @if(auth()->user()->hasRole(['super-admin', 'admin', 'teacher']))
                                                <flux:badge color="blue" size="sm">
                                                    {{ $concert->ticketPurchases()
                                                        ->whereIn('status', ['valid', 'used'])
                                                        ->where(function($query) {
                                                            $query->where('is_walk_in', false)  // Regular tickets (not walk-in)
                                                                  ->orWhere('is_sold', true);   // Or walk-in tickets that are sold
                                                        })
                                                        ->count() }} sold
                                                </flux:badge>
                                            @endif
                                            <flux:text class="text-xs text-zinc-500 dark:text-zinc-400 block mt-1">
                                                {{ $concert->date?->diffForHumans() }}
                                            </flux:text>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-8">
                                    <flux:text class="text-zinc-500 dark:text-zinc-400">No upcoming concerts scheduled</flux:text>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
