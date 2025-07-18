<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Call the role seeder first
        $this->call(RoleSeeder::class);

        // Call the permission seeder
        $this->call(PermissionSeeder::class);

        // Create all school classes
        $this->call(ClassSeeder::class);

        // Create admin users after roles and permissions are set up
        $this->call(AdminUserSeeder::class);
    }
}
