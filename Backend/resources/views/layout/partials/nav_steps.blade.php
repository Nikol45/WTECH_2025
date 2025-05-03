@php
    $context = $context   ?? 'profile'; // cart / profile
    $active  = $active    ?? '';

    $map = [
        'cart' => [
            'items'    => 'Košík',
            'form'     => 'Údaje',
            'delivery' => 'Doprava a platba',
            'summary'  => 'Sumarizácia',
        ],
        'profile' => [
            'index'      => 'Osobné údaje',
            'history'    => 'História objednávok',
            'favourites' => 'Obľúbené produkty',
            'reviews'    => 'Recenzie',
        ],
    ];

    $items = $map[$context] ?? [];
@endphp


{{-- -------- veľké obrazovky: horizontálne tabuľky -------- --}}
<ul class="nav nav-tabs mb-4 d-none d-md-flex">
    @foreach ($items as $key => $label)
        <li class="nav-item">
            <a class="nav-link {{ $active === $key ? 'active' : 'text-dark' }}"
               href="{{ $context === 'cart'
                       ? route('cart-' . $key . '.index')
                       : route('profile.' . $key) }}"
               aria-current="{{ $active === $key ? 'page' : '' }}">
                {{ $label }}
            </a>
        </li>
    @endforeach
</ul>

{{-- -------- mobily: dropdown -------- --}}
<div class="dropdown-nav d-md-none mt-3">
    <div class="dropdown w-100">
        <button class="btn btn-light w-100 text-start dropdown-toggle"
                data-bs-toggle="dropdown" aria-expanded="false">
            {{ $items[$active] ?? Arr::first($items) }}
        </button>

        <ul class="dropdown-menu w-100">
            @foreach ($items as $key => $label)
                <li>
                    <a class="dropdown-item {{ $active === $key ? 'active' : '' }}"
                       href="{{ $context === 'cart'
                               ? route('cart-' . $key . '.index')
                               : route('profile.' . $key) }}">
                        {{ $label }}
                    </a>
                </li>
            @endforeach
        </ul>
    </div>
</div>
