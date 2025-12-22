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
        Schema::create('rune_sets', function (Blueprint $table) {
            $table->id();

            $table->string('set_name', 100);
            $table->text('description')->nullable();
            $table->json('required_runes');
            $table->unsignedInteger('completion_reward');

            // Discount generation
            $table->string('discount_code_template', 100)->nullable();
            $table->decimal('discount_percentage', 5, 2)->nullable();

            $table->boolean('is_active')->default(true);

            // timestamps only ONCE
            $table->timestamps();

            // Indexes
            $table->index('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rune_sets');
    }
};
