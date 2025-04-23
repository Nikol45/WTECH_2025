<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Image;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use App\Models\Farm;
use App\Models\FarmProduct;
use App\Models\Article;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ImageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void {   
        Product::with('category')->get()->each(function ($product) {
            $categoryName = Str::slug($product->category->name, '_');
            $name = Str::slug(strtolower($product->name), '_');

            Image::create([
                'imageable_id' => $product->id,
                'imageable_type' => Product::class,
                'name' => $name,
                'path' => 'images/tovar/' . $categoryName . '/' . $name . '.png'
            ]);
        });

        $farmImages = File::files(public_path('images/farms'));
        $articleImages = File::files(public_path('images/articles'));
        $fpImages = File::files(public_path('images/farm_products'));

        Farm::all()->each(function ($farm) use ($farmImages) {
            $random = collect($farmImages)->random();
            Image::create([
                'imageable_id' => $farm->id,
                'imageable_type' => Farm::class,
                'name' => $random->getFilename(),
                'path' => 'images/farms/' . $random->getFilename()
            ]);
        });

        Article::all()->each(function ($article) use ($articleImages) {
            $random = collect($articleImages)->random();
            Image::create([
                'imageable_id' => $article->id,
                'imageable_type' => Article::class,
                'name' => $random->getFilename(),
                'path' => 'images/articles/' . $random->getFilename()
            ]);
        });

        $categoryImageNames = [
            'Ovocie' => 'evelina',
            'Zelenina' => 'zemiaky',
            'Mliečne výrobky' => 'mlieko',
            'Mäsové výrobky' => 'maso',
            'Pečivo a obilniny' => 'bageta',
            'Domáce nápoje' => 'dzus',
            'Včelie produkty' => 'med',
            'Bylinky' => 'bazalka',
            'Domáce zaváraniny' => 'dzem',
            'Pestovanie' => 'semienka',
        ];

        FarmProduct::with('product.category')->get()->each(function ($fp) use ($categoryImageNames) {
            $category = $fp->product->category->name;
            $fileName = $categoryImageNames[$category] ?? null;

            for ($i = 1; $i <= 4; $i++) {
                $filename = $fileName . "_{$i}.png";
                $path = "images/farm_products/{$filename}";

                Image::create([
                    'imageable_id' => $fp->id,
                    'imageable_type' => FarmProduct::class,
                    'name' => $filename,
                    'path' => $path,
                ]);
            }
        });

    }
}
