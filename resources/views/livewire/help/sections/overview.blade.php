<flux:heading size="xl" class="mb-6 flex items-center">
    <flux:icon.layout-grid class="w-6 h-6 mr-2 text-rose-500" />
    System Overview
</flux:heading>

<div class="prose dark:prose-invert max-w-none">
    <flux:text class="text-lg mb-6 text-zinc-600 dark:text-zinc-400">
        Welcome to the KKHS Concert Ticket Booking System! This comprehensive platform manages the entire lifecycle of concert tickets, from creation to entry validation.
    </flux:text>

    <!-- Role Explanation -->
    <div class="mb-8">
        <flux:heading size="lg" class="mb-4">Your Role: {{ ucfirst($userRole) }}</flux:heading>
        
        @if($userRole === 'super-admin')
            <flux:callout color="blue" icon="shield-check">
                <flux:callout.heading>Super Administrator</flux:callout.heading>
                <flux:callout.text>
                    You have full system access including user management, role assignment, and all administrative functions. 
                    You can perform any action in the system and have access to all documentation sections.
                </flux:callout.text>
            </flux:callout>
        @elseif($userRole === 'admin')
            <flux:callout color="emerald" icon="user-check">
                <flux:callout.heading>Administrator</flux:callout.heading>
                <flux:callout.text>
                    You have administrative access to manage concerts, tickets, sales monitoring, and walk-in tickets. 
                    You cannot manage user roles and permissions - that's reserved for Super Administrators.
                </flux:callout.text>
            </flux:callout>
        @elseif($userRole === 'teacher')
            <flux:callout color="amber" icon="academic-cap">
                <flux:callout.heading>Teacher</flux:callout.heading>
                <flux:callout.text>
                    You can sell tickets to students, scan tickets for entry during concerts, and process walk-in sales. 
                    Your role is essential for both ticket sales and concert day operations.
                </flux:callout.text>
            </flux:callout>
        @else
            <flux:callout color="purple" icon="user">
                <flux:callout.heading>Student</flux:callout.heading>
                <flux:callout.text>
                    You can view and manage your personal ticket purchases, download printable tickets, and track your concert attendance history.
                </flux:callout.text>
            </flux:callout>
        @endif
    </div>

    <!-- System Features -->
    <div class="mb-8">
        <flux:heading size="lg" class="mb-4">Key System Features</flux:heading>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            @if(Auth::user()->hasRole(['super-admin', 'admin']))
            <div class="border border-zinc-200 dark:border-zinc-600 rounded-lg p-4">
                <div class="flex items-center mb-2">
                    <flux:icon.musical-note class="w-5 h-5 mr-2 text-purple-500" />
                    <flux:text class="font-semibold">Concert Management</flux:text>
                </div>
                <flux:text class="text-sm text-zinc-600 dark:text-zinc-400">
                    Create and manage concerts with details like venue, date, time, and description.
                </flux:text>
            </div>
            
            <div class="border border-zinc-200 dark:border-zinc-600 rounded-lg p-4">
                <div class="flex items-center mb-2">
                    <flux:icon.ticket class="w-5 h-5 mr-2 text-green-500" />
                    <flux:text class="font-semibold">Ticket Management</flux:text>
                </div>
                <flux:text class="text-sm text-zinc-600 dark:text-zinc-400">
                    Create different ticket types with pricing and availability limits.
                </flux:text>
            </div>
            @endif
            
            @if(Auth::user()->hasRole(['super-admin', 'admin', 'teacher']))
            <div class="border border-zinc-200 dark:border-zinc-600 rounded-lg p-4">
                <div class="flex items-center mb-2">
                    <flux:icon.user-plus class="w-5 h-5 mr-2 text-blue-500" />
                    <flux:text class="font-semibold">Ticket Sales</flux:text>
                </div>
                <flux:text class="text-sm text-zinc-600 dark:text-zinc-400">
                    Sell tickets to students with cart functionality and email confirmations.
                </flux:text>
            </div>
            
            <div class="border border-zinc-200 dark:border-zinc-600 rounded-lg p-4">
                <div class="flex items-center mb-2">
                    <flux:icon.qr-code class="w-5 h-5 mr-2 text-rose-500" />
                    <flux:text class="font-semibold">Entry Scanning</flux:text>
                </div>
                <flux:text class="text-sm text-zinc-600 dark:text-zinc-400">
                    Scan QR codes for ticket validation and entry on concert day.
                </flux:text>
            </div>
            @endif
            
            @if(Auth::user()->hasRole(['super-admin', 'admin']))
            <div class="border border-zinc-200 dark:border-zinc-600 rounded-lg p-4">
                <div class="flex items-center mb-2">
                    <flux:icon.user-group class="w-5 h-5 mr-2 text-orange-500" />
                    <flux:text class="font-semibold">Walk-in Tickets</flux:text>
                </div>
                <flux:text class="text-sm text-zinc-600 dark:text-zinc-400">
                    Generate physical tickets for on-site sales during concerts.
                </flux:text>
            </div>
            
            <div class="border border-zinc-200 dark:border-zinc-600 rounded-lg p-4">
                <div class="flex items-center mb-2">
                    <flux:icon.chart-bar class="w-5 h-5 mr-2 text-indigo-500" />
                    <flux:text class="font-semibold">Sales Analytics</flux:text>
                </div>
                <flux:text class="text-sm text-zinc-600 dark:text-zinc-400">
                    Monitor sales performance with detailed reports and statistics.
                </flux:text>
            </div>
            @endif
            
            <div class="border border-zinc-200 dark:border-zinc-600 rounded-lg p-4">
                <div class="flex items-center mb-2">
                    <flux:icon.ticket class="w-5 h-5 mr-2 text-teal-500" />
                    <flux:text class="font-semibold">Personal Tickets</flux:text>
                </div>
                <flux:text class="text-sm text-zinc-600 dark:text-zinc-400">
                    View, download, and print your purchased tickets with QR codes.
                </flux:text>
            </div>
        </div>
    </div>

    <!-- Quick Start Guide -->
    <div class="mb-8">
        <flux:heading size="lg" class="mb-4">Quick Start Guide</flux:heading>
        
        @if($userRole === 'super-admin')
            <div class="space-y-3">
                <div class="flex items-start">
                    <flux:badge color="blue" class="mr-3 mt-1">1</flux:badge>
                    <flux:text>Start by creating concerts in the Concert Management section</flux:text>
                </div>
                <div class="flex items-start">
                    <flux:badge color="blue" class="mr-3 mt-1">2</flux:badge>
                    <flux:text>Add ticket types with pricing and availability</flux:text>
                </div>
                <div class="flex items-start">
                    <flux:badge color="blue" class="mr-3 mt-1">3</flux:badge>
                    <flux:text>Create user accounts for teachers and students</flux:text>
                </div>
                <div class="flex items-start">
                    <flux:badge color="blue" class="mr-3 mt-1">4</flux:badge>
                    <flux:text>Monitor sales and generate reports as needed</flux:text>
                </div>
            </div>
        @elseif($userRole === 'admin')
            <div class="space-y-3">
                <div class="flex items-start">
                    <flux:badge color="emerald" class="mr-3 mt-1">1</flux:badge>
                    <flux:text>Create and manage concerts for your events</flux:text>
                </div>
                <div class="flex items-start">
                    <flux:badge color="emerald" class="mr-3 mt-1">2</flux:badge>
                    <flux:text>Set up ticket types with appropriate pricing</flux:text>
                </div>
                <div class="flex items-start">
                    <flux:badge color="emerald" class="mr-3 mt-1">3</flux:badge>
                    <flux:text>Generate walk-in tickets before concert day</flux:text>
                </div>
                <div class="flex items-start">
                    <flux:badge color="emerald" class="mr-3 mt-1">4</flux:badge>
                    <flux:text>Use sales monitoring to track performance</flux:text>
                </div>
            </div>
        @elseif($userRole === 'teacher')
            <div class="space-y-3">
                <div class="flex items-start">
                    <flux:badge color="amber" class="mr-3 mt-1">1</flux:badge>
                    <flux:text>Use "Sell Tickets" to assign tickets to students</flux:text>
                </div>
                <div class="flex items-start">
                    <flux:badge color="amber" class="mr-3 mt-1">2</flux:badge>
                    <flux:text>On concert day, use "Entry Scanner" for ticket validation</flux:text>
                </div>
                <div class="flex items-start">
                    <flux:badge color="amber" class="mr-3 mt-1">3</flux:badge>
                    <flux:text>Use "Walk-in Sales Scanner" for on-site sales</flux:text>
                </div>
            </div>
        @else
            <div class="space-y-3">
                <div class="flex items-start">
                    <flux:badge color="purple" class="mr-3 mt-1">1</flux:badge>
                    <flux:text>Visit "My Tickets" to view your purchased tickets</flux:text>
                </div>
                <div class="flex items-start">
                    <flux:badge color="purple" class="mr-3 mt-1">2</flux:badge>
                    <flux:text>Download or print tickets with QR codes</flux:text>
                </div>
                <div class="flex items-start">
                    <flux:badge color="purple" class="mr-3 mt-1">3</flux:badge>
                    <flux:text>Present your QR code at the concert for entry</flux:text>
                </div>
            </div>
        @endif
    </div>

    <!-- Important Notes -->
    <flux:callout color="rose" icon="exclamation-triangle">
        <flux:callout.heading>Important Notes</flux:callout.heading>
        <flux:callout.text>
            <ul class="space-y-1 mt-2">
                <li>• QR codes are unique and can only be used once for entry</li>
                <li>• Always verify ticket details before printing or sharing</li>
                <li>• Contact system administrators if you encounter any issues</li>
                @if($userRole !== 'student')
                <li>• For concert day operations, ensure stable internet connectivity</li>
                @endif
            </ul>
        </flux:callout.text>
    </flux:callout>
</div> 