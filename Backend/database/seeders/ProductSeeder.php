<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $i = 1;

        Category::with('subcategories.subsubcategories')->get()->each(function ($category) use (&$i) {
            foreach ($category->subcategories as $subcategory) {

                if ($subcategory->subsubcategories->count()) {
                    foreach ($subcategory->subsubcategories as $subsubcategory) {
                        Product::create ([
                            'name' => Str::slug("{$subcategory->name} " . lcfirst($subsubcategory->name), '_'),
                            'description' => fake()->sentence(3),
                            'category_id' => $category->id,
                            'subcategory_id' => $subcategory->id,
                            'subsubcategory_id' => $subsubcategory->id,
                        ]);
                        $i++;
                    }
                }
                
                else {
                    Product::create ([
                        'name' => Str::slug($subcategory->name, '_'),
                        'description' => fake()->paragraph(5),
                        'category_id' => $category->id,
                        'subcategory_id' => $subcategory->id,
                        'subsubcategory_id' => null,
                    ]);
                    $i++;
                }
            }
        });
    }
}
