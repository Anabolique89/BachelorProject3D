<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('spin_prizes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained()->onDelete('cascade');
            $table->string('prize_type'); // 'discount', 'free_shipping', 'free_product', 'tokens', 'runes'
            $table->integer('prize_value'); // percentage for discounts, amount for tokens/runes
            $table->string('prize_label'); // "50% OFF", "FREE DELIVERY", etc.
            $table->string('discount_code')->nullable(); // Generated discount code if applicable
            $table->timestamp('expires_at')->nullable();
            $table->boolean('is_used')->default(false);
            $table->timestamp('used_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('spin_prizes');
    }
};
