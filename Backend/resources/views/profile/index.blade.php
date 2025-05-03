@php
    $view = $view ?? 'customer';
@endphp

@extends('layout.app')

@section('title', 'Môj profil')

@section('content')

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @elseif(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @elseif($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Tabs --}}
    @include('layout.partials.nav_steps', [
        'context' => 'profile',
        'active'  => 'index'
    ])

{{-- Späť do obchodu --}}
<a href="{{ route('homepage') }}" class="d-inline-flex align-items-center text-decoration-none text-dark">
    <span class="me-2">←</span> Späť do obchodu
</a>

@if($view === 'admin')
    <h2 class="fw-bold my-4">Môj profil</h2>

    {{-- Osobné údaje --}}
    <div class="row mb-1 g-4">
        <div class="col-md-4">
            <p class="fw-bold mb-1">Meno:</p>
            <p class="mb-1">{{ $user->name }}</p>
            <a href="#" class="text-primary" onclick="openEditModal({
                title: 'Zmeniť meno',
                submitUrl: '{{ route('profile.update.name') }}',
                fields: [
                    { label: 'Meno', name: 'name', type: 'text', value: '{{ $user->name }}' }
                ]
            })">Zmeniť meno</a>
        </div>
        <div class="col-md-4">
            <p class="fw-bold mb-1">Email:</p>
            <p class="mb-1">{{ $user->email }}</p>
            <a href="#" class="text-primary" onclick="openEditModal({
                title: 'Zmeniť email',
                submitUrl: '{{ route('profile.update.email') }}',
                fields: [
                    { label: 'Email', name: 'email', type: 'email', value: '{{ $user->email }}' }
                ]
            })">Zmeniť email</a>
        </div>
        <div class="col-md-4">
            <p class="fw-bold mb-1">Telefónne číslo:</p>
            <p class="mb-1">{{ $user->phone_number }}</p>
            <a href="#" class="text-primary" onclick="openEditModal({
                title: 'Zmeniť číslo',
                submitUrl: '{{ route('profile.update.phone') }}',
                fields: [
                    { label: 'Telefónne číslo', name: 'phone_number', type: 'text', value: '{{ $user->phone_number }}' }
                ]
            })">Zmeniť číslo</a>
        </div>
    </div>

    <div class="row align-items-stretch">

        <div class="col-md-3 mt-3">
            {{-- Fakturačná adresa --}}
            <div class="mt-5">
                <h5 class="fw-bold mb-3">Fakturačná adresa</h5>
                <p class="mb-1">Ulica: {{ $user->billingAddress->street ?? '' }}</p>
                <p class="mb-1">Číslo domu: {{ $user->billingAddress->street_number ?? '' }}</p>
                <p class="mb-1">Mesto: {{ $user->billingAddress->city ?? '' }}</p>
                <p class="mb-1">PSČ: {{ $user->billingAddress->zip_code ?? '' }}</p>
                <p class="mb-2">Krajina: {{ $user->billingAddress->country ?? '' }}</p>
                <a href="#" class="text-primary" onclick="openEditModal({
                    title: 'Upraviť fakturačnú adresu',
                    submitUrl: '{{ route('profile.update.billing') }}',
                    fields: [
                        { label: 'Ulica', name: 'street', value: '{{ $user->billingAddress->street ?? '' }}' },
                        { label: 'Číslo domu', name: 'street_number', value: '{{ $user->billingAddress->street_number ?? '' }}' },
                        { label: 'Mesto', name: 'city', value: '{{ $user->billingAddress->city ?? '' }}' },
                        { label: 'PSČ', name: 'zip_code', value: '{{ $user->billingAddress->zip_code ?? '' }}' },
                        { label: 'Krajina', name: 'country', value: '{{ $user->billingAddress->country ?? '' }}' }
                    ]
                })">Zmeniť údaje</a>
            </div>

            {{-- Dodacia adresa --}}
            <div class="mt-5">
                <h5 class="fw-bold mb-3">Dodacia adresa</h5>
                <p class="mb-1">Ulica: {{ $user->deliveryAddress->street ?? '' }}</p>
                <p class="mb-1">Číslo domu: {{ $user->deliveryAddress->street_number ?? '' }}</p>
                <p class="mb-1">Mesto: {{ $user->deliveryAddress->city ?? '' }}</p>
                <p class="mb-1">PSČ: {{ $user->deliveryAddress->zip_code ?? '' }}</p>
                <p class="mb-2">Krajina: {{ $user->deliveryAddress->country ?? '' }}</p>
                <a href="#" class="text-primary" onclick="openEditModal({
                    title: 'Upraviť dodaciu adresu',
                    submitUrl: '{{ route('profile.update.delivery') }}',
                    fields: [
                        { label: 'Ulica', name: 'street', value: '{{ $user->deliveryAddress->street ?? '' }}' },
                        { label: 'Číslo domu', name: 'street_number', value: '{{ $user->deliveryAddress->street_number ?? '' }}' },
                        { label: 'Mesto', name: 'city', value: '{{ $user->deliveryAddress->city ?? '' }}' },
                        { label: 'PSČ', name: 'zip_code', value: '{{ $user->deliveryAddress->zip_code ?? '' }}' },
                        { label: 'Krajina', name: 'country', value: '{{ $user->deliveryAddress->country ?? '' }}' }
                    ]
                })">Zmeniť údaje</a>
            </div>

            {{-- Firemné údaje --}}
            <div class="mt-5">
                <h5 class="fw-bold mb-3">Nákup na firmu</h5>
                <p class="mb-1">Názov spoločnosti: {{ $user->company->name ?? '' }}</p>
                <p class="mb-1">IČO: {{ $user->company->ico ?? '' }}</p>
                <p class="mb-2">IČ DPH: {{ $user->company->vat ?? '' }}</p>
                <a href="#" class="text-primary" onclick="openEditModal({
                    title: 'Firemné údaje',
                    submitUrl: '{{ route('profile.update.company') }}',
                    fields: [
                        { label: 'Názov spoločnosti', name: 'name', value: '{{ $user->company->name ?? '' }}' },
                        { label: 'IČO', name: 'ico', value: '{{ $user->company->ico ?? '' }}' },
                        { label: 'IČ DPH', name: 'ic_dph', value: '{{ $user->company->vat ?? '' }}' }
                    ]
                })">Zmeniť údaje</a>
            </div>
        </div>
        {{-- pravý panel: admin-zone ak view=admin, inak sidebar zákazníka --}}
        <div class="col-md-9 p-2 mt-5">
                @include('layout.partials.profile.admin_zone', [
                    'farms'    => $farms,
                    'articles' => $articles,
                    'reviews'  => $reviews,
                ])
        </div>

    </div>


