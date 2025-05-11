<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GuestController extends Controller
{
    public function shopAsGuest(Request $request)
    {
        $request->session()->put('guest', true);

        $request->session()->flash('guest_alert', 'Nakupujete ako hosť');

        return redirect()->route('homepage');
    }
}
