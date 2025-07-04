<flux:heading size="xl" class="mb-6 flex items-center">
    <flux:icon.users class="w-6 h-6 mr-2 text-rose-500" />
    User Management
</flux:heading>

<div class="prose dark:prose-invert max-w-none">
    <flux:text class="text-lg mb-6 text-zinc-600 dark:text-zinc-400">
        Complete guide to managing user accounts, roles, and permissions in the KKHS Concert Ticket Booking System. Only available to Super Administrators.
    </flux:text>

    <flux:callout color="amber" icon="shield-exclamation">
        <flux:callout.heading>Super Administrator Only</flux:callout.heading>
        <flux:callout.text>
            User management features are exclusively available to Super Administrators. Regular administrators cannot manage user roles or permissions for security reasons.
        </flux:callout.text>
    </flux:callout>

    <!-- Quick Navigation -->
    <div class="mb-8">
        <flux:heading size="lg" class="mb-4">What You Can Do</flux:heading>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="border border-zinc-200 dark:border-zinc-600 rounded-lg p-4">
                <flux:text class="font-semibold mb-2">👥 User Management</flux:text>
                <ul class="space-y-1 text-sm">
                    <li>• Create new user accounts</li>
                    <li>• Edit user information</li>
                    <li>• Assign and change user roles</li>
                    <li>• Delete user accounts (with safety checks)</li>
                    <li>• Search and filter users</li>
                </ul>
            </div>
            <div class="border border-zinc-200 dark:border-zinc-600 rounded-lg p-4">
                <flux:text class="font-semibold mb-2">🔐 Role & Permission Management</flux:text>
                <ul class="space-y-1 text-sm">
                    <li>• Configure role permissions</li>
                    <li>• View permission hierarchies</li>
                    <li>• Bulk upload student accounts</li>
                    <li>• Monitor user activity</li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Accessing User Management -->
    <div class="mb-8">
        <flux:heading size="lg" class="mb-4">Accessing User Management</flux:heading>
        
        <flux:callout color="blue" icon="information-circle">
            <flux:callout.heading>Navigation Path</flux:callout.heading>
            <flux:callout.text>
                Admin Controls → Users OR Admin Controls → Roles and Permissions
            </flux:callout.text>
        </flux:callout>

        <div class="mt-4 space-y-4">
            <div class="border border-zinc-200 dark:border-zinc-600 rounded-lg p-4">
                <flux:text class="font-semibold mb-2">Users Page</flux:text>
                <flux:text class="text-sm text-zinc-600 dark:text-zinc-400">
                    Main interface for creating, editing, and deleting user accounts. Includes search functionality and role filtering.
                </flux:text>
            </div>
            <div class="border border-zinc-200 dark:border-zinc-600 rounded-lg p-4">
                <flux:text class="font-semibold mb-2">Roles and Permissions Page</flux:text>
                <flux:text class="text-sm text-zinc-600 dark:text-zinc-400">
                    Advanced interface for configuring what each role can do in the system. Manage permissions for admin, teacher, and student roles.
                </flux:text>
            </div>
        </div>
    </div>

    <!-- Creating New Users -->
    <div class="mb-8">
        <flux:heading size="lg" class="mb-4">Creating New Users</flux:heading>
        
        <div class="space-y-4">
            <div class="border border-zinc-200 dark:border-zinc-600 rounded-lg p-6">
                <div class="flex items-center mb-3">
                    <flux:badge color="blue" class="mr-3">1</flux:badge>
                    <flux:text class="font-semibold text-lg">Click "Create New User"</flux:text>
                </div>
                <flux:text class="mb-3">On the Users page, click the "Create New User" button to open the creation form.</flux:text>
            </div>

            <div class="border border-zinc-200 dark:border-zinc-600 rounded-lg p-6">
                <div class="flex items-center mb-3">
                    <flux:badge color="blue" class="mr-3">2</flux:badge>
                    <flux:text class="font-semibold text-lg">Fill User Details</flux:text>
                </div>
                <flux:text class="mb-3">Complete the required information:</flux:text>
                <ul class="space-y-1 text-sm mb-3">
                    <li>• <strong>Name:</strong> Full name of the user</li>
                    <li>• <strong>Email:</strong> Must be unique in the system</li>
                    <li>• <strong>Password:</strong> Minimum 8 characters, will be confirmed</li>
                    <li>• <strong>Role:</strong> Select at least one role (required)</li>
                </ul>
                <div class="bg-zinc-50 dark:bg-zinc-800 p-3 rounded border">
                    <flux:text class="text-sm text-zinc-600 dark:text-zinc-400">
                        <strong>Note:</strong> Email addresses must be unique. The system will show an error if you try to use an existing email.
                    </flux:text>
                </div>
            </div>

            <div class="border border-zinc-200 dark:border-zinc-600 rounded-lg p-6">
                <div class="flex items-center mb-3">
                    <flux:badge color="blue" class="mr-3">3</flux:badge>
                    <flux:text class="font-semibold text-lg">Select User Role</flux:text>
                </div>
                <flux:text class="mb-3">Choose the appropriate role based on the user's responsibilities:</flux:text>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-3 mt-3">
                    <div class="bg-blue-50 dark:bg-blue-900/20 p-3 rounded border">
                        <flux:text class="font-semibold text-blue-800 dark:text-blue-200">Admin</flux:text>
                        <flux:text class="text-xs text-blue-700 dark:text-blue-300">Event management, tickets, sales monitoring</flux:text>
                    </div>
                    <div class="bg-green-50 dark:bg-green-900/20 p-3 rounded border">
                        <flux:text class="font-semibold text-green-800 dark:text-green-200">Teacher</flux:text>
                        <flux:text class="text-xs text-green-700 dark:text-green-300">Sell tickets, scan entries, walk-in sales</flux:text>
                    </div>
                    <div class="bg-purple-50 dark:bg-purple-900/20 p-3 rounded border">
                        <flux:text class="font-semibold text-purple-800 dark:text-purple-200">Student</flux:text>
                        <flux:text class="text-xs text-purple-700 dark:text-purple-300">View and manage personal tickets</flux:text>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bulk Student Upload -->
    <div class="mb-8">
        <flux:heading size="lg" class="mb-4">Bulk Student Upload</flux:heading>
        
        <flux:callout color="green" icon="arrow-up-tray">
            <flux:callout.heading>Excel File Import</flux:callout.heading>
            <flux:callout.text>
                Upload multiple student accounts at once using an Excel file. Perfect for class enrollment at the beginning of a term.
            </flux:callout.text>
        </flux:callout>

        <div class="mt-6 space-y-4">
            <div class="border border-zinc-200 dark:border-zinc-600 rounded-lg p-4">
                <flux:text class="font-semibold mb-2">1. Download Template</flux:text>
                <flux:text class="text-sm text-zinc-600 dark:text-zinc-400 mb-2">
                    Navigate to Admin Controls → Bulk Student Upload and click "Download Template" to get the correct Excel format.
                </flux:text>
                <div class="bg-zinc-50 dark:bg-zinc-800 p-3 rounded border text-sm">
                    <flux:text class="font-medium mb-1">Required columns:</flux:text>
                    <ul class="space-y-1 text-xs">
                        <li>• <strong>name:</strong> Student's full name</li>
                        <li>• <strong>email:</strong> Student's email address (must be unique)</li>
                        <li>• <strong>ic_number:</strong> Student IC number (preserved as text)</li>
                    </ul>
                </div>
            </div>

            <div class="border border-zinc-200 dark:border-zinc-600 rounded-lg p-4">
                <flux:text class="font-semibold mb-2">2. Upload and Review</flux:text>
                <flux:text class="text-sm text-zinc-600 dark:text-zinc-400">
                    Upload your file and review the results. The system will show you any errors (like duplicate emails) and successful imports.
                </flux:text>
            </div>
        </div>
    </div>

    <!-- Role & Permission Management -->
    <div class="mb-8">
        <flux:heading size="lg" class="mb-4">Managing Roles and Permissions</flux:heading>
        
        <div class="space-y-4">
            <div class="border border-zinc-200 dark:border-zinc-600 rounded-lg p-4">
                <flux:text class="font-semibold mb-2">Role Hierarchy</flux:text>
                <div class="space-y-2 text-sm">
                    <div class="flex items-center gap-2">
                        <flux:badge color="red">Super Admin</flux:badge>
                        <flux:text>All permissions + role management (cannot be modified)</flux:text>
                    </div>
                    <div class="flex items-center gap-2">
                        <flux:badge color="blue">Admin</flux:badge>
                        <flux:text>Event management, sales monitoring, ticket management</flux:text>
                    </div>
                    <div class="flex items-center gap-2">
                        <flux:badge color="green">Teacher</flux:badge>
                        <flux:text>Ticket sales, scanning, walk-in sales</flux:text>
                    </div>
                    <div class="flex items-center gap-2">
                        <flux:badge color="gray">Student</flux:badge>
                        <flux:text>View personal tickets only</flux:text>
                    </div>
                </div>
            </div>

            <div class="border border-zinc-200 dark:border-zinc-600 rounded-lg p-4">
                <flux:text class="font-semibold mb-2">Customizing Permissions</flux:text>
                <flux:text class="text-sm text-zinc-600 dark:text-zinc-400 mb-2">
                    On the Roles and Permissions page, you can:
                </flux:text>
                <ul class="space-y-1 text-sm">
                    <li>• Modify what each role can do (except Super Admin)</li>
                    <li>• Add or remove specific permissions</li>
                    <li>• View permission categories (Concerts, Tickets, Users, etc.)</li>
                    <li>• Changes take effect immediately</li>
                </ul>
                <div class="bg-blue-50 dark:bg-blue-900/20 p-3 rounded border border-blue-200 dark:border-blue-700 mt-3">
                    <flux:text class="text-sm text-blue-700 dark:text-blue-300">
                        <strong>Note:</strong> Super Admin permissions are fixed and cannot be modified for security reasons.
                    </flux:text>
                </div>
            </div>
        </div>
    </div>

    <!-- Best Practices -->
    <div class="mb-8">
        <flux:heading size="lg" class="mb-4">Best Practices</flux:heading>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="space-y-3">
                <flux:text class="font-semibold text-green-600 dark:text-green-400">✓ Do</flux:text>
                <ul class="space-y-1 text-sm">
                    <li>• Regularly review user roles and permissions</li>
                    <li>• Use bulk upload for multiple students</li>
                    <li>• Test role changes with a test account first</li>
                    <li>• Keep a backup list of admin users</li>
                    <li>• Use descriptive names for user accounts</li>
                    <li>• Verify email addresses before creating accounts</li>
                </ul>
            </div>
            
            <div class="space-y-3">
                <flux:text class="font-semibold text-red-600 dark:text-red-400">✗ Don't</flux:text>
                <ul class="space-y-1 text-sm">
                    <li>• Delete accounts without checking ticket history</li>
                    <li>• Give admin roles to users who don't need them</li>
                    <li>• Use weak passwords for admin accounts</li>
                    <li>• Share super admin credentials</li>
                    <li>• Make bulk changes without testing first</li>
                    <li>• Ignore failed bulk upload errors</li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Security Reminders -->
    <flux:callout color="amber" icon="shield-exclamation">
        <flux:callout.heading>Security Reminders</flux:callout.heading>
        <flux:callout.text>
            <ul class="mt-2 space-y-1">
                <li>• Always use strong passwords for administrative accounts</li>
                <li>• Review user permissions regularly</li>
                <li>• Don't share super admin credentials with anyone</li>
                <li>• Monitor user activity for suspicious behavior</li>
                <li>• Keep the system updated with latest security patches</li>
            </ul>
        </flux:callout.text>
    </flux:callout>
</div> 