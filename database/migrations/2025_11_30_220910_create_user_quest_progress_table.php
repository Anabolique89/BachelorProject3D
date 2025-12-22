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
        Schema::create('user_quest_progress', function (Blueprint $table) {
            $table->id();
            //fk
            $table->foreignId('customer_id')->constrained('customers')->cascadeOnDelete();
            $table->foreignId('quest_id')->constrained('quests')->cascadeOnDelete();
            
               $table->enum('status', ['in_progress', 'completed', 'claimed', 'expired'])->default('in_progress');
            
            // Progress tracking (JSON)
            $table->json('progress_data')->nullable();
            
            // Completion count
            $table->unsignedInteger('completion_count')->default(0);
            
            // Timestamps
            $table->timestamp('started_at')->useCurrent();
            $table->timestamp('completed_at')->nullable();
            $table->timestamp('claimed_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            
            // Indexes
            $table->index(['customer_id', 'quest_id']);
            $table->index('status');
            $table->index('expires_at');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_quest_progress');
    }
};
