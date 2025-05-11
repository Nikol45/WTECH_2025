<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\FarmProduct;
use App\Models\Farm;
use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use App\Http\Requests\StoreFarmProductRequest;
use App\Http\Requests\UpdateFarmProductRequest;

class FarmProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(FarmProduct $farmProduct)
    {
        //
    }

    public function create(Farm $farm) {
        $all = Product::orderBy('name')->pluck('name','id');
        return view('farm.products.create', compact('farm','all'));
    }

    public function store(Request $request, Farm $farm) {
        $data = $request->validate([
            'name'                 => ['required','string',Rule::in(Product::pluck('name')->toArray())],
            'price_per_unit'  => 'required|numeric|min:0.01',
            'sale_type'            => 'required|in:kg,ks,l',
            'amount'               => 'required|numeric|min:0.01',
            'discount_percentage'  => 'nullable|integer|between:0,100',
            'description'          => 'nullable|string',
            'images' => 'required|array|min:2',
            'images.*'             => 'image|mimes:jpg,jpeg,png,webp|max:5120',
        ]);

        if ($data['sale_type'] === 'ks' && floor($data['amount']) != $data['amount']) {
            return back()
                ->withErrors(['amount' => 'Pre predaj po kusoch (ks) musí byť množstvo celé číslo.'])
                ->withInput();
        }

        $product = Product::where('name', $data['name'])->firstOrFail();

        // Calculate price per unit
        $priceSellQuantity = $data['price_per_unit'] * $data['amount'];

        $finalPrice = round($priceSellQuantity, 2);
        if ($finalPrice === 0.00) {
            return back()
                ->withErrors(['price_per_unit' => 'Výsledná cena po zaokrúhlení musí byť väčšia ako 0.'])
                ->withInput();
        }

        // Create the FarmProduct
        $farmProduct = $farm->farm_products()->create([
            'product_id'                => $product->id,
            'sell_quantity'            => $data['amount'],
            'price_sell_quantity'      => round($priceSellQuantity, 2),
            'unit'                     => $data['sale_type'],
            'price_per_unit'           => $data['price_per_unit'],
            'discount_percentage'      => $data['discount_percentage'] ?? null,
            'farm_specific_description'=> $data['description'],
            'availability'             => true,
            'rating'                   => null,
        ]);
 
        $uploadPath = public_path('images/farm_products');
        if (!file_exists($uploadPath)) {
            mkdir($uploadPath, 0775, true);
        }

        foreach ($request->file('images') as $file) {
            $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
            $file->move($uploadPath, $filename);

            Image::create([
                'imageable_id' => $farmProduct->id,
                'imageable_type' => \App\Models\FarmProduct::class,
                'name' => $filename,
                'path' => 'images/farm_products/' . $filename,
            ]);
        }
 
        return back()->with('success','Produkt bol pridaný.');
    }

    public function edit(FarmProduct $product)
    {
        $all = Product::orderBy('name')->pluck('name','id');
        return view('farm.products.edit', [
            'product' => $product,
            'all'     => $all,
        ]);
    }

    public function update(Request $request, Farm $farm, FarmProduct $product)
    {
        $data = $request->validate([
            'name'                 => ['required','string',Rule::in(Product::pluck('name')->toArray())],
            'price_per_unit'  => 'required|numeric|min:0.01',
            'sale_type'            => 'required|in:kg,ks,l',
            'amount'               => 'required|numeric|min:0.01',
            'discount_percentage'  => 'nullable|integer|between:0,100',
            'description'          => 'nullable|string',
            'images.*'             => 'image|mimes:jpg,jpeg,png,webp|max:5120',
        ]);

        if ($data['sale_type'] === 'ks' && floor($data['amount']) != $data['amount']) {
            return back()
                ->withErrors(['amount' => 'Pre predaj po kusoch (ks) musí byť množstvo celé číslo.'])
                ->withInput();
        }

        $priceSellQuantity = $data['price_per_unit'] * $data['amount'];

        $finalPrice = round($priceSellQuantity, 2);
        if ($finalPrice === 0.00) {
            return back()
                ->withErrors(['price_per_unit' => 'Výsledná cena po zaokrúhlení musí byť väčšia ako 0.'])
                ->withInput();
        }
 
        $product->update([
            'price_per_unit'           => $data['price_per_unit'],
            'sell_quantity'            => $data['amount'],
            'price_sell_quantity'      => round($priceSellQuantity, 2),
            'unit'                     => $data['sale_type'],
            'discount_percentage'      => $data['discount_percentage'] ?? null,
            'farm_specific_description'=> $data['description'],
        ]);

        if ($request->has('unavailable')) {
            $product->availability = false;
            $product->save();
        
            // Remove it from all carts
            \App\Models\CartItem::where('farm_product_id', $product->id)->delete();
        } else {
            $product->availability = true;
            $product->save();
        }
 
        $toRemove = $request->input('remove_images', []);
        if (!is_array($toRemove)) $toRemove = [];

        foreach ($toRemove as $id) {
            $img = $product->images()->where('id', $id)->first();
            if ($img && file_exists(public_path($img->path))) {
                unlink(public_path($img->path));
            }
            $img?->delete();
        }

        // Ensure that remaining + new >= 2
        $remaining = $product->images()->count();
        $newCount = $request->hasFile('images') ? count($request->file('images')) : 0;
        if (($remaining + $newCount) < 2) {
            return back()->withErrors(['images' => 'Produkt musí mať aspoň 2 fotky.']);
        }

        // Save new images
        if ($request->hasFile('images')) {
            $uploadPath = public_path('images/farm_products');
            if (!file_exists($uploadPath)) mkdir($uploadPath, 0775, true);

            foreach ($request->file('images') as $file) {
                $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
                $file->move($uploadPath, $filename);

                $product->images()->create([
                    'name' => $filename,
                    'path' => 'images/farm_products/' . $filename,
                ]);
            }
        }
 
        return back()->with('success','Produkt bol upravený.');
    }

    public function destroy(FarmProduct $product)
    {
        foreach ($product->images as $img) {

            // full absolute path – the same logic you used on upload
            $absolutePath = public_path($img->path);
    
            // delete the file if it’s still there
            if (File::exists($absolutePath)) {
                File::delete($absolutePath);         // or unlink($absolutePath);
            }
    
            // remove the row from the images table
            $img->delete();
        }

        $farmId = $product->farm_id;
        $product->delete();
        return redirect()->route('farms.show', $farmId)
                         ->with('success','Produkt zmazaný');
    }
}