<flux:heading size="xl" class="mb-6 flex items-center">
    <flux:icon.ticket class="w-6 h-6 mr-2 text-rose-500" />
    Managing Tickets
</flux:heading>

<div class="prose dark:prose-invert max-w-none">
    <flux:text class="text-lg mb-6 text-zinc-600 dark:text-zinc-400">
        Learn how to create and manage different ticket types for your concerts. Tickets define pricing, availability, and categorization for your events.
    </flux:text>

    <!-- Creating Tickets -->
    <div class="mb-8">
        <flux:heading size="lg" class="mb-4">Creating Ticket Types</flux:heading>
        
        <flux:callout color="blue" icon="information-circle">
            <flux:callout.heading>Quick Access</flux:callout.heading>
            <flux:callout.text>
                Navigate to <strong>Tickets</strong> → <strong>Create Ticket</strong> from the sidebar menu.
            </flux:callout.text>
        </flux:callout>

        <div class="mt-6 space-y-4">
            <div class="border border-zinc-200 dark:border-zinc-600 rounded-lg p-4">
                <flux:text class="font-semibold mb-2">Required Information</flux:text>
                <ul class="space-y-1 text-sm">
                    <li>• <strong>Concert:</strong> Select which concert this ticket is for</li>
                    <li>• <strong>Ticket Type:</strong> Name/category (e.g., "VIP", "General Admission", "Student")</li>
                    <li>• <strong>Price:</strong> Cost per ticket in RM (can be 0 for free tickets)</li>
                    <li>• <strong>Quantity Available:</strong> Total number of tickets of this type to sell</li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Ticket Types Examples -->
    <div class="mb-8">
        <flux:heading size="lg" class="mb-4">Common Ticket Types</flux:heading>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
            <div class="border border-zinc-200 dark:border-zinc-600 rounded-lg p-4">
                <div class="flex items-center mb-2">
                    <flux:badge color="purple" class="mr-2">VIP</flux:badge>
                    <flux:text class="font-semibold">Premium Tickets</flux:text>
                </div>
                <flux:text class="text-sm text-zinc-600 dark:text-zinc-400">
                    Higher-priced tickets with special perks like front row seating or meet-and-greet opportunities.
                </flux:text>
            </div>
            
            <div class="border border-zinc-200 dark:border-zinc-600 rounded-lg p-4">
                <div class="flex items-center mb-2">
                    <flux:badge color="green" class="mr-2">General</flux:badge>
                    <flux:text class="font-semibold">Standard Tickets</flux:text>
                </div>
                <flux:text class="text-sm text-zinc-600 dark:text-zinc-400">
                    Regular admission tickets for general public with standard pricing and seating.
                </flux:text>
            </div>

            <div class="border border-zinc-200 dark:border-zinc-600 rounded-lg p-4">
                <div class="flex items-center mb-2">
                    <flux:badge color="blue" class="mr-2">Student</flux:badge>
                    <flux:text class="font-semibold">Discounted Tickets</flux:text>
                </div>
                <flux:text class="text-sm text-zinc-600 dark:text-zinc-400">
                    Reduced-price tickets for students, often the primary ticket type for school concerts.
                </flux:text>
            </div>

            <div class="border border-zinc-200 dark:border-zinc-600 rounded-lg p-4">
                <div class="flex items-center mb-2">
                    <flux:badge color="orange" class="mr-2">Family</flux:badge>
                    <flux:text class="font-semibold">Group Packages</flux:text>
                </div>
                <flux:text class="text-sm text-zinc-600 dark:text-zinc-400">
                    Special pricing for families or groups attending together.
                </flux:text>
            </div>
        </div>
    </div>

    <!-- Managing Tickets -->
    <div class="mb-8">
        <flux:heading size="lg" class="mb-4">Managing Existing Tickets</flux:heading>
        
        <div class="space-y-4">
            <div class="border border-zinc-200 dark:border-zinc-600 rounded-lg p-4">
                <flux:text class="font-semibold mb-3">Available Actions</flux:text>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="flex items-center">
                        <flux:icon.pencil class="w-4 h-4 mr-2 text-blue-500" />
                        <flux:text class="text-sm">Edit ticket details</flux:text>
                    </div>
                    <div class="flex items-center">
                        <flux:icon.eye class="w-4 h-4 mr-2 text-green-500" />
                        <flux:text class="text-sm">View sales statistics</flux:text>
                    </div>
                    <div class="flex items-center">
                        <flux:icon.trash class="w-4 h-4 mr-2 text-red-500" />
                        <flux:text class="text-sm">Delete if no sales</flux:text>
                    </div>
                </div>
            </div>

            <div class="border border-zinc-200 dark:border-zinc-600 rounded-lg p-4">
                <flux:text class="font-semibold mb-3">Ticket Status Information</flux:text>
                <div class="space-y-2 text-sm">
                    <div class="flex items-center justify-between">
                        <flux:text>Total Available:</flux:text>
                        <flux:text class="font-mono">Original quantity set when created</flux:text>
                    </div>
                    <div class="flex items-center justify-between">
                        <flux:text>Sold:</flux:text>
                        <flux:text class="font-mono">Number of tickets purchased</flux:text>
                    </div>
                    <div class="flex items-center justify-between">
                        <flux:text>Remaining:</flux:text>
                        <flux:text class="font-mono">Available - Sold = Remaining</flux:text>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Pricing Strategies -->
    <div class="mb-8">
        <flux:heading size="lg" class="mb-4">Pricing Best Practices</flux:heading>
        
        <div class="space-y-4">
            <div class="border-l-4 border-green-500 bg-green-50 dark:bg-green-900/20 p-4">
                <flux:text class="font-semibold text-green-800 dark:text-green-200 mb-2">Pricing Tips</flux:text>
                <ul class="space-y-1 text-sm text-green-700 dark:text-green-300">
                    <li>• Research similar events in your area for competitive pricing</li>
                    <li>• Consider offering early bird discounts for advance purchases</li>
                    <li>• Create tiered pricing (VIP, Standard, Student) to maximize revenue</li>
                    <li>• Keep student prices affordable to encourage participation</li>
                </ul>
            </div>

            <div class="border-l-4 border-blue-500 bg-blue-50 dark:bg-blue-900/20 p-4">
                <flux:text class="font-semibold text-blue-800 dark:text-blue-200 mb-2">Quantity Planning</flux:text>
                <ul class="space-y-1 text-sm text-blue-700 dark:text-blue-300">
                    <li>• Never exceed venue capacity across all ticket types</li>
                    <li>• Leave some capacity for walk-in sales on concert day</li>
                    <li>• Consider demand patterns (VIP sells less, General sells most)</li>
                    <li>• You can adjust quantities later if needed</li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Ticket Search and Filtering -->
    <div class="mb-8">
        <flux:heading size="lg" class="mb-4">Finding and Organizing Tickets</flux:heading>
        
        <div class="border border-zinc-200 dark:border-zinc-600 rounded-lg p-4">
            <flux:text class="font-semibold mb-3">Search and Filter Options</flux:text>
            <div class="space-y-2 text-sm">
                <div class="flex items-start">
                    <flux:badge color="gray" class="mr-2 mt-0.5">Search</flux:badge>
                    <flux:text>Find tickets by type name or concert title</flux:text>
                </div>
                <div class="flex items-start">
                    <flux:badge color="gray" class="mr-2 mt-0.5">Concert Filter</flux:badge>
                    <flux:text>View tickets for a specific concert only</flux:text>
                </div>
                <div class="flex items-start">
                    <flux:badge color="gray" class="mr-2 mt-0.5">Status</flux:badge>
                    <flux:text>See which tickets are sold out, available, or have limited quantities</flux:text>
                </div>
            </div>
        </div>
    </div>

    <!-- Important Notes -->
    <flux:callout color="rose" icon="exclamation-triangle">
        <flux:callout.heading>Important Considerations</flux:callout.heading>
        <flux:callout.text>
            <strong>Deleting Tickets:</strong> You can only delete tickets that have no purchases. Once tickets are sold, you cannot delete the ticket type.<br><br>
            <strong>Price Changes:</strong> Changing ticket prices only affects future sales. Existing purchases keep their original price.<br><br>
            <strong>Quantity Changes:</strong> You can increase quantities anytime, but decreasing below current sales will cause errors.
        </flux:callout.text>
    </flux:callout>
</div> 