<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Company>
 */
class CompanyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->company,
            'ico' => $this->faker->unique()->numberBetween(10000000, 99999999),
            'ic_dph' => $this->faker->boolean(50) ? $this->faker->unique()->numberBetween(10000000, 99999999) : null
        ];
    }
}
