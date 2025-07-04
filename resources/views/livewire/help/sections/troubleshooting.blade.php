<flux:heading size="xl" class="mb-6 flex items-center">
    <flux:icon.wrench-screwdriver class="w-6 h-6 mr-2 text-rose-500" />
    Troubleshooting
</flux:heading>

<div class="prose dark:prose-invert max-w-none">
    <flux:text class="text-lg mb-6 text-zinc-600 dark:text-zinc-400">
        Common issues and their solutions. Most problems can be resolved quickly with these troubleshooting steps.
    </flux:text>

    <!-- Quick Fixes -->
    <flux:callout color="blue" icon="light-bulb">
        <flux:callout.heading>Quick Fixes First</flux:callout.heading>
        <flux:callout.text>
            Before diving into specific issues, try these universal solutions:
            <ol class="mt-2 space-y-1">
                <li>1. Refresh your browser page (Ctrl+F5 or Cmd+R)</li>
                <li>2. Clear your browser cache and cookies</li>
                <li>3. Try a different browser (Chrome, Firefox, Safari)</li>
                <li>4. Check your internet connection</li>
                <li>5. Log out and log back in</li>
            </ol>
        </flux:callout.text>
    </flux:callout>

    <!-- Login Issues -->
    <div class="mb-8">
        <flux:heading size="lg" class="mb-4">Login & Access Issues</flux:heading>
        
        <div class="space-y-6">
            <div class="border border-zinc-200 dark:border-zinc-600 rounded-lg p-6">
                <flux:text class="font-semibold text-lg mb-3 text-red-600 dark:text-red-400">
                    🚫 "Permission Denied" or "Access Denied" Errors
                </flux:text>
                <div class="space-y-2">
                    <flux:text class="font-medium">Possible Causes:</flux:text>
                    <ul class="space-y-1 text-sm text-zinc-600 dark:text-zinc-400 ml-4">
                        <li>• Your user role doesn't have permission for this action</li>
                        <li>• You're not logged in properly</li>
                        <li>• Your session has expired</li>
                    </ul>
                    <flux:text class="font-medium mt-3">Solutions:</flux:text>
                    <ul class="space-y-1 text-sm text-zinc-600 dark:text-zinc-400 ml-4">
                        <li>• Log out and log back in</li>
                        <li>• Contact your administrator to verify your role permissions</li>
                        <li>• Clear browser cookies and try again</li>
                    </ul>
                </div>
            </div>

            <div class="border border-zinc-200 dark:border-zinc-600 rounded-lg p-6">
                <flux:text class="font-semibold text-lg mb-3 text-red-600 dark:text-red-400">
                    🔑 Can't Remember Password
                </flux:text>
                <div class="space-y-2">
                    <flux:text class="font-medium">Solutions:</flux:text>
                    <ul class="space-y-1 text-sm text-zinc-600 dark:text-zinc-400 ml-4">
                        <li>• Use the "Forgot Password" link on the login page</li>
                        <li>• Check your email for password reset instructions</li>
                        <li>• Contact system administrator if email doesn't arrive</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Scanner Issues -->
    <div class="mb-8">
        <flux:heading size="lg" class="mb-4">QR Code Scanner Issues</flux:heading>
        
        <div class="space-y-6">
            <div class="border border-zinc-200 dark:border-zinc-600 rounded-lg p-6">
                <flux:text class="font-semibold text-lg mb-3 text-red-600 dark:text-red-400">
                    📷 Camera Not Working or Not Detecting QR Codes
                </flux:text>
                <div class="space-y-2">
                    <flux:text class="font-medium">Solutions:</flux:text>
                    <ul class="space-y-1 text-sm text-zinc-600 dark:text-zinc-400 ml-4">
                        <li>• Grant camera permission in your browser</li>
                        <li>• Ensure good lighting conditions</li>
                        <li>• Clean your camera lens</li>
                        <li>• Hold the device steady at arm's length</li>
                        <li>• Try refreshing the page</li>
                        <li>• Use a different browser or device</li>
                        <li>• Restart your browser</li>
                    </ul>
                </div>
            </div>

            <div class="border border-zinc-200 dark:border-zinc-600 rounded-lg p-6">
                <flux:text class="font-semibold text-lg mb-3 text-red-600 dark:text-red-400">
                    ⚠️ Scanner Shows "System Busy" Message
                </flux:text>
                <div class="space-y-2">
                    <flux:text class="font-medium">What it means:</flux:text>
                    <flux:text class="text-sm text-zinc-600 dark:text-zinc-400 ml-4">
                        High traffic is causing temporary delays in processing.
                    </flux:text>
                    <flux:text class="font-medium mt-3">Solutions:</flux:text>
                    <ul class="space-y-1 text-sm text-zinc-600 dark:text-zinc-400 ml-4">
                        <li>• Wait 2-3 seconds and try scanning again</li>
                        <li>• This is normal during peak concert entry times</li>
                        <li>• Do not scan the same ticket multiple times rapidly</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Ticket Issues -->
    <div class="mb-8">
        <flux:heading size="lg" class="mb-4">Ticket-Related Issues</flux:heading>
        
        <div class="space-y-6">
            <div class="border border-zinc-200 dark:border-zinc-600 rounded-lg p-6">
                <flux:text class="font-semibold text-lg mb-3 text-red-600 dark:text-red-400">
                    🎫 Can't Download or Print Tickets
                </flux:text>
                <div class="space-y-2">
                    <flux:text class="font-medium">Solutions:</flux:text>
                    <ul class="space-y-1 text-sm text-zinc-600 dark:text-zinc-400 ml-4">
                        <li>• Check that your tickets are in "valid" status</li>
                        <li>• Ensure pop-ups are enabled in your browser</li>
                        <li>• Try a different browser</li>
                        <li>• Check your Downloads folder</li>
                        <li>• Contact the teacher who assigned your tickets</li>
                    </ul>
                </div>
            </div>

            <div class="border border-zinc-200 dark:border-zinc-600 rounded-lg p-6">
                <flux:text class="font-semibold text-lg mb-3 text-red-600 dark:text-red-400">
                    📧 Didn't Receive Email Confirmation
                </flux:text>
                <div class="space-y-2">
                    <flux:text class="font-medium">Solutions:</flux:text>
                    <ul class="space-y-1 text-sm text-zinc-600 dark:text-zinc-400 ml-4">
                        <li>• Check your spam/junk folder</li>
                        <li>• Verify your email address in your profile settings</li>
                        <li>• Contact the teacher who processed your ticket purchase</li>
                        <li>• You can still access tickets through "My Tickets" page</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- System Performance -->
    <div class="mb-8">
        <flux:heading size="lg" class="mb-4">System Performance Issues</flux:heading>
        
        <div class="space-y-6">
            <div class="border border-zinc-200 dark:border-zinc-600 rounded-lg p-6">
                <flux:text class="font-semibold text-lg mb-3 text-red-600 dark:text-red-400">
                    🐌 Pages Loading Slowly
                </flux:text>
                <div class="space-y-2">
                    <flux:text class="font-medium">Solutions:</flux:text>
                    <ul class="space-y-1 text-sm text-zinc-600 dark:text-zinc-400 ml-4">
                        <li>• Check your internet connection speed</li>
                        <li>• Close other browser tabs and applications</li>
                        <li>• Clear browser cache and cookies</li>
                        <li>• Try using an incognito/private browsing window</li>
                        <li>• Restart your browser</li>
                    </ul>
                </div>
            </div>

            <div class="border border-zinc-200 dark:border-zinc-600 rounded-lg p-6">
                <flux:text class="font-semibold text-lg mb-3 text-red-600 dark:text-red-400">
                    💻 Pages Not Displaying Correctly
                </flux:text>
                <div class="space-y-2">
                    <flux:text class="font-medium">Solutions:</flux:text>
                    <ul class="space-y-1 text-sm text-zinc-600 dark:text-zinc-400 ml-4">
                        <li>• Ensure JavaScript is enabled in your browser</li>
                        <li>• Try a different browser (Chrome recommended)</li>
                        <li>• Disable browser extensions temporarily</li>
                        <li>• Clear browser cache and refresh</li>
                        <li>• Check if your browser is up to date</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Error Messages -->
    <div class="mb-8">
        <flux:heading size="lg" class="mb-4">Common Error Messages</flux:heading>
        
        <div class="space-y-4">
            <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-4">
                <flux:text class="font-semibold text-red-800 dark:text-red-200 mb-2 block">
                    "Session Expired"
                </flux:text>
                <flux:text class="text-red-700 dark:text-red-300 text-sm">
                    Your login session has timed out. Simply log in again to continue.
                </flux:text>
            </div>

            <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-4">
                <flux:text class="font-semibold text-red-800 dark:text-red-200 mb-2 block">
                    "Not enough tickets available"
                </flux:text>
                <flux:text class="text-red-700 dark:text-red-300 text-sm">
                    The ticket type you selected is sold out or doesn't have enough remaining tickets for your request.
                </flux:text>
            </div>

            <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-4">
                <flux:text class="font-semibold text-red-800 dark:text-red-200 mb-2 block">
                    "Invalid QR code"
                </flux:text>
                <flux:text class="text-red-700 dark:text-red-300 text-sm">
                    The QR code is not from this system, is corrupted, or the ticket has been cancelled.
                </flux:text>
            </div>
        </div>
    </div>

    <!-- When to Contact Support -->
    <flux:callout color="amber" icon="phone">
        <flux:callout.heading>When to Contact Support</flux:callout.heading>
        <flux:callout.text>
            Contact your system administrator if:
            <ul class="mt-2 space-y-1">
                <li>• You've tried all troubleshooting steps without success</li>
                <li>• You need your role or permissions changed</li>
                <li>• You're experiencing repeated technical issues</li>
                <li>• You need to recover deleted data</li>
                <li>• There are system-wide issues affecting multiple users</li>
            </ul>
        </flux:callout.text>
    </flux:callout>

    <!-- Browser Compatibility -->
    <div class="mb-8">
        <flux:heading size="lg" class="mb-4">Recommended Browsers</flux:heading>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="space-y-3">
                <flux:text class="font-semibold text-green-600 dark:text-green-400">✓ Recommended</flux:text>
                <ul class="space-y-2 text-sm">
                    <li>• Google Chrome (latest version)</li>
                    <li>• Mozilla Firefox (latest version)</li>
                    <li>• Safari (latest version)</li>
                    <li>• Microsoft Edge (latest version)</li>
                </ul>
            </div>
            
            <div class="space-y-3">
                <flux:text class="font-semibold text-amber-600 dark:text-amber-400">⚠️ Limited Support</flux:text>
                <ul class="space-y-2 text-sm">
                    <li>• Internet Explorer (not recommended)</li>
                    <li>• Very old browser versions</li>
                    <li>• Browsers with JavaScript disabled</li>
                </ul>
            </div>
        </div>
    </div>
</div> 