@else
    <div class="row align-items-stretch">
        {{-- ľavý panel: vždy col-md-9 pre customer alebo col-md-3 pre admin-view --}}
        <div class="col-md-{{ $view === 'admin' ? '3' : '9' }}">
            <h2 class="fw-bold my-4">Môj profil</h2>

            {{-- Osobné údaje --}}
            <div class="row mb-5 g-4">
                <div class="col-md-4">
                    <p class="fw-bold mb-1">Meno:</p>
                    <p class="mb-1">{{ $user->name }}</p>
                    <a href="#" class="text-primary" onclick="openEditModal({
                        title: 'Zmeniť meno',
                        submitUrl: '{{ route('profile.update.name') }}',
                        fields: [
                            { label: 'Meno', name: 'name', type: 'text', value: '{{ $user->name }}' }
                        ]
                    })">Zmeniť meno</a>
                </div>
                <div class="col-md-4">
                    <p class="fw-bold mb-1">Email:</p>
                    <p class="mb-1">{{ $user->email }}</p>
                    <a href="#" class="text-primary" onclick="openEditModal({
                        title: 'Zmeniť email',
                        submitUrl: '{{ route('profile.update.email') }}',
                        fields: [
                            { label: 'Email', name: 'email', type: 'email', value: '{{ $user->email }}' }
                        ]
                    })">Zmeniť email</a>
                </div>
                <div class="col-md-4">
                    <p class="fw-bold mb-1">Telefónne číslo:</p>
                    <p class="mb-1">{{ $user->phone_number }}</p>
                    <a href="#" class="text-primary" onclick="openEditModal({
                        title: 'Zmeniť číslo',
                        submitUrl: '{{ route('profile.update.phone') }}',
                        fields: [
                            { label: 'Telefónne číslo', name: 'phone_number', type: 'text', value: '{{ $user->phone_number }}' }
                        ]
                    })">Zmeniť číslo</a>
                </div>
            </div>

            {{-- Adresy a firma --}}
            <div class="row mb-4 g-4">
                {{-- Fakturačná adresa --}}
                <div class="col-md-4">
                    <h5 class="fw-bold mb-3">Fakturačná adresa</h5>
                    <p class="mb-1">Ulica: {{ $user->billingAddress->street ?? '' }}</p>
                    <p class="mb-1">Číslo domu: {{ $user->billingAddress->street_number ?? '' }}</p>
                    <p class="mb-1">Mesto: {{ $user->billingAddress->city ?? '' }}</p>
                    <p class="mb-1">PSČ: {{ $user->billingAddress->zip_code ?? '' }}</p>
                    <p class="mb-2">Krajina: {{ $user->billingAddress->country ?? '' }}</p>
                    <a href="#" class="text-primary" onclick="openEditModal({
                        title: 'Upraviť fakturačnú adresu',
                        submitUrl: '{{ route('profile.update.billing') }}',
                        fields: [
                            { label: 'Ulica', name: 'street', value: '{{ $user->billingAddress->street ?? '' }}' },
                            { label: 'Číslo domu', name: 'street_number', value: '{{ $user->billingAddress->street_number ?? '' }}' },
                            { label: 'Mesto', name: 'city', value: '{{ $user->billingAddress->city ?? '' }}' },
                            { label: 'PSČ', name: 'zip_code', value: '{{ $user->billingAddress->zip_code ?? '' }}' },
                            { label: 'Krajina', name: 'country', value: '{{ $user->billingAddress->country ?? '' }}' }
                        ]
                    })">Zmeniť údaje</a>
                </div>

                {{-- Dodacia adresa --}}
                <div class="col-md-4">
                    <h5 class="fw-bold mb-3">Dodacia adresa</h5>
                    <p class="mb-1">Ulica: {{ $user->deliveryAddress->street ?? '' }}</p>
                    <p class="mb-1">Číslo domu: {{ $user->deliveryAddress->street_number ?? '' }}</p>
                    <p class="mb-1">Mesto: {{ $user->deliveryAddress->city ?? '' }}</p>
                    <p class="mb-1">PSČ: {{ $user->deliveryAddress->zip_code ?? '' }}</p>
                    <p class="mb-2">Krajina: {{ $user->deliveryAddress->country ?? '' }}</p>
                    <a href="#" class="text-primary" onclick="openEditModal({
                        title: 'Upraviť dodaciu adresu',
                        submitUrl: '{{ route('profile.update.delivery') }}',
                        fields: [
                            { label: 'Ulica', name: 'street', value: '{{ $user->deliveryAddress->street ?? '' }}' },
                            { label: 'Číslo domu', name: 'street_number', value: '{{ $user->deliveryAddress->street_number ?? '' }}' },
                            { label: 'Mesto', name: 'city', value: '{{ $user->deliveryAddress->city ?? '' }}' },
                            { label: 'PSČ', name: 'zip_code', value: '{{ $user->deliveryAddress->zip_code ?? '' }}' },
                            { label: 'Krajina', name: 'country', value: '{{ $user->deliveryAddress->country ?? '' }}' }
                        ]
                    })">Zmeniť údaje</a>
                </div>

                {{-- Firemné údaje --}}
                <div class="col-md-4">
                    <h5 class="fw-bold mb-3">Nákup na firmu</h5>
                    <p class="mb-1">Názov spoločnosti: {{ $user->company->name ?? '' }}</p>
                    <p class="mb-1">IČO: {{ $user->company->ico ?? '' }}</p>
                    <p class="mb-2">IČ DPH: {{ $user->company->vat ?? '' }}</p>
                    <a href="#" class="text-primary" onclick="openEditModal({
                        title: 'Firemné údaje',
                        submitUrl: '{{ route('profile.update.company') }}',
                        fields: [
                            { label: 'Názov spoločnosti', name: 'name', value: '{{ $user->company->name ?? '' }}' },
                            { label: 'IČO', name: 'ico', value: '{{ $user->company->ico ?? '' }}' },
                            { label: 'IČ DPH', name: 'ic_dph', value: '{{ $user->company->vat ?? '' }}' }
                        ]
                    })">Zmeniť údaje</a>
                </div>
            </div>
        </div>
        {{-- pravý panel: admin-zone ak view=admin, inak sidebar zákazníka --}}
        <div class="{{ $view === 'admin' ? 'col-md-9 p-2' : 'col-md-3 my-5' }}">
                @include('layout.partials.profile.sidebar', ['user' => $user])
        </div>
    </div>
