<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
        
        // Create permissions for users
        Permission::create(['name' => 'create users']);
        Permission::create(['name' => 'view users']);
        Permission::create(['name' => 'edit users']);
        Permission::create(['name' => 'delete users']);
        
        // Assign permissions to roles
        $adminRole = Role::findByName('admin');
        $teacherRole = Role::findByName('teacher');
        $studentRole = Role::findByName('student');
        
        // Admin gets all permissions
        $adminRole->givePermissionTo(Permission::all());
        
        // Teacher permissions
        $teacherRole->givePermissionTo([
            'view concerts',
            'view tickets',
            'create tickets',
            'edit tickets',
            'confirm tickets',
            'scan tickets',
            'view users'
        ]);
        
        // Student permissions
        $studentRole->givePermissionTo([
            'view concerts',
            'view tickets'
        ]);
    }
}
