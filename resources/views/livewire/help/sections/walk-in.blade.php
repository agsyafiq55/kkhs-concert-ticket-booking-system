<flux:heading size="xl" class="mb-6 flex items-center">
    <flux:icon.user-group class="w-6 h-6 mr-2 text-rose-500" />
    Walk-in Tickets
</flux:heading>

<div class="prose dark:prose-invert max-w-none">
    <flux:text class="text-lg mb-6 text-zinc-600 dark:text-zinc-400">
        Learn how to generate and manage walk-in tickets for on-site sales during concerts. These tickets allow for last-minute sales to non-students.
    </flux:text>

    <!-- What are Walk-in Tickets -->
    <div class="mb-8">
        <flux:heading size="lg" class="mb-4">Understanding Walk-in Tickets</flux:heading>
        
        <flux:callout color="blue" icon="information-circle">
            <flux:callout.heading>Quick Access</flux:callout.heading>
            <flux:callout.text>
                Navigate to <strong>Walk-in Tickets</strong> from the sidebar menu under "Admin Controls".
            </flux:callout.text>
        </flux:callout>

        <div class="mt-6 space-y-4">
            <div class="border border-zinc-200 dark:border-zinc-600 rounded-lg p-4">
                <flux:text class="font-semibold mb-2">Walk-in Tickets vs Regular Tickets</flux:text>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                    <div>
                        <flux:text class="font-semibold text-blue-600 dark:text-blue-400">Regular Tickets</flux:text>
                        <ul class="space-y-1 mt-2">
                            <li>• Assigned to specific students</li>
                            <li>• Sold in advance by teachers</li>
                            <li>• Payment confirmed before creation</li>
                            <li>• Email confirmations sent</li>
                        </ul>
                    </div>
                    <div>
                        <flux:text class="font-semibold text-orange-600 dark:text-orange-400">Walk-in Tickets</flux:text>
                        <ul class="space-y-1 mt-2">
                            <li>• Pre-generated for on-site sales</li>
                            <li>• Sold to anyone on concert day</li>
                            <li>• Payment collected during scanning</li>
                            <li>• Physical tickets printed in advance</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Generating Walk-in Tickets -->
    <div class="mb-8">
        <flux:heading size="lg" class="mb-4">Generating Walk-in Tickets</flux:heading>
        
        <div class="space-y-6">
            <div class="border border-zinc-200 dark:border-zinc-600 rounded-lg p-6">
                <div class="flex items-center mb-3">
                    <flux:badge color="blue" class="mr-3">1</flux:badge>
                    <flux:text class="font-semibold text-lg">Select Concert and Ticket Type</flux:text>
                </div>
                <flux:text class="mb-3">Choose which concert and ticket type you want to generate walk-in tickets for.</flux:text>
                <div class="bg-zinc-50 dark:bg-zinc-800 p-3 rounded border">
                    <flux:text class="text-sm text-zinc-600 dark:text-zinc-400">
                        <strong>Note:</strong> Only ticket types with remaining availability will be shown.
                    </flux:text>
                </div>
            </div>

            <div class="border border-zinc-200 dark:border-zinc-600 rounded-lg p-6">
                <div class="flex items-center mb-3">
                    <flux:badge color="blue" class="mr-3">2</flux:badge>
                    <flux:text class="font-semibold text-lg">Choose Quantity</flux:text>
                </div>
                <flux:text class="mb-3">Decide how many walk-in tickets to pre-generate (maximum 50 per batch).</flux:text>
                <ul class="space-y-1 text-sm mb-3">
                    <li>• Consider expected walk-in demand</li>
                    <li>• Don't exceed remaining ticket availability</li>
                    <li>• You can generate more later if needed</li>
                </ul>
            </div>

            <div class="border border-zinc-200 dark:border-zinc-600 rounded-lg p-6">
                <div class="flex items-center mb-3">
                    <flux:badge color="blue" class="mr-3">3</flux:badge>
                    <flux:text class="font-semibold text-lg">Generate and Print</flux:text>
                </div>
                <flux:text class="mb-3">Click "Generate Walk-in Tickets" to create them, then print for concert day.</flux:text>
                <div class="bg-green-50 dark:bg-green-900/20 p-3 rounded border border-green-200 dark:border-green-800">
                    <flux:text class="text-sm text-green-800 dark:text-green-200">
                        <strong>Tip:</strong> Print tickets immediately after generation and store them securely until concert day.
                    </flux:text>
                </div>
            </div>
        </div>
    </div>

    <!-- Managing Walk-in Tickets -->
    <div class="mb-8">
        <flux:heading size="lg" class="mb-4">Managing Existing Walk-in Tickets</flux:heading>
        
        <div class="space-y-4">
            <div class="border border-zinc-200 dark:border-zinc-600 rounded-lg p-4">
                <flux:text class="font-semibold mb-3">Status Filtering</flux:text>
                <div class="space-y-2 text-sm">
                    <div class="flex items-center">
                        <flux:badge color="gray" class="mr-3">All</flux:badge>
                        <flux:text>View all walk-in tickets regardless of status</flux:text>
                    </div>
                    <div class="flex items-center">
                        <flux:badge color="blue" class="mr-3">Pre-generated</flux:badge>
                        <flux:text>Tickets created but not yet sold (ready for concert day)</flux:text>
                    </div>
                    <div class="flex items-center">
                        <flux:badge color="green" class="mr-3">Sold</flux:badge>
                        <flux:text>Tickets that have been scanned and payment collected</flux:text>
                    </div>
                    <div class="flex items-center">
                        <flux:badge color="red" class="mr-3">Used</flux:badge>
                        <flux:text>Tickets that have been used for entry validation</flux:text>
                    </div>
                </div>
            </div>

            <div class="border border-zinc-200 dark:border-zinc-600 rounded-lg p-4">
                <flux:text class="font-semibold mb-3">Bulk Printing</flux:text>
                <div class="space-y-2 text-sm">
                    <flux:text>Use the "Print All Walk-in Tickets" button to:</flux:text>
                    <ul class="space-y-1 ml-4 mt-2">
                        <li>• Print all tickets for a specific concert</li>
                        <li>• Get a summary sheet with instructions</li>
                        <li>• Ensure all tickets are ready for concert day</li>
                        <li>• Get properly formatted tickets for cutting</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Concert Day Process -->
    <div class="mb-8">
        <flux:heading size="lg" class="mb-4">Concert Day Process</flux:heading>
        
        <div class="space-y-4">
            <div class="border-l-4 border-orange-500 bg-orange-50 dark:bg-orange-900/20 p-4">
                <flux:text class="font-semibold text-orange-800 dark:text-orange-200 mb-2">Before the Concert</flux:text>
                <ul class="space-y-1 text-sm text-orange-700 dark:text-orange-300">
                    <li>• Print all walk-in tickets and cut them individually</li>
                    <li>• Set up a sales station with a scanner device</li>
                    <li>• Assign staff to handle walk-in sales</li>
                    <li>• Prepare cash box and change</li>
                </ul>
            </div>

            <div class="border-l-4 border-blue-500 bg-blue-50 dark:bg-blue-900/20 p-4">
                <flux:text class="font-semibold text-blue-800 dark:text-blue-200 mb-2">During Sales</flux:text>
                <ul class="space-y-1 text-sm text-blue-700 dark:text-blue-300">
                    <li>• Use the Walk-in Sales Scanner to scan QR codes</li>
                    <li>• Collect exact payment as shown on ticket</li>
                    <li>• Hand physical ticket to customer after scanning</li>
                    <li>• Customer uses same ticket for entry validation</li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Important Notes -->
    <flux:callout color="rose" icon="exclamation-triangle">
        <flux:callout.heading>Important: Two Different Scanners</flux:callout.heading>
        <flux:callout.text>
            <strong>Walk-in Sales Scanner:</strong> Used to process payment for walk-in tickets (marks them as "sold").<br>
            <strong>Entry Scanner:</strong> Used to validate tickets for entry after payment has been completed.<br><br>
            Walk-in tickets must go through BOTH scanners: first for sales, then for entry.
        </flux:callout.text>
    </flux:callout>

    <!-- Best Practices -->
    <div class="mb-8">
        <flux:heading size="lg" class="mb-4">Best Practices</flux:heading>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
            <div class="border border-zinc-200 dark:border-zinc-600 rounded-lg p-4">
                <div class="flex items-center mb-2">
                    <flux:icon.clock class="w-5 h-5 mr-2 text-blue-500" />
                    <flux:text class="font-semibold">Timing</flux:text>
                </div>
                <ul class="space-y-1 text-sm text-zinc-600 dark:text-zinc-400">
                    <li>• Generate walk-in tickets 1-2 days before concert</li>
                    <li>• Don't generate too early (plans may change)</li>
                    <li>• Print immediately after generation</li>
                    <li>• Store securely until concert day</li>
                </ul>
            </div>
            
            <div class="border border-zinc-200 dark:border-zinc-600 rounded-lg p-4">
                <div class="flex items-center mb-2">
                    <flux:icon.calculator class="w-5 h-5 mr-2 text-green-500" />
                    <flux:text class="font-semibold">Quantity Planning</flux:text>
                </div>
                <ul class="space-y-1 text-sm text-zinc-600 dark:text-zinc-400">
                    <li>• Start with 10-20% of remaining capacity</li>
                    <li>• Generate more if demand is higher</li>
                    <li>• Better to have extra than run out</li>
                    <li>• Unused tickets can be deleted later</li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Troubleshooting -->
    <div class="mb-8">
        <flux:heading size="lg" class="mb-4">Common Issues</flux:heading>
        
        <div class="space-y-4">
            <div class="border border-zinc-200 dark:border-zinc-600 rounded-lg p-4">
                <flux:text class="font-semibold mb-2">Cannot generate walk-in tickets</flux:text>
                <flux:text class="text-sm text-zinc-600 dark:text-zinc-400">
                    This usually means all tickets for that type are sold out. Check ticket availability in the Tickets section.
                </flux:text>
            </div>

            <div class="border border-zinc-200 dark:border-zinc-600 rounded-lg p-4">
                <flux:text class="font-semibold mb-2">Walk-in ticket won't scan for entry</flux:text>
                <flux:text class="text-sm text-zinc-600 dark:text-zinc-400">
                    The ticket must be scanned with the Walk-in Sales Scanner first to collect payment. Only then can it be used for entry.
                </flux:text>
            </div>

            <div class="border border-zinc-200 dark:border-zinc-600 rounded-lg p-4">
                <flux:text class="font-semibold mb-2">Too many walk-in tickets generated</flux:text>
                <flux:text class="text-sm text-zinc-600 dark:text-zinc-400">
                    You can delete unsold walk-in tickets after the concert. Contact support if you need to delete sold tickets.
                </flux:text>
            </div>
        </div>
    </div>
</div> 