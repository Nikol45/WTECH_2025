@php
    $view = $view ?? 'customer';
@endphp

@extends('layout.app')

@section('title', 'Môj profil')

@section('content')
    {{-- Tabs --}}
    @include('layout.partials.profile.profile_tabs', ['active' => 'profile'])

    {{-- Späť do obchodu --}}
    <a href="{{ route('homepage') }}" class="d-inline-flex align-items-center text-decoration-none text-dark">
        <span class="me-2">←</span> Späť do obchodu
    </a>

    @if($view === 'admin')
        <h2 class="fw-bold my-4">Môj profil</h2>

        {{-- Osobné údaje --}}
        <div class="row mb-5 g-4">
            <div class="col-md-4">
                <p class="fw-bold mb-1">Meno:</p>
                <p class="mb-0">{{ $user->name }}</p>
                <a href="#" class="text-primary">Zmeniť meno</a>
            </div>
            <div class="col-md-4">
                <p class="fw-bold mb-1">Email:</p>
                <p class="mb-0">{{ $user->email }}</p>
                <a href="#" class="text-primary">Zmeniť email</a>
            </div>
            <div class="col-md-4">
                <p class="fw-bold mb-1">Telefónne číslo:</p>
                <p class="mb-0">{{ $user->phone_number }}</p>
                <a href="#" class="text-primary">Zmeniť číslo</a>
            </div>
        </div>

        <div class="row align-items-stretch">

            <div class="col-md-3 mt-3">
                {{-- Fakturačná adresa --}}
                <div class="col-md-4">
                    <h5 class="fw-bold mb-3">Fakturačná adresa</h5>
                    <p class="mb-0">Ulica: {{ $user->billing_address->street ?? '-' }}</p>
                    <p class="mb-0">Číslo domu: {{ $user->billing_address->street_number ?? '-' }}</p>
                    <p class="mb-0">Mesto: {{ $user->billing_address->city ?? '-' }}</p>
                    <p class="mb-0">PSČ: {{ $user->billing_address->zip ?? '-' }}</p>
                    <p class="mb-2">Krajina: {{ $user->billing_address->country ?? '-' }}</p>
                    <a href="#" class="text-primary">Zmeniť údaje</a>
                </div>

                {{-- Dodacia adresa --}}
                <div class="col-md-4">
                    <h5 class="fw-bold mb-3">Dodacia adresa</h5>
                    <p class="mb-0">Ulica: {{ $user->delivery_address->street ?? '-' }}</p>
                    <p class="mb-0">Číslo domu: {{ $user->delivery_address->street_number ?? '-' }}</p>
                    <p class="mb-0">Mesto: {{ $user->delivery_address->city ?? '-' }}</p>
                    <p class="mb-0">PSČ: {{ $user->delivery_address->zip ?? '-' }}</p>
                    <p class="mb-2">Krajina: {{ $user->delivery_address->country ?? '-' }}</p>
                    <a href="#" class="text-primary">Zmeniť údaje</a>
                </div>

                {{-- Firemné údaje --}}
                <div class="col-md-4">
                    <h5 class="fw-bold mb-3">Nákup na firmu</h5>
                    <p class="mb-0">Názov spoločnosti: {{ $user->company->name ?? '-' }}</p>
                    <p class="mb-0">IČO: {{ $user->company->ico ?? '-' }}</p>
                    <p class="mb-2">IČ DPH: {{ $user->company->vat ?? '-' }}</p>
                    <a href="#" class="text-primary">Zmeniť údaje</a>
                </div>
            </div>
            {{-- pravý panel: admin-zone ak view=admin, inak sidebar zákazníka --}}
            <div class="col-md-9 p-2">
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
                        <p class="mb-0">{{ $user->name }}</p>
                        <a href="#" class="text-primary">Zmeniť meno</a>
                    </div>
                    <div class="col-md-4">
                        <p class="fw-bold mb-1">Email:</p>
                        <p class="mb-0">{{ $user->email }}</p>
                        <a href="#" class="text-primary">Zmeniť email</a>
                    </div>
                    <div class="col-md-4">
                        <p class="fw-bold mb-1">Telefónne číslo:</p>
                        <p class="mb-0">{{ $user->phone_number }}</p>
                        <a href="#" class="text-primary">Zmeniť číslo</a>
                    </div>
                </div>

                {{-- Adresy a firma --}}
                <div class="row mb-4 g-4">
                    {{-- Fakturačná adresa --}}
                    <div class="col-md-4">
                        <h5 class="fw-bold mb-3">Fakturačná adresa</h5>
                        <p class="mb-0">Ulica: {{ $user->billing_address->street ?? '-' }}</p>
                        <p class="mb-0">Číslo domu: {{ $user->billing_address->street_number ?? '-' }}</p>
                        <p class="mb-0">Mesto: {{ $user->billing_address->city ?? '-' }}</p>
                        <p class="mb-0">PSČ: {{ $user->billing_address->zip ?? '-' }}</p>
                        <p class="mb-2">Krajina: {{ $user->billing_address->country ?? '-' }}</p>
                        <a href="#" class="text-primary">Zmeniť údaje</a>
                    </div>

                    {{-- Dodacia adresa --}}
                    <div class="col-md-4">
                        <h5 class="fw-bold mb-3">Dodacia adresa</h5>
                        <p class="mb-0">Ulica: {{ $user->delivery_address->street ?? '-' }}</p>
                        <p class="mb-0">Číslo domu: {{ $user->delivery_address->street_number ?? '-' }}</p>
                        <p class="mb-0">Mesto: {{ $user->delivery_address->city ?? '-' }}</p>
                        <p class="mb-0">PSČ: {{ $user->delivery_address->zip ?? '-' }}</p>
                        <p class="mb-2">Krajina: {{ $user->delivery_address->country ?? '-' }}</p>
                        <a href="#" class="text-primary">Zmeniť údaje</a>
                    </div>

                    {{-- Firemné údaje --}}
                    <div class="col-md-4">
                        <h5 class="fw-bold mb-3">Nákup na firmu</h5>
                        <p class="mb-0">Názov spoločnosti: {{ $user->company->name ?? '-' }}</p>
                        <p class="mb-0">IČO: {{ $user->company->ico ?? '-' }}</p>
                        <p class="mb-2">IČ DPH: {{ $user->company->vat ?? '-' }}</p>
                        <a href="#" class="text-primary">Zmeniť údaje</a>
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
        <a href="#" class="btn btn-secondary">Zmeniť heslo {{-- route('profile.password') --}}</a>
        <form action="#" method="POST"> {{-- route('logout') --}}
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
            <h3 class="fw-bold mb-3">Chcete sa stať našim partnerom?</h3>
            <form action="{{ route('profile.admin') }}" method="POST">
                @csrf
                <button class="btn btn-secondary">Prepnúť na administrátorský účet</button>
            </form>
        @endif
    </div>

    {{-- Bannery --}}
    @include('layout.partials.ads-section')
@endsection
