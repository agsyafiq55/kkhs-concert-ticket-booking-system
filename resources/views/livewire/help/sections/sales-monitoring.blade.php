<flux:heading size="xl" class="mb-6 flex items-center">
    <flux:icon.chart-bar class="w-6 h-6 mr-2 text-rose-500" />
    Sales Monitoring
</flux:heading>

<div class="prose dark:prose-invert max-w-none">
    <flux:text class="text-lg mb-6 text-zinc-600 dark:text-zinc-400">
        Monitor ticket sales performance, track revenue, and generate detailed reports for analysis and record-keeping.
    </flux:text>

    <!-- Accessing Sales Monitoring -->
    <div class="mb-8">
        <flux:heading size="lg" class="mb-4">Getting Started</flux:heading>
        
        <flux:callout color="blue" icon="information-circle">
            <flux:callout.heading>Quick Access</flux:callout.heading>
            <flux:callout.text>
                Navigate to <strong>Ticket Sales</strong> from the sidebar menu under "Admin Controls".
            </flux:callout.text>
        </flux:callout>

        <div class="mt-6">
            <div class="border border-zinc-200 dark:border-zinc-600 rounded-lg p-4">
                <flux:text class="font-semibold mb-2">What You Can Monitor</flux:text>
                <ul class="space-y-1 text-sm">
                    <li>• Real-time sales statistics and revenue</li>
                    <li>• Individual ticket purchases and status</li>
                    <li>• Teacher performance and sales activity</li>
                    <li>• Concert-specific sales breakdowns</li>
                    <li>• Revenue by ticket type and date range</li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Sales Dashboard Overview -->
    <div class="mb-8">
        <flux:heading size="lg" class="mb-4">Sales Dashboard Overview</flux:heading>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
            <div class="border border-zinc-200 dark:border-zinc-600 rounded-lg p-4">
                <div class="flex items-center mb-2">
                    <flux:icon.currency-dollar class="w-5 h-5 mr-2 text-green-500" />
                    <flux:text class="font-semibold">Revenue Tracking</flux:text>
                </div>
                <flux:text class="text-sm text-zinc-600 dark:text-zinc-400">
                    View total revenue, sales counts, and ticket status breakdowns in real-time.
                </flux:text>
            </div>
            
            <div class="border border-zinc-200 dark:border-zinc-600 rounded-lg p-4">
                <div class="flex items-center mb-2">
                    <flux:icon.users class="w-5 h-5 mr-2 text-blue-500" />
                    <flux:text class="font-semibold">Teacher Performance</flux:text>
                </div>
                <flux:text class="text-sm text-zinc-600 dark:text-zinc-400">
                    Track which teachers are selling the most tickets and generating the highest revenue.
                </flux:text>
            </div>

            <div class="border border-zinc-200 dark:border-zinc-600 rounded-lg p-4">
                <div class="flex items-center mb-2">
                    <flux:icon.musical-note class="w-5 h-5 mr-2 text-purple-500" />
                    <flux:text class="font-semibold">Concert Analytics</flux:text>
                </div>
                <flux:text class="text-sm text-zinc-600 dark:text-zinc-400">
                    Compare performance across different concerts and track demand patterns.
                </flux:text>
            </div>

            <div class="border border-zinc-200 dark:border-zinc-600 rounded-lg p-4">
                <div class="flex items-center mb-2">
                    <flux:icon.clock class="w-5 h-5 mr-2 text-orange-500" />
                    <flux:text class="font-semibold">Time-based Analysis</flux:text>
                </div>
                <flux:text class="text-sm text-zinc-600 dark:text-zinc-400">
                    Filter sales by date ranges to analyze trends and peak selling periods.
                </flux:text>
            </div>
        </div>
    </div>

    <!-- Using Filters and Search -->
    <div class="mb-8">
        <flux:heading size="lg" class="mb-4">Filtering and Searching Sales Data</flux:heading>
        
        <div class="space-y-4">
            <div class="border border-zinc-200 dark:border-zinc-600 rounded-lg p-4">
                <flux:text class="font-semibold mb-3">Available Filters</flux:text>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                    <div>
                        <flux:text class="font-semibold text-blue-600 dark:text-blue-400">Concert Filter</flux:text>
                        <flux:text class="text-zinc-600 dark:text-zinc-400">View sales for a specific concert only</flux:text>
                    </div>
                    <div>
                        <flux:text class="font-semibold text-green-600 dark:text-green-400">Teacher Filter</flux:text>
                        <flux:text class="text-zinc-600 dark:text-zinc-400">See sales by a particular teacher</flux:text>
                    </div>
                    <div>
                        <flux:text class="font-semibold text-orange-600 dark:text-orange-400">Status Filter</flux:text>
                        <flux:text class="text-zinc-600 dark:text-zinc-400">Filter by ticket status (Valid, Used, Cancelled)</flux:text>
                    </div>
                    <div>
                        <flux:text class="font-semibold text-purple-600 dark:text-purple-400">Date Range</flux:text>
                        <flux:text class="text-zinc-600 dark:text-zinc-400">Select custom date ranges for analysis</flux:text>
                    </div>
                </div>
            </div>

            <div class="border border-zinc-200 dark:border-zinc-600 rounded-lg p-4">
                <flux:text class="font-semibold mb-3">Search Functionality</flux:text>
                <div class="space-y-2 text-sm">
                    <flux:text>Use the search box to find specific:</flux:text>
                    <ul class="space-y-1 ml-4 mt-2">
                        <li>• Student names or email addresses</li>
                        <li>• Order IDs for tracking specific purchases</li>
                        <li>• Concert titles</li>
                        <li>• Teacher names</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Understanding Sales Statistics -->
    <div class="mb-8">
        <flux:heading size="lg" class="mb-4">Reading Sales Statistics</flux:heading>
        
        <div class="space-y-4">
            <div class="border border-zinc-200 dark:border-zinc-600 rounded-lg p-4">
                <flux:text class="font-semibold mb-3">Key Metrics Explained</flux:text>
                <div class="space-y-3 text-sm">
                    <div class="flex items-center justify-between p-2 bg-zinc-50 dark:bg-zinc-800 rounded">
                        <flux:text class="font-semibold">Total Revenue:</flux:text>
                        <flux:text>Sum of all confirmed ticket sales (includes both regular and walk-in tickets)</flux:text>
                    </div>
                    <div class="flex items-center justify-between p-2 bg-zinc-50 dark:bg-zinc-800 rounded">
                        <flux:text class="font-semibold">Total Sales:</flux:text>
                        <flux:text>Number of individual tickets sold</flux:text>
                    </div>
                    <div class="flex items-center justify-between p-2 bg-zinc-50 dark:bg-zinc-800 rounded">
                        <flux:text class="font-semibold">Valid Tickets:</flux:text>
                        <flux:text>Tickets sold but not yet used for entry</flux:text>
                    </div>
                    <div class="flex items-center justify-between p-2 bg-zinc-50 dark:bg-zinc-800 rounded">
                        <flux:text class="font-semibold">Used Tickets:</flux:text>
                        <flux:text>Tickets scanned for entry (actual attendance)</flux:text>
                    </div>
                </div>
            </div>

            <div class="border border-zinc-200 dark:border-zinc-600 rounded-lg p-4">
                <flux:text class="font-semibold mb-3">Concert Revenue Breakdown</flux:text>
                <flux:text class="text-sm text-zinc-600 dark:text-zinc-400">
                    The concert revenue section shows total earnings per concert, helping you identify your most successful events and optimal pricing strategies.
                </flux:text>
            </div>
        </div>
    </div>

    <!-- Export and Reporting -->
    <div class="mb-8">
        <flux:heading size="lg" class="mb-4">Export and Reporting</flux:heading>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
            <div class="border border-zinc-200 dark:border-zinc-600 rounded-lg p-4">
                <div class="flex items-center mb-2">
                    <flux:icon.document-text class="w-5 h-5 mr-2 text-green-500" />
                    <flux:text class="font-semibold">CSV Export</flux:text>
                </div>
                <flux:text class="text-sm text-zinc-600 dark:text-zinc-400 mb-2">
                    Export filtered data to CSV for analysis in Excel or other tools.
                </flux:text>
                <ul class="space-y-1 text-xs text-zinc-500 dark:text-zinc-400">
                    <li>• Includes all visible columns</li>
                    <li>• Respects current filters</li>
                    <li>• Compatible with Excel and Google Sheets</li>
                </ul>
            </div>
            
            <div class="border border-zinc-200 dark:border-zinc-600 rounded-lg p-4">
                <div class="flex items-center mb-2">
                    <flux:icon.printer class="w-5 h-5 mr-2 text-blue-500" />
                    <flux:text class="font-semibold">PDF Reports</flux:text>
                </div>
                <flux:text class="text-sm text-zinc-600 dark:text-zinc-400 mb-2">
                    Generate professional PDF reports for presentations and record-keeping.
                </flux:text>
                <ul class="space-y-1 text-xs text-zinc-500 dark:text-zinc-400">
                    <li>• Professional formatting</li>
                    <li>• Includes summary statistics</li>
                    <li>• Ready for printing or sharing</li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Analyzing Sales Trends -->
    <div class="mb-8">
        <flux:heading size="lg" class="mb-4">Analyzing Sales Trends</flux:heading>
        
        <div class="space-y-4">
            <div class="border-l-4 border-blue-500 bg-blue-50 dark:bg-blue-900/20 p-4">
                <flux:text class="font-semibold text-blue-800 dark:text-blue-200 mb-2">Peak Sales Periods</flux:text>
                <ul class="space-y-1 text-sm text-blue-700 dark:text-blue-300">
                    <li>• Use date filters to identify when most tickets are sold</li>
                    <li>• Plan marketing efforts around these peak periods</li>
                    <li>• Ensure adequate teacher availability during busy times</li>
                </ul>
            </div>

            <div class="border-l-4 border-green-500 bg-green-50 dark:bg-green-900/20 p-4">
                <flux:text class="font-semibold text-green-800 dark:text-green-200 mb-2">Teacher Performance</flux:text>
                <ul class="space-y-1 text-sm text-green-700 dark:text-green-300">
                    <li>• Identify top-performing teachers for recognition</li>
                    <li>• Provide additional support to teachers with lower sales</li>
                    <li>• Balance ticket allocation based on performance</li>
                </ul>
            </div>

            <div class="border-l-4 border-purple-500 bg-purple-50 dark:bg-purple-900/20 p-4">
                <flux:text class="font-semibold text-purple-800 dark:text-purple-200 mb-2">Ticket Type Preferences</flux:text>
                <ul class="space-y-1 text-sm text-purple-700 dark:text-purple-300">
                    <li>• Analyze which ticket types sell fastest</li>
                    <li>• Adjust pricing and quantities for future events</li>
                    <li>• Understand customer preferences and demands</li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Real-time Monitoring -->
    <div class="mb-8">
        <flux:heading size="lg" class="mb-4">Real-time Monitoring</flux:heading>
        
        <div class="border border-zinc-200 dark:border-zinc-600 rounded-lg p-4">
            <flux:text class="font-semibold mb-3">Live Updates</flux:text>
            <div class="space-y-2 text-sm">
                <flux:text>The sales monitoring page automatically updates to show:</flux:text>
                <ul class="space-y-1 ml-4 mt-2">
                    <li>• New ticket purchases as they happen</li>
                    <li>• Updated revenue calculations</li>
                    <li>• Changes in ticket status (valid → used)</li>
                    <li>• Walk-in sales as they're processed</li>
                </ul>
                <flux:text class="mt-3 text-zinc-600 dark:text-zinc-400">
                    This allows you to monitor concert day activities and sales performance in real-time.
                </flux:text>
            </div>
        </div>
    </div>

    <!-- Important Notes -->
    <flux:callout color="rose" icon="exclamation-triangle">
        <flux:callout.heading>Important Considerations</flux:callout.heading>
        <flux:callout.text>
            <strong>Walk-in Tickets:</strong> Only walk-in tickets that have been "sold" (payment processed) are included in revenue calculations.<br><br>
            <strong>Cancelled Tickets:</strong> Cancelled tickets are excluded from revenue but may still appear in counts for tracking purposes.<br><br>
            <strong>Data Accuracy:</strong> All statistics are calculated in real-time based on current database values.
        </flux:callout.text>
    </flux:callout>
</div> 