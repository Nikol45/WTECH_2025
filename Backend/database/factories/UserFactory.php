<?php

namespace Database\Factories;

use App\Models\Address;
use App\Models\Company;
use App\Models\Icon;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        $billingAddress = $this->faker->boolean(70) ? Address::factory()->state(['address_type' => 'billing'])->create() : null;
        $deliveryAddress = $this->faker->boolean(70) ? Address::factory()->state(['address_type' => 'delivery'])->create() : null;
        $company = $this->faker->boolean(10) ? Company::factory()->create() : null;
        $iconId = Icon::inRandomOrder()->value('id');

        return [
            'billing_address_id' => $billingAddress ? $billingAddress->id : null,
            'delivery_address_id' => $deliveryAddress ? $deliveryAddress->id : null,
            'company_id' => $company ? $company->id : null,
            'icon_id' => $iconId,
            'name' => $this->faker->name,
            'nickname' => $this->faker->unique()->userName,
            'email' => $this->faker->unique()->safeEmail,
            'email_verified_at' => now(),
            'password' => Hash::make(fake()->password()),
            'remember_token' => Str::random(10),
            'phone_number' => $this->faker->unique()->numerify('+421 ### ### ###'),
            'has_admin_account' => $this->faker->boolean(20)
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
