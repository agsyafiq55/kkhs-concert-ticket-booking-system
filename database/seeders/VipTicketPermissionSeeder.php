<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class VipTicketPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create VIP ticket sales permission
        $permission = Permission::firstOrCreate(['name' => 'sell vip tickets']);
        
        // Assign to appropriate roles
        $superAdminRole = Role::findByName('super-admin');
        $adminRole = Role::findByName('admin');
        
        // Give permission to super-admin and admin roles
        $superAdminRole->givePermissionTo($permission);
        $adminRole->givePermissionTo($permission);
        
        $this->command->info('VIP ticket sales permission created and assigned successfully!');
    }
}
