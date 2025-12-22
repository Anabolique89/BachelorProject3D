<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\RuneType;

class RuneTypeSeeder extends Seeder
{
    public function run(): void
    {
        $runes = [
            [
                'rune_name' => 'Rune of Strength',
                'rarity' => 'common',
                'description' => 'Found throughout the Viking lands',
                'spawn_rate' => 0.4000,
                'token_value' => 5,
                'icon' => '💪',
                'is_active' => true,
            ],
            [
                'rune_name' => 'Rune of Wisdom',
                'rarity' => 'common',
                'description' => 'Knowledge of the ancients',
                'spawn_rate' => 0.3000,
                'token_value' => 5,
                'icon' => '📚',
                'is_active' => true,
            ],
            [
                'rune_name' => 'Rune of Fortune',
                'rarity' => 'uncommon',
                'description' => 'Brings luck to traders',
                'spawn_rate' => 0.1500,
                'token_value' => 15,
                'icon' => '🍀',
                'is_active' => true,
            ],
            [
                'rune_name' => 'Rune of Thunder',
                'rarity' => 'rare',
                'description' => 'Blessed by Thor himself',
                'spawn_rate' => 0.0800,
                'token_value' => 30,
                'icon' => '⚡',
                'is_active' => true,
            ],
            [
                'rune_name' => 'Rune of Ice',
                'rarity' => 'rare',
                'description' => 'Frozen in eternal winter',
                'spawn_rate' => 0.0500,
                'token_value' => 35,
                'icon' => '❄️',
                'is_active' => true,
            ],
            [
                'rune_name' => 'Rune of Fire',
                'rarity' => 'epic',
                'description' => 'Burns with eternal flame',
                'spawn_rate' => 0.0150,
                'token_value' => 75,
                'icon' => '🔥',
                'is_active' => true,
            ],
            [
                'rune_name' => 'Rune of the Sea',
                'rarity' => 'epic',
                'description' => 'Commands the ocean waves',
                'spawn_rate' => 0.0030,
                'token_value' => 100,
                'icon' => '🌊',
                'is_active' => true,
            ],
            [
                'rune_name' => 'Rune of Odin',
                'rarity' => 'legendary',
                'description' => 'The All-Father\'s mark',
                'spawn_rate' => 0.0020,
                'token_value' => 500,
                'icon' => '👁️',
                'is_active' => true,
            ],
        ];

        foreach ($runes as $rune) {
            RuneType::create($rune);
        }

        $this->command->info('✅ Rune types created!');
    }
}