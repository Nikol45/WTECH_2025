<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\FarmProduct;
use Illuminate\Http\Request;
use App\Models\Subcategory;
use App\Models\Subsubcategory;
use App\Models\Article;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request) {
        $search = trim((string) $request->input('q', ''));
        $clickedSubCat = $request->integer('subcategory');
        $selectedSubs  = $request->input('subcategories', []);

        $priceMin = $request->filled('price_min') ? (float) $request->input('price_min') : null;
        $priceMax = $request->filled('price_max') ? (float) $request->input('price_max') : null;
        $rating = $request->integer('rating');
        $discountOnly = $request->input('discount_only') === '1';

        $sort = $request->input('sort','');

        $products = FarmProduct::with(['product.subsubcategory', 'farm'])
            ->when($search !== '',
                fn ($q) => $q->whereHas('product',
                    fn ($qq) => $qq->where('name', 'ILIKE', "%{$search}%")
                )
            )

            ->when($clickedSubCat, function ($q) use ($clickedSubCat) {
                    $q->where(function ($qq) use ($clickedSubCat) {
                        $qq->whereHas('product.subsubcategory',
                                fn ($sss) => $sss->where('subcategory_id', $clickedSubCat))
                        ->orWhereHas('product',
                                fn ($pp)  => $pp->whereNull('subsubcategory_id')
                                                ->where('subcategory_id', $clickedSubCat));
                    });
            })

            ->when($selectedSubs   !== [],
                fn ($q) => $q->whereIn('product_id',
                    Product::whereIn('subsubcategory_id', $selectedSubs)->pluck('id')
                )
            )

            ->when($priceMin !== null, fn($q) => $q->whereRaw(
                '(price_sell_quantity * (100 - COALESCE(discount_percentage,0)) / 100) >= ?', 
                [$priceMin]
            ))

            ->when($priceMax !== null, fn($q) => $q->whereRaw(
                '(price_sell_quantity * (100 - COALESCE(discount_percentage,0)) / 100) <= ?', 
                [$priceMax]
            ))

            ->when($rating,
                fn ($q) => $q->where('rating', '>=', $rating)
            )

            ->when($discountOnly,
                fn ($q) => $q->whereNotNull('discount_percentage')
                            ->where('discount_percentage', '>', 0)
            )

            ->when(in_array($sort, ['priceAsc','priceDesc']), function($q) use($sort) {
                $dir = $sort === 'priceAsc' ? 'asc' : 'desc';
                $q->orderByRaw(
                    'price_sell_quantity * (100 - COALESCE(discount_percentage,0)) / 100 ' . $dir
                );
            })

            ->when($sort === 'ratingAsc', fn($q)=> $q->orderBy('rating', 'asc'))

            ->when($sort === 'ratingDesc',fn($q)=> $q->orderBy('rating', 'desc'));

            $products = $products
            ->paginate(24)
            ->withQueryString();

        if ($clickedSubCat || !empty($selectedSubs)) {
            $parentId = $clickedSubCat?: Subsubcategory::whereIn('id', $selectedSubs)->value('subcategory_id');

            $filterSubsubs = Subsubcategory::where('subcategory_id', $parentId)
                                           ->orderBy('name')
                                           ->get();
        
        }
        else {
            $ids = $products->pluck('product.subsubcategory.id')->filter()->unique();
    
            $filterSubsubs = Subsubcategory::whereIn('id', $ids)
                                           ->orderBy('name')
                                           ->get();
        }

        $headline = null;
        if ($search !== '') {
            $headline = 'Výsledky pre výraz „'.$search.'“';
        }
        elseif ($clickedSubCat) {
            $headline = 'Výsledky pre kategóriu „'.
                        optional(Subcategory::find($clickedSubCat))->name.'“';
        }

        $articles = Article::with(['user.farms', 'image'])
            ->inRandomOrder()
            ->limit(10)
            ->get();

        return view('products.index', compact(
            'products', 'filterSubsubs', 'selectedSubs',
            'priceMin', 'priceMax', 'rating',
            'discountOnly', 'search', 'clickedSubCat', 'headline'
        ), ['articles' => $this->mapArticles($articles)]);
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


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request, Product $product)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        //
    }
}
