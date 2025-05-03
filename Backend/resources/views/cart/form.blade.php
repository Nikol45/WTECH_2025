@extends('layout.app')

@section('title', 'Údaje')

@section('content')
    {{-- Tabs --}}
    @include('layout.partials.nav_steps', [
        'context' => 'cart',
        'active'  => 'form'
    ])
    {{-- Späť do košíka --}}
    <a href="{{ route('cart-items.index') }}" class="d-inline-flex align-items-center mb-4 text-decoration-none text-dark">
        <span class="me-1">←</span> Späť do košíka
    </a>

    {{-- Formulár --}}
    <form method="POST" action="{{ route('cart-form.store') }}">
        @csrf

        {{-- Chyby --}}
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="row g-4">
            {{-- Ľavý stĺpec --}}
            <div class="col-md-8">

                {{-- Základné údaje --}}
                <h5 class="fw-bold mb-3">Základné údaje</h5>
                <div class="mb-3">
                    <label for="first_name" class="form-label">Meno *</label>
                    <input type="text" class="form-control" name="first_name" id="first_name" value="{{ old('first_name', $data['first_name'] ?? '') }}" required>
                </div>
                <div class="mb-3">
                    <label for="last_name" class="form-label">Priezvisko *</label>
                    <input type="text" class="form-control" name="last_name" id="last_name" value="{{ old('last_name', $data['last_name'] ?? '') }}" required>
                </div>
                <div class="row g-3 mb-3">
                    <div class="col-sm-6">
                        <label for="email" class="form-label">Email *</label>
                        <input type="email" class="form-control" name="email" id="email" value="{{ old('email', $data['email'] ?? '') }}" required>
                    </div>
                    <div class="col-sm-6">
                        <label class="form-label">Telefónne číslo *</label>
                        <input type="tel" class="form-control" name="phone" id="phone" value="{{ old('phone', $data['phone'] ?? '') }}" required>
                    </div>
                </div>

                {{-- Fakturačná adresa --}}
                <h5 class="fw-bold mb-3 mt-5">Fakturačná adresa</h5>
                <div class="row g-3 mb-3">
                    <div class="col-8">
                        <label for="bill_street" class="form-label">Ulica *</label>
                        <input type="text" class="form-control" name="bill_street" id="bill_street" value="{{ old('bill_street', $data['billing_address']['street'] ?? '') }}" required>
                    </div>
                    <div class="col-4">
                        <label for="bill_street_number" class="form-label"><span class="label-text"></span></label>
                        <input type="text" class="form-control" name="bill_street_number" id="bill_street_number" value="{{ old('bill_street_number', $data['billing_address']['street_number'] ?? '') }}" required>
                    </div>
                </div>
                <div class="row g-3 mb-3">
                    <div class="col-sm-6 col-8">
                        <label for="bill_city" class="form-label">Mesto *</label>
                        <input type="text" class="form-control" name="bill_city" id="bill_city" value="{{ old('bill_city', $data['billing_address']['city'] ?? '') }}" required>
                    </div>
                    <div class="col-sm-2 col-4">
                        <label for="bill_zip" class="form-label">PSČ *</label>
                        <input type="text" class="form-control" name="bill_zip" id="bill_zip" value="{{ old('bill_zip', $data['billing_address']['zip'] ?? '') }}" required>
                    </div>
                    <div class="col-sm-4">
                        <label for="bill_country" class="form-label">Krajina *</label>
                        <input type="text" class="form-control" name="bill_country" id="bill_country" value="{{ old('bill_country', $data['billing_address']['country'] ?? '') }}" required>
                    </div>
                </div>

                {{-- Checkbox – doručiť na inú adresu --}}
                <div class="form-check mb-4">
                    <input class="form-check-input" type="checkbox" value="1" id="different_address" name="different_address" {{ old('different_address') ? 'checked' : '' }}>
                    <label class="form-check-label" for="different_address">Doručiť na inú adresu</label>
                </div>

                {{-- Dodacia adresa --}}
                <div id="deliveryAddressSection" class="{{ old('different_address') ? '' : 'd-none' }}">
                    <h5 class="fw-bold mb-3 mt-5">Dodacia adresa</h5>
                    <div class="row g-3 mb-3">
                        <div class="col-8">
                            <label for="del_street" class="form-label">Ulica *</label>
                            <input type="text" class="form-control" name="del_street" id="del_street"
                                   value="{{ old('del_street', $data['delivery_address']['street'] ?? '') }}"
                                {{ old('different_address') ? 'required' : '' }}>
                        </div>
                        <div class="col-4">
                            <label for="del_street_number" class="form-label">Č. domu *</label>
                            <input type="text" class="form-control" name="del_street_number" id="del_street_number"
                                   value="{{ old('del_street_number', $data['delivery_address']['street_number'] ?? '') }}"
                                {{ old('different_address') ? 'required' : '' }}>
                        </div>
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col-sm-6 col-8">
                            <label for="del_city" class="form-label">Mesto *</label>
                            <input type="text" class="form-control" name="del_city" id="del_city"
                                   value="{{ old('del_city', $data['delivery_address']['city'] ?? '') }}"
                                {{ old('different_address') ? 'required' : '' }}>
                        </div>
                        <div class="col-sm-2 col-4">
                            <label for="del_zip" class="form-label">PSČ *</label>
                            <input type="text" class="form-control" name="del_zip" id="del_zip"
                                   value="{{ old('del_zip', $data['delivery_address']['zip'] ?? '') }}"
                                {{ old('different_address') ? 'required' : '' }}>
                        </div>
                        <div class="col-sm-4">
                            <label for="del_country" class="form-label">Krajina *</label>
                            <input type="text" class="form-control" name="del_country" id="del_country"
                                   value="{{ old('del_country', $data['delivery_address']['country'] ?? '') }}"
                                {{ old('different_address') ? 'required' : '' }}>
                        </div>
                    </div>
                </div>

                {{-- Checkbox – nákup na firmu --}}
                <div class="form-check mb-4">
                    <input class="form-check-input" type="checkbox" value="1" id="companyCheck" name="company_check" {{ old('company_check') ? 'checked' : '' }}>
                    <label class="form-check-label" for="companyCheck">Nákup na firmu</label>
                </div>

                {{-- Firemná sekcia --}}
                <div id="companySection" class="{{ old('company_check') ? '' : 'd-none' }}">
                    <h5 class="fw-bold mb-3 mt-5">Firma</h5>
                    <div class="mb-3">
                        <label for="company_name" class="form-label">Názov spoločnosti *</label>
                        <input type="text" class="form-control" name="company_name" id="company_name"
                               value="{{ old('company_name', $data['company']['name'] ?? '') }}"
                            {{ old('company_check') ? 'required' : '' }}>
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col-sm-6">
                            <label for="ico" class="form-label">IČO *</label>
                            <input type="text" class="form-control" name="ico" id="ico"
                                   value="{{ old('ico', $data['company']['ico'] ?? '') }}"
                                {{ old('company_check') ? 'required' : '' }}>
                        </div>
                        <div class="col-sm-6">
                            <label for="vat" class="form-label">IČ DPH</label>
                            <input type="text" class="form-control" name="vat" id="vat"
                                   value="{{ old('vat', $data['company']['vat'] ?? '') }}">
                        </div>
                    </div>
                </div>

            </div>

            {{-- Pravý stĺpec --}}
            <div class="col-md-4">
                <div class="mb-5">
                    <label for="note" class="form-label fw-bold">Poznámka pre kuriéra</label>
                    <textarea class="form-control" name="note" id="note" rows="10">{{ old('note', $data['note'] ?? '') }}</textarea>
                </div>
            </div>
        </div>

        {{-- Tlačidlo --}}
        <div class="text-center text-md-end">
            <button type="submit" class="btn btn-primary px-4 py-2">
                Prejsť k doprave a platbe
            </button>
        </div>
    </form>
@endsection
