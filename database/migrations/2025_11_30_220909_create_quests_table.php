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
        Schema::create('quests', function (Blueprint $table) {
            $table->id();

            $table->enum('quest_type', ['daily', 'weekly', 'special', 'achievement']);

            $table->string('title', 255);
            $table->text('description');
            $table->enum('quest_category', ['purchase', 'engagement', 'social', 'exploration', 'collection']);
             $table->json('requirements');

              // Rewards
            $table->unsignedInteger('token_reward');
            $table->unsignedInteger('experience_reward')->default(0);
            
            // Limits
            $table->unsignedInteger('max_completions')->default(1); // 0 = unlimited
            
            // Difficulty
            $table->enum('difficulty', ['easy', 'medium', 'hard', 'legendary'])->default('medium');
            
            // Active status
            $table->boolean('is_active')->default(true);
            
            // Date range
            $table->timestamp('start_date')->nullable();
            $table->timestamp('end_date')->nullable();
            
            // Icon
            $table->string('icon', 255)->nullable();

            $table->timestamps();

               // Indexes
            $table->index('quest_type');
            $table->index('is_active');
            $table->index(['start_date', 'end_date']);
            $table->index('quest_category');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quests');
    }
};
