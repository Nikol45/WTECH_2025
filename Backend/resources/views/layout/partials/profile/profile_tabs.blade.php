@php
    $tabs = [
        'index'      => 'Osobné údaje',
        'history'    => 'História objednávok',
        'favourites' => 'Obľúbené produkty',
        'reviews'    => 'Recenzie',
    ];
@endphp

{{-- Pre väčšie obrazovky --}}
<ul class="nav nav-tabs d-none d-md-flex mb-4">
    @foreach ($tabs as $key => $label)
        <li class="nav-item">
            <a class="nav-link {{ $active === $key ? 'active' : 'text-dark' }}"
               href="{{ route('profile.' . $key) }}"
               aria-current="{{ $active === $key ? 'page' : '' }}">
                {{ $label }}
            </a>
        </li>
    @endforeach
</ul>

{{-- Pre mobily --}}
<div class="dropdown-nav d-md-none mt-3">
    <div class="dropdown">
        <button class="btn btn-light w-100 text-start dropdown-toggle" type="button"
                data-bs-toggle="dropdown" aria-expanded="false">
            {{ $tabs[$active] ?? 'Osobné údaje' }}
        </button>
        <ul class="dropdown-menu w-100">
            @foreach ($tabs as $key => $label)
                <li>
                    <a class="dropdown-item {{ $active === $key ? 'active' : '' }}"
                       href="{{ route('profile.' . $key) }}">
                        {{ $label }}
                    </a>
                </li>
            @endforeach
        </ul>
    </div>
</div>