@endif


{{-- Tlačidlá --}}
<div class="d-flex flex-wrap gap-3 mb-5">
    <a href="#" class="btn btn-secondary" onclick="openEditModal({
        title: 'Zmeniť heslo',
        submitUrl: '{{ route('profile.update.password') }}',
        fields: [
            { label: 'Aktuálne heslo', name: 'current_password', type: 'password' },
            { label: 'Nové heslo', name: 'password', type: 'password' },
            { label: 'Zopakovať heslo', name: 'password_confirmation', type: 'password' }
        ]
    })">Zmeniť heslo</a>
    <form action="{{ route('logout') }}" method="POST">
        @csrf
        <button class="btn btn-secondary">Odhlásiť sa</button>
    </form>

</div>

{{-- Prepínanie účtu --}}
<div class="mb-5">
    @if ($view === 'admin')
        <h3 class="fw-bold mb-3">Chcete pokračovať ako bežný zákazník?</h3>
        <form action="{{ route('profile.customer') }}" method="POST">
            @csrf
            <button class="btn btn-secondary">Prepnúť na zákaznícky účet</button>
        </form>
    @else
        @if ($user->is_admin)
            <h3 class="fw-bold mb-3">Chcete pokračovať ako admin?</h3>

            <form action="{{ route('profile.admin') }}" method="POST">
                @csrf
                <button class="btn btn-secondary">Prepnúť na administrátorský účet</button>
            </form>
        @else
            <h3 class="fw-bold mb-3">Chcete sa stať našim partnerom?</h3>

            <button class="btn btn-secondary"
                    onclick="openEditModal({
                        title: 'Vytvoriť administrátorský účet',
                        submitUrl: '{{ route('profile.admin.create') }}',
                        fields: [
                            { label: 'Zobrazované meno', name: 'name', placeholder: 'Zobrazované meno' }
                        ]
                    })">
                Vytvoriť administrátorský účet
            </button>

        @endif
    @endif
</div>


{{-- Pop-up pre editáciu --}}
@include('layout.partials.edit_popup')

{{-- Bannery --}}
@include('layout.partials.ads-section')
@endsection
