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
        Schema::create('user_rune_collections', function (Blueprint $table) {
            $table->id();

            $table->foreignId('customer_id')->constrained('customers')->cascadeOnDelete();
            $table->foreignId('rune_type_id')->constrained('rune_types')->cascadeOnDelete();

            $table->unsignedInteger('quantity')->default(1);
            $table->timestamp('collected_at')->useCurrent();

            // Prevent duplicates
            $table->unique(['customer_id', 'rune_type_id']);

            // Indexes
            $table->index('customer_id');
            $table->index('rune_type_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_rune_collections');
    }
};
