<?php

namespace Database\Seeders;

use App\Models\Farm;
use App\Models\FarmProduct;
use App\Models\Package;
use App\Models\Order;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $this->call([
            CategorySeeder::class,
            ProductSeeder::class,
            IconSeeder::class,
            UserSeeder::class,
            ArticleSeeder::class,
            FarmSeeder::class,
            OrderSeeder::class,
            PackageSeeder::class,
            FarmProductSeeder::class,
            OrderItemSeeder::class,
            ReviewSeeder::class,
            FavouriteSeeder::class,
            CartItemSeeder::class,
            ImageSeeder::class
        ]);

        Package::with('order_items')->get()->each(function ($package) {
            $total = $package->order_items->sum(function ($item) {
                return $item->quantity * $item->price_when_ordered;
            });
        
            $package->update(['price' => round($total, 2)]);
        });

        Order::with('packages')->get()->each(function ($order) {
            $total = $order->packages->sum('price');
            $order->update(['total_price' => round($total, 2)]);
        });

        FarmProduct::with('reviews')->get()->each(function ($product) {
            $average = $product->reviews()->avg('rating') ?? 0;
            $product->update(['rating' => round($average, 2)]);
        });

        Farm::with('farm_products')->get()->each(function ($farm) {
            $avgRating = $farm->farm_products()->avg('rating') ?? 0;
            $farm->update(['rating' => round($avgRating, 2)]);
        });

    }
}
