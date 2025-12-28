<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Quest;

class QuestSeeder extends Seeder
{
    public function run(): void
    {
        $quests = [
            // ====================================
            // STORY QUESTS (Main Achievements)
            // ====================================
            [
                'quest_type' => 'achievement',
                'title' => "Ragnar's Journey",
                'description' => 'Follow Ragnar Lothbrok as he discovers the mystical floating island and establishes the first trade routes with the Island Merchants.',
                'quest_category' => 'exploration', // ← Changed from 'main'
                'requirements' => json_encode([
                    'action' => 'complete_story_steps',
                    'steps' => [
                        'Watch Ragnar\'s ship approach',
                        'Learn about the first encounter',
                        'Discover the Island Merchants',
                        'Establish trade routes',
                        'Complete the journey'
                    ]
                ]),
                'token_reward' => 100,
                'experience_reward' => 50,
                'max_completions' => 1,
                'difficulty' => 'easy',
                'is_active' => true,
                'icon' => '🚢',
            ],
            [
                'quest_type' => 'achievement',
                'title' => 'The Island Merchant',
                'description' => 'Meet Astrid the Truthseer and prove yourself worthy of trading with the ancient Island Merchants.',
                'quest_category' => 'exploration', // ← Changed from 'main'
                'requirements' => json_encode([
                    'action' => 'complete_story_steps',
                    'steps' => [
                        'Meet Astrid the Wise',
                        'Accept the Trial',
                        'Visit the Sacred Trees',
                        'Touch the Ancient Pillars',
                        'Discover the Hidden Flowers'
                    ]
                ]),
                'token_reward' => 150,
                'experience_reward' => 75,
                'max_completions' => 1,
                'difficulty' => 'easy',
                'is_active' => true,
                'icon' => '🏝️',
            ],
            [
                'quest_type' => 'achievement',
                'title' => 'The Great Raid',
                'description' => 'Join the legendary raid on Lindisfarne and claim treasures from across the seas.',
                'quest_category' => 'exploration', // ← Changed from 'combat'
                'requirements' => json_encode([
                    'action' => 'complete_story_steps',
                    'steps' => [
                        'Prepare the longship',
                        'Navigate the North Sea',
                        'Storm the monastery',
                        'Claim the treasures'
                    ]
                ]),
                'token_reward' => 250,
                'experience_reward' => 150,
                'max_completions' => 1,
                'difficulty' => 'medium',
                'is_active' => true,
                'icon' => '⚔️',
            ],
            [
                'quest_type' => 'achievement',
                'title' => "Shield Maiden's Trial",
                'description' => 'Prove your worth alongside Lagertha in the ultimate test of strength and cunning.',
                'quest_category' => 'exploration', // ← Changed from 'combat'
                'requirements' => json_encode([
                    'action' => 'complete_story_steps',
                    'steps' => [
                        'Master the shield wall',
                        'Win the sparring match',
                        'Lead a successful raid',
                        'Earn Lagertha\'s blessing'
                    ]
                ]),
                'token_reward' => 300,
                'experience_reward' => 200,
                'max_completions' => 1,
                'difficulty' => 'medium',
                'is_active' => true,
                'icon' => '🛡️',
            ],
            [
                'quest_type' => 'achievement',
                'title' => 'Voyage to Valhalla',
                'description' => 'Sail to the gates of Valhalla and return with Odin\'s blessing.',
                'quest_category' => 'exploration', // ← Changed from 'legendary'
                'requirements' => json_encode([
                    'action' => 'complete_story_steps',
                    'steps' => [
                        'Consult the Völva',
                        'Gather the sacred runes',
                        'Navigate the Rainbow Bridge',
                        'Face the Valkyries',
                        'Receive Odin\'s blessing'
                    ]
                ]),
                'token_reward' => 500,
                'experience_reward' => 500,
                'max_completions' => 1,
                'difficulty' => 'hard',
                'is_active' => true,
                'icon' => '⚡',
            ],

            // ====================================
            // DAILY QUESTS
            // ====================================
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

            // ====================================
            // WEEKLY QUESTS
            // ====================================
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

            // ====================================
            // ACHIEVEMENT QUESTS
            // ====================================
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
            [
                'quest_type' => 'achievement',
                'title' => 'First Purchase',
                'description' => 'Complete your first order',
                'quest_category' => 'purchase', // ← Changed from 'milestone'
                'requirements' => json_encode(['action' => 'first_order', 'count' => 1]),
                'token_reward' => 200,
                'experience_reward' => 100,
                'max_completions' => 1,
                'difficulty' => 'easy',
                'is_active' => true,
                'icon' => '🎉',
            ],
        ];

        foreach ($quests as $quest) {
            Quest::updateOrCreate(
                ['title' => $quest['title']],
                $quest
            );
        }

        $this->command->info('✅ ' . count($quests) . ' quests created/updated successfully!');
    }
}
