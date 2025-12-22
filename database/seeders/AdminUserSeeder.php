<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Customer;
use App\Models\UserToken;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run()
    {
        // Create admin user
        $adminUser = User::create([
            'name' => 'Admin Viking',
            'email' => 'admin@vikings.com',
            'password' => Hash::make('admin123'),
            'email_verified_at' => now(),
        ]);

        // Create admin customer profile
        Customer::create([
            'user_id' => $adminUser->id,
            'first_name' => 'Admin',
            'last_name' => 'Viking',
            'email' => 'admin@vikings.com',
            'phone' => '+45 12345678',
            'role' => 'super_admin',
            'status' => 'active',
        ]);

        // Create test customer
        $testUser = User::create([
            'name' => 'Erik the Red',
            'email' => 'erik@vikings.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);

        $testCustomer = Customer::create([
            'user_id' => $testUser->id,
            'first_name' => 'Erik',
            'last_name' => 'the Red',
            'email' => 'erik@vikings.com',
            'phone' => '+45 87654321',
            'role' => 'customer',
            'status' => 'active',
        ]);

        // Initialize tokens for test customer
        UserToken::create([
            'customer_id' => $testCustomer->id,
            'available_tokens' => 100,
            'total_tokens' => 100,
            'lifetime_earned' => 100,
            'current_level' => 1,
            'experience_points' => 0,
        ]);

        $this->command->info('✅ Admin and test users created!');
        $this->command->info('   Admin: admin@vikings.com / admin123');
        $this->command->info('   Test: erik@vikings.com / password');
    }
}
