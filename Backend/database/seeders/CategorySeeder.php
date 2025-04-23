<?php

namespace Database\Seeders;

use App\Models\Category;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tree = [
            'Ovocie' => [
                'Jablká' => ['Evelina', 'Gala', 'Golden Delicious', 'Granny Smith', 'Red Delicious', 'Pink Lady'],
                'Hrušky',
                'Broskyne',
                'Čerešne',
                'Černice',
                'Čučoriedky',
                'Egreše' => ['Ružové', 'Zelené'],
                'Figy',
                'Granátové jablká',
                'Hrášok',
                'Hrozno' => ['Zelené', 'Ružové', 'Modré'],
                'Jahody',
                'Kiwi',
                'Maliny',
                'Marhule',
                'Melóny',
                'Ríbezle' => ['Čierne', 'Červené'],
                'Slivky'
            ],

            'Zelenina' => [
                'Zemiaky' => ['Mozart', 'Sunita', 'Agria'],
                'Cibuľa',
                'Mrkva',
                'Tekvica',
                'Reďkovky',
                'Papriky' => ['Červené', 'Žlté', 'Zelené'],
                'Paradajky' => ['Cherry', 'Roma', 'Býčie srdce'],
                'Uhorky' => ['Šalátové', 'Kyslé']
            ],

            'Mliečne výrobky' => [
                'Mlieko' => ['Kravské', 'Kozie', 'Ovčie'],
                'Vajcia',
                'Syr',
                'Jogurt',
                'Tvaroh'
            ],

            'Mäsové výrobky' => [
                'Kuracie' => ['Prsia', 'Stehná'],
                'Hovädzie' => ['Sviečkovica', 'Steaky'],
                'Bravčové mäso'
            ],

            'Pečivo a obilniny' => [
                'Múka' => ['Pšeničná', 'Špaldová', 'Ražná'],
                'Chlieb' => ['Kváskový', 'Ražný'],
                'Bagety'
            ],

            'Domáce nápoje' => [
                'Džús' => ['Pomarančový', 'Jablkový', 'Broskyňový'],
                'Syrup' => ['Bazový', 'Malinový'],
            ],

            'Včelie produkty' => [
                'Med' => ['Agátový', 'Kvetový', 'Lesný'],
                'Orechy' => ['Vlašské', 'Lieskové'],
                'Vosk',
                'Propolis',
                'Peľ'
            ],

            'Bylinky' => [
                'Bazalka', 
                'Tymián', 
                'Oregano', 
                'Rozmarín', 
                'Medvedí cesnak',
                'Čaj' => ['Mäta', 'Harmanček', 'Medovka'],
            ],

            'Domáce zaváraniny' => [
                'Džem' => ['Marhuľový', 'Jahodový', 'Malinový'],
                'Kompót' => ['Broskyňový', 'Slivkový'],
            ],

            'Pestovanie' => [
                'Semená' => ['Ovocie', 'Zelenina', 'Bylinky', 'Kvetiny'],
                'Hnojivo',
                'Sadenica'
            ],
        ];

        foreach ($tree as $catName => $subcats) {
            $category = Category::create(['name' => $catName]);

            foreach ($subcats as $key => $value) {
                if (is_array($value)) {
                    $subcategory = $category->subcategories()->create([
                        'name' => $key,
                    ]);
            
                    foreach ($value as $subsubName) {
                        $subcategory->subsubcategories()->create([
                            'name' => $subsubName,
                        ]);
                    }
                }

                else {
                    $subcategory = $category->subcategories()->create([
                        'name' => $value,
                    ]);
                }
            }
        }
    }
}
