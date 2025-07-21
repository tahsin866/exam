<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Hash;

class CreateSajedaUserSeeder extends Seeder
{
    public function run()
    {
        // Create or find user
        $user = User::updateOrCreate(
            ['email' => 'sajeda@befaq.edu.bd'],
            [
                'name' => 'সাজেদা',
                'email' => 'sajeda@befaq.edu.bd',
                'password' => Hash::make('password'),
                'user_type' => 'admin',
                'email_verified_at' => now(),
            ]
        );

        // Create or find role
        $role = Role::firstOrCreate(['name' => 'board-admin']);

        // Define permissions for board admin
        $permissions = [
            'mumtahin.access',
            'mumtahin.application_list',
            'mumtahin.proposed_list',
            'mumtahin.inspector_setup'
        ];

        // Give permissions to role
        $role->syncPermissions($permissions);

        // Assign role to user
        $user->syncRoles([$role->name]);

        $this->command->info('সাজেদা user created/updated with board-admin role');
        $this->command->info('User ID: ' . $user->id);
        $this->command->info('Permissions: ' . implode(', ', $permissions));
    }
}
