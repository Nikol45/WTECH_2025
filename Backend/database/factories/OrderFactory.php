<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Address;
use App\Models\Company;

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
            'billing_address_id' => Address::factory()->state(['address_type' => 'billing'])->create()->id,
            'delivery_address_id' => Address::factory()->state(['address_type' => 'delivery'])->create()->id,
            'company_id' => Company::factory()->create()->id,
            'total_price' => null,
            'payment_type' => $this->faker->randomElement(['online', 'transfer', 'cash']),
            'delivery_type' => $this->faker->randomElement(['in_person', 'express', 'standard']),
            'note' => $this->faker->boolean(40) ? $this->faker->sentence(10) : null
        ];
    }
}
