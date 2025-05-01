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
    public function definition(): array
    {
        $notes = [
            'Zvoňte dvakrát, pes síce breše, ale je kamarátsky.',
            'Prosím, nevolať – som v práci. Nechajte balík na verande, ďakujem.',
            'Tretie poschodie, úplne vľavo – nezablúďte.',
            'Červený dom s bielym plotom – nedá sa prehliadnuť.',
            'Zavolajte, prosím, 5 minút pred príchodom.',
            'Brána bude otvorená, balík nechajte za dverami.',
            'Potrebujem to do 17:00, potom už nebudem doma.',
            'Dvere sú zle označené, ale správne číslo je 12B.',
            'Ak nebudem doma, pokojne nechajte balík u suseda.',
            'Dom s modrými okenicami – hneď vedľa obchodu.',
            'Minule kuriér búchal o desiatej večer – prosím cez deň.',
            'Zvonček nefunguje, prosím klopte.',
            'Pozor na schody – môžu byť šmykľavé.',
            'Nenechávať v skrinke – minule sa balík stratil.',
            'Nezvoniť, prosím – dieťa spí.',
            'Pozor na kozu – má občas turistické sklony.',
            'Za traktorom doprava, potom druhý dom vľavo.',
            'Keď budete pri kravíne, dajte mi prosím vedieť.',
            'Balík môžete položiť do vedra pred dverami (vážne!).',
            'Zvonček nefunguje – zakričte „haló“, budem počuť.',
            'Nechajte v pivnici – vieme si to nájsť.',
            'Som v záhrade – ak počujete motyku, už bežím.',
            'Prosím nebúchať – dedo si dáva poobedňajší šlofík.',
            'Zvoňte len raz – suseda má slabé nervy.',
            'Balík nechajte na lavičke pod orechom.',
            'V chlieviku je voľné miesto – tam to môžete nechať.',
        ];

        return [
            'user_id' => User::inRandomOrder()->value('id'),
            'billing_address_id' => Address::factory()->state(['address_type' => 'billing'])->create()->id,
            'delivery_address_id' => Address::factory()->state(['address_type' => 'delivery'])->create()->id,
            'company_id' => Company::factory()->create()->id,
            'total_price' => null,
            'payment_type' => $this->faker->randomElement(['online', 'transfer', 'cash']),
            'delivery_type' => $this->faker->randomElement(['in_person', 'express', 'standard']),
            'note' => $this->faker->boolean(20)
                ? $this->faker->randomElement($notes)
                : null,
        ];
    }
}
