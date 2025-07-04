<flux:heading size="xl" class="mb-6 flex items-center">
    <flux:icon.musical-note class="w-6 h-6 mr-2 text-rose-500" />
    Managing Concerts
</flux:heading>

<div class="prose dark:prose-invert max-w-none">
    <flux:text class="text-lg mb-6 text-zinc-600 dark:text-zinc-400">
        Learn how to create, edit, and manage concerts in the system. Concerts are the foundation of the ticket booking system.
    </flux:text>

    <!-- Creating Concerts -->
    <div class="mb-8">
        <flux:heading size="lg" class="mb-4">Creating Concerts</flux:heading>
        
        <flux:callout color="blue" icon="information-circle">
            <flux:callout.heading>Quick Access</flux:callout.heading>
            <flux:callout.text>
                Navigate to <strong>Concerts</strong> → <strong>Create Concert</strong> from the sidebar menu.
            </flux:callout.text>
        </flux:callout>

        <div class="mt-6 space-y-4">
            <div class="border border-zinc-200 dark:border-zinc-600 rounded-lg p-4">
                <flux:text class="font-semibold mb-2">Required Information</flux:text>
                <ul class="space-y-1 text-sm">
                    <li>• <strong>Title:</strong> Name of the concert (e.g., "Spring Music Concert 2025")</li>
                    <li>• <strong>Description:</strong> Detailed information about the event</li>
                    <li>• <strong>Venue:</strong> Location where the concert will be held</li>
                    <li>• <strong>Date:</strong> Concert date (must be today or future date)</li>
                    <li>• <strong>Start Time:</strong> When the concert begins</li>
                    <li>• <strong>End Time:</strong> When the concert ends (must be after start time)</li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Managing Existing Concerts -->
    <div class="mb-8">
        <flux:heading size="lg" class="mb-4">Managing Existing Concerts</flux:heading>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
            <div class="border border-zinc-200 dark:border-zinc-600 rounded-lg p-4">
                <div class="flex items-center mb-2">
                    <flux:icon.pencil class="w-5 h-5 mr-2 text-blue-500" />
                    <flux:text class="font-semibold">Editing Concerts</flux:text>
                </div>
                <flux:text class="text-sm text-zinc-600 dark:text-zinc-400">
                    Click the "Edit" button on any concert to modify its details. All fields can be updated.
                </flux:text>
            </div>
            
            <div class="border border-zinc-200 dark:border-zinc-600 rounded-lg p-4">
                <div class="flex items-center mb-2">
                    <flux:icon.trash class="w-5 h-5 mr-2 text-red-500" />
                    <flux:text class="font-semibold">Deleting Concerts</flux:text>
                </div>
                <flux:text class="text-sm text-zinc-600 dark:text-zinc-400">
                    Use caution when deleting concerts. This will also remove all associated tickets and purchases.
                </flux:text>
            </div>
        </div>
    </div>

    <!-- Concert Planning Tips -->
    <div class="mb-8">
        <flux:heading size="lg" class="mb-4">Concert Planning Best Practices</flux:heading>
        
        <div class="space-y-4">
            <div class="border-l-4 border-green-500 bg-green-50 dark:bg-green-900/20 p-4">
                <flux:text class="font-semibold text-green-800 dark:text-green-200 mb-2">Planning Ahead</flux:text>
                <ul class="space-y-1 text-sm text-green-700 dark:text-green-300">
                    <li>• Create concerts well in advance to allow time for ticket setup</li>
                    <li>• Consider venue capacity when planning ticket quantities</li>
                    <li>• Set realistic start and end times with buffer for setup/cleanup</li>
                    <li>• Include detailed descriptions to help with promotion</li>
                </ul>
            </div>

            <div class="border-l-4 border-blue-500 bg-blue-50 dark:bg-blue-900/20 p-4">
                <flux:text class="font-semibold text-blue-800 dark:text-blue-200 mb-2">After Creating a Concert</flux:text>
                <ul class="space-y-1 text-sm text-blue-700 dark:text-blue-300">
                    <li>• Create ticket types immediately after concert creation</li>
                    <li>• Set up different pricing tiers if needed (VIP, General, Student, etc.)</li>
                    <li>• Generate walk-in tickets before concert day</li>
                    <li>• Monitor sales progress through the Sales Monitoring section</li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Concert Search and Filtering -->
    <div class="mb-8">
        <flux:heading size="lg" class="mb-4">Finding and Organizing Concerts</flux:heading>
        
        <div class="border border-zinc-200 dark:border-zinc-600 rounded-lg p-4">
            <flux:text class="font-semibold mb-3">Search and Filter Options</flux:text>
            <div class="space-y-2 text-sm">
                <div class="flex items-start">
                    <flux:badge color="gray" class="mr-2 mt-0.5">Search</flux:badge>
                    <flux:text>Use the search box to find concerts by title or venue</flux:text>
                </div>
                <div class="flex items-start">
                    <flux:badge color="gray" class="mr-2 mt-0.5">Sort</flux:badge>
                    <flux:text>Concerts are automatically sorted with upcoming events first</flux:text>
                </div>
                <div class="flex items-start">
                    <flux:badge color="gray" class="mr-2 mt-0.5">Status</flux:badge>
                    <flux:text>View concerts with active ticket sales, past events, or upcoming</flux:text>
                </div>
            </div>
        </div>
    </div>

    <!-- Important Notes -->
    <flux:callout color="rose" icon="exclamation-triangle">
        <flux:callout.heading>Important Considerations</flux:callout.heading>
        <flux:callout.text>
            <strong>Deleting Concerts:</strong> When you delete a concert, all associated tickets and ticket purchases are permanently removed. This action cannot be undone.<br><br>
            <strong>Date Changes:</strong> If you need to change a concert date after tickets have been sold, consider the impact on customers and communicate changes clearly.<br><br>
            <strong>Venue Capacity:</strong> Always ensure your total ticket quantities don't exceed venue capacity for safety and legal compliance.
        </flux:callout.text>
    </flux:callout>
</div> 