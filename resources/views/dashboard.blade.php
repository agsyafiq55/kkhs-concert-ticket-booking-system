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
    <div class="space-y-6">
        <!-- Welcome Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
            <div>
                <flux:heading size="xl">Welcome back, {{ auth()->user()->name }}!</flux:heading>
                <flux:text class="mt-1 text-zinc-600 dark:text-zinc-400">
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

        @if(auth()->user()->hasRole('super-admin'))
            <!-- Super Admin Dashboard -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="bg-white dark:bg-zinc-600 rounded-lg p-6 border border-zinc-200 dark:border-zinc-700">
                    <div class="flex items-center justify-between mb-2">
                        <flux:heading size="sm">Total Users</flux:heading>
                        <flux:icon name="users" variant="solid" class="text-blue-500 w-7 h-7" />
                    </div>
                    <flux:text class="text-2xl font-bold">{{ $totalUsers }}</flux:text>
                    <flux:text class="text-sm text-zinc-500">Registered accounts</flux:text>
                </div>
                <div class="bg-white dark:bg-zinc-600 rounded-lg p-6 border border-zinc-200 dark:border-zinc-700">
                    <div class="flex items-center justify-between mb-2">
                        <flux:heading size="sm">Active Concerts</flux:heading>
                        <flux:icon name="calendar" variant="solid" class="text-purple-500 w-7 h-7" />
                    </div>
                    <flux:text class="text-2xl font-bold">{{ $activeConcerts }}</flux:text>
                    <flux:text class="text-sm text-zinc-500">Upcoming events</flux:text>
                </div>
                <div class="bg-white dark:bg-zinc-600 rounded-lg p-6 border border-zinc-200 dark:border-zinc-700">
                    <div class="flex items-center justify-between mb-2">
                        <flux:heading size="sm">Total Revenue</flux:heading>
                        <flux:icon name="currency-dollar" variant="solid" class="text-green-500 w-7 h-7" />
                    </div>
                    <flux:text class="text-2xl font-bold">RM {{ number_format($totalRevenue, 2) }}</flux:text>
                    <flux:text class="text-sm text-zinc-500">All time sales</flux:text>
                </div>
                <div class="bg-white dark:bg-zinc-600 rounded-lg p-6 border border-zinc-200 dark:border-zinc-700">
                    <div class="flex items-center justify-between mb-2">
                        <flux:heading size="sm">Tickets Sold</flux:heading>
                        <flux:icon name="ticket" variant="solid" class="text-orange-500 w-7 h-7" />
                    </div>
                    <flux:text class="text-2xl font-bold">{{ $totalTicketsSold }}</flux:text>
                    <flux:text class="text-sm text-zinc-500">Total sales</flux:text>
                </div>
            </div>

        @elseif(auth()->user()->hasRole('admin'))
            <!-- Admin Dashboard -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white dark:bg-zinc-600 rounded-lg p-6 border border-zinc-200 dark:border-zinc-700">
                    <div class="flex items-center justify-between mb-2">
                        <flux:heading size="sm">Active Concerts</flux:heading>
                        <flux:icon name="calendar" variant="solid" class="text-purple-500 w-7 h-7" />
                    </div>
                    <flux:text class="text-2xl font-bold">{{ $activeConcerts }}</flux:text>
                    <flux:text class="text-sm text-zinc-500">Events this month</flux:text>
                </div>
                <div class="bg-white dark:bg-zinc-600 rounded-lg p-6 border border-zinc-200 dark:border-zinc-700">
                    <div class="flex items-center justify-between mb-2">
                        <flux:heading size="sm">Monthly Revenue</flux:heading>
                        <flux:icon name="currency-dollar" variant="solid" class="text-green-500 w-7 h-7" />
                    </div>
                    <flux:text class="text-2xl font-bold">RM {{ number_format($monthlyRevenue, 2) }}</flux:text>
                    <flux:text class="text-sm text-zinc-500">This month</flux:text>
                </div>
                <div class="bg-white dark:bg-zinc-600 rounded-lg p-6 border border-zinc-200 dark:border-zinc-700">
                    <div class="flex items-center justify-between mb-2">
                        <flux:heading size="sm">Pending Tickets</flux:heading>
                        <flux:icon name="clock" variant="solid" class="text-amber-500 w-7 h-7" />
                    </div>
                    <flux:text class="text-2xl font-bold">{{ $pendingTickets }}</flux:text>
                    <flux:text class="text-sm text-zinc-500">Ready for scanning</flux:text>
                </div>
            </div>

        @elseif(auth()->user()->hasRole('teacher'))
            <!-- Teacher Dashboard -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white dark:bg-zinc-600 rounded-lg p-6 border border-zinc-200 dark:border-zinc-700">
                    <div class="flex items-center justify-between mb-2">
                        <flux:heading size="sm">My Tickets Sold</flux:heading>
                        <flux:icon name="ticket" variant="solid" class="text-orange-500 w-7 h-7" />
                    </div>
                    <flux:text class="text-2xl font-bold">{{ $teacherTicketsSold }}</flux:text>
                    <flux:text class="text-sm text-zinc-500">Students helped</flux:text>
                </div>
                <div class="bg-white dark:bg-zinc-600 rounded-lg p-6 border border-zinc-200 dark:border-zinc-700">
                    <div class="flex items-center justify-between mb-2">
                        <flux:heading size="sm">My Sales Revenue</flux:heading>
                        <flux:icon name="currency-dollar" variant="solid" class="text-green-500 w-7 h-7" />
                    </div>
                    <flux:text class="text-2xl font-bold">RM {{ number_format($teacherRevenue, 2) }}</flux:text>
                    <flux:text class="text-sm text-zinc-500">Total generated</flux:text>
                </div>
                <div class="bg-white dark:bg-zinc-600 rounded-lg p-6 border border-zinc-200 dark:border-zinc-700">
                    <div class="flex items-center justify-between mb-2">
                        <flux:heading size="sm">Available Concerts</flux:heading>
                        <flux:icon name="calendar" variant="solid" class="text-purple-500 w-7 h-7" />
                    </div>
                    <flux:text class="text-2xl font-bold">{{ $activeConcerts }}</flux:text>
                    <flux:text class="text-sm text-zinc-500">Events to sell</flux:text>
                </div>
            </div>

        @else
            <!-- Student Dashboard -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-white dark:bg-zinc-600 rounded-lg p-6 border border-zinc-200 dark:border-zinc-700">
                    <div class="flex items-center justify-between mb-2">
                        <flux:heading size="sm">My Active Tickets</flux:heading>
                        <flux:icon name="ticket" variant="solid" class="text-orange-500 w-7 h-7" />
                    </div>
                    <flux:text class="text-2xl font-bold">{{ $studentActiveTickets }}</flux:text>
                    <flux:text class="text-sm text-zinc-500">Ready to use</flux:text>
                </div>
                <div class="bg-white dark:bg-zinc-600 rounded-lg p-6 border border-zinc-200 dark:border-zinc-700">
                    <div class="flex items-center justify-between mb-2">
                        <flux:heading size="sm">Events Attended</flux:heading>
                        <flux:icon name="check-circle" variant="solid" class="text-emerald-500 w-7 h-7" />
                    </div>
                    <flux:text class="text-2xl font-bold">{{ $studentUsedTickets }}</flux:text>
                    <flux:text class="text-sm text-zinc-500">Concerts enjoyed</flux:text>
                </div>
            </div>
        @endif

        <!-- Recent Activity & Upcoming Events -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Recent Activity -->
            <div class="bg-white dark:bg-zinc-600 rounded-lg border border-zinc-200 dark:border-zinc-700">
                <div class="p-6">
                    <div class="flex items-center gap-2 mb-4">
                        <flux:icon name="clock" variant="solid" class="text-blue-500 w-7 h-7" />
                        <flux:heading size="lg">Recent Activity</flux:heading>
                    </div>
                    <flux:text class="mb-4">View your recent ticket purchases, sales, and activity.</flux:text>
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
                                <div class="flex items-center justify-between py-2 border-b border-zinc-100 dark:border-zinc-600 last:border-b-0">
                                    <div class="flex-1">
                                        <flux:text class="font-medium">{{ $purchase->student->name ?? 'Walk-in Customer' }}</flux:text>
                                        <flux:text class="text-sm text-zinc-500">
                                            Purchased {{ $purchase->ticket->ticket_type }} for {{ $purchase->ticket->concert->title }}
                                        </flux:text>
                                    </div>
                                    <div class="text-right">
                                        <flux:text class="text-sm font-medium">RM {{ number_format($purchase->ticket->price, 2) }}</flux:text>
                                        <flux:text class="text-xs text-zinc-500">{{ $purchase->created_at?->diffForHumans() }}</flux:text>
                                    </div>
                                </div>
                            @empty
                                <flux:text class="text-zinc-500">No recent ticket purchases</flux:text>
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
                                <div class="flex items-center justify-between py-2 border-b border-zinc-100 dark:border-zinc-700 last:border-b-0">
                                    <div class="flex-1">
                                        <flux:text class="font-medium">{{ $sale->student->name ?? 'Walk-in Customer' }}</flux:text>
                                        <flux:text class="text-sm text-zinc-500">
                                            {{ $sale->ticket->ticket_type }} for {{ $sale->ticket->concert->title }}
                                        </flux:text>
                                    </div>
                                    <div class="text-right">
                                        <flux:badge color="green" size="sm">Sold</flux:badge>
                                        <flux:text class="text-xs text-zinc-500 block">{{ $sale->created_at?->diffForHumans() }}</flux:text>
                                    </div>
                                </div>
                            @empty
                                <flux:text class="text-zinc-500">No recent ticket sales</flux:text>
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
                                <div class="flex items-center justify-between py-2 border-b border-zinc-100 dark:border-zinc-700 last:border-b-0">
                                    <div class="flex-1">
                                        <flux:text class="font-medium">{{ $ticket->ticket->concert->title }}</flux:text>
                                        <flux:text class="text-sm text-zinc-500">
                                            {{ $ticket->ticket->ticket_type }} • {{ $ticket->ticket->concert->date?->format('M j, Y') }}
                                        </flux:text>
                                    </div>
                                    <div class="text-right">
                                        <flux:badge 
                                            color="{{ $ticket->status === 'valid' ? 'green' : ($ticket->status === 'used' ? 'blue' : 'gray') }}" 
                                            size="sm"
                                        >
                                            {{ ucfirst($ticket->status) }}
                                        </flux:badge>
                                        <flux:text class="text-xs text-zinc-500 block">{{ $ticket->created_at?->diffForHumans() }}</flux:text>
                                    </div>
                                </div>
                            @empty
                                <flux:text class="text-zinc-500">No tickets yet</flux:text>
                            @endforelse
                        @endif
                    </div>
                </div>
            </div>

            <!-- Upcoming Events -->
            <div class="bg-white dark:bg-zinc-600 rounded-lg border border-zinc-200 dark:border-zinc-700">
                <div class="p-6">
                    <div class="flex items-center gap-2 mb-4">
                        <flux:icon name="calendar-days" variant="solid" class="text-purple-500 w-7 h-7" />
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
                            <div class="py-3 border-b border-zinc-100 dark:border-zinc-700 last:border-b-0">
                                <div class="flex items-start justify-between">
                                    <div class="flex-1">
                                        <flux:text class="font-medium">{{ $concert->title }}</flux:text>
                                        <flux:text class="text-sm text-zinc-500">{{ $concert->venue }}</flux:text>
                                        <flux:text class="text-xs text-zinc-400">
                                            {{ $concert->date?->format('M j, Y') }} at {{ $concert->start_time?->format('g:i A') }}
                                        </flux:text>
                                    </div>
                                    <div class="text-right ml-4">
                                        @if(auth()->user()->hasRole(['super-admin', 'admin', 'teacher']))
                                            <flux:text class="text-sm font-medium">
                                                {{ $concert->ticketPurchases()
                                                    ->whereIn('status', ['valid', 'used'])
                                                    ->where(function($query) {
                                                        $query->where('is_walk_in', false)  // Regular tickets (not walk-in)
                                                              ->orWhere('is_sold', true);   // Or walk-in tickets that are sold
                                                    })
                                                    ->count() }} sold
                                            </flux:text>
                                        @endif
                                        <flux:text class="text-xs text-zinc-500">
                                            {{ $concert->date?->diffForHumans() }}
                                        </flux:text>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <flux:text class="text-zinc-500">No upcoming concerts scheduled</flux:text>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
