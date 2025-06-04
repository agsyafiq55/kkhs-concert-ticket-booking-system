<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Spatie\Permission\Models\Role;

class AssignAdminRole extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:assign-admin-role {email : The email of the user to assign the admin role to}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Assign the admin role to a user by email';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email');
        
        $user = User::where('email', $email)->first();
        
        if (!$user) {
            $this->error("User with email {$email} not found.");
            return 1;
        }
        
        if ($user->hasRole('admin')) {
            $this->info("User {$user->name} already has the admin role.");
            return 0;
        }
        
        $user->assignRole('admin');
        
        $this->info("Admin role assigned to {$user->name} ({$user->email}).");
        
        return 0;
    }
}
