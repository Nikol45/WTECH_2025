@extends('layout.app')

@section('title', 'Údaje')

@section('content')
    <main class="container-fluid custom-fluid py-4">

        {{-- ---------- KROKY KOŠÍKA (tabs) ---------- --}}
        @include('layout.partials.cart_steps', ['active' => 'form'])

        {{-- ---------- SPÄŤ DO KOŠÍKA ---------- --}}
        <a href="{{ route('cart-items.index') }}"
           class="d-inline-flex align-items-center mb-4 text-decoration-none text-dark">
            <span class="me-1">←</span> Späť do košíka
        </a>

        {{-- ---------- FORMULÁR S ÚDAJMİ ---------- --}}
        <form
            method="POST"
            action="{{ route('cart-form.store') }}"
            x-data="{
            diffAddress: {{ old('differentAddress') ? 'true' : 'false' }},
            company:     {{ old('companyCheck')    ? 'true' : 'false' }}
        }"
        >
            @csrf

            <div class="row g-4">
                {{-- Ľavý stĺpec ---------------------------------------------------- --}}
                <div class="col-md-8">

                    {{-- === Základné údaje === --}}
                    <h5 class="fw-bold mb-3">Základné údaje</h5>

                    <x-form.group name="first_name" label="Meno *" :value="old('first_name')" />
                    <x-form.group name="last_name"  label="Priezvisko *" :value="old('last_name')" />

                    <div class="row g-3 mb-3">
                        <div class="col-sm-6">
                            <x-form.group type="email" name="email" label="Email *" :value="old('email')" />
                        </div>

                        {{-- Telefón s prefixom --}}
                        <div class="col-sm-6">
                            <label class="form-label">Telefónne číslo *</label>
                            <div class="row g-2">
                                <div class="col-4">
                                    <select name="phone_prefix" class="form-select">
                                        @foreach(['+421', '+420', '+48'] as $pfx)
                                            <option value="{{ $pfx }}" {{ old('phone_prefix')===$pfx ? 'selected' : '' }}>
                                                {{ $pfx }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-8">
                                    <x-form.input name="phone" type="tel" :value="old('phone')" />
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- === Fakturačná adresa === --}}
                    <h5 class="fw-bold mb-3 mt-5">Fakturačná adresa</h5>

                    <div class="row g-3 mb-3">
                        <div class="col-8">
                            <x-form.group name="street" label="Ulica *" :value="old('street')" />
                        </div>
                        <div class="col-4">
                            <x-form.group name="street_no" label="Číslo domu *" :value="old('street_no')" />
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-sm-6 col-8">
                            <x-form.group name="city" label="Mesto *" :value="old('city')" />
                        </div>
                        <div class="col-sm-2 col-4">
                            <x-form.group name="zip" label="PSČ *" :value="old('zip')" />
                        </div>
                        <div class="col-sm-4">
                            <x-form.group name="country" label="Krajina *" :value="old('country','Slovensko')" />
                        </div>
                    </div>

                    {{-- === Doručiť na inú adresu? === --}}
                    <div class="form-check mb-4">
                        <input class="form-check-input"
                               type="checkbox"
                               id="differentAddress"
                               name="differentAddress"
                               x-model="diffAddress">
                        <label class="form-check-label" for="differentAddress">
                            Doručiť na inú adresu
                        </label>
                    </div>

                    {{-- === Dodacia adresa (skrytá podľa checkboxu) === --}}
                    <div x-show="diffAddress" id="deliveryAddressSection" x-cloak>
                        <h5 class="fw-bold mb-3 mt-5">Dodacia adresa</h5>

                        <div class="row g-3 mb-3">
                            <div class="col-8">
                                <x-form.group name="delivery_street" label="Ulica *" :value="old('delivery_street')" />
                            </div>
                            <div class="col-4">
                                <x-form.group name="delivery_street_no" label="Číslo domu *" :value="old('delivery_street_no')" />
                            </div>
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-sm-6 col-8">
                                <x-form.group name="delivery_city" label="Mesto *" :value="old('delivery_city')" />
                            </div>
                            <div class="col-sm-2 col-4">
                                <x-form.group name="delivery_zip" label="PSČ *" :value="old('delivery_zip')" />
                            </div>
                            <div class="col-sm-4">
                                <x-form.group name="delivery_country" label="Krajina *" :value="old('delivery_country','Slovensko')" />
                            </div>
                        </div>
                    </div>

                    {{-- === Nákup na firmu? === --}}
                    <div class="form-check mb-4">
                        <input class="form-check-input"
                               type="checkbox"
                               id="companyCheck"
                               name="companyCheck"
                               x-model="company">
                        <label class="form-check-label" for="companyCheck">
                            Nákup na firmu
                        </label>
                    </div>

                    {{-- === Sekcia Firma === --}}
                    <div x-show="company" id="companySection" x-cloak>
                        <h5 class="fw-bold mb-3">Firma</h5>
                        <x-form.group name="company_name" label="Názov spoločnosti *" :value="old('company_name')" />

                        <div class="row g-3 mb-3">
                            <div class="col-sm-6">
                                <x-form.group name="ico" label="IČO *" :value="old('ico')" />
                            </div>
                            <div class="col-sm-6">
                                <x-form.group name="vat" label="IČ DPH" :value="old('vat')" />
                            </div>
                        </div>
                    </div>

                </div>

                {{-- Pravý stĺpec -------------------------------------------------- --}}
                <div class="col-md-4">
                    <div class="mb-5">
                        <label for="courierNote" class="form-label fw-bold">Poznámka pre kuriéra</label>
                        <textarea class="form-control" id="courierNote" name="note" rows="10">{{ old('note') }}</textarea>
                    </div>
                </div>
            </div>

            {{-- ---------- TLAČIDLO SUBMIT ---------- --}}
            <div class="text-center text-md-end">
                <button type="submit" class="btn btn-primary px-4 py-2">
                    Prejsť k doprave a platbe
                </button>
            </div>
        </form>
    </main>
@endsection
