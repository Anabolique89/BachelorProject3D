<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder  // ← Correct class name
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Weapons',
                'slug' => 'weapons',
                'description' => 'Viking weapons including axes, swords, and spears',
                'parent_id' => null,
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'Armor',
                'slug' => 'armor',
                'description' => 'Protective gear including helmets, shields, and chainmail',
                'parent_id' => null,
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'Jewelry',
                'slug' => 'jewelry',
                'description' => 'Viking jewelry including pendants, bracelets, and rings',
                'parent_id' => null,
                'is_active' => true,
                'sort_order' => 3,
            ],
            [
                'name' => 'Clothing',
                'slug' => 'clothing',
                'description' => 'Authentic Viking clothing and garments',
                'parent_id' => null,
                'is_active' => true,
                'sort_order' => 4,
            ],
            [
                'name' => 'Accessories',
                'slug' => 'accessories',
                'description' => 'Viking accessories and everyday items',
                'parent_id' => null,
                'is_active' => true,
                'sort_order' => 5,
            ],
            [
                'name' => 'Decorations',
                'slug' => 'decorations',
                'description' => 'Viking-themed home decorations and wall art',
                'parent_id' => null,
                'is_active' => true,
                'sort_order' => 6,
            ],
            // Subcategories for Weapons
            [
                'name' => 'Axes',
                'slug' => 'axes',
                'description' => 'Battle axes and throwing axes',
                'parent_id' => 1, // Weapons category
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'Swords',
                'slug' => 'swords',
                'description' => 'Viking swords and blades',
                'parent_id' => 1, // Weapons category
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'Spears',
                'slug' => 'spears',
                'description' => 'Throwing spears and javelins',
                'parent_id' => 1, // Weapons category
                'is_active' => true,
                'sort_order' => 3,
            ],
            [
                'name' => 'T-Shirts and Hoodies',
                'slug' => 'shirts',
                'description' => 'Amazing Modern Viking Tees',
                'parent_id' => 4, // Clothing category
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'Mugs',
                'slug' => 'mugs',
                'description' => 'Amazing Modern Viking Mugs',
                'parent_id' => 5, // Accessories category
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'Hats',
                'slug' => 'hats',
                'description' => 'Amazing Modern Viking Hats',
                'parent_id' => 4, // Clothing category
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'Stickers',
                'slug' => 'stickers',
                'description' => 'Amazing Modern Viking Stickers',
                'parent_id' => 5, // Accessories category
                'is_active' => true,
                'sort_order' => 2,
            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
