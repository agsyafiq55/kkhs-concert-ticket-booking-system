<div class="py-12">
    <div class="mx-auto sm:px-6 lg:px-8 space-y-8">
        <!-- Header -->
        <div>
            <div class="flex justify-between items-center">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center mr-4 shadow-lg">
                        <flux:icon.chart-bar variant="solid" class="w-7 h-7 text-white" />
                    </div>
                    <div>
                        <flux:heading size="xl" class="mb-2">Ticket Sales</flux:heading>
                        <flux:text class="text-zinc-600 dark:text-zinc-400">
                            Keep track of ticket sales generated from each concert or by individual teachers
                        </flux:text>
                    </div>
                </div>
                <div class="flex space-x-2">
                    <!-- Export Dropdown -->
                    <flux:dropdown>
                        <flux:button variant="primary" icon="arrow-down-tray" icon:trailing="chevron-down" class="shadow-md hover:shadow-lg transition-shadow duration-200">
                            Export Report
                        </flux:button>

                        <flux:menu>
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
        </div>

        <!-- Stats Dashboard -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Total Revenue Card -->
            <div class="bg-white dark:bg-zinc-700 rounded-lg p-6 shadow-sm border border-zinc-200/50 dark:border-zinc-700/50 hover:shadow-md transition-shadow">
                <flux:text class="text-sm text-zinc-600 dark:text-zinc-400 font-medium">Total Revenue</flux:text>
                <flux:heading size="xl" class="text-zinc-900 dark:text-zinc-100 font-bold mt-2 mb-3">
                    RM{{ number_format($totalRevenue, 2) }}
                </flux:heading>
                <div class="flex items-center gap-2">
                    <div class="flex items-center gap-1 text-green-600 dark:text-green-400">
                        <flux:icon.arrow-trending-up class="w-3 h-3" />
                        <span class="text-sm font-medium">+12.5%</span>
                    </div>
                    <flux:text class="text-xs text-zinc-500 dark:text-zinc-500">vs last month</flux:text>
                </div>
            </div>

            <!-- Total Sales Card -->
            <div class="bg-white dark:bg-zinc-700 rounded-lg p-6 shadow-sm border border-zinc-200/50 dark:border-zinc-700/50 hover:shadow-md transition-shadow">
                <flux:text class="text-sm text-zinc-600 dark:text-zinc-400 font-medium">Total Sales</flux:text>
                <flux:heading size="xl" class="text-zinc-900 dark:text-zinc-100 font-bold mt-2 mb-3">
                    {{ number_format($totalSales) }}
                </flux:heading>
                <div class="flex items-center gap-2">
                    <div class="flex items-center gap-1 text-green-600 dark:text-green-400">
                        <flux:icon.arrow-trending-up class="w-3 h-3" />
                        <span class="text-sm font-medium">+8.2%</span>
                    </div>
                    <flux:text class="text-xs text-zinc-500 dark:text-zinc-500">vs last month</flux:text>
                </div>
            </div>

            <!-- Valid Tickets Card -->
            <div class="bg-white dark:bg-zinc-700 rounded-lg p-6 shadow-sm border border-zinc-200/50 dark:border-zinc-700/50 hover:shadow-md transition-shadow">
                <flux:text class="text-sm text-zinc-600 dark:text-zinc-400 font-medium">Valid Tickets</flux:text>
                <flux:heading size="xl" class="text-zinc-900 dark:text-zinc-100 font-bold mt-2 mb-3">
                    {{ number_format($validTickets) }}
                </flux:heading>
                <div class="flex items-center gap-2">
                    <div class="flex items-center gap-1 text-blue-600 dark:text-blue-400">
                        <flux:icon.arrow-trending-up class="w-3 h-3" />
                        <span class="text-sm font-medium">+5.3%</span>
                    </div>
                    <flux:text class="text-xs text-zinc-500 dark:text-zinc-500">this week</flux:text>
                </div>
            </div>

            <!-- Used Tickets Card -->
            <div class="bg-white dark:bg-zinc-700 rounded-lg p-6 shadow-sm border border-zinc-200/50 dark:border-zinc-700/50 hover:shadow-md transition-shadow">
                <flux:text class="text-sm text-zinc-600 dark:text-zinc-400 font-medium">Used Tickets</flux:text>
                <flux:heading size="xl" class="text-zinc-900 dark:text-zinc-100 font-bold mt-2 mb-3">
                    {{ number_format($usedTickets) }}
                </flux:heading>
                <div class="flex items-center gap-2">
                    <div class="flex items-center gap-1 text-green-600 dark:text-green-400">
                        <flux:icon.arrow-trending-up class="w-3 h-3" />
                        <span class="text-sm font-medium">+15.8%</span>
                    </div>
                    <flux:text class="text-xs text-zinc-500 dark:text-zinc-500">event day</flux:text>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white dark:bg-zinc-700 overflow-hidden shadow-md hover:shadow-lg sm:rounded-xl transition-all duration-200 border border-zinc-100 dark:border-zinc-600">
            <div class="p-6">
                <div class="flex justify-between items-center mb-6">
                    <div class="flex items-center">
                        <div class="w-8 h-8 bg-gradient-to-br from-zinc-500 to-zinc-600 rounded-lg flex items-center justify-center mr-3">
                            <flux:icon.funnel class="w-5 h-5 text-white" />
                        </div>
                        <flux:heading size="lg">Filters</flux:heading>
                    </div>
                    <flux:button icon="arrow-path" variant="subtle" wire:click="resetFilters" class="hover:bg-zinc-100 dark:hover:bg-zinc-600 transition-colors duration-200">
                        Reset Filters
                    </flux:button>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
                    <div class="space-y-2">
                        <flux:text class="text-sm font-medium text-zinc-700 dark:text-zinc-300">Concert</flux:text>
                        <flux:select placeholder="Filter by Concert" wire:model.live="concertFilter">
                            @foreach ($concerts as $concert)
                            <option value="{{ $concert->id }}">
                                {{ $concert->title }} ({{ $concert->date->format('d/m/Y') }})
                            </option>
                            @endforeach
                        </flux:select>
                    </div>

                    <div class="space-y-2">
                        <flux:text class="text-sm font-medium text-zinc-700 dark:text-zinc-300">Teacher</flux:text>
                        <flux:select placeholder="Filter by Teacher" wire:model.live="teacherFilter">
                            @foreach ($teachers as $teacher)
                            <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
                            @endforeach
                        </flux:select>
                    </div>

                    <div class="space-y-2">
                        <flux:text class="text-sm font-medium text-zinc-700 dark:text-zinc-300">Status</flux:text>
                        <flux:select placeholder="Filter by Status" wire:model.live="statusFilter">
                            <option value="valid">Valid</option>
                            <option value="used">Used</option>
                        </flux:select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="space-y-2">
                        <flux:text class="text-sm font-medium text-zinc-700 dark:text-zinc-300">From Date</flux:text>
                        <flux:input
                            type="date"
                            placeholder="From Date"
                            wire:model.live="dateFrom" />
                    </div>

                    <div class="space-y-2">
                        <flux:text class="text-sm font-medium text-zinc-700 dark:text-zinc-300">To Date</flux:text>
                        <flux:input
                            type="date"
                            placeholder="To Date"
                            wire:model.live="dateTo" />
                    </div>

                    <div class="space-y-2">
                        <flux:text class="text-sm font-medium text-zinc-700 dark:text-zinc-300">Search</flux:text>
                        <flux:input
                            icon="magnifying-glass"
                            placeholder="Search students, concerts, ticket types..."
                            wire:model.live.debounce.300ms="search" />
                    </div>
                </div>
            </div>
        </div>

        <!-- Concert Revenue Breakdown -->
        <div class="bg-white dark:bg-zinc-700 overflow-hidden shadow-md hover:shadow-lg sm:rounded-xl transition-all duration-200 border border-zinc-100 dark:border-zinc-600">
            <div class="p-6">
                <div class="flex items-center mb-6">
                    <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg flex items-center justify-center mr-3">
                        <flux:icon.musical-note class="w-5 h-5 text-white" />
                    </div>
                    <flux:heading size="lg">Revenue by Concert</flux:heading>
                </div>

                <div class="overflow-hidden rounded-lg border border-zinc-200 dark:border-zinc-600">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-zinc-600">
                        <thead class="bg-zinc-50 dark:bg-zinc-800">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-zinc-600 dark:text-zinc-300 uppercase tracking-wider first:rounded-tl-lg">Concert</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-zinc-600 dark:text-zinc-300 uppercase tracking-wider">Date</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-zinc-600 dark:text-zinc-300 uppercase tracking-wider">Total Sales</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-zinc-600 dark:text-zinc-300 uppercase tracking-wider">Revenue</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-zinc-600 dark:text-zinc-300 uppercase tracking-wider">Valid</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-zinc-600 dark:text-zinc-300 uppercase tracking-wider last:rounded-tr-lg">Used</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-zinc-800/50 divide-y divide-gray-200 dark:divide-zinc-700">
                            @forelse ($concertRevenue as $concert)
                            <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-800/75 transition-colors duration-150">
                                <td class="px-6 py-4 whitespace-nowrap font-medium text-zinc-900 dark:text-zinc-100">{{ $concert->title }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-zinc-600 dark:text-zinc-400">{{ \Carbon\Carbon::parse($concert->date)->format('d/m/Y') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-zinc-900 dark:text-zinc-100 font-medium">{{ number_format($concert->total_sales) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap font-bold text-green-600 dark:text-green-400">
                                    RM{{ number_format($concert->revenue, 2) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <flux:badge color="green" class="font-medium">{{ $concert->valid_count }}</flux:badge>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <flux:badge color="blue" class="font-medium">{{ $concert->used_count }}</flux:badge>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center first:rounded-bl-lg last:rounded-br-lg">
                                    <div class="flex flex-col items-center">
                                        <div class="w-12 h-12 bg-zinc-100 dark:bg-zinc-600 rounded-full flex items-center justify-center mb-4">
                                            <flux:icon.musical-note class="w-6 h-6 text-zinc-400" />
                                        </div>
                                        <flux:text class="text-zinc-500 dark:text-zinc-400 font-medium">No concert sales data found</flux:text>
                                        <flux:text class="text-sm text-zinc-400 dark:text-zinc-500 mt-1">Try adjusting your filters or check back later</flux:text>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Teacher Sales Performance -->
        <div class="bg-white dark:bg-zinc-700 overflow-hidden shadow-md hover:shadow-lg sm:rounded-xl transition-all duration-200 border border-zinc-100 dark:border-zinc-600">
            <div class="p-6">
                <div class="flex items-center mb-6">
                    <div class="w-8 h-8 bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg flex items-center justify-center mr-3">
                        <flux:icon.user-group class="w-5 h-5 text-white" />
                    </div>
                    <flux:heading size="lg">Sales by Teacher</flux:heading>
                </div>

                <div class="overflow-hidden rounded-lg border border-zinc-200 dark:border-zinc-600">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-zinc-600">
                        <thead class="bg-zinc-50 dark:bg-zinc-800">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-zinc-600 dark:text-zinc-300 uppercase tracking-wider first:rounded-tl-lg">Teacher</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-zinc-600 dark:text-zinc-300 uppercase tracking-wider">Email</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-zinc-600 dark:text-zinc-300 uppercase tracking-wider">Total Sales</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-zinc-600 dark:text-zinc-300 uppercase tracking-wider">Revenue</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-zinc-600 dark:text-zinc-300 uppercase tracking-wider">Valid</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-zinc-600 dark:text-zinc-300 uppercase tracking-wider last:rounded-tr-lg">Used</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-zinc-800/50 divide-y divide-gray-200 dark:divide-zinc-700">
                            @forelse ($teacherSales as $teacher)
                            <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-800/75 transition-colors duration-150">
                                <td class="px-6 py-4 whitespace-nowrap font-medium text-zinc-900 dark:text-zinc-100">{{ $teacher->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-zinc-500 dark:text-zinc-400">{{ $teacher->email }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-zinc-900 dark:text-zinc-100 font-medium">{{ number_format($teacher->total_sales) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap font-bold text-green-600 dark:text-green-400">
                                    RM{{ number_format($teacher->revenue, 2) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <flux:badge color="green" class="font-medium">{{ $teacher->valid_count }}</flux:badge>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <flux:badge color="blue" class="font-medium">{{ $teacher->used_count }}</flux:badge>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center first:rounded-bl-lg last:rounded-br-lg">
                                    <div class="flex flex-col items-center">
                                        <div class="w-12 h-12 bg-zinc-100 dark:bg-zinc-600 rounded-full flex items-center justify-center mb-4">
                                            <flux:icon.user-group class="w-6 h-6 text-zinc-400" />
                                        </div>
                                        <flux:text class="text-zinc-500 dark:text-zinc-400 font-medium">No teacher sales data found</flux:text>
                                        <flux:text class="text-sm text-zinc-400 dark:text-zinc-500 mt-1">Try adjusting your filters or check back later</flux:text>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Individual Sales Details -->
        <div class="bg-white dark:bg-zinc-700 overflow-hidden shadow-md hover:shadow-lg sm:rounded-xl transition-all duration-200 border border-zinc-100 dark:border-zinc-600">
            <div class="p-6">
                <div class="flex items-center mb-6">
                    <div class="w-8 h-8 bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-lg flex items-center justify-center mr-3">
                        <flux:icon.clipboard-document-list class="w-5 h-5 text-white" />
                    </div>
                    <flux:heading size="lg">Individual Sales</flux:heading>
                </div>

                <div class="overflow-hidden rounded-lg border border-zinc-200 dark:border-zinc-600">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-zinc-600">
                        <thead class="bg-zinc-50 dark:bg-zinc-800">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-zinc-600 dark:text-zinc-300 uppercase tracking-wider first:rounded-tl-lg">Student</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-zinc-600 dark:text-zinc-300 uppercase tracking-wider">Order ID</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-zinc-600 dark:text-zinc-300 uppercase tracking-wider">Concert</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-zinc-600 dark:text-zinc-300 uppercase tracking-wider">Ticket Type</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-zinc-600 dark:text-zinc-300 uppercase tracking-wider">Price</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-zinc-600 dark:text-zinc-300 uppercase tracking-wider">Sold by</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-zinc-600 dark:text-zinc-300 uppercase tracking-wider">Purchase Date</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-zinc-600 dark:text-zinc-300 uppercase tracking-wider last:rounded-tr-lg">Status</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-zinc-800/50 divide-y divide-gray-200 dark:divide-zinc-700">
                            @forelse ($sales as $sale)
                            <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-800/75 transition-colors duration-150">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div>
                                        <div class="font-medium text-zinc-900 dark:text-zinc-100">{{ $sale->student_name }}</div>
                                        <div class="text-sm text-zinc-500 dark:text-zinc-400">{{ $sale->student_email }}</div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="font-mono text-sm bg-zinc-100 dark:bg-zinc-800 px-2 py-1 rounded">ORD-{{ $sale->order_id }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div>
                                        <div class="font-medium text-zinc-900 dark:text-zinc-100">{{ $sale->concert_title }}</div>
                                        <div class="text-sm text-zinc-500 dark:text-zinc-400">{{ \Carbon\Carbon::parse($sale->concert_date)->format('d/m/Y') }}</div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-zinc-100 text-zinc-800 dark:bg-zinc-800 dark:text-zinc-200">
                                        {{ $sale->ticket_type }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap font-bold text-green-600 dark:text-green-400">RM{{ number_format($sale->price, 2) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-zinc-900 dark:text-zinc-100">{{ $sale->teacher_name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-zinc-500 dark:text-zinc-400">{{ \Carbon\Carbon::parse($sale->purchase_date)->format('d/m/Y | g:i A') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($sale->status === 'used')
                                    <flux:badge color="blue" class="font-medium">Used</flux:badge>
                                    @elseif($sale->status === 'valid')
                                    <flux:badge color="green" class="font-medium">Valid</flux:badge>
                                    @else
                                    <flux:badge color="zinc" class="font-medium">{{ ucfirst($sale->status) }}</flux:badge>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="px-6 py-12 text-center first:rounded-bl-lg last:rounded-br-lg">
                                    <div class="flex flex-col items-center">
                                        <div class="w-12 h-12 bg-zinc-100 dark:bg-zinc-600 rounded-full flex items-center justify-center mb-4">
                                            <flux:icon.clipboard-document-list class="w-6 h-6 text-zinc-400" />
                                        </div>
                                        <flux:text class="text-zinc-500 dark:text-zinc-400 font-medium">No sales found matching your criteria</flux:text>
                                        <flux:text class="text-sm text-zinc-400 dark:text-zinc-500 mt-1">Try adjusting your filters to see more results</flux:text>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-6 flex justify-center">
                    {{ $sales->links() }}
                </div>
            </div>
        </div>
    </div>
</div>