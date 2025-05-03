<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Farm;
use App\Models\Review;
use App\Models\Address;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    /* ======================== DASHBOARD ======================== */

    public function index()
    {
        $user = Auth::user()->load([
            'billingAddress',
            'deliveryAddress',
            'company',
        ]);        if (! $user) {
            return redirect()->route('homepage');
        }

        // režim zobrazenia: customer / admin
        $view = session('profile_view', 'customer');

        // odznak podľa počtu recenzií
        $reviewCount  = Review::where('user_id', $user->id)->count();
        $thresholds   = [
            0   => ['🌱', 'Nováčik'],
            1   => ['🥚', 'Začiatočník'],
            5   => ['🪴', 'Oceniteľ chutí'],
            15  => ['🧺', 'Skúsený ochutnávač'],
            30  => ['🐓', 'Miestny odporúčač'],
            50  => ['🍯', 'Gurmánsky prieskumník'],
            100 => ['🏆', 'Znalec Slovenska'],
        ];

        $badgeIcon = $badgeName = null;
        foreach ($thresholds as $t => $info) {
            if ($reviewCount >= $t) [$badgeIcon, $badgeName] = $info;
            else break;
        }
        $nextT = collect(array_keys($thresholds))
            ->first(fn ($t) => $t > $reviewCount) ?? $reviewCount;

        $user->badge_icon    = $badgeIcon;
        $user->badge_name    = $badgeName;
        $user->badge_current = $reviewCount;
        $user->badge_target  = $nextT;

        $data = compact('user', 'view') + ['active' => 'index'];

        if ($user->is_admin) {
            $data['farms']    = Farm::whereUserId($user->id)->get();
            $data['articles'] = Article::whereUserId($user->id)->get();
            $data['reviews']  = Review::whereUserId($user->id)
                ->with('farm_product')
                ->latest()
                ->take(5)
                ->get();
        }

        return view('profile.index', $data);
    }

    /* ======================== PREPÍNAČ REŽIMU ======================== */

    public function viewAsAdmin()
    {
        $user = Auth::user();
        session(['profile_view' => $user?->is_admin ? 'admin' : 'customer']);
        return redirect()->route('profile.index');
    }

    public function viewAsCustomer()
    {
        session(['profile_view' => 'customer']);
        return redirect()->route('profile.index');
    }

    /* ======================== UPDATE MÉTODY ======================== */

    public function updateName(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255']);
        $request->user()->update(['name' => $request->name]);
        return back()->with('success', 'Meno bolo upravené.');
    }

    public function updateEmail(Request $request)
    {
        $request->validate([
            'email' => [
                'required', 'email', 'max:255',
                Rule::unique('users')->ignore($request->user()->id),
            ],
        ]);
        $request->user()->update(['email' => $request->email]);
        return back()->with('success', 'Email bol upravený.');
    }

    public function updatePhone(Request $request)
    {
        $request->validate([
            'phone_number' => 'required|string|min:4|max:32',
        ]);

        $raw = preg_replace('/\D+/', '', $request->phone_number); // len čísla

        // ak začína 0, odstráň a pridaj +421
        if (str_starts_with($raw, '0')) {
            $raw = substr($raw, 1);
            $formatted = '+421 ' . substr($raw, 0, 3) . ' ' . substr($raw, 3, 3) . ' ' . substr($raw, 6);
        }
        // ak už začína na 421, predpokladáme že je to slovenské číslo bez +
        elseif (str_starts_with($raw, '421')) {
            $raw = substr($raw, 3);
            $formatted = '+421 ' . substr($raw, 0, 3) . ' ' . substr($raw, 3, 3) . ' ' . substr($raw, 6);
        }
        // fallback – len čísla (nezmení sa nič)
        else {
            $formatted = $request->phone_number;
        }

        $request->user()->update(['phone_number' => $formatted]);

        return back()->with('success', 'Telefónne číslo bolo upravené.');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password'      => 'required',
            'password'              => 'required|confirmed|min:8',
        ]);

        if (! Hash::check($request->current_password, $request->user()->password)) {
            return back()->withErrors(['current_password' => 'Nesprávne aktuálne heslo.']);
        }

        $request->user()->update(['password' => Hash::make($request->password)]);
        return back()->with('success', 'Heslo bolo zmenené.');
    }

    /* ---------- Adresy ---------- */

    public function updateBillingAddress(Request $request)
    {
        $data = $this->validateAddress($request);
        $this->upsertAddress($request->user(), 'billingAddress', $data);
        return back()->with('success', 'Fakturačná adresa bola uložená.');
    }

    public function updateDeliveryAddress(Request $request)
    {
        $data = $this->validateAddress($request);
        $this->upsertAddress($request->user(), 'deliveryAddress', $data);
        return back()->with('success', 'Dodacia adresa bola uložená.');
    }

    /* ---------- Firma ---------- */

    public function updateCompany(Request $request)
    {
        $data = $request->validate([
            'name'    => 'required|string|max:255',
            'ico'     => 'required|string|max:32',
            'ic_dph'  => 'nullable|string|max:32',
        ]);

        $user = $request->user();

        // ak má, aktualizuj
        if ($user->company_id && $user->company) {
            $user->company->update($data);
        } else {
            // vytvor novú firmu
            $company = Company::create($data);
            $user->company_id = $company->id;
            $user->save();
        }

        return back()->with('success', 'Firemné údaje boli uložené.');
    }


    public function updateNickname(Request $r)
    {
        $r->validate(['nickname' => 'required|string|max:50']);
        $r->user()->update(['nickname' => $r->nickname]);
        return back()->with('success', 'Prezývka bola uložená.');
    }

    public function updateIcon(Request $r)
    {
        $r->validate(['icon_id' => 'required|exists:icons,id']);
        $r->user()->update(['icon_id' => $r->icon_id]);
        return back()->with('success', 'Avatar bol zmenený.');
    }

    public function createAdmin(Request $r)
    {
        $data = $r->validate([
            'name' => 'nullable|string|max:255',
        ]);

        $user = $r->user();

        if (!empty($data['name'])) {
            $user->name = $data['name'];
        }

        $user->is_admin = true;
        $user->save();

        return $this->viewAsAdmin();
    }


    /* ----------- uloženie farmy z modalu ----------- */
    public function storeFarm(Request $r)
    {
        $data = $r->validate([
            'name'           => 'required|string|max:255',
            'description'    => 'nullable|string',
            'image'          => 'nullable|image|max:2048',
            // adresa
            'street'         => 'required|string|max:255',
            'street_number'  => 'required|string|max:32',
            'city'           => 'required|string|max:255',
            'zip_code'       => 'required|string|max:16',
            'country'        => 'required|string|max:255',
        ]);

        // 1. adresa
        $address = Address::create([
            'street'        => $data['street'],
            'street_number' => $data['street_number'],
            'city'          => $data['city'],
            'zip_code'      => $data['zip_code'],
            'country'       => $data['country'],
            'address_type'  => 'farm',
        ]);

        // 2. farma
        $farm = Farm::create([
            'user_id'    => $r->user()->id,
            'address_id' => $address->id,
            'name'       => $data['name'],
            'description'=> $data['description'] ?? null,
        ]);

        // 3. obrázok ak existuje
        if ($r->hasFile('image')) {
            $path = $r->file('image')->store('farms', 'public');
            $farm->image()->create(['path' => $path]);
        }

        return back()->with('success', 'Farma bola pridaná.');
    }


    /* ----------- uloženie článku ----------- */
    public function storeArticle(Request $r)
    {
        $data = $r->validate([
            'title' => 'required|string|max:255',
            'text'  => 'required|string',
            'image' => 'nullable|image|max:2048',
        ]);

        // článok bez obrázka
        $article = Article::create([
            'user_id' => $r->user()->id,
            'title'   => $data['title'],
            'text'    => $data['text'],
        ]);

        // ak bol nahraný obrázok, priraď ho cez morphOne
        if ($r->hasFile('image')) {
            $path = $r->file('image')->store('articles', 'public');
            $article->image()->create(['path' => $path]);
        }

        return back()->with('success', 'Článok bol pridaný.');
    }


    /* ----------- uloženie recenzie ----------- */
    public function storeReview(Request $r)
    {
        $data = $r->validate([
            'farm_product_id' => 'required|exists:farm_products,id',
            'rating'          => 'required|integer|between:1,5',
            'content'         => 'required|string',
        ]);

        $data['user_id'] = $r->user()->id;

        Review::create($data);

        return back()->with('success', 'Recenzia bola pridaná.');
    }

    public function replyToReview(Request $r, Review $review)
    {
        // overenie, že ide o farmára danej farmy
        $this->authorize('reply', $review);

        $r->validate([
            'reply' => 'required|string|max:1000',
        ]);

        $review->reply = $r->input('reply');
        $review->save();

        return back()->with('success', 'Odpoveď bola uložená.');
    }


    /* ======================== POMOCNÉ METÓDY ======================== */

    private function validateAddress(Request $request): array
    {
        return $request->validate([
            'street'        => 'required|string|max:255',
            'street_number' => 'required|string|max:32',
            'city'          => 'required|string|max:255',
            'zip_code'           => 'required|string|max:16',
            'country'       => 'required|string|max:255',
        ]);
    }

    private function upsertAddress($user, string $relation, array $data): void
    {
        $typeMap = [
            'billingAddress'  => 'billing',
            'deliveryAddress' => 'delivery',
        ];

        $data['address_type'] = $typeMap[$relation] ?? 'other';

        if ($user->$relation) {
            $user->$relation->update($data);
        } else {
            $address = Address::create($data);

            // nastav foreign key do users
            if ($relation === 'billingAddress') {
                $user->billing_address_id = $address->id;
            } elseif ($relation === 'deliveryAddress') {
                $user->delivery_address_id = $address->id;
            }

            $user->save();
        }
    }
}
