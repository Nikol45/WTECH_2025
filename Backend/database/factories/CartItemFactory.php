<?php

namespace Database\Factories;

use App\Models\FarmProduct;
use App\Models\User;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CartItem>
 */
class CartItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'farm_product_id' => FarmProduct::inRandomOrder()->value('id'),
            'user_id' => User::inRandomOrder()->value('id'),
            'quantity' => $this->faker->numberBetween(1, 20)
        ];
    }
}
