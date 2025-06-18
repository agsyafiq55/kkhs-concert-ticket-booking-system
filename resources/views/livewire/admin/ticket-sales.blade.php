<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

        <!-- Header -->
        <div class="bg-white dark:bg-zinc-700 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <div class="flex justify-between items-center">
                    <flux:heading size="xl">Ticket Sales</flux:heading>
                    <div class="flex space-x-2">
                        <!-- Export Dropdown -->
                        <flux:dropdown>
                            <flux:button variant="primary" icon="arrow-down-tray" icon:trailing="chevron-down">
                                Export Report
                            </flux:button>

                            <flux:menu>
                                <flux:menu.item icon="document-chart-bar" wire:click="exportPDF">
                                    PDF Report (Print/View)
                                </flux:menu.item>
                                <flux:menu.item icon="table-cells" wire:click="exportCSV">
                                    Detailed CSV Export
                                </flux:menu.item>
                                <flux:menu.item icon="chart-bar" wire:click="exportSummaryCSV">
                                    Summary CSV Export
                                </flux:menu.item>
                            </flux:menu>
                        </flux:dropdown>
                    </div>
                </div>
                <flux:text class="mt-2">Track and analyze ticket sales across all concerts and teachers</flux:text>
            </div>
        </div>

        <!-- Stats Dashboard -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6">
            <div class="bg-white dark:bg-zinc-700 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-green-500 rounded-md flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1" />
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <flux:text class="text-sm text-gray-500 dark:text-gray-400">Total Revenue</flux:text>
                            <flux:heading size="lg" class="text-green-600 dark:text-green-400">
                                RM{{ number_format($totalRevenue, 2) }}
                            </flux:heading>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-zinc-700 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-blue-500 rounded-md flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <flux:text class="text-sm text-gray-500 dark:text-gray-400">Total Sales</flux:text>
                            <flux:heading size="lg" class="text-blue-600 dark:text-blue-400">
                                {{ number_format($totalSales) }}
                            </flux:heading>
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
                            <flux:text class="text-sm text-gray-500 dark:text-gray-400">Valid Tickets</flux:text>
                            <flux:heading size="lg" class="text-emerald-600 dark:text-emerald-400">
                                {{ number_format($validTickets) }}
                            </flux:heading>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-zinc-700 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-purple-500 rounded-md flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <flux:text class="text-sm text-gray-500 dark:text-gray-400">Used Tickets</flux:text>
                            <flux:heading size="lg" class="text-purple-600 dark:text-purple-400">
                                {{ number_format($usedTickets) }}
                            </flux:heading>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-zinc-700 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-red-500 rounded-md flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <flux:text class="text-sm text-gray-500 dark:text-gray-400">Cancelled</flux:text>
                            <flux:heading size="lg" class="text-red-600 dark:text-red-400">
                                {{ number_format($cancelledTickets) }}
                            </flux:heading>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white dark:bg-zinc-700 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <flux:heading size="lg">Filters</flux:heading>
                    <flux:button icon="arrow-path" variant="filled" wire:click="resetFilters">
                        Reset Filters
                    </flux:button>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-4">
                    <div>
                        <flux:select placeholder="Filter by Concert" wire:model.live="concertFilter">
                            @foreach ($concerts as $concert)
                            <option value="{{ $concert->id }}">
                                {{ $concert->title }} ({{ $concert->date->format('M d, Y') }})
                            </option>
                            @endforeach
                        </flux:select>
                    </div>

                    <div>
                        <flux:select placeholder="Filter by Teacher" wire:model.live="teacherFilter">
                            @foreach ($teachers as $teacher)
                            <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
                            @endforeach
                        </flux:select>
                    </div>

                    <div>
                        <flux:select placeholder="Filter by Status" wire:model.live="statusFilter">
                            <option value="valid">Valid</option>
                            <option value="used">Used</option>
                            <option value="cancelled">Cancelled</option>
                        </flux:select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <flux:input
                            type="date"
                            placeholder="From Date"
                            wire:model.live="dateFrom" />
                    </div>

                    <div>
                        <flux:input
                            type="date"
                            placeholder="To Date"
                            wire:model.live="dateTo" />
                    </div>

                    <div>
                        <flux:input
                            icon="magnifying-glass"
                            placeholder="Search"
                            wire:model.live.debounce.300ms="search"
                            placeholder="Search students, concerts, ticket types..." />
                    </div>
                </div>
            </div>
        </div>

        <!-- Concert Revenue Breakdown -->
        <div class="bg-white dark:bg-zinc-700 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <flux:heading size="lg" class="mb-4">Revenue by Concert</flux:heading>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-zinc-700">
                        <thead class="bg-gray-50 dark:bg-zinc-800">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Concert</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Total Sales</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Revenue</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Valid</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Used</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Cancelled</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-zinc-800/50 divide-y divide-gray-200 dark:divide-zinc-700">
                            @forelse ($concertRevenue as $concert)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap font-medium">{{ $concert->title }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ \Carbon\Carbon::parse($concert->date)->format('M d, Y') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ number_format($concert->total_sales) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap font-semibold text-green-600 dark:text-green-400">
                                    RM{{ number_format($concert->revenue, 2) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <flux:badge color="green">{{ $concert->valid_count }}</flux:badge>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <flux:badge color="blue">{{ $concert->used_count }}</flux:badge>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($concert->cancelled_count > 0)
                                    <flux:badge color="red">{{ $concert->cancelled_count }}</flux:badge>
                                    @else
                                    <span class="text-gray-400">0</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">
                                    No concert sales data found.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Teacher Sales Performance -->
        <div class="bg-white dark:bg-zinc-700 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <flux:heading size="lg" class="mb-4">Sales by Teacher</flux:heading>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-zinc-700">
                        <thead class="bg-gray-50 dark:bg-zinc-800">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Teacher</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Email</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Total Sales</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Revenue</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Valid</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Used</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Cancelled</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-zinc-800/50 divide-y divide-gray-200 dark:divide-zinc-700">
                            @forelse ($teacherSales as $teacher)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap font-medium">{{ $teacher->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $teacher->email }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ number_format($teacher->total_sales) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap font-semibold text-green-600 dark:text-green-400">
                                    RM{{ number_format($teacher->revenue, 2) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <flux:badge color="green">{{ $teacher->valid_count }}</flux:badge>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <flux:badge color="blue">{{ $teacher->used_count }}</flux:badge>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($teacher->cancelled_count > 0)
                                    <flux:badge color="red">{{ $teacher->cancelled_count }}</flux:badge>
                                    @else
                                    <span class="text-gray-400">0</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">
                                    No teacher sales data found.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Individual Sales Details -->
        <div class="bg-white dark:bg-zinc-700 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <flux:heading size="lg" class="mb-4">Individual Sales</flux:heading>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-zinc-700">
                        <thead class="bg-gray-50 dark:bg-zinc-800">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Student</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Concert</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Ticket Type</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Price</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Teacher</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Purchase Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-zinc-800/50 divide-y divide-gray-200 dark:divide-zinc-700">
                            @forelse ($sales as $sale)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div>
                                        <div class="font-medium">{{ $sale->student_name }}</div>
                                        <div class="text-sm text-gray-500 dark:text-gray-400">{{ $sale->student_email }}</div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div>
                                        <div class="font-medium">{{ $sale->concert_title }}</div>
                                        <div class="text-sm text-gray-500 dark:text-gray-400">{{ \Carbon\Carbon::parse($sale->concert_date)->format('M d, Y') }}</div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $sale->ticket_type }}</td>
                                <td class="px-6 py-4 whitespace-nowrap font-semibold">RM{{ number_format($sale->price, 2) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $sale->teacher_name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">{{ \Carbon\Carbon::parse($sale->purchase_date)->format('M d, Y g:i A') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($sale->status === 'valid')
                                    <flux:badge color="green">Valid</flux:badge>
                                    @elseif($sale->status === 'used')
                                    <flux:badge color="blue">Used</flux:badge>
                                    @else
                                    <flux:badge color="red">Cancelled</flux:badge>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">
                                    No sales found matching your criteria.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $sales->links() }}
                </div>
            </div>
        </div>
    </div>
</div>