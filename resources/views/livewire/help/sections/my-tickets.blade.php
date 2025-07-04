<flux:heading size="xl" class="mb-6 flex items-center">
    <flux:icon.ticket class="w-6 h-6 mr-2 text-rose-500" />
    Managing My Tickets
</flux:heading>

<div class="prose dark:prose-invert max-w-none">
    <flux:text class="text-lg mb-6 text-zinc-600 dark:text-zinc-400">
        Learn how to view, download, and manage your personal concert tickets. Everything you need to know about your ticket purchases.
    </flux:text>

    <!-- Accessing My Tickets -->
    <div class="mb-8">
        <flux:heading size="lg" class="mb-4">Accessing Your Tickets</flux:heading>
        
        <flux:callout color="blue" icon="information-circle">
            <flux:callout.heading>Quick Access</flux:callout.heading>
            <flux:callout.text>
                Navigate to <strong>My Tickets</strong> from the sidebar menu under "Student Features".
            </flux:callout.text>
        </flux:callout>

        <div class="mt-6">
            <div class="border border-zinc-200 dark:border-zinc-600 rounded-lg p-4">
                <flux:text class="font-semibold mb-2">What You'll See</flux:text>
                <ul class="space-y-1 text-sm">
                    <li>• All tickets purchased for you by teachers</li>
                    <li>• Concert details including date, time, and venue</li>
                    <li>• Ticket status (Valid, Used, or Cancelled)</li>
                    <li>• QR codes for entry validation</li>
                    <li>• Order IDs for tracking and reference</li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Understanding Ticket Information -->
    <div class="mb-8">
        <flux:heading size="lg" class="mb-4">Understanding Your Ticket Details</flux:heading>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
            <div class="border border-zinc-200 dark:border-zinc-600 rounded-lg p-4">
                <div class="flex items-center mb-2">
                    <flux:icon.calendar class="w-5 h-5 mr-2 text-blue-500" />
                    <flux:text class="font-semibold">Concert Information</flux:text>
                </div>
                <flux:text class="text-sm text-zinc-600 dark:text-zinc-400">
                    Concert title, date, start time, venue, and ticket type are clearly displayed on each ticket.
                </flux:text>
            </div>
            
            <div class="border border-zinc-200 dark:border-zinc-600 rounded-lg p-4">
                <div class="flex items-center mb-2">
                    <flux:icon.identification class="w-5 h-5 mr-2 text-green-500" />
                    <flux:text class="font-semibold">Order Details</flux:text>
                </div>
                <flux:text class="text-sm text-zinc-600 dark:text-zinc-400">
                    Each ticket has a unique Order ID for tracking and a price showing what was paid.
                </flux:text>
            </div>
        </div>

        <div class="border border-zinc-200 dark:border-zinc-600 rounded-lg p-4">
            <flux:text class="font-semibold mb-3">Ticket Status Meanings</flux:text>
            <div class="space-y-2">
                <div class="flex items-center">
                    <flux:badge color="green" class="mr-3">Valid</flux:badge>
                    <flux:text class="text-sm">Ticket is active and ready for use at the concert</flux:text>
                </div>
                <div class="flex items-center">
                    <flux:badge color="gray" class="mr-3">Used</flux:badge>
                    <flux:text class="text-sm">Ticket has been scanned for entry (you've already attended)</flux:text>
                </div>
                <div class="flex items-center">
                    <flux:badge color="red" class="mr-3">Cancelled</flux:badge>
                    <flux:text class="text-sm">Ticket has been cancelled and cannot be used</flux:text>
                </div>
            </div>
        </div>
    </div>

    <!-- Using QR Codes -->
    <div class="mb-8">
        <flux:heading size="lg" class="mb-4">Using Your QR Codes</flux:heading>
        
        <div class="space-y-4">
            <div class="border-l-4 border-blue-500 bg-blue-50 dark:bg-blue-900/20 p-4">
                <flux:text class="font-semibold text-blue-800 dark:text-blue-200 mb-2">On Concert Day</flux:text>
                <ul class="space-y-1 text-sm text-blue-700 dark:text-blue-300">
                    <li>• Present your ticket (printed or on phone) at the entrance</li>
                    <li>• Staff will scan the QR code for validation</li>
                    <li>• Keep your ticket/phone ready to speed up entry</li>
                    <li>• Each QR code can only be used once for entry</li>
                </ul>
            </div>

            <div class="border border-zinc-200 dark:border-zinc-600 rounded-lg p-4">
                <flux:text class="font-semibold mb-3">QR Code Features</flux:text>
                <div class="space-y-2 text-sm">
                    <div class="flex items-start">
                        <flux:badge color="gray" class="mr-2 mt-0.5">Click to Enlarge</flux:badge>
                        <flux:text>Click any QR code to view it larger for easier scanning</flux:text>
                    </div>
                    <div class="flex items-start">
                        <flux:badge color="gray" class="mr-2 mt-0.5">Print-Friendly</flux:badge>
                        <flux:text>QR codes are high-quality and work well when printed</flux:text>
                    </div>
                    <div class="flex items-start">
                        <flux:badge color="gray" class="mr-2 mt-0.5">Mobile-Ready</flux:badge>
                        <flux:text>Display on your phone screen for contactless scanning</flux:text>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Downloading and Printing -->
    <div class="mb-8">
        <flux:heading size="lg" class="mb-4">Downloading and Printing Tickets</flux:heading>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
            <div class="border border-zinc-200 dark:border-zinc-600 rounded-lg p-4">
                <div class="flex items-center mb-2">
                    <flux:icon.arrow-down-on-square class="w-5 h-5 mr-2 text-green-500" />
                    <flux:text class="font-semibold">Download Individual Tickets</flux:text>
                </div>
                <flux:text class="text-sm text-zinc-600 dark:text-zinc-400 mb-2">
                    Click "Download Ticket" on any ticket to get a printable version.
                </flux:text>
                <ul class="space-y-1 text-xs text-zinc-500 dark:text-zinc-400">
                    <li>• Opens in a new tab for easy printing</li>
                    <li>• Includes all ticket details and QR code</li>
                    <li>• Professional format suitable for presentation</li>
                </ul>
            </div>
            
            <div class="border border-zinc-200 dark:border-zinc-600 rounded-lg p-4">
                <div class="flex items-center mb-2">
                    <flux:icon.printer class="w-5 h-5 mr-2 text-blue-500" />
                    <flux:text class="font-semibold">Printing Tips</flux:text>
                </div>
                <flux:text class="text-sm text-zinc-600 dark:text-zinc-400 mb-2">
                    Best practices for printing your tickets.
                </flux:text>
                <ul class="space-y-1 text-xs text-zinc-500 dark:text-zinc-400">
                    <li>• Use white or light-colored paper</li>
                    <li>• Print in high quality for clear QR codes</li>
                    <li>• Avoid folding across the QR code area</li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Email Confirmations -->
    <div class="mb-8">
        <flux:heading size="lg" class="mb-4">Email Confirmations</flux:heading>
        
        <div class="border border-zinc-200 dark:border-zinc-600 rounded-lg p-4">
            <flux:text class="font-semibold mb-3">What to Expect</flux:text>
            <div class="space-y-2 text-sm">
                <flux:text>When a teacher assigns tickets to you, you'll automatically receive an email confirmation containing:</flux:text>
                <ul class="space-y-1 ml-4 mt-2">
                    <li>• Complete ticket details and concert information</li>
                    <li>• Order ID for your records</li>
                    <li>• Instructions for concert day</li>
                    <li>• Digital copies of your tickets with QR codes</li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Troubleshooting Common Issues -->
    <div class="mb-8">
        <flux:heading size="lg" class="mb-4">Common Questions</flux:heading>
        
        <div class="space-y-4">
            <div class="border border-zinc-200 dark:border-zinc-600 rounded-lg p-4">
                <flux:text class="font-semibold mb-2">What if I don't see my tickets?</flux:text>
                <flux:text class="text-sm text-zinc-600 dark:text-zinc-400">
                    Tickets appear immediately after a teacher assigns them to you. If you don't see tickets you expect, check with your teacher or contact support.
                </flux:text>
            </div>

            <div class="border border-zinc-200 dark:border-zinc-600 rounded-lg p-4">
                <flux:text class="font-semibold mb-2">Can I transfer my tickets to someone else?</flux:text>
                <flux:text class="text-sm text-zinc-600 dark:text-zinc-400">
                    Tickets are assigned specifically to you and cannot be transferred. If you cannot attend, contact your teacher for assistance.
                </flux:text>
            </div>

            <div class="border border-zinc-200 dark:border-zinc-600 rounded-lg p-4">
                <flux:text class="font-semibold mb-2">What if my QR code won't scan?</flux:text>
                <flux:text class="text-sm text-zinc-600 dark:text-zinc-400">
                    Ensure the QR code is clean and clearly visible. If using a phone, increase screen brightness. If problems persist, show your Order ID to staff.
                </flux:text>
            </div>
        </div>
    </div>

    <!-- Important Notes -->
    <flux:callout color="rose" icon="exclamation-triangle">
        <flux:callout.heading>Important Reminders</flux:callout.heading>
        <flux:callout.text>
            <strong>Bring Your Ticket:</strong> Always bring your ticket (printed or on phone) to the concert. Screenshots may not work for scanning.<br><br>
            <strong>Arrive Early:</strong> Arrive 15-30 minutes before the concert start time to allow for entry processing.<br><br>
            <strong>Keep Your Order ID:</strong> Note your Order ID in case you need support assistance.
        </flux:callout.text>
    </flux:callout>
</div> 