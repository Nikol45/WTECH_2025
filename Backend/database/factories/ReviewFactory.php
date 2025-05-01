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
        $positiveTitles = [
            'Výborná kvalita!',
            'Určite odporúčam',
            'Chutné a čerstvé',
            'Niečo takéto som dlho hľadal',
            'Domáce ako od babičky',
            'Veľmi chutné',
            'Znova objednám',
            'Príjemne prekvapený',
            'Skvelá chuť a vôňa',
            'Cením si poctivú výrobu',
            'Výborný produkt zo Slovenska',
            'Bez chyby!',
            'Lepšie ako z obchodu',
            'Top kvalita!',
        ];

        $positiveTexts = [
            'Produkt prišiel rýchlo a krásne zabalený. Určite objednám znova.',
            'Chuť výborná, cítiť domácu poctivosť. Som veľmi spokojný.',
            'Zemiaky boli mäkké, chutné a ideálne na pečenie.',
            'Vajíčka čerstvé a veľké, presne ako z dvora.',
            'Syr je famózny – jemný, ale výrazný. Veľká spokojnosť.',
            'Jogurt má prirodzenú chuť a nie je presladený.',
            'Med chutí ako z detstva. Výborný výber.',
            'Všetko bolo čerstvé a krásne voňalo. Desiatka z desiatich.',
            'Domáca šunka šťavnatá, poctivá, presne ako má byť.',
            'Džem skvelý na palacinky aj do koláčov. Objednám znova.',
            'Už sme ochutnali a nemáme čo vytknúť.',
            'Cítiť, že to je robené s láskou. Super chuť!',
            'Krásne balenie, rýchle doručenie, skvelá chuť.',
        ];

        $neutralTitles = [
            'Zodpovedá popisu',
            'Presne podľa očakávania',
            'Spokojnosť',
            'Dobrý nákup',
            'Doručenie v poriadku',
        ];

        $neutralTexts = [
            'Produkt bol v poriadku, všetko zodpovedalo popisu.',
            'Doručené včas, zodpovedá tomu, čo som očakával.',
            'Niečo mi chýbalo, ale inak v poriadku.',
            'Nie najlepšie, ale určite ani zlé.',
            'Zachráni to chuť, ale obal bol poškodený.',
        ];

        $negativeTitles = [
            'Nie som spokojný',
            'Čakal som viac',
            'Priemer, nič extra',
            'Chuť ma sklamala',
            'Znova neobjednám',
            'Produkt nebol čerstvý',
        ];

        $negativeTexts = [
            'Produkt prišiel neskoro a vyzeral unavene.',
            'Chuť nevýrazná, čakal som viac domácej kvality.',
            'Obal bol poškodený a výrobok trochu zvädnutý.',
            'Nezodpovedá fotke ani popisu. Sklamanie.',
            'Dodanie trvalo dlho, medzičasom som si kúpil inde.',
            'Bolo to zjedlé, ale neobjednal by som znova.',
            'Zvláštna vôňa aj chuť. Možno starší kus.',
        ];

        // Urči hodnotenie
        $rating = $this->faker->boolean(80)
            ? $this->faker->randomElement([4.0, 4.5, 5.0])
            : $this->faker->randomElement([1.0, 2.0, 2.5, 3.0]);

        // Výber titulu a textu podľa hodnotenia
        if ($rating >= 4.0) {
            $title = $this->faker->randomElement($positiveTitles);
            $text = $this->faker->randomElement($positiveTexts);
        } elseif ($rating >= 3.0) {
            $title = $this->faker->randomElement($neutralTitles);
            $text = $this->faker->randomElement($neutralTexts);
        } else {
            $title = $this->faker->randomElement($negativeTitles);
            $text = $this->faker->randomElement($negativeTexts);
        }

        return [
            'farm_product_id' => FarmProduct::inRandomOrder()->value('id'),
            'user_id' => User::inRandomOrder()->value('id'),
            'title' => $title,
            'rating' => $rating,
            'text' => $text,
        ];
    }
}
