<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;

class TestUserPermissions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:user-permissions {email}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test user permissions';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email');
        $user = User::where('email', $email)->first();
        
        if (!$user) {
            $this->error('User not found');
            return;
        }
        
        $user->load(['roles.permissions', 'permissions']);
        
        $this->info('User: ' . $user->name);
        $this->info('Email: ' . $user->email);
        $this->info('Type: ' . $user->user_type);
        
        $this->info('Roles:');
        foreach ($user->roles as $role) {
            $this->line('  - ' . $role->name);
        }
        
        $this->info('Direct Permissions:');
        foreach ($user->getDirectPermissions() as $permission) {
            $this->line('  - ' . $permission->name);
        }
    }
}
