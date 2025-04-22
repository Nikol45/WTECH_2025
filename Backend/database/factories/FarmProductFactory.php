<?php

namespace Database\Factories;

use App\Models\Farm;
use App\Models\Product;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\FarmProduct>
 */
class FarmProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        $sellQuantity = $this->faker->randomFloat(1,0.1,20);
        $priceSellQuantity = $this->faker->randomFloat(2,0.1,20);

        return [
            'farm_id' => Farm::inRandomOrder()->value('id'),
            'product_id' => Product::inRandomOrder()->value('id'),
            'sell_quantity' => $sellQuantity,
            'price_sell_quantity' => $priceSellQuantity,
            'unit' => $this->faker->randomElement(['kg', 'l', 'ks']),
            'price_per_unit' => round($priceSellQuantity / $sellQuantity, 2),
            'discount_percentage' => $this->faker->optional(0.3)->numberBetween(5, 90),
            'farm_specific_description' => $this->faker->optional(0.5)->paragraph(5),
            'availability' => $this->faker->boolean(80),
            'rating' => null
        ];
    }
}
