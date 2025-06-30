<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class FixSuperAdminPermissions extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'admin:fix-permissions';

    /**
     * The console command description.
     */
    protected $description = 'Fix super admin permissions';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Fixing super admin permissions...');
        
        try {
            // Get the super admin role
            $superAdminRole = Role::findByName('super-admin');
            
            if (!$superAdminRole) {
                $this->error('Super admin role not found!');
                return 1;
            }
            
            // Get all permissions
            $allPermissions = Permission::all();
            
            // Sync all permissions to super admin
            $superAdminRole->syncPermissions($allPermissions);
            
            $this->info("Super admin now has {$superAdminRole->permissions->count()} permissions out of {$allPermissions->count()} total permissions.");
            
            // Also ensure the superadmin@gmail.com user has the role
            $superAdminUser = User::where('email', 'superadmin@gmail.com')->first();
            if ($superAdminUser && !$superAdminUser->hasRole('super-admin')) {
                $superAdminUser->assignRole('super-admin');
                $this->info('Assigned super-admin role to superadmin@gmail.com user.');
            }
            
            $this->success('Super admin permissions have been fixed!');
            
        } catch (\Exception $e) {
            $this->error('Error fixing permissions: ' . $e->getMessage());
            return 1;
        }
        
        return 0;
    }
} 