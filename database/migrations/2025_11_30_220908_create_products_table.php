<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('title', 2000);
            $table->string('slug', 2000);
            $table->string('image', 2000)->nullable();
            $table->string('image_mime')->nullable();
            $table->integer('image_size')->nullable();
            $table->string('description')->nullable();
            $table->decimal('price', 10, 2);
             $table->decimal('sale_price', 10, 2)->nullable();
            
            // Inventory
            $table->integer('stock_quantity')->default(0);
            
            // Status
            $table->string('status', 25); // 'draft', 'published', 'out_of_stock', 'discontinued'
            $table->boolean('is_featured')->default(false);
              // Gamification
            $table->unsignedInteger('token_reward_on_purchase')->default(0); // MISSING
            
            $table->foreignId('category_id')->nullable()->constrained('categories')->nullOnDelete();
            
            $table->foreignIdFor(User::class, 'created_by')->nullable();
            $table->foreignIdFor(User::class, 'updated_by')->nullable();
            $table->softDeletes();
            $table->foreignIdFor(User::class, 'deleted_by')->nullable();
            $table->timestamps();

            $table->index('category_id');
            $table->index(['slug'], 'idx_slug');
            $table->index('status');
            $table->index('price');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
