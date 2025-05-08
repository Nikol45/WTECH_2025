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

    }

    public function create()
    {
    }

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

        /* uložíme adresu */
        $address = Address::create([
            'street'        => $data['street'],
            'street_number' => $data['street_number'],
            'city'          => $data['city'],
            'zip_code'      => $data['zip_code'],
            'country'       => $data['country'],
            'address_type'  => 'farm',
        ]);

        /* pripravíme dáta pre farmu */
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

    public function show(Farm $farm)
    {
    }

    public function edit(Farm $farm)
    {
    }

    public function update(Request $request, Farm $farm)
    {
        if ($farm->user_id !== auth()->id()) {
            abort(403);
        }

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

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => "Farma „{$farm->name}“ bola upravená."]);
        }

        return back()->with('success', "Farma „{$farm->name}“ bola upravená.");
    }

    public function destroy(Farm $farm)
    {
        if ($farm->user_id !== auth()->id()) {
            abort(403);
        }
        $farm->delete();
        return back()->with('success', 'Farma bola vymazaná.');
    }
}
