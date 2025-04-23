<?php

namespace Database\Factories;

use App\Models\Farm;
use App\Models\Product;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\FarmProduct>
 */
class FarmProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        $sellQuantity = $this->faker->randomFloat(1,0.1,20);
        $priceSellQuantity = $this->faker->randomFloat(2,0.1,20);

        $kgOrKs = ['Jablká', 'Hrušky', 'Broskyne', 'Granátové jablká', 'Kiwi', 'Marhule', 'Slivky'];
        
        $onlyKg = ['Čerešne', 'Černice', 'Čučoriedky', 'Egreše', 'Figy', 'Hrášok', 'Hrozno', 'Jahody', 'Maliny', 'Ríbezle', 'Zemiaky', 'Cibuľa', 'Mrkva', 'Reďkovky', 'Papriky', 'Paradajky', 'Uhorky', 'Múka', 'Syr', 'Tvaroh', 'Jogurt', 'Orechy', 'Kuracie', 'Hovädzie', 'Bravčové mäso', 'Med', 'Vosk', 'Propolis', 'Peľ', 'Bazalka', 'Tymián', 'Oregano', 'Rozmarín', 'Medvedí cesnak', 'Čaj', 'Džem', 'Kompót', 'Semená', 'Hnojivo'];
        
        $onlyKs = ['Melóny', 'Tekvica', 'Vajcia', 'Bagety', 'Chlieb', 'Sadenica'];
        
        $onlyL = ['Mlieko', 'Syrup', 'Džús'];
    
        $product = Product::inRandomOrder()->with('subcategory')->first();
        $subcategory = $product->subcategory->name;

        if (in_array($subcategory, $kgOrKs)) {
            $unit = $this->faker->randomElement(['kg', 'ks']);
        }
        elseif (in_array($subcategory, $onlyKg)) {
            $unit = 'kg';
        }
        elseif (in_array($subcategory, $onlyKs)) {
            $unit = 'ks';
        }
        elseif (in_array($subcategory, $onlyL)) {
            $unit = 'l';
        }
        else {
            $unit = $this->faker->randomElement(['kg', 'l', 'ks']);
        }

        return [
            'farm_id' => Farm::inRandomOrder()->value('id'),
            'product_id' => $product->id,
            'sell_quantity' => $sellQuantity,
            'price_sell_quantity' => $priceSellQuantity,
            'unit' => $unit,
            'price_per_unit' => round($priceSellQuantity / $sellQuantity, 2),
            'discount_percentage' => $this->faker->optional(0.3)->numberBetween(5, 90),
            'farm_specific_description' => $this->faker->optional(0.5)->paragraph(5),
            'availability' => $this->faker->boolean(80),
            'rating' => null
        ];
    }
}
