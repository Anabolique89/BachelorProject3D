<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CountrySeeder extends Seeder
{
    public function run(): void
    {
        $countries = [
            [
                'code' => 'DK',
                'name' => 'Denmark',
                'states' => json_encode(['Capital Region', 'Central Denmark', 'North Denmark', 'Zealand', 'South Denmark']),
                'is_active' => true,
            ],
            [
                'code' => 'NO',
                'name' => 'Norway',
                'states' => json_encode(['Oslo', 'Vestland', 'Nordland', 'Trøndelag', 'Rogaland']),
                'is_active' => true,
            ],
            [
                'code' => 'SE',
                'name' => 'Sweden',
                'states' => json_encode(['Stockholm', 'Västra Götaland', 'Skåne', 'Uppsala']),
                'is_active' => true,
            ],
            [
                'code' => 'IS',
                'name' => 'Iceland',
                'states' => json_encode(['Capital Region', 'Southern Peninsula', 'Western Region']),
                'is_active' => true,
            ],
            [
                'code' => 'FI',
                'name' => 'Finland',
                'states' => json_encode(['Uusimaa', 'Pirkanmaa', 'North Ostrobothnia']),
                'is_active' => true,
            ],
            [
                'code' => 'US',
                'name' => 'United States',
                'states' => json_encode(['California', 'Texas', 'New York', 'Florida']),
                'is_active' => true,
            ],
            [
                'code' => 'CA',
                'name' => 'Canada',
                'states' => json_encode(['Ontario', 'Quebec', 'British Columbia']),
                'is_active' => true,
            ],
            [
                'code' => 'GB',
                'name' => 'United Kingdom',
                'states' => json_encode(['England', 'Scotland', 'Wales', 'Northern Ireland']),
                'is_active' => true,
            ],
        ];

        DB::table('countries')->insert($countries);

        $this->command->info('✅ Countries created!');
    }
}