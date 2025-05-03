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
            @foreach($order->items as $item)
                <div class="row mb-3 align-items-center">
                    <div class="col-auto">
                        <img src="{{ asset($item->product->image_url) }}" alt="{{ $item->product->name }}" class="img-fluid rounded" style="max-width:80px;">
                    </div>
                    <div class="col">
                        <p class="mb-1 fw-bold">{{ $item->product->name }}</p>
                        <p class="mb-0 text-muted">Farma: {{ $item->product->farm->name }}, {{ $item->product->farm->city }}</p>
                    </div>
                    <div class="col-auto">
                        <span class="badge bg-light text-dark px-3 py-2">{{ $item->quantity }} {{ $item->unit_label }}</span>
                    </div>
                    <div class="col-auto">
                        <p class="fs-5 mb-0">{{ currency($item->total_price) }}</p>
                    </div>
                </div>
            @endforeach

            {{-- Súhrn objednávky (číslo, dátum, cena, reorder) --}}
            <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top">
                <div>
                    <span class="me-4">č. {{ $order->number }}</span>
                    <span>{{ $order->created_at->format('j.n.Y') }}</span>
                </div>
                <div class="d-flex align-items-center gap-3">
                    <span class="fw-bold">Cena: {{ currency($order->total_price) }}</span>
                    <form action="{{ route('orders.reorder', $order) }}" method="POST">
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
