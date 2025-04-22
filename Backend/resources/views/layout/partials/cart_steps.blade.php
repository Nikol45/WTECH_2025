@php
    $steps = [
        'items' => 'Košík',
        'form' => 'Údaje',
        'delivery' => 'Doprava a platba',
        'summary' => 'Sumarizácia',
    ];
@endphp

<ul class="nav nav-tabs mb-4 d-none">
    @foreach ($steps as $key => $label)
        <li class="nav-item">
            <a class="nav-link {{ $active === $key ? 'active' : 'text-dark' }}"
               href="{{ route('cart-' . $key . '.index') }}"
               aria-current="{{ $active === $key ? 'page' : null }}">
                {{ $label }}
            </a>
        </li>
    @endforeach
</ul>
