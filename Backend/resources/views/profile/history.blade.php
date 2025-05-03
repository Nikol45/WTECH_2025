@extends('layout.app')

@section('title', 'História objednávok')

@section('content')

    {{-- Navigačné tabuľky profilu (desktop & mobile) --}}
    @include('layout.partials.nav_steps', [
        'context' => 'profile',
        'active'  => 'history'
    ])

    {{-- Odkaz späť do obchodu --}}
    <a href="{{ route('homepage') }}" class="d-inline-flex align-items-center text-decoration-none text-dark">
        <span class="me-2">←</span> Späť do obchodu
    </a>

    <h2 class="fw-bold my-4">História objednávok</h2>

    {{-- Prehľad všetkých objednávok --}}
    @forelse($orders as $order)
        <div class="border rounded-3 p-3 mb-4">
            <h3 class="fw-bold fs-5 mb-3">Zásielka č. {{ $order->number }}</h3>

            {{-- Položky objednávky --}}
            @foreach($order->packages as $package)
                @foreach($package->order_items as $item)
                    @php
                        $product = $item->farm_product->product ?? null;
                        $farm    = $item->farm_product->farm ?? null;
                    @endphp

                    <div class="row mb-3 align-items-center">
                        <div class="col-auto">
                            <img src="{{ asset($product->image->path ?? 'images/placeholder.png') }}"
                                 alt="{{ $product->name ?? 'Produkt' }}"
                                 class="img-fluid rounded"
                                 style="max-width:80px;">
                        </div>
                        <div class="col">
                            <p class="mb-1 fw-bold">{{ $product->name ?? 'Neznámy produkt' }}</p>
                            <p class="mb-0 text-muted">
                                Farma: {{ $farm->name ?? 'neznáma' }},
                                {{ $farm->address->city ?? 'bez adresy' }}
                            </p>
                        </div>
                        <div class="col-auto">
                <span class="badge bg-light text-dark px-3 py-2">
                    {{ $item->quantity }} {{ $item->unit_label }}
                </span>
                        </div>
                        <div class="col-auto">
                            <p class="fs-5 mb-0">{{ ($item->total_price) }}</p>
                        </div>
                    </div>
                @endforeach
            @endforeach

            {{-- Súhrn objednávky (číslo, dátum, cena, reorder) --}}
            <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top">
                <div>
                    <span class="me-4">č. {{ $order->number }}</span>
                    <span>{{ $order->created_at->format('j.n.Y') }}</span>
                </div>
                <div class="d-flex align-items-center gap-3">
                    <span class="fw-bold">Cena: {{ ($order->total_price) }}</span>
                    <form action="{{ route('profile.history.reorder', $order) }}" method="POST">
                        @csrf
                        <button class="btn btn-secondary">Objednať znova</button>
                    </form>
                </div>
            </div>
        </div>
    @empty
        <p class="text-muted">Nemáte zatiaľ žiadne objednávky.</p>
    @endforelse

    {{-- Stránkovanie --}}
    <div class="mt-4">
        {{ $orders->links() }}
    </div>

    {{-- Reklamné bannery --}}
    @include('layout.partials.ads-section')
@endsection
