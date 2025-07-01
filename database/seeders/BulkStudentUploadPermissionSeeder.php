<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class BulkStudentUploadPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear the cache
        app()['cache']->forget('spatie.permission.cache');

        // Create the bulk student upload permission
        $permission = Permission::firstOrCreate(['name' => 'bulk upload students']);

        // Get the roles
        $superAdminRole = Role::findByName('super-admin');
        $adminRole = Role::findByName('admin');
        $teacherRole = Role::findByName('teacher');

        // Assign the permission to the appropriate roles
        $superAdminRole->givePermissionTo($permission);
        $adminRole->givePermissionTo($permission);
        $teacherRole->givePermissionTo($permission);

        $this->command->info('Bulk student upload permission created and assigned successfully!');
        $this->command->info('Roles with permission:');
        $this->command->info('- Super Admin');
        $this->command->info('- Admin');
        $this->command->info('- Teacher');
    }
}
