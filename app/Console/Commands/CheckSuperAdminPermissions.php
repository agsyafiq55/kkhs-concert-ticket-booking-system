<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class CheckSuperAdminPermissions extends Command
{
    protected $signature = 'admin:check-permissions {email=superadmin@gmail.com}';

    protected $description = 'Check permissions for a user';

    public function handle()
    {
        $email = $this->argument('email');
        $user = User::where('email', $email)->first();

        if (! $user) {
            $this->error("User with email {$email} not found!");

            return 1;
        }

        $this->info("Checking permissions for: {$user->name} ({$user->email})");
        $this->info('Roles: '.$user->roles->pluck('name')->join(', '));

        $this->info("\nDirect Permissions:");
        foreach ($user->permissions as $permission) {
            $this->line("- {$permission->name}");
        }

        $this->info("\nAll Permissions (including via roles):");
        foreach ($user->getAllPermissions() as $permission) {
            $this->line("- {$permission->name}");
        }

        // Check specific permissions
        $criticalPermissions = [
            'assign tickets',
            'scan tickets',
            'scan walk-in sales',
            'view own tickets',
        ];

        $this->info("\nCritical Permissions Check:");
        foreach ($criticalPermissions as $permissionName) {
            $hasPermission = $user->can($permissionName);
            $status = $hasPermission ? '✓' : '✗';
            $this->line("{$status} {$permissionName}");
        }

        return 0;
    }
}
