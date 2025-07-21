<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Hash;

class CreateAdminUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:create-user {name} {email} {password} {permissions*}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create admin user with specific permissions';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = $this->argument('name');
        $email = $this->argument('email');
        $password = $this->argument('password');
        $permissions = $this->argument('permissions');

        // Check if user exists
        if (User::where('email', $email)->exists()) {
            $this->error('User with this email already exists!');
            return;
        }

        // Create user
        $user = User::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
            'user_type' => 'admin',
            'email_verified_at' => now(),
        ]);

        // Create custom role for this user
        $roleName = strtolower(str_replace(' ', '-', $name)) . '-role';
        $role = Role::firstOrCreate(['name' => $roleName]);

        // Assign permissions to role
        $validPermissions = [];
        foreach ($permissions as $permission) {
            if (Permission::where('name', $permission)->exists()) {
                $validPermissions[] = $permission;
            } else {
                $this->warn("Permission '{$permission}' does not exist!");
            }
        }

        if (!empty($validPermissions)) {
            $role->syncPermissions($validPermissions);
            $user->assignRole($role);
        }

        $this->info("Admin user '{$name}' created successfully!");
        $this->info("Email: {$email}");
        $this->info("Password: {$password}");
        $this->info("Permissions: " . implode(', ', $validPermissions));
    }
}
