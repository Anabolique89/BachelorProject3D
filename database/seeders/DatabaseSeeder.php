<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            CountrySeeder::class,
            UserLevelSeeder::class,
            DiscountTierSeeder::class,
            RuneTypeSeeder::class,
            QuestSeeder::class,
            AdminUserSeeder::class,
        ]);

        $this->command->info('');
        $this->command->info('Database seeded');
        $this->command->info('');
        $this->command->info('Login credentials:');
        $this->command->info('Admin: admin@vikings.com / admin123');
        $this->command->info('Test: erik@vikings.com / password');
    }
}