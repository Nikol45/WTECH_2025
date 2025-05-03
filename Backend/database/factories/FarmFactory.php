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

        $customFarmDescriptions = [
            'Rodinná farma zameraná na pestovanie sezónnej zeleniny bez chémie.',
            'Na našej farme nájdete ovocie, zeleninu aj domáce vajíčka od sliepok z voľného výbehu.',
            'Zameriavame sa na tradičné slovenské odrody a šetrné hospodárenie.',
            'Naša sadovňa ponúka čerstvé ovocie priamo zo stromu do vašej bedničky.',
            'Pestujeme lokálne, poctivo a s láskou k pôde aj ľuďom.',
            'Domáci med, bylinky a voňavé kvety — všetko z jednej lúky.',
            'Kombinujeme tradičné gazdovstvo s modernými ekologickými princípmi.',
            'Každý produkt z našej farmy má svoj príbeh a pochádza z čistej prírody.',
            'Od semienka po tanier — všetko robíme vlastnými rukami.',
            'U nás nenájdete chémiu, len čerstvosť a poctivú prácu.'
        ];

        $namePrefixes = [
            'Farma', 'Gazdovstvo', 'Salaš', 'Statok', 'Dvor', 'Záhrada', 'Sadovňa',
            'Ovčia farma', 'Kozia farma', 'Rodinná farma', 'Bio farma', 'Eko farma',
            'U Jožka', 'U Tety Anky', 'U Deda', 'U Margity', 'U Peťa', 'U Bylinkárky'
        ];

        $nameSuffixes = [
            'Pod Orechom', 'Na Hriadkach', 'Pod Hruškou', 'Na Paseke', 'Z Lúk', 'Pod Vinicou',
            'Na Vŕšku', 'Sedem Jabloní', 'Viničný Vrch', 'Kozia Skala', 'Dúhový Vrch',
            'Tichý Dvor', 'Makové Pole', 'Višňový Sad', 'Medová Paseka', 'Zemiakové Kráľovstvo',
            'Šípková Dolina', 'Slivkový Dvor', 'Zelený Klások', 'Strakaté Lúky', 'Ovčí Sen',
            'Hrejivé Slnečnice', 'Dobrý Koreň', 'Chrumkavé Záhony'
        ];

        $userId = \App\Models\User::where('is_admin', true)->inRandomOrder()->value('id');
        $deliveryAvailable = $this->faker->boolean(70);

        return [
            'user_id' => $userId,
            'address_id' => Address::factory()->state(['address_type' => 'farm']),
            'name' => $this->faker->randomElement($namePrefixes) . ' ' . $this->faker->randomElement($nameSuffixes),
            'description' => $this->faker->randomElement($customFarmDescriptions),
            'rating' => null,
            'delivery_available' => $deliveryAvailable,
            'min_delivery_price' => $deliveryAvailable ? $this->faker->randomFloat(2,0,10) : null,
            'avg_delivery_time' => $deliveryAvailable ? $this->faker->numberBetween(1,60) : null
        ];
    }
}
