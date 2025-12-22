<?php

use App\Models\User;
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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
             $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('first_name', 45);
            $table->string('last_name', 45);
            $table->string('phone', 45)->nullable();
            $table->string('email',45);
            $table->enum('role', ['customer', 'admin', 'super_admin'])->default('customer');
            $table->enum('status', ['active', 'inactive', 'banned'])->default('active');
            
            //avatar
            $table->string('avatar', 255)->nullable();
             $table->timestamp('last_login_at')->nullable();
            $table->foreignIdFor(User::class, 'created_by')->nullable();
            $table->foreignIdFor(User::class, 'updated_by')->nullable();
            $table->timestamps();

             
            // Indexes
            $table->index('user_id');
            $table->index('email');
            $table->index('role');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
