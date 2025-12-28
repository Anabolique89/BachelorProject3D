<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('daily_login_streaks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained()->onDelete('cascade');
            $table->integer('current_day')->default(1);
            $table->integer('current_streak')->default(1);
            $table->integer('longest_streak')->default(1);
            $table->date('last_login_date');
            $table->boolean('has_claimed_today')->default(false);
            $table->integer('total_tokens_earned')->default(0);
            $table->integer('total_runes_earned')->default(0);
            $table->timestamps();

            $table->unique('customer_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('daily_login_streaks');
    }
};
