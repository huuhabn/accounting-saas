<?php

namespace Database\Seeders;

use App\Models\Locale\Country;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DefaultValuesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (! Country::count()) {
            $this->call(CountrySeeder::class);
        }
    }
}
