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
        Schema::create('user_levels', function (Blueprint $table) {
            $table->id();

            $table->unsignedInteger('level')->unique();
            $table->string('level_name', 100);

            $table->unsignedInteger('min_experience');
            $table->unsignedInteger('max_experience');

            $table->decimal('token_multiplier', 3, 2)->default(1.00);
            $table->json('perks')->nullable();
            $table->string('icon', 255)->nullable();

            $table->timestamps();

             // Indexes
            $table->index('level');
            $table->index(['min_experience', 'max_experience']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_levels');
    }
};
