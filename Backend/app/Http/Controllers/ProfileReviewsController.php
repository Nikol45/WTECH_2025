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
            'farm_product.product',
            'farm_product.farm',
        ])
            ->where('user_id', Auth::id())
            ->latest()
            ->paginate(6);

        return view('profile.reviews', compact('reviews'));
    }

    /** Aktualizácia recenzie z modalu */
    public function update(Request $request, Review $review)
    {
        $this->authorize('update', $review);

        $data = $request->validate([
            'title'  => 'required|string|max:255',
            'rating' => 'required|integer|min:1|max:5',
            'text'   => 'required|string',
        ]);

        $review->update($data);

        return back()->with('success', 'Recenzia bola upravená.');
    }

    /** Zmazanie recenzie */
    public function destroy(Review $review)
    {
        $this->authorize('delete', $review);

        $review->delete();

        return back()->with('success', 'Recenzia bola zmazaná.');
    }
}
