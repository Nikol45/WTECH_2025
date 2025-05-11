<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $data = $request->validate([
            'email_or_username' => 'required|string',
            'password'          => 'required|string',
//            'remember'          => 'sometimes|boolean',
        ]);

        $loginField = filter_var($data['email_or_username'], FILTER_VALIDATE_EMAIL)
            ? 'email'
            : 'nickname';

        $credentials = [
            $loginField => $data['email_or_username'],
            'password'  => $data['password'],
        ];

        // Pokus o prihlásenie
        if (! Auth::attempt($credentials, $data['remember'] ?? false)) {
            if ($request->expectsJson()) {
                return response()->json([
                    'errors' => ['login' => 'Neplatné prihlasovacie údaje.']
                ], 422);
            }
            // fallback
            return back()
                ->withErrors(['login' => 'Neplatné prihlasovacie údaje.'])
                ->withInput($request->only('email_or_username', 'remember'))
                ->with('openModal', 'loginModal');
        }

        // 4) Regenerácia session pri úspechu
        $request->session()->regenerate();

        $sessionCart = session('cart.items', []);
        $userId = Auth::id();

        foreach ($sessionCart as $fpId => $quantity) {
            $fpId = (int) $fpId;
            if (!is_numeric($fpId)) continue;

            $item = CartItem::firstOrNew([
                'user_id' => $userId,
                'farm_product_id' => $fpId,
            ]);

            $item->quantity = $item->exists
                ? $item->quantity + $quantity
                : $quantity;

            $item->save();
        }

        session()->forget('cart.items');

        // 5) Odpoveď – JSON pre AJAX, alebo back so značkou úspechu pre non-AJAX
        if ($request->expectsJson()) {
            return response()->json(['success' => true]);
        }
        return back()->with('loginSuccess', true);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // AJAX odpoveď
        if ($request->ajax()) {
            return response()->json(['success' => true]);
        }

        // Inak redirect
        return redirect('/');
    }

}
