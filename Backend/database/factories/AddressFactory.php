<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Address>
 */
class AddressFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'street' => fake()->randomElement(['Hlavná', 'Mierová', 'Družstevná', 'Šancová', 'Kvetná', 'Jarná', 'Zimná', 'Letná', 'Jesenná', 'Štúrova', 'Karpatská', 'Tatranská', 'Slovenská', 'Česká', 'Maďarská', 'Rakúska', 'Nemecká', 'Pankúchova', 'Potočná', 'Rybárska', 'Jazerná', 'Lúčna', 'Záhradná']),
            'street_number' => $this->faker->buildingNumber,
            'zip_code' => $this->faker->postcode,
            'city' => $this->faker->city,
            'country' => 'Slovensko',
            'address_type' => $this->faker->randomElement(['billing', 'shipping', 'farm']),
        ];
    }
}
