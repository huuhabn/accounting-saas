<?php
namespace HS\Inventory\Database\Factories;

use HS\Inventory\Models\Supplier;
use Illuminate\Database\Eloquent\Factories\Factory;

class SupplierFactory extends Factory
{
    protected $model = Supplier::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->company(),
            'contact_title' => $this->faker->title(),
            'contact_name' => $this->faker->name(),
            'contact_phone' => $this->faker->phoneNumber(),
            'contact_fax' => $this->faker->phoneNumber(),
            'contact_email' => $this->faker->email(),
            'country' => $this->faker->countryCode(),
            'street' => $this->faker->streetAddress(),
            'state' => $this->faker->state(),
            'city' => $this->faker->city(),
            'zip' => $this->faker->postcode(),
        ];
    }
}
