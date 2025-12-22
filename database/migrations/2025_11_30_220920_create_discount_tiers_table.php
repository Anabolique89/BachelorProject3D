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
        Schema::create('discount_tiers', function (Blueprint $table) {
        $table->id();
            $table->string('tier_name', 100);
            $table->unsignedInteger('token_cost');
            $table->enum('discount_type', ['percentage', 'fixed_amount', 'free_shipping']);
            $table->decimal('discount_value', 10, 2);
            $table->decimal('minimum_purchase_amount', 10, 2)-> default(0);
            //limits
            $table->unsignedInteger('max_uses_per_user')->default(1);
            $table->unsignedInteger('valid_days')->default(30);
            
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

             // Indexes
            $table->index('token_cost');
            $table->index('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('discount_tiers');
    }
};
