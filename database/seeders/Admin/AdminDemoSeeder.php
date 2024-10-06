<?php

namespace Database\Seeders\Admin;

use Illuminate\Database\Seeder;

class AdminDemoSeeder extends Seeder
{

    public function run(): void
    {
        $this->call(CollectionSeeder::class);
        $this->call(AttributeSeeder::class);
        $this->call(TaxSeeder::class);
        $this->call(ProductSeeder::class);
        $this->call(CustomerSeeder::class);
        $this->call(ShippingSeeder::class);
        $this->call(OrderSeeder::class);
    }
}
