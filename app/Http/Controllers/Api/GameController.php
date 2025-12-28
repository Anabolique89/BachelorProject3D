<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SpinPrize;
use App\Models\DailyLoginStreak;
use App\Models\QuestCompletion;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class GameController extends Controller
{
    /**
     * Check which popups to show
     */
    public function checkPopups(Request $request)
    {
        $customer = $request->user()->customer;

        // Check if quests are already completed
        $ragnarsJourneyCompleted = \App\Models\QuestCompletion::where('customer_id', $customer->id)
            ->where('quest_id', 1)
            ->exists();

        $islandMerchantCompleted = \App\Models\QuestCompletion::where('customer_id', $customer->id)
            ->where('quest_id', 2)
            ->exists();

        $popups = [];

        // Only show Ragnar's Journey if not completed
        if (!$ragnarsJourneyCompleted) {
            $popups[] = [
                'type' => 'viking_boat',
                'message' => 'Click the Viking boat to start Ragnar\'s Journey!',
                'quest_id' => 1,
                'rewards' => [
                    'tokens' => 100,
                    'runes' => 3,
                    'discount' => 10,
                ]
            ];
        }

        // Only show Island Merchant if not completed
        if (!$islandMerchantCompleted) {
            $popups[] = [
                'type' => 'island_merchant',
                'message' => 'Explore the mysterious floating island!',
                'quest_id' => 2,
                'rewards' => [
                    'tokens' => 150,
                    'runes' => 5,
                    'discount' => 20,
                    'badge' => 'merchant-blessing',
                ]
            ];
        }

        return response()->json([
            'success' => true,
            'popups' => $popups,
            'ragnars_journey_completed' => $ragnarsJourneyCompleted,
            'island_merchant_completed' => $islandMerchantCompleted,
        ]);
    }

    public function claimSpinPrize(Request $request)
    {
        $validated = $request->validate([
            'prize_type' => 'required|string',
            'prize_value' => 'required|integer',
            'prize_label' => 'required|string',
        ]);

        $customer = $request->user()->customer;

        DB::beginTransaction();

        try {
            $expiresAt = now()->addDays(30);
            $discountCode = null;

            if ($validated['prize_type'] === 'discount' || $validated['prize_type'] === 'free_shipping') {
                $discountCode = 'SPIN-' . strtoupper(uniqid());
            }

            $spinPrize = SpinPrize::create([
                'customer_id' => $customer->id,
                'prize_type' => $validated['prize_type'],
                'prize_value' => $validated['prize_value'],
                'prize_label' => $validated['prize_label'],
                'discount_code' => $discountCode,
                'expires_at' => $expiresAt,
            ]);

            // Award tokens/runes if applicable
            if ($validated['prize_type'] === 'tokens') {
                $customer->increment('total_tokens', $validated['prize_value']);
                $customer->increment('available_tokens', $validated['prize_value']);
            } elseif ($validated['prize_type'] === 'runes') {
                // TODO: Add rune logic
            }

            $customer->update(['has_used_welcome_spin' => true]);

            DB::commit();

            return response()->json([
                'success' => true,
                'prize' => $spinPrize,
                'message' => 'Prize claimed successfully!',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to claim prize',
            ], 500);
        }
    }

    /**
     * Claim daily login reward
     */
    public function claimDailyReward(Request $request)
    {
        $validated = $request->validate([
            'day' => 'required|integer|min:1|max:7',
        ]);

        $customer = $request->user()->customer;
        $loginStreak = DailyLoginStreak::where('customer_id', $customer->id)->first();

        if (!$loginStreak || $loginStreak->has_claimed_today) {
            return response()->json([
                'success' => false,
                'message' => 'Already claimed today or no login streak found',
            ], 400);
        }

        DB::beginTransaction();

        try {
            // Define rewards per day
            $rewards = [
                1 => ['tokens' => 10, 'runes' => 0],
                2 => ['tokens' => 20, 'runes' => 0],
                3 => ['tokens' => 30, 'runes' => 1],
                4 => ['tokens' => 50, 'runes' => 0],
                5 => ['tokens' => 50, 'runes' => 2],
                6 => ['tokens' => 0, 'runes' => 0, 'discount' => 15],
                7 => ['tokens' => 500, 'runes' => 1, 'discount' => 20], // MEGA reward
            ];

            $dayReward = $rewards[$validated['day']];

            // Award tokens
            if ($dayReward['tokens'] > 0) {
                $customer->increment('total_tokens', $dayReward['tokens']);
                $customer->increment('available_tokens', $dayReward['tokens']);
                $loginStreak->increment('total_tokens_earned', $dayReward['tokens']);
            }

            // Award runes
            if ($dayReward['runes'] > 0) {
                // TODO: Add rune collection logic
                $loginStreak->increment('total_runes_earned', $dayReward['runes']);
            }

            // Mark as claimed
            $loginStreak->update(['has_claimed_today' => true]);

            DB::commit();

            return response()->json([
                'success' => true,
                'rewards' => $dayReward,
                'message' => 'Daily reward claimed!',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to claim reward',
            ], 500);
        }
    }

    /**
     * Complete a quest
     */
    public function completeQuest(Request $request)
    {
        $validated = $request->validate([
            'quest_id' => 'required|string',
            'rewards' => 'required|array',
            'rewards.tokens' => 'sometimes|integer',
            'rewards.runes' => 'sometimes|integer',
            'rewards.discount' => 'sometimes|integer',
            'rewards.badge' => 'sometimes|string',
        ]);

        $customer = $request->user()->customer;

        // Check if already completed
        $alreadyCompleted = QuestCompletion::where('customer_id', $customer->id)
            ->where('quest_id', $validated['quest_id'])
            ->exists();

        if ($alreadyCompleted) {
            return response()->json([
                'success' => false,
                'message' => 'Quest already completed',
            ], 400);
        }

        DB::beginTransaction();

        try {
            $questNames = [
                'ragnar-journey' => "Ragnar's Journey",
                'island-merchant' => 'Island Merchant Quest',
            ];

            // Award tokens
            if (isset($validated['rewards']['tokens'])) {
                $customer->increment('total_tokens', $validated['rewards']['tokens']);
                $customer->increment('available_tokens', $validated['rewards']['tokens']);
            }

            // Award runes
            if (isset($validated['rewards']['runes'])) {
                // TODO: Implement rune collection
            }

            // Create quest completion record
            QuestCompletion::create([
                'customer_id' => $customer->id,
                'quest_id' => $validated['quest_id'],
                'quest_name' => $questNames[$validated['quest_id']] ?? 'Unknown Quest',
                'rewards' => $validated['rewards'],
                'completed_at' => now(),
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'rewards' => $validated['rewards'],
                'message' => 'Quest completed!',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to complete quest',
            ], 500);
        }
    }

    /**
     * Get all available quests and their completion status
     */

    public function getQuests(Request $request)
    {
        $customer = $request->user()->customer;

        $quests = \App\Models\Quest::where('is_active', true)
            ->get()
            ->map(function ($quest) use ($customer) {
                $completion = \App\Models\QuestCompletion::where('customer_id', $customer->id)
                    ->where('quest_id', $quest->id)
                    ->first();

                return [
                    'id' => $quest->id,
                    'title' => $quest->title,
                    'description' => $quest->description,
                    'quest_type' => $quest->quest_type,
                    'quest_category' => $quest->quest_category,
                    'difficulty' => $quest->difficulty,
                    'icon' => $quest->icon ?? '⚔️', // ← Default icon if null
                    'requirements' => $quest->requirements,
                    'rewards' => [ // ← FIX: Always return rewards object
                        'tokens' => $quest->token_reward ?? 0,
                        'experience' => $quest->experience_reward ?? 0,
                    ],
                    'max_completions' => $quest->max_completions,
                    'is_completed' => $completion !== null,
                    'completed_at' => $completion?->completed_at,
                ];
            });

        return response()->json([
            'success' => true,
            'quests' => $quests,
        ]);
    }

    /**
     * Get customer stats
     */
    public function getStats(Request $request)
    {
        $customer = $request->user()->customer;

        $completedQuests = \App\Models\QuestCompletion::where('customer_id', $customer->id)->count();
        $totalQuests = \App\Models\Quest::count();

        return response()->json([
            'success' => true,
            'stats' => [
                'level' => $customer->userLevel?->level ?? 1,
                'level_name' => $customer->userLevel?->name ?? 'Thrall',
                'total_tokens' => $customer->total_tokens,
                'available_tokens' => $customer->available_tokens,
                'used_tokens' => $customer->used_tokens,
                'completed_quests' => $completedQuests,
                'total_quests' => $totalQuests,
                'completion_percentage' => $totalQuests > 0 ? round(($completedQuests / $totalQuests) * 100, 2) : 0,
            ],
        ]);
    }

    /**
     * Get customer treasury/stats
     */
    public function getTreasury(Request $request)
    {
        $customer = $request->user()->customer;

        // Get completed quests count
        $completedQuests = \App\Models\QuestCompletion::where('customer_id', $customer->id)->count();

        // Get orders
        $orders = \App\Models\Order::where('customer_id', $customer->id)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return response()->json([
            'success' => true,
            'tokens' => $customer->total_tokens ?? 0,
            'runes' => 0, // TODO: Implement runes when ready
            'order_count' => $orders->count(),
            'total_saved' => 0, // TODO: Calculate from discount usage
            'level' => [
                'name' => $customer->userLevel?->name ?? 'Thrall',
                'level' => $customer->userLevel?->level ?? 1,
            ],
            'tokens_to_next_level' => 500, // TODO: Calculate based on next level
            'level_progress' => 0, // TODO: Calculate percentage
            'active_discounts' => [], // TODO: Get from database when implemented
            'recent_orders' => $orders->map(function ($order) {
                return [
                    'id' => $order->id,
                    'order_number' => $order->order_number,
                    'total_price' => $order->total_price,
                    'status' => $order->status,
                    'created_at' => $order->created_at->format('M d, Y'),
                ];
            }),
        ]);
    }

    /**
     * Get customer runes data
     */
    public function getRunes(Request $request)
    {
        $customer = $request->user()->customer;

        // TODO: Implement actual rune collection when database tables are ready
        return response()->json([
            'success' => true,
            'total_runes' => 0,
            'rune_collection' => [], // Will contain rune types and counts
            'available_conversions' => [], // Runes that can be converted to discounts
            'message' => 'Rune system coming soon!',
        ]);
    }
    /**
     * Check if user should see welcome spin
     */
    public function checkWelcomeSpin(Request $request)
    {
        $customer = $request->user()->customer;

        return response()->json([
            'success' => true,
            'should_show_spin' => !$customer->has_used_welcome_spin,
            'has_spun' => $customer->has_used_welcome_spin,
        ]);
    }
}
