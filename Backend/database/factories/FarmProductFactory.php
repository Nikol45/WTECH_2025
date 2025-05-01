<?php

namespace Database\Factories;

use App\Models\Farm;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class FarmProductFactory extends Factory
{
    private array $kgOrKs = [
        'Jablká', 'Hrušky', 'Broskyne', 'Granátové jablká', 'Kiwi', 'Marhule',
        'Slivky', 'Tekvica', 'Melóny', 'Bagety', 'Papriky', 'Paradajky', 'Uhorky',
        'Bazalka', 'Tymián', 'Oregano', 'Rozmarín', 'Medvedí cesnak',

    ];

    private array $onlyKg = [
        'Čerešne', 'Černice', 'Čučoriedky', 'Egreše', 'Figy', 'Hrášok', 'Hrozno',
        'Jahody', 'Maliny', 'Ríbezle', 'Zemiaky', 'Cibuľa', 'Mrkva', 'Reďkovky',
        'Múka', 'Syr', 'Tvaroh', 'Jogurt', 'Orechy', 'Med', 'Vosk', 'Propolis',
        'Peľ', 'Semená', 'Hnojivo', 'Chlieb', 'Kuracie', 'Hovädzie', 'Bravčové mäso',
        'Čaj',
    ];

    private array $onlyKs = ['Bagety', 'Sadenica', 'Vajcia'];

    private array $onlyL = ['Mlieko', 'Syrup', 'Džús', 'Džem', 'Kompót'];

    private array $priceRanges = [
        'Ovocie'            => [0.8, 2.5],
        'Zelenina'          => [0.6, 2.0],
        'Mäsové výrobky'    => [6.0, 14.0],
        'Mliečne výrobky'   => [1.5, 4.5],
        'Včelie produkty'   => [6.0, 12.0],
        'Pečivo a obilniny' => [0.7, 2.2],
        'Domáce nápoje'     => [0.9, 2.0],
        'Bylinky'           => [0.4, 1.5],
        'Domáce zaváraniny' => [1.2, 3.5],
        'Pestovanie'        => [0.2, 2.5],
        'default'           => [1.0, 5.0],
    ];

    private array $descriptions = [
        'Ovocie' => [
            'Tieto čučoriedky sú z našich južných svahov, kde dozrievajú pomaly a prirodzene.',
            'Slivky zo starých odrôd, ktoré máme na farme už vyše 40 rokov.',
            'Naše jablká rastú bez chemického ošetrenia, hnojené len domácim kompostom.',
        ],
        'Zelenina' => [
            'Mrkva vypestovaná v piesočnatých záhonoch — sladká, chrumkavá a deti ju milujú.',
            'Zemiaky z nášho poľa pri potoku. Skladujeme ich v hline pre trvanlivosť.',
            'Uhorky pestované tradične na slame, chuť ako z babkinej záhrady.',
        ],
        'Mliečne výrobky' => [
            'Jogurt z nepasterizovaného mlieka, fermentovaný prirodzene bez prídavných látok.',
            'Syr z ovčieho mlieka podľa receptu starej mamy, zreje aspoň 3 týždne.',
            'Tvaroh ručne miešaný, ideálny na pečenie alebo do bryndzových halušiek.',
        ],
        'Mäsové výrobky' => [
            'Domáce kuracie prsia z voľného výbehu, kŕmené bez granulátu.',
            'Bravčovina pochádza z našej malej farmy, kde majú prasiatka priestor a pokoj.',
            'Hovädzie mäso z mladého býčka, ktorý sa pásol pod horou celé leto.',
        ],
        'Včelie produkty' => [
            'Kvetový med z lúky plnej púpav, ďateliny a agátu.',
            'Propolis z lesov nad dedinou — silný, čistý a voňavý.',
            'Peľ sušený šetrne, aby si zachoval všetky živiny.',
        ],
        'Pečivo a obilniny' => [
            'Chlieb pečený z kvásku starého 8 rokov, v peci na drevo.',
            'Bagety sú ručne tvarované, ideálne na grilovanie.',
        ],
        'Domáce nápoje' => [
            'Bazový sirup zo slnečných dní, sladený trstinovým cukrom.',
            'Džús z jabĺk a mrkvy lisovaný za studena, bez pridaného cukru.',
        ],
        'Bylinky' => [
            'Tymian pestujeme bez postrekov, zbierame len predpoludním.',
            'Sušená mäta vhodná na čaj aj inhaláciu, zberaná ručne.',
        ],
        'Domáce zaváraniny' => [
            'Džem z prezretých marhúľ, sladený medom. Skvelý do jogurtu.',
            'Slivkový kompót s klinčekom a škoricou — chutí ako Vianoce.',
        ],
        'Pestovanie' => [
            'Semená našej vlastnej odrody rajčín – výnosné a odolné.',
            'Sadenice bazalky vhodné aj do kvetináča na balkóne.',
        ],
        'default' => [
            'Produkt pestovaný pod horou, kde líšky dávajú dobrú noc.',
            'Koza, ktorá dáva toto mlieko, sa volá Amálka. Poctivé a chutné!',
            'Naše produkty vznikajú z lásky k pôde a tradíciám gazdovstva.',
        ],
    ];

    public function definition(): array
    {
        $product = Product::inRandomOrder()->with('subcategory.category')->firstOrFail();
        $subcategory = $product->subcategory->name;
        $category = $product->subcategory->category->name;

        if (in_array($subcategory, $this->kgOrKs, true)) {
            $unit = $this->faker->randomElement(['kg', 'ks']);
        } elseif (in_array($subcategory, $this->onlyKg, true)) {
            $unit = 'kg';
        } elseif (in_array($subcategory, $this->onlyKs, true)) {
            $unit = 'ks';
        } elseif (in_array($subcategory, $this->onlyL, true)) {
            $unit = 'l';
        } else {
            $unit = $this->faker->randomElement(['kg', 'ks', 'l']);
        }

        [$min, $max] = $this->priceRanges[$category] ?? $this->priceRanges['default'];
        $pricePerUnit = $this->faker->randomFloat(2, $min, $max);

        $sellQuantity = match ($unit) {
            'ks' => $this->faker->randomElement([1, 5, 10, 30]),
            'l'  => $this->faker->randomElement([0.25, 0.5, 1, 1.5, 2, 3]),
            default => $this->faker->randomElement([0.1, 0.25, 0.5, 0.75, 1, 1.5, 2, 3, 5, 10]),
        };

        $priceSellQuantity = round($pricePerUnit * $sellQuantity, 2);

        return [
            'farm_id' => Farm::inRandomOrder()->value('id'),
            'product_id' => $product->id,
            'sell_quantity' => $sellQuantity,
            'price_sell_quantity' => $priceSellQuantity,
            'unit' => $unit,
            'price_per_unit' => round($pricePerUnit, 2),
            'discount_percentage' => $this->faker->optional(0.25)->passthrough(
                $this->faker->boolean(80)
                    ? $this->faker->numberBetween(5, 40)
                    : $this->faker->numberBetween(41, 90)
            ),
            'farm_specific_description' => $this->faker->optional(0.75)->randomElement(
                $this->descriptions[$category] ?? $this->descriptions['default']
            ),
            'availability' => $this->faker->boolean(80),
            'rating' => null,
        ];
    }
}
