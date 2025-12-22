<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            // Add gamification fields if they don't exist
            if (!Schema::hasColumn('customers', 'user_level_id')) {
                $table->unsignedBigInteger('user_level_id')->nullable()->after('role');
                $table->foreign('user_level_id')->references('id')->on('user_levels')->onDelete('set null');
            }

            if (!Schema::hasColumn('customers', 'total_tokens')) {
                $table->integer('total_tokens')->default(0)->after('user_level_id');
            }

            if (!Schema::hasColumn('customers', 'used_tokens')) {
                $table->integer('used_tokens')->default(0)->after('total_tokens');
            }

            if (!Schema::hasColumn('customers', 'available_tokens')) {
                $table->integer('available_tokens')->default(0)->after('used_tokens');
            }
        });
    }

    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropForeign(['user_level_id']);
            $table->dropColumn(['user_level_id', 'total_tokens', 'used_tokens', 'available_tokens']);
        });
    }
};
