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
        Schema::create('game_sessions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('customer_id')->constrained('customers')->cascadeOnDelete();

            // Game info
            $table->enum('game_type', ['treasure_hunt', 'merchant_raid', 'puzzle', 'memory', 'wheel_spin', 'custom']);

            // Score and rewards
            $table->unsignedInteger('score')->default(0);
            $table->unsignedInteger('tokens_earned')->default(0);

            // Duration
            $table->unsignedInteger('duration_seconds')->nullable();

            // Game data (JSON)
            $table->json('game_data')->nullable();

            // Completion status
            $table->boolean('completed')->default(false);

            $table->timestamp('started_at')->useCurrent();
            $table->timestamp('completed_at')->nullable();

            // Indexes
            $table->index('customer_id');
            $table->index('game_type');
            $table->index('started_at');
            $table->index('completed');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('game_sessions');
    }
};
