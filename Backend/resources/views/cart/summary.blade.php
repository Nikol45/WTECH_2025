@extends('layout.app')

@section('title', 'Sumarizácia objednávky')

@section('content')
    {{-- ---------- KROKY KOŠÍKA (tabs) ---------- --}}
    @include('layout.partials.cart_steps', ['active' => 'summary'])

    {{-- ---------- SPÄŤ NA DORUČENIE A PLATBU ---------- --}}
    <a href="{{ route('cart-delivery.index') }}"
       class="d-inline-flex align-items-center mb-4 text-decoration-none text-dark">
        <span class="me-1">←</span> Späť na dopravu a platbu
    </a>

    <div class="row g-4">
        {{-- Ľavý stĺpec: col-12 na mobiloch, col-md-8 na desktopoch --}}
        <div class="col-12 col-md-6">
            <div class="p-3 border-0 shadow-sm">
                <h5 class="fw-bold mb-3">Osobné údaje</h5>

                <div class="ms-2 mb-3">
                    <p class="mb-1"><strong>Meno:</strong> {{ $customer['name'] }}</p>
                    <p class="mb-1"><strong>Email:</strong> {{ $customer['email'] }}</p>
                    <p class="mb-1"><strong>Telefónne číslo:</strong> {{ $customer['phone'] }}</p>
                </div>
                <hr class="my-3"/>

                {{-- Fakturačná adresa --}}
                <h6 class="fw-bold ms-2">Fakturačná adresa</h6>
                <div class="ms-2 mb-3">
                    <p class="mb-1">{{ $billing['street'] ?? '' }}</p>
                    <p class="mb-1">{{ $billing['zip'] ?? '' }} {{ $billing['city'] ?? '' }}, {{ $billing['country'] ?? '' }}</p>
                </div>
                <hr class="my-3"/>

                {{-- Dodacia adresa (ak iná) --}}
                @isset($deliveryAddress)
                    <h6 class="fw-bold ms-2">Dodacia adresa</h6>
                    <div class="ms-2 mb-3">
                        <p class="mb-1">{{ $deliveryAddress['street'] ?? '' }}</p>
                        <p class="mb-1">{{ $deliveryAddress['zip'] ?? '' }} {{ $deliveryAddress['city'] ?? '' }}, {{ $deliveryAddress['country'] ?? '' }}</p>
                    </div>
                    <hr class="my-3"/>
                @endisset

                {{-- Firma (ak nákup na firmu) --}}
                @isset($company)
                    <h6 class="fw-bold ms-2">Nákup na firmu</h6>
                    <div class="ms-2 mb-3">
                        <p class="mb-1">IČO: {{ $company['ico'] }}</p>
                        <p class="mb-1">Názov spoločnosti: {{ $company['name'] }}</p>
                        @isset($company['vat'])
                            <p class="mb-1">IČ DPH: {{ $company['vat'] }}</p>
                        @endisset
                    </div>
                    <hr class="my-3"/>
                @endisset

                {{-- Poznámka pre kuriéra --}}
                @isset($note)
                    <h6 class="fw-bold ms-2">Poznámka pre kuriéra</h6>
                    <div class="ms-2 mb-3">
                        <p class="mb-1">{{ $note }}</p>
                    </div>
                @endisset

                <a href="{{ route('cart-form.index') }}" class="ms-2 text-primary">Upraviť údaje</a>
            </div>
        </div>

        {{-- Pravý stĺpec: col-12 na mobiloch, col-md-4 na desktopoch --}}
        <div class="col-12 col-md-6">
            <div class="border-0 shadow-sm">
                <h5 class="fw-bold mb-3">Sumarizácia objednávky</h5>

                @foreach($farms as $farm)
                    <div class="p-3 bg-light mb-3 rounded-2">
                        <strong>{{ $farm['name'] }}</strong>

                        @foreach($farm['items'] as $item)
                            <p class="mb-1">
                                {{ $item['quantity'] }} × {{ $item['label'] }}
                                <span class="float-end">{{ $item['price'] }}</span>
                            </p>
                        @endforeach

                        <p class="mb-0 mt-3 text-muted">
                            <em>Doprava: {{ $farm['shipping'] }}</em>
                            <span class="float-end">{{ $farm['shipping_price'] }}</span>
                        </p>
                    </div>
                @endforeach

                {{-- Súhrn cien --}}
                <div class="mt-3">
                    <p class="mb-1">Cena bez DPH: <strong>{{ $summary['without_vat'] }}</strong></p>
                    <p class="mb-1">Cena dopravy: <strong>{{ $summary['shipping'] }}</strong></p>
                    @if($isCod)
                        <p class="mb-1">Cena za dobierku:<strong>{{ number_format(\App\Http\Controllers\CartDeliveryController::COD_PRICE, 2, ',', ' ') }} €</strong></p>
                    @endif
                    <p>Spôsob platby: Online / bez dobierky</p>

                    <h5 class="fw-bold mt-3">Celkom na úhradu: {{ $summary['total'] }}</h5>
                </div>

                <form method="POST" action="{{ route('cart-summary.store') }}">
                    @csrf
                    <button class="btn btn-primary w-100 mt-3">
                        Objednať s povinnosťou platby
                    </button>
                </form>

                <p class="text-muted small mt-2 mb-0">
                    Dokončením objednávky súhlasíte s
                    <a href="{{ route('terms') }}" class="text-muted text-decoration-underline">
                        obchodnými podmienkami
                    </a>.
                </p>
            </div>
        </div>
    </div>
@endsection
