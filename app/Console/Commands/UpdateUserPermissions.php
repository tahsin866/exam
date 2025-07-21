<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class UpdateUserPermissions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:update-permissions {email} {permissions*}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update user permissions by email';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email');
        $permissions = $this->argument('permissions');

        // Find user
        $user = User::where('email', $email)->first();
        if (!$user) {
            $this->error('User not found!');
            return;
        }

        // Create custom role for this user
        $roleName = strtolower(str_replace(' ', '-', $user->name)) . '-role';
        $role = Role::firstOrCreate(['name' => $roleName]);

        // Validate permissions
        $validPermissions = [];
        foreach ($permissions as $permission) {
            if (Permission::where('name', $permission)->exists()) {
                $validPermissions[] = $permission;
            } else {
                $this->warn("Permission '{$permission}' does not exist!");
            }
        }

        if (!empty($validPermissions)) {
            // Clear existing permissions and roles
            $user->roles()->detach();
            $user->permissions()->detach();
            
            // Assign new permissions
            $role->syncPermissions($validPermissions);
            $user->assignRole($role);
        }

        $this->info("User '{$user->name}' permissions updated!");
        $this->info("Email: {$email}");
        $this->info("New Permissions: " . implode(', ', $validPermissions));
    }
}
