<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GuestController extends Controller
{
    public function shopAsGuest(Request $request)
    {
        // Mark as guest in session
        $request->session()->put('guest', true);

        // Optional banner alert for guest shopping
        $request->session()->flash('guest_alert', 'Nakupujete ako hosť');

        // Redirect to homepage
        return redirect()->route('homepage');
    }
}
