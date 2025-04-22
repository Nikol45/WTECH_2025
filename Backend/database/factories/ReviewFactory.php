<?php

namespace Database\Factories;

use App\Models\FarmProduct;
use App\Models\User;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Review>
 */
class ReviewFactory extends Factory
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
            'title' => $this->faker->sentence,
            'rating' => $this->faker->randomFloat(2, 1, 5),
            'text' => $this->faker->paragraph(3)
        ];
    }
}
