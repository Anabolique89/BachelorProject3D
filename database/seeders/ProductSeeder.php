<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        // Get category IDs
        $weaponsId = Category::where('slug', 'weapons')->first()?->id;
        $armorId = Category::where('slug', 'armor')->first()?->id;
        $jewelryId = Category::where('slug', 'jewelry')->first()?->id;
        $clothingId = Category::where('slug', 'clothing')->first()?->id;
        $axesId = Category::where('slug', 'axes')->first()?->id;
        $swordsId = Category::where('slug', 'swords')->first()?->id;
        // $shirtsId = Category::where('slug', 'shirts')->first()?->id;
         // $shirtsId = Category::where('slug', 'shirts')->first()?->id;
          // $shirtsId = Category::where('slug', 'shirts')->first()?->id;

        $products = [
            [
                'title' => 'Viking Battle Axe - Ragnar',
                'slug' => 'viking-battle-axe-ragnar',
                'image' => 'https://images.unsplash.com/photo-1589519160732-57fc498494f8?w=800',
                'image_mime' => 'image/jpeg',
                'image_size' => null,
                'description' => 'Authentic replica of a Viking battle axe. Hand-forged steel blade with ash wood handle. Perfect for display or reenactments.',
                'price' => 149.99,
                'sale_price' => 129.99,
                'stock_quantity' => 25,
                'status' => 'published',
                'is_featured' => true,
                'token_reward_on_purchase' => 150,
                'category_id' => $axesId ?? $weaponsId,
                'created_by' => 1, // Admin user
            ],
            [
                'title' => 'Thor\'s Hammer Pendant - Mjölnir',
                'slug' => 'thors-hammer-pendant-mjolnir',
                'image' => 'https://images.unsplash.com/photo-1599643478518-a784e5dc4c8f?w=800',
                'image_mime' => 'image/jpeg',
                'image_size' => null,
                'description' => 'Sterling silver Mjölnir pendant. Symbol of strength and protection. Includes leather cord.',
                'price' => 49.99,
                'sale_price' => null,
                'stock_quantity' => 100,
                'status' => 'published',
                'is_featured' => true,
                'token_reward_on_purchase' => 50,
                'category_id' => $jewelryId,
                'created_by' => 1,
            ],
            [
                'title' => 'Viking Helmet - Gjermundbu Replica',
                'slug' => 'viking-helmet-gjermundbu',
                'image' => 'https://images.unsplash.com/photo-1578632292335-df3abbb0d586?w=800',
                'image_mime' => 'image/jpeg',
                'image_size' => null,
                'description' => 'Steel Viking helmet with nose guard and chainmail aventail. Based on the famous Gjermundbu find.',
                'price' => 299.99,
                'sale_price' => 279.99,
                'stock_quantity' => 15,
                'status' => 'published',
                'is_featured' => false,
                'token_reward_on_purchase' => 300,
                'category_id' => $armorId,
                'created_by' => 1,
            ],
            [
                'title' => 'Viking Leather Bracelet with Runes',
                'slug' => 'viking-leather-bracelet-runes',
                'image' => 'https://images.unsplash.com/photo-1611652022419-a9419f74343a?w=800',
                'image_mime' => 'image/jpeg',
                'image_size' => null,
                'description' => 'Handcrafted leather bracelet with carved runic inscriptions. Adjustable size.',
                'price' => 29.99,
                'sale_price' => null,
                'stock_quantity' => 75,
                'status' => 'published',
                'is_featured' => false,
                'token_reward_on_purchase' => 30,
                'category_id' => $jewelryId,
                'created_by' => 1,
            ],
            [
                'title' => 'Viking Sword - Ulfberht',
                'slug' => 'viking-sword-ulfberht',
                'image' => 'https://images.unsplash.com/photo-1597091771168-1ac8d9ea8c46?w=800',
                'image_mime' => 'image/jpeg',
                'image_size' => null,
                'description' => 'Full-tang Viking sword inspired by the legendary Ulfberht blades. High carbon steel with leather-wrapped grip and wood scabbard.',
                'price' => 449.99,
                'sale_price' => null,
                'stock_quantity' => 0,
                'status' => 'out_of_stock',
                'is_featured' => true,
                'token_reward_on_purchase' => 450,
                'category_id' => $swordsId ?? $weaponsId,
                'created_by' => 1,
            ],
            [
                'title' => 'Viking Wool Tunic - Jarl Edition',
                'slug' => 'viking-wool-tunic-jarl',
                'image' => 'https://images.unsplash.com/photo-1620799140408-edc6dcb6d633?w=800',
                'image_mime' => 'image/jpeg',
                'image_size' => null,
                'description' => 'Authentic Viking-style wool tunic. Hand-woven with natural dyes. Available in forest green and burgundy.',
                'price' => 89.99,
                'sale_price' => 69.99,
                'stock_quantity' => 30,
                'status' => 'published',
                'is_featured' => false,
                'token_reward_on_purchase' => 90,
                'category_id' => $clothingId,
                'created_by' => 1,
            ],
            [
                'title' => 'Viking Shield - Round Linden Wood',
                'slug' => 'viking-shield-linden-wood',
                'image' => 'https://images.unsplash.com/photo-1595433707802-6b2626ef1c91?w=800',
                'image_mime' => 'image/jpeg',
                'image_size' => null,
                'description' => 'Hand-painted Viking round shield. 75cm diameter, linden wood with steel boss. Custom designs available.',
                'price' => 199.99,
                'sale_price' => 179.99,
                'stock_quantity' => 12,
                'status' => 'published',
                'is_featured' => true,
                'token_reward_on_purchase' => 200,
                'category_id' => $armorId,
                'created_by' => 1,
            ],
            [
                'title' => 'Valknut Silver Ring',
                'slug' => 'valknut-silver-ring',
                'image' => 'https://images.unsplash.com/photo-1605100804763-247f67b3557e?w=800',
                'image_mime' => 'image/jpeg',
                'image_size' => null,
                'description' => '925 sterling silver ring featuring the Valknut symbol. Sizes 7-12 available.',
                'price' => 39.99,
                'sale_price' => null,
                'stock_quantity' => 50,
                'status' => 'published',
                'is_featured' => false,
                'token_reward_on_purchase' => 40,
                'category_id' => $jewelryId,
                'created_by' => 1,
            ],
            [
                'title' => 'Viking Throwing Axe - Bearded',
                'slug' => 'viking-throwing-axe-bearded',
                'image' => 'https://images.unsplash.com/photo-1589519160732-57fc498494f8?w=800',
                'image_mime' => 'image/jpeg',
                'image_size' => null,
                'description' => 'Competition-grade throwing axe with bearded design. Balanced for accuracy.',
                'price' => 79.99,
                'sale_price' => 64.99,
                'stock_quantity' => 40,
                'status' => 'published',
                'is_featured' => false,
                'token_reward_on_purchase' => 80,
                'category_id' => $axesId ?? $weaponsId,
                'created_by' => 1,
            ],
            [
                'title' => 'Raven Brooch - Odin\'s Companions',
                'slug' => 'raven-brooch-odins-companions',
                'image' => 'https://images.unsplash.com/photo-1515562141207-7a88fb7ce338?w=800',
                'image_mime' => 'image/jpeg',
                'image_size' => null,
                'description' => 'Bronze raven brooch inspired by Norse mythology. Represents Huginn and Muninn.',
                'price' => 34.99,
                'sale_price' => null,
                'stock_quantity' => 60,
                'status' => 'published',
                'is_featured' => false,
                'token_reward_on_purchase' => 35,
                'category_id' => $jewelryId,
                'created_by' => 1,
            ],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}