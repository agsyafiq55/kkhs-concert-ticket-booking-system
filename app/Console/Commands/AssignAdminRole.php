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
    protected $signature = 'app:assign-admin-role {email : The email of the user to assign the admin role to} {--super : Assign super-admin role instead of admin}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Assign the admin or super-admin role to a user by email';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email');
        $isSuper = $this->option('super');
        $roleName = $isSuper ? 'super-admin' : 'admin';
        
        $user = User::where('email', $email)->first();
        
        if (!$user) {
            $this->error("User with email {$email} not found.");
            return 1;
        }
        
        if ($user->hasRole($roleName)) {
            $this->info("User {$user->name} already has the {$roleName} role.");
            return 0;
        }
        
        // Remove any existing admin-level roles before assigning new one
        $user->removeRole(['admin', 'super-admin']);
        
        $user->assignRole($roleName);
        
        $this->info("{$roleName} role assigned to {$user->name} ({$user->email}).");
        
        return 0;
    }
}
