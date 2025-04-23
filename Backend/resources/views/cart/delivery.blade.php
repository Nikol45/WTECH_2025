@extends('layout.app')

@section('title', 'Doprava a platba')

@section('content')
    <main class="container-fluid custom-fluid py-4">
        {{-- ---------- KROKY KOŠÍKA (tabs) ---------- --}}
        @include('layout.partials.cart_steps', ['active' => 'delivery'])

        {{-- ---------- SPÄŤ LINK ---------- --}}
        <a href="{{ route('cart-form.index') }}" class="d-inline-flex align-items-center mb-4 text-decoration-none text-dark">
            <span class="me-1">←</span> Späť na údaje
        </a>

        <h2 class="fw-bold mb-4">Vyberte spôsob dopravy</h2>

        {{-- ---------- ZÁSIELKY Z FARIEM ---------- --}}
        @foreach ($packages as $package)
            <div class="package-products border rounded-3 p-3 pt-4 mb-4">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h5 class="fw-bold mb-0">Zásielka z farmy {{ $package['farm_name'] }}</h5>
                    @if($package['only_personal'] ?? false)<div class="text-danger small">Doručenie nie je možné, len osobný odber.</div>@endif
                </div>

                {{-- Karusel produktov --}}
                <div class="arrows-around position-relative mt-4">
                    <div class="arrows">
                        <button class="btn custom-button arrow-left" type="button" id="arrowLeft{{ $loop->iteration }}">
                            <span class="material-icons">chevron_left</span>
                        </button>
                        <button class="btn custom-button arrow-right" type="button" id="arrowRight{{ $loop->iteration }}">
                            <span class="material-icons">chevron_right</span>
                        </button>
                    </div>

                    <div class="products-carousel-container px-4" id="carouselContainer{{ $loop->iteration }}">
                        <div class="no-scrollbar row row-cols-sm-2 row-cols-md-3 row-cols-lg-4 row-cols-xl-5 g-3 mb-3 flex-nowrap"
                             id="carouselRow{{ $loop->iteration }}">

                            @foreach ($package['products'] as $product)
                                <div class="col flex-shrink-0">
                                    <div class="card text-start p-3">
                                        <img src="{{ asset($product['image']) }}" alt="{{ $product['name'] }}" class="img-half-cover">
                                        <div class="card-body mt-3">
                                            <h5 class="card-title truncate-ellipsis px-2">{{ $product['name'] }}</h5>
                                            <div class="d-flex align-items-center justify-content-between px-2 mb-2">
                                                <span class="price fw-bold">{{ $product['price'] }}</span>
                                                <span class="counter">{{ $product['quantity'] }}ks</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach

                        </div>
                    </div>
                </div>

                <h5 class="fw-bold mb-0 my-2 mx-3 text-end">Dokopy: {{ $package['total_price'] }}</h5>
            </div>
        @endforeach

        {{-- ---------- MEDZISÚČET BEZ DOPRAVY ---------- --}}
        <h5 class="mt-3 text-center">
            Cena bez dopravy:
            <span class="fw-bold">{{ $totalWithoutDelivery }}</span>
        </h5>
        <hr class="mt-4" />

{{--        <div id="delivery-prices"--}}
{{--             data-gls-standard="{{ App\Http\Controllers\CartDeliveryController::deliveryPrices()['GLS_STANDARD'] }}"--}}
{{--             data-gls-express="{{ App\Http\Controllers\CartDeliveryController::deliveryPrices()['GLS_EXPRESS'] }}"--}}
{{--             data-personal="{{ App\Http\Controllers\CartDeliveryController::deliveryPrices()['PERSONAL'] }}"--}}
{{--             data-cod="{{ App\Http\Controllers\CartDeliveryController::deliveryPrices()['COD'] }}"--}}
{{--             data-total="{{ $totalWithoutDelivery }}">--}}
{{--        </div>--}}

        <div id="delivery-prices"
             @foreach ($deliveryMethods as $key => $label)
                 data-price-{{ $key }}="{{ number_format($deliveryMethods[$key]['price'], 2, ',', ' ') }}"
                data-eta-{{ $key }}="{{ $deliveryMethods[$key]['eta'] }}"
             @endforeach
        ></div>


        {{-- ---------- FORMULÁR – DOPRAVA + PLATBA ---------- --}}
        <form method="POST" action="{{ route('cart-delivery.store') }}">
            @csrf

            {{-- === Doprava === --}}
            <h5 class="fw-bold mb-3">Vyberte spôsob dopravy</h5>
            @foreach($deliveryMethods as $value => $label)
                <div class="form-check mb-2">
                    <input class="form-check-input"
                           type="radio"
                           name="deliveryMethod"
                           id="delivery_{{ $value }}"
                           value="{{ $value }}"
                    {{ (old('deliveryMethod') ?? $selectedDelivery) === $value ? 'checked' : '' }}

                    <label class="form-check-label" for="delivery_{{ $value }}">{{ $label['label'] }}</label>
                </div>
            @endforeach

            {{-- Info o doprave --}}
            <div id="deliveryRow" class="border p-3 mb-4 d-none">
                <p class="mb-1">Cena dopravy: <strong class="delivery-price"></strong></p>
                <p class="text-muted my-2">Predpokladaný dátum doručenia: <span class="delivery-eta"></span></p>
            </div>

            {{-- === Platba === --}}
            <h5 class="fw-bold my-3">Vyberte spôsob platby</h5>
            @foreach($paymentMethods as $value => $label)
                <div id="wrapper_payment_{{ $value }}"
                class="form-check mb-2 {{ in_array($value, ['cash_on_delivery', 'cash_on_pickup']) ? 'd-none' : '' }}">
                    <input class="form-check-input"
                           type="radio"
                           name="paymentMethod"
                           id="payment_{{ $value }}"
                           value="{{ $value }}"
                        {{ old('paymentMethod', $selectedPayment) === $value ? 'checked' : '' }}>
                    <label class="form-check-label" for="payment_{{ $value }}">{{ $label }}</label>
                </div>
            @endforeach

            {{-- Rekapitulácia ceny --}}
            <div id="priceRow" class="border p-3 my-3 {{ $showPriceRow ? '' : 'd-none' }}">
                <p class="mb-1">Cena tovaru: <strong>{{ $totalWithoutDelivery }}</strong></p>
                <p class="mb-1 {{ !$deliveryPrice ? 'd-none' : '' }}" id="price_deliveryRow">
                    Cena dopravy: <strong class="delivery-price"></strong>
                    <span class="text-warning mx-3 {{ !$showDeliveryWarning ? 'd-none' : '' }}" id="delivery_warningRow">
                        Pozor! v košíku sú aj zásielky len na osobný odber.
                    </span>
                </p>

                <p class="mb-1 {{ $selectedPayment !== 'cash_on_delivery' ? 'd-none' : '' }}" id="price_dobierkaRow">
                    Cena za dobierku:
                    <strong class="dobierka-price">{{ number_format(\App\Http\Controllers\CartDeliveryController::COD_PRICE, 2, ',', ' ') }} €</strong>
                </p>
                <p class="mb-0">Celkom: <strong id="grand-total" data-base="{{ floatval(str_replace(',', '.', $totalWithoutDelivery)) }}">{{ $totalWithoutDelivery }}</strong> €</p>
            </div>

            {{-- Tlačidlo: pokračovať --}}
            <div class="text-center text-md-end">
                <button type="submit" class="btn btn-primary px-4 py-2 mb-5">
                    Prejsť na sumarizáciu
                </button>
            </div>
        </form>
    </main>
@endsection
