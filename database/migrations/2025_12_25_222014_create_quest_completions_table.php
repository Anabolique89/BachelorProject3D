<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('quest_completions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained()->onDelete('cascade');
            $table->string('quest_id');
            $table->string('quest_name');
            $table->json('rewards');
            $table->timestamp('completed_at');
            $table->timestamps();

            $table->index(['customer_id', 'quest_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('quest_completions');
    }
};
