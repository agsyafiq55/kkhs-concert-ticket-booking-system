<flux:heading size="xl" class="mb-6 flex items-center">
    <flux:icon.qr-code class="w-6 h-6 mr-2 text-rose-500" />
    Scanning for Entry
</flux:heading>

<div class="prose dark:prose-invert max-w-none">
    <flux:text class="text-lg mb-6 text-zinc-600 dark:text-zinc-400">
        The Entry Scanner is used on concert day to validate tickets and grant entry to attendees. Follow this guide for smooth concert operations.
    </flux:text>

    <!-- Quick Access -->
    <flux:callout color="blue" icon="information-circle">
        <flux:callout.heading>Quick Access</flux:callout.heading>
        <flux:callout.text>
            Navigate to <strong>Entry Scanner</strong> from the sidebar under "Used for during Concert Day" section.
        </flux:callout.text>
    </flux:callout>

    <!-- Step by Step Guide -->
    <div class="mb-8">
        <flux:heading size="lg" class="mb-4">Step-by-Step Guide</flux:heading>
        
        <div class="space-y-6">
            <div class="border border-zinc-200 dark:border-zinc-600 rounded-lg p-6">
                <div class="flex items-center mb-3">
                    <flux:badge color="blue" class="mr-3">1</flux:badge>
                    <flux:text class="font-semibold text-lg">Access the Scanner</flux:text>
                </div>
                <flux:text class="mb-3">Navigate to the Entry Scanner page from the sidebar menu.</flux:text>
                <flux:text class="text-sm text-zinc-600 dark:text-zinc-400">
                    The scanner will automatically start when the page loads and request camera permission.
                </flux:text>
            </div>

            <div class="border border-zinc-200 dark:border-zinc-600 rounded-lg p-6">
                <div class="flex items-center mb-3">
                    <flux:badge color="blue" class="mr-3">2</flux:badge>
                    <flux:text class="font-semibold text-lg">Grant Camera Permission</flux:text>
                </div>
                <flux:text class="mb-3">Allow the browser to access your device's camera when prompted.</flux:text>
                <flux:text class="text-sm text-zinc-600 dark:text-zinc-400">
                    If permission is denied, you'll need to enable it in your browser settings.
                </flux:text>
            </div>

            <div class="border border-zinc-200 dark:border-zinc-600 rounded-lg p-6">
                <div class="flex items-center mb-3">
                    <flux:badge color="blue" class="mr-3">3</flux:badge>
                    <flux:text class="font-semibold text-lg">Scan QR Codes</flux:text>
                </div>
                <flux:text class="mb-3">Point the camera at the QR code on the attendee's ticket.</flux:text>
                <flux:text class="text-sm text-zinc-600 dark:text-zinc-400">
                    The system will automatically detect and process the QR code. Hold steady for best results.
                </flux:text>
            </div>

            <div class="border border-zinc-200 dark:border-zinc-600 rounded-lg p-6">
                <div class="flex items-center mb-3">
                    <flux:badge color="blue" class="mr-3">4</flux:badge>
                    <flux:text class="font-semibold text-lg">Check Results</flux:text>
                </div>
                <flux:text class="mb-3">Review the scan result and take appropriate action based on the status.</flux:text>
                <flux:text class="text-sm text-zinc-600 dark:text-zinc-400">
                    Valid tickets will show green confirmation, while issues will display specific error messages.
                </flux:text>
            </div>
        </div>
    </div>

    <!-- Scan Results -->
    <div class="mb-8">
        <flux:heading size="lg" class="mb-4">Understanding Scan Results</flux:heading>
        
        <div class="space-y-4">
            <div class="border-l-4 border-green-500 bg-green-50 dark:bg-green-900/20 p-4">
                <div class="flex items-center mb-2">
                    <flux:badge color="green" class="mr-2">✓</flux:badge>
                    <flux:text class="font-semibold text-green-800 dark:text-green-200">Valid Ticket</flux:text>
                </div>
                <flux:text class="text-green-700 dark:text-green-300">
                    <strong>Action:</strong> Grant entry to the attendee. The ticket is now marked as "used" and cannot be used again.
                </flux:text>
            </div>

            <div class="border-l-4 border-yellow-500 bg-yellow-50 dark:bg-yellow-900/20 p-4">
                <div class="flex items-center mb-2">
                    <flux:badge color="amber" class="mr-2">!</flux:badge>
                    <flux:text class="font-semibold text-yellow-800 dark:text-yellow-200">Already Used</flux:text>
                </div>
                <flux:text class="text-yellow-700 dark:text-yellow-300">
                    <strong>Action:</strong> This ticket was already scanned. Check with the attendee or deny entry if suspicious.
                </flux:text>
            </div>

            <div class="border-l-4 border-red-500 bg-red-50 dark:bg-red-900/20 p-4">
                <div class="flex items-center mb-2">
                    <flux:badge color="red" class="mr-2">✗</flux:badge>
                    <flux:text class="font-semibold text-red-800 dark:text-red-200">Invalid/Cancelled Ticket</flux:text>
                </div>
                <flux:text class="text-red-700 dark:text-red-300">
                    <strong>Action:</strong> Deny entry. The QR code is not valid or the ticket has been cancelled.
                </flux:text>
            </div>

            <div class="border-l-4 border-orange-500 bg-orange-50 dark:bg-orange-900/20 p-4">
                <div class="flex items-center mb-2">
                    <flux:badge color="orange" class="mr-2">W</flux:badge>
                    <flux:text class="font-semibold text-orange-800 dark:text-orange-200">Walk-in Ticket Not Sold</flux:text>
                </div>
                <flux:text class="text-orange-700 dark:text-orange-300">
                    <strong>Action:</strong> Direct the attendee to the Walk-in Sales scanner to complete payment first.
                </flux:text>
            </div>
        </div>
    </div>

    <!-- Important Distinctions -->
    <flux:callout color="rose" icon="exclamation-triangle">
        <flux:callout.heading>Important: Entry Scanner vs Walk-in Sales Scanner</flux:callout.heading>
        <flux:callout.text>
            <strong>Entry Scanner:</strong> Used to validate tickets for entry after payment has been confirmed.<br>
            <strong>Walk-in Sales Scanner:</strong> Used to process payment for walk-in tickets before entry.<br><br>
            If someone has a walk-in ticket that hasn't been sold yet, direct them to the Walk-in Sales scanner first.
        </flux:callout.text>
    </flux:callout>

    <!-- Best Practices -->
    <div class="mb-8">
        <flux:heading size="lg" class="mb-4">Best Practices</flux:heading>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="space-y-3">
                <flux:text class="font-semibold text-green-600 dark:text-green-400">✓ Do</flux:text>
                <ul class="space-y-2 text-sm">
                    <li>• Ensure good lighting for QR code scanning</li>
                    <li>• Keep the device charged throughout the event</li>
                    <li>• Hold the device steady when scanning</li>
                    <li>• Verify attendee details match ticket information</li>
                    <li>• Be courteous and helpful to attendees</li>
                </ul>
            </div>
            
            <div class="space-y-3">
                <flux:text class="font-semibold text-red-600 dark:text-red-400">✗ Don't</flux:text>
                <ul class="space-y-2 text-sm">
                    <li>• Rush the scanning process</li>
                    <li>• Allow entry without successful scan</li>
                    <li>• Share your login credentials</li>
                    <li>• Manually mark tickets as used</li>
                    <li>• Ignore error messages or warnings</li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Troubleshooting -->
    <div class="mb-8">
        <flux:heading size="lg" class="mb-4">Troubleshooting</flux:heading>
        
        <div class="space-y-4">
            <div>
                <flux:text class="font-semibold mb-2 block">Camera not working?</flux:text>
                <flux:text class="text-sm text-zinc-600 dark:text-zinc-400">
                    Check browser permissions, try refreshing the page, or use a different device/browser.
                </flux:text>
            </div>
            
            <div>
                <flux:text class="font-semibold mb-2 block">QR code not scanning?</flux:text>
                <flux:text class="text-sm text-zinc-600 dark:text-zinc-400">
                    Ensure good lighting, clean camera lens, and hold device steady. Try adjusting distance.
                </flux:text>
            </div>
            
            <div>
                <flux:text class="font-semibold mb-2 block">System showing errors?</flux:text>
                <flux:text class="text-sm text-zinc-600 dark:text-zinc-400">
                    Check internet connection. If problems persist, contact technical support immediately.
                </flux:text>
            </div>
        </div>
    </div>

    <!-- Emergency Procedures -->
    <flux:callout color="amber" icon="exclamation-triangle">
        <flux:callout.heading>Emergency Procedures</flux:callout.heading>
        <flux:callout.text>
            If the scanning system is completely unavailable:
            <ol class="mt-2 space-y-1">
                <li>1. Switch to manual ticket checking (verify order IDs and names)</li>
                <li>2. Keep a written log of manually verified entries</li>
                <li>3. Contact technical support immediately</li>
                <li>4. Resume scanning once system is restored</li>
            </ol>
        </flux:callout.text>
    </flux:callout>
</div> 