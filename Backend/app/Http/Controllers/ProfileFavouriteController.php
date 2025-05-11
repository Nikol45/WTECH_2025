<?php

namespace App\Http\Controllers;

use App\Models\Favourite;
use App\Models\FarmProduct;
use App\Http\Requests\StoreFavouriteRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ProfileFavouriteController extends Controller
{
    public function index()
    {
        $favourites = Favourite::with([
            'farm_product.product',
            'farm_product.farm',
        ])
            ->where('user_id', Auth::id())
            ->latest()
            ->paginate(12);

        return view('profile.favourites', compact('favourites'));
    }

    public function store(StoreFavouriteRequest $request): RedirectResponse
    {
        $farmProductId = $request->integer('farm_product_id');

        if (!FarmProduct::where('id', $farmProductId)->exists()) {
            return back()->with('error', 'Produkt už neexistuje.');
        }

        Favourite::firstOrCreate([
            'user_id'         => Auth::id(),
            'farm_product_id' => $farmProductId,
        ]);


        return back()->with('success', 'Produkt bol pridaný do obľúbených.');
    }

    public function destroy(Favourite $favourite): RedirectResponse
    {
        // používateľ môže mazať len svoje záznamy
        $this->authorize('delete', $favourite);

        $favourite->delete();

        return back()->with('success', 'Produkt bol odstránený z obľúbených.');
    }

    public function create()  { abort(404); }
    public function show(Favourite $favourite)   { abort(404); }
    public function edit(Favourite $favourite)   { abort(404); }
    public function update(Request $request, Favourite $favourite)
    {
        abort(404);
    }
}
