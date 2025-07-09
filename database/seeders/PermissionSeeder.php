<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create permissions for concerts
        Permission::create(['name' => 'create concerts']);
        Permission::create(['name' => 'view concerts']);
        Permission::create(['name' => 'edit concerts']);
        Permission::create(['name' => 'delete concerts']);

        // Create permissions for tickets
        Permission::create(['name' => 'create tickets']);
        Permission::create(['name' => 'view tickets']);
        Permission::create(['name' => 'edit tickets']);
        Permission::create(['name' => 'delete tickets']);
        Permission::create(['name' => 'confirm tickets']);
        Permission::create(['name' => 'scan tickets']);
        Permission::create(['name' => 'assign tickets']);

        // Create permissions for users
        Permission::create(['name' => 'create users']);
        Permission::create(['name' => 'view users']);
        Permission::create(['name' => 'edit users']);
        Permission::create(['name' => 'delete users']);

        // Create permissions for roles and permissions management
        Permission::create(['name' => 'manage roles']);
        Permission::create(['name' => 'manage permissions']);
        Permission::create(['name' => 'assign roles']);

        // Create permissions for ticket sales and reports
        Permission::create(['name' => 'view ticket sales']);
        Permission::create(['name' => 'generate reports']);
        Permission::create(['name' => 'sell vip tickets']);

        // Create permissions for walk-in tickets
        Permission::create(['name' => 'manage walk-in tickets']);
        Permission::create(['name' => 'scan walk-in sales']);

        // Create permission for viewing own tickets
        Permission::create(['name' => 'view own tickets']);

        // Create permission for bulk student upload
        Permission::create(['name' => 'bulk upload students']);

        // Assign permissions to roles
        $superAdminRole = Role::findByName('super-admin');
        $adminRole = Role::findByName('admin');
        $teacherRole = Role::findByName('teacher');
        $studentRole = Role::findByName('student');

        // Super Admin gets ALL permissions
        $superAdminRole->givePermissionTo(Permission::all());

        // Admin gets all permissions EXCEPT role/permission management
        $adminRole->givePermissionTo([
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
            'sell vip tickets',
            'manage walk-in tickets',
            'scan walk-in sales',
            'bulk upload students',
        ]);

        // Teacher permissions - scanning, assigning tickets, and bulk student upload
        $teacherRole->givePermissionTo([
            'view concerts',
            'view tickets',
            'scan tickets',
            'assign tickets',
            'scan walk-in sales',
            'bulk upload students',
        ]);

        // Student permissions - only view their own tickets
        $studentRole->givePermissionTo([
            'view own tickets',
        ]);
    }
}
