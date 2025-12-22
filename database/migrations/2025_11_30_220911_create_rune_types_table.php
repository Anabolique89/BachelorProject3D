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
        Schema::create('rune_types', function (Blueprint $table) {
            $table->id();
    
            // Rune info
            $table->string('rune_name', 100);
            $table->enum('rarity', ['common', 'uncommon', 'rare', 'epic', 'legendary']);
            $table->text('description')->nullable();
            
            // Spawn mechanics
            $table->decimal('spawn_rate', 5, 4); // 0.0001 to 1.0000
            
            // Token value
            $table->unsignedInteger('token_value')->default(0);
            
        
            $table->string('icon', 255)->nullable();
            $table->boolean('is_active')->default(true);
            
            $table->timestamps();
            
            // Indexes
            $table->index('rarity');
            $table->index('is_active');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rune_types');
    }
};
