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
        Permission::firstOrCreate(['name' => 'create concerts']);
        Permission::firstOrCreate(['name' => 'view concerts']);
        Permission::firstOrCreate(['name' => 'edit concerts']);
        Permission::firstOrCreate(['name' => 'delete concerts']);

        // Create permissions for tickets
        Permission::firstOrCreate(['name' => 'create tickets']);
        Permission::firstOrCreate(['name' => 'view tickets']);
        Permission::firstOrCreate(['name' => 'edit tickets']);
        Permission::firstOrCreate(['name' => 'delete tickets']);
        Permission::firstOrCreate(['name' => 'confirm tickets']);
        Permission::firstOrCreate(['name' => 'scan tickets']);
        Permission::firstOrCreate(['name' => 'assign tickets']);

        // Create permissions for users
        Permission::firstOrCreate(['name' => 'create users']);
        Permission::firstOrCreate(['name' => 'view users']);
        Permission::firstOrCreate(['name' => 'edit users']);
        Permission::firstOrCreate(['name' => 'delete users']);

        // Create permissions for roles and permissions management
        Permission::firstOrCreate(['name' => 'manage roles']);
        Permission::firstOrCreate(['name' => 'manage permissions']);
        Permission::firstOrCreate(['name' => 'assign roles']);

        // Create permissions for ticket sales and reports
        Permission::firstOrCreate(['name' => 'view ticket sales']);
        Permission::firstOrCreate(['name' => 'generate reports']);
        Permission::firstOrCreate(['name' => 'sell vip tickets']);

        // Create permissions for walk-in tickets
        Permission::firstOrCreate(['name' => 'manage walk-in tickets']);
        Permission::firstOrCreate(['name' => 'scan walk-in sales']);

        // Create permission for viewing own tickets
        Permission::firstOrCreate(['name' => 'view own tickets']);

        // Create permissions for bulk uploads
        Permission::firstOrCreate(['name' => 'bulk upload students']);
        Permission::firstOrCreate(['name' => 'bulk upload teachers']);

        // Create permissions for class management
        Permission::firstOrCreate(['name' => 'manage classes']);
        Permission::firstOrCreate(['name' => 'assign student classes']);
        Permission::firstOrCreate(['name' => 'assign teacher classes']);

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
            'bulk upload teachers',
            'manage classes',
            'assign student classes',
            'assign teacher classes',
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
