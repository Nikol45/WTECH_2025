<?php

namespace App\Http\Controllers;

use App\Models\Farm;
use App\Models\Address;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FarmController extends Controller
{
    /* ====== LISTING ====== */
    public function index()
    {
        $farms = Farm::where('user_id', Auth::id())->latest()->paginate(12);
        return view('farms.index', compact('farms'));
    }

    /* ====== FORM – CREATE ====== */
    public function create()
    {
        return view('farms.create');
    }

    /* ====== STORE ====== */
    public function store(Request $request)
    {
        $data = $request->validate([
            /* farma */
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'image'       => 'nullable|image|max:2048',

            /* adresa */
            'street'        => 'required|string|max:255',
            'street_number' => 'required|string|max:32',
            'city'          => 'required|string|max:255',
            'zip_code'      => 'required|string|max:16',
            'country'       => 'required|string|max:255',
        ]);

        /* 1️⃣  uložíme adresu */
        $address = Address::create([
            'street'        => $data['street'],
            'street_number' => $data['street_number'],
            'city'          => $data['city'],
            'zip_code'      => $data['zip_code'],
            'country'       => $data['country'],
            'address_type'  => 'farm',
        ]);

        /* 2️⃣  pripravíme dáta pre farmu */
        unset(
            $data['street'], $data['street_number'],
            $data['city'],   $data['zip_code'], $data['country']
        );

        $data['user_id']    = Auth::id();
        $data['address_id'] = $address->id;

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')
                ->store('farms', 'public');
        }

        $farm = Farm::create($data);

        return back()->with('success', "Farma „{$farm->name}“ bola pridaná.");
    }

    /* ====== SHOW ====== */
    public function show(Farm $farm)
    {
        return view('farms.show', compact('farm'));
    }

    /* ====== FORM – EDIT ====== */
    public function edit(Farm $farm)
    {
        $this->authorize('update', $farm);
        return view('farms.edit', compact('farm'));
    }

    /* ====== UPDATE ====== */
    public function update(Request $request, Farm $farm)
    {
        $this->authorize('update', $farm);

        $data = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'image'       => 'nullable|image|max:2048',

            'street'        => 'required|string|max:255',
            'street_number' => 'required|string|max:32',
            'city'          => 'required|string|max:255',
            'zip_code'      => 'required|string|max:16',
            'country'       => 'required|string|max:255',
        ]);

        /* adresa – aktualizuj */
        $farm->address->update([
            'street'        => $data['street'],
            'street_number' => $data['street_number'],
            'city'          => $data['city'],
            'zip_code'      => $data['zip_code'],
            'country'       => $data['country'],
        ]);

        unset(
            $data['street'], $data['street_number'],
            $data['city'],   $data['zip_code'], $data['country']
        );

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')
                ->store('farms', 'public');
        }

        $farm->update($data);

        return back()->with('success', "Farma „{$farm->name}“ bola upravená.");
    }

    /* ====== DESTROY ====== */
    public function destroy(Farm $farm)
    {
        if ($farm->user_id !== auth()->id()) {
            abort(403);
        }
        // ak chceš, môžeš zmazať aj adresu / obrázok
        $farm->delete();

        return back()->with('success', 'Farma bola vymazaná.');
    }
}
