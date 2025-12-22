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
        Schema::create('daily_login_streaks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained('customers')->cascadeOnDelete();

            // Streak tracking
            $table->unsignedInteger('current_streak')->default(0);
            $table->unsignedInteger('longest_streak')->default(0);

            // Last login date
            $table->date('last_login_date');
            $table->unsignedInteger('tokens_earned_today')->default(0);
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();

            // Unique constraint
            $table->unique('customer_id');

            // Indexes
            $table->index('last_login_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('daily_login_streaks');
    }
};
