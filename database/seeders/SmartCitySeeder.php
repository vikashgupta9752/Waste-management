<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SmartCitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Initial Badges
        \App\Models\Badge::create([
            'name' => 'Green Champion',
            'description' => 'Complete your first 10 waste requests.',
            'icon' => 'fa-leaf',
            'requirement_points' => 100,
        ]);

        \App\Models\Badge::create([
            'name' => 'Eco Hero',
            'description' => 'Segregate 50kg of dry waste.',
            'icon' => 'fa-recycle',
            'requirement_points' => 500,
        ]);

        \App\Models\Badge::create([
            'name' => 'Waste Warrior',
            'description' => 'Help 5 neighbors with their recycling.',
            'icon' => 'fa-shield-halved',
            'requirement_points' => 1000,
        ]);

        // Sample notifications for testing
        $users = \App\Models\User::all();
        foreach ($users as $user) {
            \App\Models\AppNotification::create([
                'user_id' => $user->id,
                'title' => 'Welcome to Smart City!',
                'message' => 'Check out the new Gamification features in your dashboard.',
                'type' => 'info',
            ]);
        }
    }
}
