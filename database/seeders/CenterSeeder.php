<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Center;
use Illuminate\Support\Facades\DB;

class CenterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing data
        DB::table('centers')->truncate();

        // Insert initial data
        $centers = [
            [
                'id' => 1,
                'code' => 'D',
                'name' => 'Darshiyat',
                'description' => 'Darshiyat center for Islamic studies',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => 2,
                'code' => 'H',
                'name' => 'Hifz',
                'description' => 'Hifz center for Quran memorization',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => 3,
                'code' => 'K',
                'name' => 'Qiraat',
                'description' => 'Qiraat center for Quran recitation',
                'created_at' => now(),
                'updated_at' => now()
            ]
        ];

        // Insert using Eloquent for better handling
        foreach ($centers as $center) {
            Center::create($center);
        }

        $this->command->info('Centers seeded successfully!');
    }
}
