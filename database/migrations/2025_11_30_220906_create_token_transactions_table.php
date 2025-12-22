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
        Schema::create('token_transactions', function (Blueprint $table) {
            $table->id();
//rel
            $table->foreignId('customer_id')->constrained('customers')->cascadeOnDelete();

            $table->enum('transaction_type', ['earn', 'spend', 'expire', 'refund', 'admin_adjustment']);
            $table->integer('amount');
            $table->unsignedInteger('balance_after');

            $table->enum('source_type', ['quest', 'game', 'rune_collection', 'purchase', 'refferal', 
            'daily_login', 'island_exploration', 'admin', 'promotional', 'achievement']);

            $table->unsignedInteger('source_id')->nullable();
            $table->text('description')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamp('expires_at')->nullable();


            $table->timestamp('created_at')->useCurrent();

            // Indexes
            $table->index('customer_id');
            $table->index('transaction_type');
            $table->index('source_type');
            $table->index('created_at');
            $table->index('expires_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('token_transactions');
    }
};
