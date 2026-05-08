<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\WasteCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'Driver John',
            'email' => 'driver@example.com',
            'password' => Hash::make('password'),
            'role' => 'driver',
        ]);

        User::create([
            'name' => 'Citizen Jane',
            'email' => 'citizen@example.com',
            'password' => Hash::make('password'),
            'role' => 'citizen',
        ]);

        WasteCategory::create([
            'name' => 'Wet Waste',
            'description' => 'Organic matter like food scraps',
            'guidelines' => 'Keep in green bins. Do not mix with plastic.',
        ]);

        WasteCategory::create([
            'name' => 'Dry Waste',
            'description' => 'Recyclable materials like paper, plastic, glass',
            'guidelines' => 'Keep in blue bins. Ensure items are clean.',
        ]);

        WasteCategory::create([
            'name' => 'Hazardous Waste',
            'description' => 'Batteries, chemicals, medical waste',
            'guidelines' => 'Keep in red bins. Handle with care.',
        ]);
    }
}
