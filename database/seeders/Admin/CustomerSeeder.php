<?php

namespace Database\Seeders\Admin;

use Faker\Factory;
use App\Models\User;
use Database\Seeders\AbstractSeeder;
use Illuminate\Support\Facades\DB;
use Lunar\Models\Address;
use Lunar\Models\Customer;

class CustomerSeeder extends AbstractSeeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::transaction(function () {
            $faker = Factory::create();

            $customers = Customer::factory(100)->create();

            foreach ($customers as $customer) {
                $users = User::factory($faker->numberBetween(1, 10))->create();
                $customer->users()->attach($users);

                $addressesData = [
                    [
                        'shipping_default' => true,
                        'billing_default' => false,
                        'country_id' => 235,
                        'customer_id' => $customer->id,
                    ],
                    [
                        'shipping_default' => false,
                        'billing_default' => false,
                        'country_id' => 235,
                        'customer_id' => $customer->id,
                    ],
                    [
                        'shipping_default' => false,
                        'billing_default' => true,
                        'country_id' => 235,
                        'customer_id' => $customer->id,
                    ],
                    [
                        'shipping_default' => false,
                        'billing_default' => false,
                        'country_id' => 235,
                        'customer_id' => $customer->id,
                    ],
                ];

                Address::factory()->createMany($addressesData);
            }
        });
    }

}
