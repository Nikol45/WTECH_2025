<?php

namespace App\Http\Controllers;

use App\Models\FarmProduct;
use App\Models\Product;
use App\Models\Image;
use App\Models\Article;
use Illuminate\Support\Str;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function homepage() {
        $recommended = FarmProduct::with(['product', 'farm'])
        ->inRandomOrder()
        ->limit(20)
        ->get();

        $newest = FarmProduct::with(['product', 'farm'])
            ->orderByDesc('updated_at')
            ->limit(20)
            ->get();

        $discounted = FarmProduct::with(['product', 'farm'])
            ->whereNotNull('discount_percentage')
            ->orderByDesc('discount_percentage')
            ->limit(20)
            ->get();

        $seasonalNames = ['Jahody', 'Mrkvy', 'Reďkovky', 'Varenie Medvedí cesnak', 'Hrášok'];
        $seasonalProductIds = Product::whereIn('name', $seasonalNames)->pluck('id');

        $seasonal = FarmProduct::with(['product', 'farm'])
            ->whereIn('product_id', $seasonalProductIds)
            ->limit(20)
            ->get();

        $articles = Article::with(['user.farms', 'image'])
            ->inRandomOrder()
            ->limit(10)
            ->get();

        return view('homepage', [
            'recommendedProducts' => $this->mapProducts($recommended),
            'newestProducts' => $this->mapProducts($newest),
            'discountedProducts' => $this->mapProducts($discounted),
            'seasonalProducts' => $this->mapProducts($seasonal),
            'articles' => $this->mapArticles($articles),
        ]);
    }

    private function mapProducts($products) {
        return $products->map(function ($fp) {
            $image = $fp->product->image;

            $effective = $fp->price_sell_quantity * (100 - ($fp->discount_percentage ?? 0)) / 100;
            $price = number_format($effective, 2) . ' €';
            $original = $fp->discount_percentage ? number_format($fp->price_sell_quantity, 2) . ' €' : null;
            
            return [
                'id' => $fp->id,
                'name' => $fp->product->name,
                'image' => $image->path,
                'alt' => $image->name,
                'price' => $price,
                'original_price' => $original,
                'price_per' => number_format($fp->price_per_unit, 2) . ' €/' . $fp->unit,
                'discount' => $fp->discount_percentage,
                'rating' => $fp->rating ?? 0,
                'location' => $fp->farm->name . ', ' . ($fp->farm->address->city ?? ''),
                'farm_id' => $fp->farm->id
            ];
        });
    }

    private function mapArticles($articles) {
        return $articles->map(function ($article) {
            $farms = $article->user->farms;
            $randomFarmName = $farms->isNotEmpty() ? $farms->random()->name : '';
    
            return [
                'title' => $article->title,
                'image' => $article->image->path,
                'author' => $article->user->name . ($randomFarmName ? ', ' . $randomFarmName : ''),
            ];
        });
    }
}
