<?php

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
        Schema::create('user_discount_codes', function (Blueprint $table) {
            $table->id();

            $table->foreignId('customer_id')->constrained('customers')->cascadeOnDelete();
            $table->foreignId('discount_tier_id')->nullable()->constrained('discount_tiers')->nullOnDelete();


            $table->string('code', 50)->unique();
            $table->enum('discount_type', ['percentage', 'fixed_amount', 'free_shipping']);
            $table->decimal('discount_value', 10, 2);
            $table->decimal('minimum_purchase_amount', 10, 2)->default(0);

            $table->unsignedInteger('tokens_spent')->default(0);
            $table->enum('source', ['token_conversion', 'quest_reward', 'rune_set', 'referral', 'admin', 'promotional']);
            $table->enum('status', ['active', 'used', 'expired', 'revoked'])->default('active');
            $table->unsignedInteger('max_uses')->default(1);
            $table->unsignedInteger('times_used')->default(0);

            $table->timestamp('valid_from')->useCurrent();
            $table->timestamp('valid_until')->nullable();

            $table->timestamps();

            // Indexes
            $table->index('customer_id');
            $table->index('code');
            $table->index('status');
            $table->index(['valid_from', 'valid_until']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_discount_codes');
    }
};
