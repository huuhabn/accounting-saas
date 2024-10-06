<?php

namespace HS\Inventory\Console;

use Illuminate\Console\Command;
use HS\Inventory\Database\Seeders\DatabaseSeeder;

class Install extends Command
{
    public $signature = 'hs-inventory:install';

    public $description = 'Install inventory stock management';

    public function handle(): int
    {
        if ($this->confirm('Run demo data seeder?', true)) {
            $this->call('db:seed', ['--class' => DatabaseSeeder::class]);
        }

        $this->comment('All done');

        return self::SUCCESS;
    }
}
