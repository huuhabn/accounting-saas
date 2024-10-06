<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Database\Seeders\Admin\AdminDemoSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        if ($this->command->confirm('Run default values seeder?', true)) {
            $this->call(DefaultValuesSeeder::class);
        }

        if ($this->command->confirm('Run supper admin seeder?', true)) {
            $this->call(BusinessSeeder::class);
        }

        if ($this->command->confirm('Run admin demo seeder?', true)) {
            $this->call(AdminDemoSeeder::class);
        }

    }
}
