<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DiscountTier;

class DiscountTierSeeder extends Seeder
{
    public function run(): void
    {
        $tiers = [
            [
                'tier_name' => 'Bronze Treasure',
                'token_cost' => 50,
                'discount_type' => 'percentage',
                'discount_value' => 5.00,
                'minimum_purchase_amount' => 0.00,
                'max_uses_per_user' => 1,
                'valid_days' => 30,
                'description' => 'Convert 50 tokens for 5% off',
                'is_active' => true,
            ],
            [
                'tier_name' => 'Silver Hoard',
                'token_cost' => 100,
                'discount_type' => 'percentage',
                'discount_value' => 10.00,
                'minimum_purchase_amount' => 0.00,
                'max_uses_per_user' => 1,
                'valid_days' => 30,
                'description' => 'Convert 100 tokens for 10% off',
                'is_active' => true,
            ],
            [
                'tier_name' => 'Gold Cache',
                'token_cost' => 200,
                'discount_type' => 'percentage',
                'discount_value' => 15.00,
                'minimum_purchase_amount' => 50.00,
                'max_uses_per_user' => 1,
                'valid_days' => 30,
                'description' => 'Convert 200 tokens for 15% off orders over $50',
                'is_active' => true,
            ],
            [
                'tier_name' => 'Platinum Vault',
                'token_cost' => 350,
                'discount_type' => 'percentage',
                'discount_value' => 20.00,
                'minimum_purchase_amount' => 100.00,
                'max_uses_per_user' => 1,
                'valid_days' => 30,
                'description' => 'Convert 350 tokens for 20% off orders over $100',
                'is_active' => true,
            ],
            [
                'tier_name' => 'Viking Fortune',
                'token_cost' => 500,
                'discount_type' => 'fixed_amount',
                'discount_value' => 50.00,
                'minimum_purchase_amount' => 150.00,
                'max_uses_per_user' => 1,
                'valid_days' => 30,
                'description' => 'Convert 500 tokens for $50 off orders over $150',
                'is_active' => true,
            ],
            [
                'tier_name' => 'Odin Blessing',
                'token_cost' => 750,
                'discount_type' => 'percentage',
                'discount_value' => 30.00,
                'minimum_purchase_amount' => 200.00,
                'max_uses_per_user' => 1,
                'valid_days' => 30,
                'description' => 'Convert 750 tokens for 30% off orders over $200',
                'is_active' => true,
            ],
        ];

        foreach ($tiers as $tier) {
            DiscountTier::create($tier);
        }

        $this->command->info('✅ Discount tiers created!');
    }
}
