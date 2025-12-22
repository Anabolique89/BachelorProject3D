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
        Schema::create('user_tokens', function (Blueprint $table) {
            $table->id();
            //FK
            $table->foreignId('customer_id')->constrained('customers')->cascadeOnDelete();

            $table->unsignedInteger('total_tokens')->default(0);
             $table->unsignedInteger('available_tokens')->default(0);
              $table->unsignedInteger('spent_tokens')->default(0);
               $table->unsignedInteger('lifetime_earned')->default(0);

               $table->unsignedInteger('current_level')->default(1);
                $table->unsignedInteger('experience_points')->default(0);

                //fk user-levels
                $table->foreign('current_level')->references('level')->on('user_levels')->restrictOnDelete();
                
            $table->timestamps();
            
             // Indexes
            $table->unique('customer_id');
            $table->index('available_tokens');
            $table->index('current_level');
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_tokens');
    }
};
