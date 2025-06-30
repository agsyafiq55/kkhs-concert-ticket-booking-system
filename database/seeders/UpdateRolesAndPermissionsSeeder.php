<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UpdateRolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * This seeder updates existing roles and permissions to the new hierarchy.
     */
    public function run(): void
    {
        // Clear the cache
        app()['cache']->forget('spatie.permission.cache');

        // Create new permissions if they don't exist
        $newPermissions = [
            'assign tickets',
            'manage roles',
            'manage permissions', 
            'assign roles',
            'view ticket sales',
            'generate reports',
            'manage walk-in tickets',
            'scan walk-in sales',
            'view own tickets'
        ];

        foreach ($newPermissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Create super-admin role if it doesn't exist
        $superAdminRole = Role::firstOrCreate(['name' => 'super-admin']);

        // Get existing roles
        $adminRole = Role::findByName('admin');
        $teacherRole = Role::findByName('teacher');  
        $studentRole = Role::findByName('student');

        // Super Admin gets ALL permissions
        $superAdminRole->givePermissionTo(Permission::all());

        // Admin gets all permissions EXCEPT role/permission management
        $adminRole->syncPermissions([
            'create concerts',
            'view concerts',
            'edit concerts',
            'delete concerts',
            'create tickets',
            'view tickets',
            'edit tickets',
            'delete tickets',
            'confirm tickets',
            'scan tickets',
            'assign tickets',
            'create users',
            'view users',
            'edit users',
            'delete users',
            'view ticket sales',
            'generate reports',
            'manage walk-in tickets',
            'scan walk-in sales'
        ]);

        // Teacher permissions - only scanning and assigning tickets
        $teacherRole->syncPermissions([
            'view concerts',
            'view tickets',
            'scan tickets',
            'assign tickets',
            'scan walk-in sales'
        ]);

        // Student permissions - only view their own tickets
        $studentRole->syncPermissions([
            'view own tickets'
        ]);

        $this->command->info('Roles and permissions updated successfully!');
        $this->command->info('New hierarchy:');
        $this->command->info('- Super Admin: All permissions');
        $this->command->info('- Admin: All permissions except role management');
        $this->command->info('- Teacher: Ticket scanning and assignment only');
        $this->command->info('- Student: View own tickets only');
    }
} 