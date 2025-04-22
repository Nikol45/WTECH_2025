<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\Farm;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Package>
 */
class PackageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'order_id' => Order::inRandomOrder()->value('id'),
            'farm_id' => Farm::inRandomOrder()->value('id'),
            'price' => null,
            'expected_delivery_date' => $this->faker->dateTimeBetween('now', '+1 month'),
            'status' => $this->faker->randomElement(['waiting', 'confirmed', 'shipped', 'delivered']),
        ];
    }
}
