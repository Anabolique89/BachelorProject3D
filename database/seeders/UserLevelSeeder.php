<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\UserLevel;

class UserLevelSeeder extends Seeder
{
    public function run(): void
    {
        $levels = [
            [
                'level' => 1,
                'level_name' => 'Novice Raider',
                'min_experience' => 0,
                'max_experience' => 99,
                'token_multiplier' => 1.00,
                'perks' => json_encode(['description' => 'Welcome to Valhalla!']),
                'icon' => '🛡️',
            ],
            [
                'level' => 2,
                'level_name' => 'Apprentice Viking',
                'min_experience' => 100,
                'max_experience' => 299,
                'token_multiplier' => 1.10,
                'perks' => json_encode(['description' => '10% bonus tokens']),
                'icon' => '⚔️',
            ],
            [
                'level' => 3,
                'level_name' => 'Skilled Warrior',
                'min_experience' => 300,
                'max_experience' => 599,
                'token_multiplier' => 1.20,
                'perks' => json_encode(['description' => '20% bonus tokens']),
                'icon' => '🪓',
            ],
            [
                'level' => 4,
                'level_name' => 'Veteran Raider',
                'min_experience' => 600,
                'max_experience' => 999,
                'token_multiplier' => 1.30,
                'perks' => json_encode(['description' => '30% bonus tokens']),
                'icon' => '⚡',
            ],
            [
                'level' => 5,
                'level_name' => 'Elite Viking',
                'min_experience' => 1000,
                'max_experience' => 1499,
                'token_multiplier' => 1.40,
                'perks' => json_encode(['description' => '40% bonus tokens']),
                'icon' => '👑',
            ],
            [
                'level' => 6,
                'level_name' => 'Master Warrior',
                'min_experience' => 1500,
                'max_experience' => 2499,
                'token_multiplier' => 1.50,
                'perks' => json_encode(['description' => '50% bonus tokens']),
                'icon' => '🔱',
            ],
            [
                'level' => 7,
                'level_name' => 'Legendary Viking',
                'min_experience' => 2500,
                'max_experience' => 9999999,
                'token_multiplier' => 2.00,
                'perks' => json_encode(['description' => '100% bonus tokens']),
                'icon' => '⚡👑',
            ],
        ];

        foreach ($levels as $level) {
            UserLevel::create($level);
        }

        $this->command->info('✅ User levels created!');
    }
}