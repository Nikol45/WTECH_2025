<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    public function register(Request $request)
    {
        try {
            // Validácia vstupov
            $data = $request->validate([
                'nickname' => 'required|string|min:3',
                'email'    => 'required|email|unique:users,email',
                'password' => 'required|string|min:8|confirmed',
                'terms'    => 'required|accepted',
            ]);

            // Vytvorenie používateľa
            $user = User::create([
                'nickname'     => $data['nickname'],
                'email'    => $data['email'],
                'password' => Hash::make($data['password']),
            ]);

            // Prihlásenie
            Auth::login($user);

            // AJAX odpoveď
            if ($request->expectsJson()) {
                return response()->json(['success' => true], 200);
            }

            // Klasický redirect
            return redirect()->route('homepage');
        } catch (\Throwable $e) {
            // Pri výnimke: pošli chybu cez JSON (napr. 500)
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Server error',
                    'error' => $e->getMessage()
                ], 500);
            }

            return back()->with('error', 'Nastala chyba pri registrácii.');
        }
    }


}
