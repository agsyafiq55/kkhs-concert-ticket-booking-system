<flux:heading size="xl" class="mb-6 flex items-center">
    <flux:icon.user-plus class="w-6 h-6 mr-2 text-rose-500" />
    Selling Tickets to Students
</flux:heading>

<div class="prose dark:prose-invert max-w-none">
    <flux:text class="text-lg mb-6 text-zinc-600 dark:text-zinc-400">
        Learn how to assign tickets to students using the cart-based system. This process handles payment confirmation and automatically sends email notifications.
    </flux:text>

    <!-- Accessing Ticket Sales -->
    <div class="mb-8">
        <flux:heading size="lg" class="mb-4">Getting Started</flux:heading>
        
        <flux:callout color="blue" icon="information-circle">
            <flux:callout.heading>Quick Access</flux:callout.heading>
            <flux:callout.text>
                Navigate to <strong>Sell Tickets</strong> from the sidebar menu under "Ticket Sales & Management".
            </flux:callout.text>
        </flux:callout>

        <div class="mt-6">
            <div class="border border-zinc-200 dark:border-zinc-600 rounded-lg p-4">
                <flux:text class="font-semibold mb-2">What This Page Does</flux:text>
                <ul class="space-y-1 text-sm">
                    <li>• Assign tickets to students after receiving payment</li>
                    <li>• Support multiple tickets per transaction</li>
                    <li>• Automatically generate QR codes for each ticket</li>
                    <li>• Send email confirmations with ticket details</li>
                    <li>• Track student purchase history</li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Step-by-Step Process -->
    <div class="mb-8">
        <flux:heading size="lg" class="mb-4">Step-by-Step Guide</flux:heading>
        
        <div class="space-y-6">
            <div class="border border-zinc-200 dark:border-zinc-600 rounded-lg p-6">
                <div class="flex items-center mb-3">
                    <flux:badge color="blue" class="mr-3">1</flux:badge>
                    <flux:text class="font-semibold text-lg">Find the Student</flux:text>
                </div>
                <flux:text class="mb-3">Use the search box to find the student by name or email.</flux:text>
                <div class="bg-zinc-50 dark:bg-zinc-800 p-3 rounded border">
                    <flux:text class="text-sm text-zinc-600 dark:text-zinc-400">
                        <strong>Tip:</strong> You can search by partial names or email addresses. The search is case-insensitive.
                    </flux:text>
                </div>
            </div>

            <div class="border border-zinc-200 dark:border-zinc-600 rounded-lg p-6">
                <div class="flex items-center mb-3">
                    <flux:badge color="blue" class="mr-3">2</flux:badge>
                    <flux:text class="font-semibold text-lg">Select Concert and Tickets</flux:text>
                </div>
                <flux:text class="mb-3">Choose a concert from the dropdown to see available ticket types.</flux:text>
                <ul class="space-y-1 text-sm mb-3">
                    <li>• Only concerts with available tickets are shown</li>
                    <li>• Each ticket type shows remaining quantity</li>
                    <li>• Prices are displayed clearly for each type</li>
                </ul>
                <div class="bg-zinc-50 dark:bg-zinc-800 p-3 rounded border">
                    <flux:text class="text-sm text-zinc-600 dark:text-zinc-400">
                        <strong>Note:</strong> If no tickets appear, either the concert is sold out or no tickets have been created yet.
                    </flux:text>
                </div>
            </div>

            <div class="border border-zinc-200 dark:border-zinc-600 rounded-lg p-6">
                <div class="flex items-center mb-3">
                    <flux:badge color="blue" class="mr-3">3</flux:badge>
                    <flux:text class="font-semibold text-lg">Add to Cart</flux:text>
                </div>
                <flux:text class="mb-3">Select quantity and add tickets to the cart. You can add multiple ticket types.</flux:text>
                <ul class="space-y-1 text-sm mb-3">
                    <li>• Cart shows running total of all items</li>
                    <li>• You can remove items before finalizing</li>
                    <li>• Maximum quantity per item depends on availability</li>
                </ul>
            </div>

            <div class="border border-zinc-200 dark:border-zinc-600 rounded-lg p-6">
                <div class="flex items-center mb-3">
                    <flux:badge color="blue" class="mr-3">4</flux:badge>
                    <flux:text class="font-semibold text-lg">Confirm Payment</flux:text>
                </div>
                <flux:text class="mb-3">Verify the total amount and confirm payment has been received.</flux:text>
                <div class="bg-rose-50 dark:bg-rose-900/20 p-3 rounded border border-rose-200 dark:border-rose-800">
                    <flux:text class="text-sm text-rose-800 dark:text-rose-200">
                        <strong>Important:</strong> Only check "Payment received" after you have actually collected the money from the student.
                    </flux:text>
                </div>
            </div>
        </div>
    </div>

    <!-- Payment Handling -->
    <div class="mb-8">
        <flux:heading size="lg" class="mb-4">Payment Best Practices</flux:heading>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
            <div class="border border-zinc-200 dark:border-zinc-600 rounded-lg p-4">
                <div class="flex items-center mb-2">
                    <flux:icon.banknotes class="w-5 h-5 mr-2 text-green-500" />
                    <flux:text class="font-semibold">Cash Payments</flux:text>
                </div>
                <ul class="space-y-1 text-sm text-zinc-600 dark:text-zinc-400">
                    <li>• Count money carefully before confirming</li>
                    <li>• Provide change if necessary</li>
                    <li>• Keep receipts organized</li>
                    <li>• Record payment in school systems if required</li>
                </ul>
            </div>
            
            <div class="border border-zinc-200 dark:border-zinc-600 rounded-lg p-4">
                <div class="flex items-center mb-2">
                    <flux:icon.credit-card class="w-5 h-5 mr-2 text-blue-500" />
                    <flux:text class="font-semibold">Other Payment Methods</flux:text>
                </div>
                <ul class="space-y-1 text-sm text-zinc-600 dark:text-zinc-400">
                    <li>• Bank transfers (verify before confirming)</li>
                    <li>• Online payments (check account balance)</li>
                    <li>• Cheques (ensure they clear first)</li>
                    <li>• Follow school payment policies</li>
                </ul>
            </div>
        </div>
    </div>

    <!-- After Purchase -->
    <div class="mb-8">
        <flux:heading size="lg" class="mb-4">After Successful Purchase</flux:heading>
        
        <div class="space-y-4">
            <div class="border-l-4 border-green-500 bg-green-50 dark:bg-green-900/20 p-4">
                <flux:text class="font-semibold text-green-800 dark:text-green-200 mb-2">Automatic Actions</flux:text>
                <ul class="space-y-1 text-sm text-green-700 dark:text-green-300">
                    <li>• QR codes are generated for each ticket</li>
                    <li>• Email confirmation is sent to the student</li>
                    <li>• Tickets appear immediately in student's "My Tickets"</li>
                    <li>• Purchase is recorded in sales reports</li>
                </ul>
            </div>

            <div class="border border-zinc-200 dark:border-zinc-600 rounded-lg p-4">
                <flux:text class="font-semibold mb-3">What Students Receive</flux:text>
                <div class="space-y-2 text-sm">
                    <flux:text>Each student automatically gets:</flux:text>
                    <ul class="space-y-1 ml-4 mt-2">
                        <li>• Email with ticket details and QR codes</li>
                        <li>• Access to printable tickets</li>
                        <li>• Unique Order ID for tracking</li>
                        <li>• Concert information and instructions</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Managing Student History -->
    <div class="mb-8">
        <flux:heading size="lg" class="mb-4">Viewing Student Purchase History</flux:heading>
        
        <div class="border border-zinc-200 dark:border-zinc-600 rounded-lg p-4">
            <flux:text class="font-semibold mb-3">Student Information Panel</flux:text>
            <div class="space-y-2 text-sm">
                <flux:text>When you select a student, you can see:</flux:text>
                <ul class="space-y-1 ml-4 mt-2">
                    <li>• All previous ticket purchases</li>
                    <li>• Concert names and dates</li>
                    <li>• Ticket types and status</li>
                    <li>• Purchase dates for tracking</li>
                </ul>
                <flux:text class="mt-3 text-zinc-600 dark:text-zinc-400">
                    This helps you avoid duplicate sales and track student participation.
                </flux:text>
            </div>
        </div>
    </div>

    <!-- Troubleshooting -->
    <div class="mb-8">
        <flux:heading size="lg" class="mb-4">Common Issues and Solutions</flux:heading>
        
        <div class="space-y-4">
            <div class="border border-zinc-200 dark:border-zinc-600 rounded-lg p-4">
                <flux:text class="font-semibold mb-2">Student not found in search</flux:text>
                <flux:text class="text-sm text-zinc-600 dark:text-zinc-400">
                    The student may not have an account yet. Contact an administrator to create the student account first.
                </flux:text>
            </div>

            <div class="border border-zinc-200 dark:border-zinc-600 rounded-lg p-4">
                <flux:text class="font-semibold mb-2">No tickets available for concert</flux:text>
                <flux:text class="text-sm text-zinc-600 dark:text-zinc-400">
                    Either all tickets are sold out, or no ticket types have been created. Check with administrators about ticket availability.
                </flux:text>
            </div>

            <div class="border border-zinc-200 dark:border-zinc-600 rounded-lg p-4">
                <flux:text class="font-semibold mb-2">Email not sent to student</flux:text>
                <flux:text class="text-sm text-zinc-600 dark:text-zinc-400">
                    Email issues are usually temporary. The tickets are still valid and can be accessed through "My Tickets". Ask students to check spam folders.
                </flux:text>
            </div>
        </div>
    </div>

    <!-- Important Notes -->
    <flux:callout color="rose" icon="exclamation-triangle">
        <flux:callout.heading>Important Reminders</flux:callout.heading>
        <flux:callout.text>
            <strong>Payment First:</strong> Always collect payment before confirming the purchase in the system.<br><br>
            <strong>Double-Check Details:</strong> Verify student name and ticket details before finalizing the purchase.<br><br>
            <strong>Order IDs:</strong> Note the Order ID for each transaction in case you need to reference it later.
        </flux:callout.text>
    </flux:callout>
</div> 