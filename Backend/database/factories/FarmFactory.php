<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Address;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Farm>
 */
class FarmFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        $userId = \App\Models\User::where('has_admin_account', true)->inRandomOrder()->value('id');
        $deliveryAvailable = $this->faker->boolean(66);

        return [
            'user_id' => $userId,
            'address_id' => Address::factory()->state(['address_type' => 'farm']),
            'name' => $this->faker->company,
            'description' => $this->faker->paragraph(3),
            'rating' => null,
            'delivery_available' => $deliveryAvailable,
            'min_delivery_price' => $deliveryAvailable ? $this->faker->randomFloat(2,0,10) : null,
            'avg_delivery_time' => $deliveryAvailable ? $this->faker->numberBetween(1,60) : null
        ];
    }
}
