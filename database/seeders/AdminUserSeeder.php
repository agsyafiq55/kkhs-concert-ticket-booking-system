<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Super Admin account
        $superAdmin = User::create([
            'name' => 'Super Administrator',
            'email' => 'superadmin@gmail.com',
            'password' => Hash::make('superadminpass'),
            'email_verified_at' => now(),
        ]);

        // Assign super-admin role
        $superAdmin->assignRole('super-admin');

        // Create Admin account
        $admin = User::create([
            'name' => 'Administrator',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('adminpass'),
            'email_verified_at' => now(),
        ]);

        // Assign admin role
        $admin->assignRole('admin');
    }
}
