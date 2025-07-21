<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DefaultAdminSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        // Create default super admin
        $admin = User::firstOrCreate(
            ['email' => 'admin@befaq.edu.bd'],
            [
                'name' => 'সুপার এডমিন',
                'email' => 'admin@befaq.edu.bd',
                'password' => Hash::make('Admin@123456'),
                'user_type' => 'admin',
                'phone' => '01700000000',
                'nid' => '1234567890123',
                'address' => 'বেফাক অফিস, ঢাকা',
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );

        // Assign super admin role
        $admin->assignRole('super-admin');

        echo "Default Admin Created:\n";
        echo "Email: admin@befaq.edu.bd\n";
        echo "Password: Admin@123456\n";
        echo "\nPlease change this password after first login!\n";
    }
}
