<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Bin;

class BinSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $bins = [
            [
                'location_name' => 'Main Street Central',
                'latitude' => 23.8103,
                'longitude' => 90.4125,
                'fill_level' => 45,
                'status' => 'active'
            ],
            [
                'location_name' => 'North Park Entrance',
                'latitude' => 23.8203,
                'longitude' => 90.4225,
                'fill_level' => 85,
                'status' => 'active'
            ],
            [
                'location_name' => 'University Gate 1',
                'latitude' => 23.8003,
                'longitude' => 90.4025,
                'fill_level' => 10,
                'status' => 'active'
            ],
            [
                'location_name' => 'Shopping Mall South',
                'latitude' => 23.8153,
                'longitude' => 90.4155,
                'fill_level' => 60,
                'status' => 'active'
            ],
            [
                'location_name' => 'Residential Block B',
                'latitude' => 23.8053,
                'longitude' => 90.4085,
                'fill_level' => 95,
                'status' => 'full'
            ],
        ];

        foreach ($bins as $bin) {
            Bin::create($bin);
        }
    }
}
