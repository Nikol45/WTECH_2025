@extends('layout.app')

@section('title', 'Moje recenzie')

@section('content')

    {{-- Navigačné tabuľky profilu (desktop & mobile) --}}
    @include('layout.partials.nav_steps', [
        'context' => 'profile',
        'active'  => 'reviews'
    ])

    {{-- Odkaz späť do obchodu --}}
    <a href="{{ route('homepage') }}" class="d-inline-flex align-items-center text-decoration-none text-dark">
        <span class="me-2">←</span> Späť do obchodu
    </a>

    <h2 class="fw-bold my-4">Moje recenzie</h2>

    {{-- Zoznam recenzií --}}
    @forelse($reviews as $review)
        <div class="border rounded p-3 mb-4 position-relative">
            {{-- Akcie Upraviť / Zmazať --}}
            <div class="position-absolute" style="top: 10px; right: 10px;">
                <button class="btn btn-light border me-1" title="Upraviť"
                        onclick="openEditModal({
                            title: 'Upraviť recenziu',
                            submitUrl: '{{ route('reviews.update', $review) }}',
                            method: 'PUT',
                            fields: [
                                { label: 'Titulok', name: 'title', value: '{{ $review->title }}' },
                                { label: 'Text recenzie', name: 'body', type: 'textarea', value: `{{ $review->body }}` },
                                { label: 'Počet hviezdičiek', name: 'rating', type: 'number', value: {{ $review->rating }}, min: 1, max: 5 }
                            ]
                        })">
                    <i class="bi bi-pencil"></i>
                </button>

                <form action="{{ route('reviews.destroy', $review) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-light border" title="Zmazať" onclick="return confirm('Naozaj zmazať recenziu?');">
                        <i class="bi bi-trash"></i>
                    </button>
                </form>
            </div>

            {{-- Produkt + farma --}}
            <div class="d-flex align-items-start gap-3 mb-3">
                <img src="{{ asset($review->product->image_url) }}" alt="{{ $review->product->name }}" class="img-fluid rounded" style="width: 80px; height: 80px;">
                <div>
                    <p class="fw-bold mb-1">{{ $review->product->name }}</p>
                    <p class="text-muted mb-0">{{ $review->product->farm->name }}, {{ $review->product->farm->city }}</p>
                </div>
            </div>

            {{-- Titulok, autor, text recenzie --}}
            <h5 class="mb-1">{{ $review->title }}</h5>
            <p class="text-muted mb-2">
                <i class="bi bi-person me-2"></i>
                {{ $review->user->name }}
            </p>
            <p class="mb-3">{{ $review->body }}</p>

            {{-- Hviezdičky + dátum --}}
            <div class="d-flex justify-content-between align-items-center">
                <div></div> {{-- prázdny stĺpec pre zarovnanie vpravo --}}
                <div class="text-end">
                    @for($i = 1; $i <= 5; $i++)
                        <i class="bi {{ $i <= $review->rating ? 'bi-star-fill text-warning' : 'bi-star' }}"></i>
                    @endfor
                    <span class="text-muted ms-2">{{ $review->created_at->format('d.m.Y') }}</span>
                </div>
            </div>
        </div>
    @empty
        <p class="text-muted">Zatiaľ ste nepridali žiadne recenzie.</p>
    @endforelse

    {{-- Stránkovanie --}}
    <div class="mt-4">
        {{ $reviews->links() }}
    </div>

    {{-- Reklamné bannery --}}
    @include('layout.partials.ads-section')
@endsection
