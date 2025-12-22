<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Quest;

class QuestSeeder extends Seeder
{
    public function run(): void
    {
        $quests = [
            [
                'quest_type' => 'daily',
                'title' => 'Daily Visitor',
                'description' => 'Visit the marketplace today',
                'quest_category' => 'engagement',
                'requirements' => json_encode(['action' => 'site_visit', 'count' => 1]),
                'token_reward' => 10,
                'experience_reward' => 5,
                'max_completions' => 0,
                'difficulty' => 'easy',
                'is_active' => true,
                'icon' => '🏛️',
            ],
            [
                'quest_type' => 'daily',
                'title' => 'Product Explorer',
                'description' => 'View 3 different products',
                'quest_category' => 'engagement',
                'requirements' => json_encode(['action' => 'product_view', 'count' => 3]),
                'token_reward' => 15,
                'experience_reward' => 10,
                'max_completions' => 0,
                'difficulty' => 'easy',
                'is_active' => true,
                'icon' => '🔍',
            ],
            [
                'quest_type' => 'daily',
                'title' => 'Treasure Seeker',
                'description' => 'Find and collect 1 rune today',
                'quest_category' => 'collection',
                'requirements' => json_encode(['action' => 'collect_rune', 'count' => 1]),
                'token_reward' => 20,
                'experience_reward' => 15,
                'max_completions' => 0,
                'difficulty' => 'medium',
                'is_active' => true,
                'icon' => '💎',
            ],
            [
                'quest_type' => 'weekly',
                'title' => 'Viking Shopper',
                'description' => 'Make a purchase this week',
                'quest_category' => 'purchase',
                'requirements' => json_encode(['action' => 'complete_order', 'count' => 1]),
                'token_reward' => 100,
                'experience_reward' => 50,
                'max_completions' => 0,
                'difficulty' => 'medium',
                'is_active' => true,
                'icon' => '🛒',
            ],
            [
                'quest_type' => 'weekly',
                'title' => 'Social Warrior',
                'description' => 'Refer a friend',
                'quest_category' => 'social',
                'requirements' => json_encode(['action' => 'successful_referral', 'count' => 1]),
                'token_reward' => 150,
                'experience_reward' => 75,
                'max_completions' => 0,
                'difficulty' => 'hard',
                'is_active' => true,
                'icon' => '👥',
            ],
            [
                'quest_type' => 'achievement',
                'title' => 'Master Collector',
                'description' => 'Complete 5 rune sets',
                'quest_category' => 'collection',
                'requirements' => json_encode(['action' => 'complete_rune_sets', 'count' => 5]),
                'token_reward' => 500,
                'experience_reward' => 250,
                'max_completions' => 1,
                'difficulty' => 'legendary',
                'is_active' => true,
                'icon' => '🏆',
            ],
        ];

        foreach ($quests as $quest) {
            Quest::create($quest);
        }

        $this->command->info('✅ Quests created!');
    }
}
