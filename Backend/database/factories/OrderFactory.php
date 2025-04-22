<?php

namespace Database\Factories;

use App\Models\User;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::inRandomOrder()->value('id'),
            'total_price' => null,
            'payment_type' => $this->faker->randomElement(['online', 'transfer', 'cash']),
            'delivery_type' => $this->faker->randomElement(['in_person', 'express', 'standard']),
            'note' => $this->faker->boolean(40) ? $this->faker->sentence(10) : null
        ];
    }
}
