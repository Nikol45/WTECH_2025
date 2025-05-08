<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileReviewsController extends Controller
{
    /** Zoznam recenzií prihláseného používateľa */
    public function index()
    {
        $reviews = Review::with([
            'farm_product.product.image', // obrázok produktu
            'farm_product.farm',          // názov farmy
        ])
            ->where('user_id', Auth::id())
            ->latest()
            ->paginate(6);

        return view('profile.reviews', compact('reviews'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'farm_product_id' => ['required', 'exists:farm_products,id'],
            'title'           => ['required', 'string', 'max:255'],
            'rating'          => [
                'required',
                'numeric',
                Rule::in([1, 1.5, 2, 2.5, 3, 3.5, 4, 4.5, 5])
            ],
            'text'            => ['required', 'string'],
        ]);

        $validated['user_id'] = Auth::id();

        Review::create($validated);

        return back()->with('success', 'Recenzia bola pridaná.');
    }

    /** Aktualizácia recenzie z modalu (PATCH) */
    public function update(Request $request, Review $review)
    {
        if ($review->user_id !== Auth::id()) {
            abort(403, 'Nemáte oprávnenie upraviť túto recenziu.');
        }

        $validated = $request->validate([
            'title'  => ['required', 'string', 'max:255'],
            'rating' => [
                'required',
                'numeric',
                Rule::in([1, 1.5, 2, 2.5, 3, 3.5, 4, 4.5, 5])
            ],
            'text'   => ['required', 'string'],
        ]);

        $review->update($validated);

        return back()->with('success', 'Recenzia bola upravená.');
    }

    /** Zmazanie recenzie (DELETE) */
    public function destroy(Review $review)
    {
        if ($review->user_id !== Auth::id()) {
            abort(403, 'Nemáte oprávnenie zmazať túto recenziu.');
        }

        $review->delete();

        return back()->with('success', 'Recenzia bola zmazaná.');
    }
}
