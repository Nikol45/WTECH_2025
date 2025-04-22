<?php

namespace Database\Factories;

use App\Models\Package;
use App\Models\FarmProduct;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\OrderItem>
 */
class OrderItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        $package = Package::inRandomOrder()->first();
        $farmProduct = FarmProduct::where('farm_id', $package->farm_id)->inRandomOrder()->first();

        if (!$farmProduct) {
            $farmProduct = FarmProduct::factory()->create([
                'farm_id' => $package->farm_id,
            ]);
        }

        return [
            'package_id' => $package->id,
            'farm_product_id' => $farmProduct->id,
            'quantity' => $this->faker->numberBetween(1, 20),
            'price_when_ordered' => $farmProduct?->price_sell_quantity ?? 0
        ];
    }
}
