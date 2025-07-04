<flux:heading size="xl" class="mb-6 flex items-center">
    <flux:icon.currency-dollar class="w-6 h-6 mr-2 text-rose-500" />
    Walk-in Sales Scanner
</flux:heading>

<div class="prose dark:prose-invert max-w-none">
    <flux:text class="text-lg mb-6 text-zinc-600 dark:text-zinc-400">
        Learn how to use the Walk-in Sales Scanner to process payments for pre-generated walk-in tickets during concert events.
    </flux:text>

    <!-- Quick Access -->
    <div class="mb-8">
        <flux:heading size="lg" class="mb-4">Getting Started</flux:heading>
        
        <flux:callout color="blue" icon="information-circle">
            <flux:callout.heading>Quick Access</flux:callout.heading>
            <flux:callout.text>
                Navigate to <strong>Walk-in Sales Scanner</strong> from the sidebar menu under "Used for during Concert Day".
            </flux:callout.text>
        </flux:callout>

        <div class="mt-6">
            <div class="border border-zinc-200 dark:border-zinc-600 rounded-lg p-4">
                <flux:text class="font-semibold mb-2">What This Scanner Does</flux:text>
                <ul class="space-y-1 text-sm">
                    <li>• Processes payment for pre-generated walk-in tickets</li>
                    <li>• Marks tickets as "sold" after payment collection</li>
                    <li>• Validates that tickets are legitimate walk-in tickets</li>
                    <li>• Records transaction details for sales tracking</li>
                    <li>• Prepares tickets for later entry validation</li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Important Distinction -->
    <flux:callout color="rose" icon="exclamation-triangle">
        <flux:callout.heading>Important: Walk-in Sales vs Entry Scanner</flux:callout.heading>
        <flux:callout.text>
            <strong>Walk-in Sales Scanner:</strong> Used to collect payment and mark walk-in tickets as "sold" (this page).<br>
            <strong>Entry Scanner:</strong> Used to validate tickets for entry after payment has been confirmed.<br><br>
            Walk-in tickets require BOTH steps: first payment processing here, then entry validation at the door.
        </flux:callout.text>
    </flux:callout>

    <!-- Step-by-Step Process -->
    <div class="mb-8">
        <flux:heading size="lg" class="mb-4">Walk-in Sales Process</flux:heading>
        
        <div class="space-y-6">
            <div class="border border-zinc-200 dark:border-zinc-600 rounded-lg p-6">
                <div class="flex items-center mb-3">
                    <flux:badge color="blue" class="mr-3">1</flux:badge>
                    <flux:text class="font-semibold text-lg">Set Up Sales Station</flux:text>
                </div>
                <flux:text class="mb-3">Prepare your sales area before customers arrive.</flux:text>
                <ul class="space-y-1 text-sm mb-3">
                    <li>• Have pre-printed walk-in tickets ready</li>
                    <li>• Ensure scanner device is working and charged</li>
                    <li>• Prepare cash box with change</li>
                    <li>• Have receipt book or payment tracking method</li>
                </ul>
            </div>

            <div class="border border-zinc-200 dark:border-zinc-600 rounded-lg p-6">
                <div class="flex items-center mb-3">
                    <flux:badge color="blue" class="mr-3">2</flux:badge>
                    <flux:text class="font-semibold text-lg">Customer Approaches</flux:text>
                </div>
                <flux:text class="mb-3">When a customer wants to buy tickets:</flux:text>
                <ul class="space-y-1 text-sm mb-3">
                    <li>• Show them available ticket types and prices</li>
                    <li>• Let them choose the type and quantity they want</li>
                    <li>• Select appropriate physical tickets from your stack</li>
                    <li>• Calculate total amount due</li>
                </ul>
            </div>

            <div class="border border-zinc-200 dark:border-zinc-600 rounded-lg p-6">
                <div class="flex items-center mb-3">
                    <flux:badge color="blue" class="mr-3">3</flux:badge>
                    <flux:text class="font-semibold text-lg">Collect Payment</flux:text>
                </div>
                <flux:text class="mb-3">Process payment before scanning the tickets.</flux:text>
                <div class="bg-rose-50 dark:bg-rose-900/20 p-3 rounded border border-rose-200 dark:border-rose-800">
                    <flux:text class="text-sm text-rose-800 dark:text-rose-200">
                        <strong>Important:</strong> Always collect full payment before scanning any QR codes. This ensures proper transaction tracking.
                    </flux:text>
                </div>
            </div>

            <div class="border border-zinc-200 dark:border-zinc-600 rounded-lg p-6">
                <div class="flex items-center mb-3">
                    <flux:badge color="blue" class="mr-3">4</flux:badge>
                    <flux:text class="font-semibold text-lg">Scan QR Codes</flux:text>
                </div>
                <flux:text class="mb-3">After payment is received, scan each ticket's QR code.</flux:text>
                <ul class="space-y-1 text-sm mb-3">
                    <li>• Scan one ticket at a time</li>
                    <li>• Wait for confirmation before scanning the next</li>
                    <li>• Each successful scan marks that ticket as "sold"</li>
                    <li>• Handle any scanning errors appropriately</li>
                </ul>
            </div>

            <div class="border border-zinc-200 dark:border-zinc-600 rounded-lg p-6">
                <div class="flex items-center mb-3">
                    <flux:badge color="blue" class="mr-3">5</flux:badge>
                    <flux:text class="font-semibold text-lg">Hand Over Tickets</flux:text>
                </div>
                <flux:text class="mb-3">After successful scanning, give tickets to the customer.</flux:text>
                <ul class="space-y-1 text-sm mb-3">
                    <li>• Provide the physical tickets to the customer</li>
                    <li>• Explain they need these same tickets for entry</li>
                    <li>• Mention the entry process and timing</li>
                    <li>• Provide any additional event information</li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Scanner Results -->
    <div class="mb-8">
        <flux:heading size="lg" class="mb-4">Understanding Scanner Results</flux:heading>
        
        <div class="space-y-4">
            <div class="border-l-4 border-green-500 bg-green-50 dark:bg-green-900/20 p-4">
                <div class="flex items-center mb-2">
                    <flux:badge color="green" class="mr-2">✓</flux:badge>
                    <flux:text class="font-semibold text-green-800 dark:text-green-200">Payment Processed Successfully</flux:text>
                </div>
                <flux:text class="text-green-700 dark:text-green-300">
                    <strong>Action:</strong> Hand the physical ticket to the customer. The ticket is now ready for entry validation.
                </flux:text>
            </div>

            <div class="border-l-4 border-red-500 bg-red-50 dark:bg-red-900/20 p-4">
                <div class="flex items-center mb-2">
                    <flux:badge color="red" class="mr-2">✗</flux:badge>
                    <flux:text class="font-semibold text-red-800 dark:text-red-200">Invalid QR Code</flux:text>
                </div>
                <flux:text class="text-red-700 dark:text-red-300">
                    <strong>Action:</strong> This is not a valid walk-in ticket QR code. Do not accept payment and select a different ticket.
                </flux:text>
            </div>

            <div class="border-l-4 border-yellow-500 bg-yellow-50 dark:bg-yellow-900/20 p-4">
                <div class="flex items-center mb-2">
                    <flux:badge color="amber" class="mr-2">!</flux:badge>
                    <flux:text class="font-semibold text-yellow-800 dark:text-yellow-200">Already Sold</flux:text>
                </div>
                <flux:text class="text-yellow-700 dark:text-yellow-300">
                    <strong>Action:</strong> This ticket has already been sold. Select a different unsold ticket from your stack.
                </flux:text>
            </div>

            <div class="border-l-4 border-orange-500 bg-orange-50 dark:bg-orange-900/20 p-4">
                <div class="flex items-center mb-2">
                    <flux:badge color="orange" class="mr-2">W</flux:badge>
                    <flux:text class="font-semibold text-orange-800 dark:text-orange-200">Not a Walk-in Ticket</flux:text>
                </div>
                <flux:text class="text-orange-700 dark:text-orange-300">
                    <strong>Action:</strong> This appears to be a regular student ticket. Refer to the Entry Scanner for validation.
                </flux:text>
            </div>
        </div>
    </div>

    <!-- Best Practices -->
    <div class="mb-8">
        <flux:heading size="lg" class="mb-4">Best Practices for Walk-in Sales</flux:heading>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
            <div class="border border-zinc-200 dark:border-zinc-600 rounded-lg p-4">
                <div class="flex items-center mb-2">
                    <flux:icon.banknotes class="w-5 h-5 mr-2 text-green-500" />
                    <flux:text class="font-semibold">Payment Handling</flux:text>
                </div>
                <ul class="space-y-1 text-sm text-zinc-600 dark:text-zinc-400">
                    <li>• Always collect exact payment when possible</li>
                    <li>• Keep change ready for larger bills</li>
                    <li>• Count money carefully and double-check</li>
                    <li>• Keep payment records organized</li>
                </ul>
            </div>
            
            <div class="border border-zinc-200 dark:border-zinc-600 rounded-lg p-4">
                <div class="flex items-center mb-2">
                    <flux:icon.ticket class="w-5 h-5 mr-2 text-blue-500" />
                    <flux:text class="font-semibold">Ticket Management</flux:text>
                </div>
                <ul class="space-y-1 text-sm text-zinc-600 dark:text-zinc-400">
                    <li>• Keep tickets organized by type and price</li>
                    <li>• Track which tickets have been sold</li>
                    <li>• Separate sold and unsold tickets clearly</li>
                    <li>• Have backup tickets available</li>
                </ul>
            </div>

            <div class="border border-zinc-200 dark:border-zinc-600 rounded-lg p-4">
                <div class="flex items-center mb-2">
                    <flux:icon.users class="w-5 h-5 mr-2 text-purple-500" />
                    <flux:text class="font-semibold">Customer Service</flux:text>
                </div>
                <ul class="space-y-1 text-sm text-zinc-600 dark:text-zinc-400">
                    <li>• Explain the entry process clearly</li>
                    <li>• Provide concert timing and location details</li>
                    <li>• Be patient with payment processing</li>
                    <li>• Answer questions about the event</li>
                </ul>
            </div>

            <div class="border border-zinc-200 dark:border-zinc-600 rounded-lg p-4">
                <div class="flex items-center mb-2">
                    <flux:icon.shield-check class="w-5 h-5 mr-2 text-rose-500" />
                    <flux:text class="font-semibold">Security</flux:text>
                </div>
                <ul class="space-y-1 text-sm text-zinc-600 dark:text-zinc-400">
                    <li>• Keep cash secure and organized</li>
                    <li>• Don't leave the sales station unattended</li>
                    <li>• Verify QR codes before accepting payment</li>
                    <li>• Report any suspicious activities</li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Troubleshooting -->
    <div class="mb-8">
        <flux:heading size="lg" class="mb-4">Common Issues and Solutions</flux:heading>
        
        <div class="space-y-4">
            <div class="border border-zinc-200 dark:border-zinc-600 rounded-lg p-4">
                <flux:text class="font-semibold mb-2">Scanner not reading QR codes</flux:text>
                <flux:text class="text-sm text-zinc-600 dark:text-zinc-400">
                    Ensure good lighting, clean camera lens, and hold the device steady. Try increasing screen brightness if using a phone scanner.
                </flux:text>
            </div>

            <div class="border border-zinc-200 dark:border-zinc-600 rounded-lg p-4">
                <flux:text class="font-semibold mb-2">Customer wants refund after scanning</flux:text>
                <flux:text class="text-sm text-zinc-600 dark:text-zinc-400">
                    Once a ticket is scanned as sold, contact an administrator for refund processing. The system tracks all transactions for accountability.
                </flux:text>
            </div>

            <div class="border border-zinc-200 dark:border-zinc-600 rounded-lg p-4">
                <flux:text class="font-semibold mb-2">Running out of tickets</flux:text>
                <flux:text class="text-sm text-zinc-600 dark:text-zinc-400">
                    Check with administrators about generating more walk-in tickets if demand is higher than expected.
                </flux:text>
            </div>

            <div class="border border-zinc-200 dark:border-zinc-600 rounded-lg p-4">
                <flux:text class="font-semibold mb-2">Payment disputes</flux:text>
                <flux:text class="text-sm text-zinc-600 dark:text-zinc-400">
                    Keep clear records of all transactions. If disputes arise, refer to your payment tracking method and contact administrators.
                </flux:text>
            </div>
        </div>
    </div>

    <!-- End of Day Process -->
    <div class="mb-8">
        <flux:heading size="lg" class="mb-4">End of Sales Process</flux:heading>
        
        <div class="border border-zinc-200 dark:border-zinc-600 rounded-lg p-4">
            <flux:text class="font-semibold mb-3">After Concert Sales End</flux:text>
            <div class="space-y-2 text-sm">
                <flux:text>When walk-in sales are complete:</flux:text>
                <ul class="space-y-1 ml-4 mt-2">
                    <li>• Count total cash collected and verify against sales records</li>
                    <li>• Collect any unsold tickets for proper disposal</li>
                    <li>• Report total sales numbers to administrators</li>
                    <li>• Turn in cash box according to school procedures</li>
                    <li>• Note any issues or feedback for future events</li>
                </ul>
            </div>
        </div>
    </div>
</div> 