<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Farm;
use App\Models\Article;
use App\Models\Review;

class ProfileController extends Controller
{

    public function index()
    {
        $user = Auth::user();
        if (! $user) {
            return redirect()->route('homepage');
        }

        // režim zobrazenia: 'customer' alebo 'admin' (default customer)
        $view = session('profile_view', 'customer');

        // koľko recenzií napísal
        $reviewCount = Review::where('user_id', $user->id)->count();

        // prahy pre odznaky
        $thresholds = [
            0   => ['🌱', 'Nováčik'],
            1   => ['🥚', 'Začiatočník'],
            5   => ['🪴', 'Oceniteľ chutí'],
            15  => ['🧺', 'Skúsený ochutnávač'],
            30  => ['🐓', 'Miestny odporúčač'],
            50  => ['🍯', 'Gurmánsky prieskumník'],
            100 => ['🏆', 'Znalec Slovenska'],
        ];

        // najvyšší dosiahnutý prah
        $currentThreshold = 0;
        $badgeIcon       = null;
        $badgeName       = null;

        foreach ($thresholds as $threshold => $info) {
            if ($reviewCount >= $threshold) {
                $currentThreshold = $threshold;
                [$badgeIcon, $badgeName] = $info;
            } else {
                break;
            }
        }

        // progress bar
        $nextThreshold = null;
        foreach (array_keys($thresholds) as $threshold) {
            if ($threshold > $reviewCount) {
                $nextThreshold = $threshold;
                break;
            }
        }
        $badgeTarget = $nextThreshold ?? $currentThreshold;

        // vlastnosti do $user pre blade
        $user->badge_icon    = $badgeIcon;
        $user->badge_name    = $badgeName;
        $user->badge_current = $reviewCount;
        $user->badge_target  = $badgeTarget;

        // dáta pre view
        $data = [
            'user'   => $user,
            'view'   => $view,
            'active' => 'index',
        ];

        if ($user->admin_account) {
            $data['farms']    = Farm::where('user_id', $user->id)->get();
            $data['articles'] = Article::where('user_id', $user->id)->get();
            $data['reviews'] = Review::where('user_id', $user->id)
                ->with('farm_product') // pridá produkt k recenzii
                ->latest()
                ->take(5)
                ->get();        }

        return view('profile.index', $data);
    }

    /**
     * Nastaví režim zobrazenia na "admin" (iba pre admin účty).
     */
    public function viewAsAdmin()
    {
        $user = Auth::user();
        if (! $user || ! $user->admin_account) {
            session(['profile_view' => 'customer']);
            return redirect()->route('profile.index');
        }
        session(['profile_view' => 'admin']);
        return redirect()->route('profile.index');
    }

    /**
     * Nastaví režim zobrazenia na "customer".
     */
    public function viewAsCustomer()
    {
        session(['profile_view' => 'customer']);
        return redirect()->route('profile.index');
    }
}
