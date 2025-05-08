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
        <div class="border-top pt-3 mt-3">
            <div class="border rounded p-3 mb-4 position-relative">

                <div class="position-absolute top-0 end-0 m-2 d-flex flex-column align-items-end gap-2 mt-3">
                    {{-- Hviezdičky --}}
                    @php
                        $rounded   = round($review->rating * 2) / 2;
                        $fullStars = floor($rounded);
                        $halfStar  = ($rounded - $fullStars) === 0.5 ? 1 : 0;
                        $emptyStars= 5 - $fullStars - $halfStar;
                    @endphp

                    <div class="d-flex me-3">
                        @for ($i = 0; $i < $fullStars; $i++)
                            <span class="material-icons star filled">star</span>
                        @endfor
                        @if ($halfStar)
                            <span class="material-icons star half-filled">star_half</span>
                        @endif
                        @for ($i = 0; $i < $emptyStars; $i++)
                            <span class="material-icons star empty">star_outline</span>
                        @endfor
                    </div>

                    {{-- Tlačidlá --}}
                    <div class="d-flex gap-1 me-1 mt-2">
                        {{-- EDIT modal --}}
                        <button class="btn custom-button"
                                title="Upraviť recenziu"
                                onclick="openEditModal({
                                    title: 'Upraviť recenziu',
                                    submitUrl: '{{ route('reviews.update', $review->id) }}',
                                    method: 'PUT',
                                    enctype: 'multipart/form-data',
                                    fields: [
                                    { label: 'Titulok', name: 'title',
                                      value: `{{ $review->title }}`, required: true },

                                    { label: 'Text recenzie', name: 'text', type: 'textarea',
                                      value: `{{ $review->text }}`, required: true },

                                    { label: 'Počet hviezdičiek', name: 'rating',
                                      value: {{ $review->rating }}, min: 1, max: 5, step: 0.5 }
                                    ]
                                })">
                            <span class="material-symbols-outlined">edit_square</span>
                        </button>

                        {{-- DELETE confirm --}}
                        <button class="btn custom-button"
                                title="Zmazať recenziu"
                                onclick="openConfirmModal({
                                    title: 'Zmazať recenziu',
                                    text : 'Naozaj chcete zmazať recenziu &quot;{{ $review->title }}&quot;?',
                                    submitUrl: '{{ route('reviews.destroy', $review->id) }}'
                                })">
                            <span class="material-icons">delete</span>
                        </button>
                    </div>
                </div>

                {{-- Obrázok + názov produktu ---------------------------------------- --}}
                <div class="d-flex align-items-center gap-3 mb-3">
                    <img src="{{ asset($review->farm_product->product->image->path) }}"
                         alt="Produkt" class="rounded" style="height: 80px;">
                    <div>
                        <h5 class="mb-2">{{ $review->farm_product->product->name }}</h5>
                        <small class="text-muted">{{ $review->farm_product->farm->name }}</small>
                    </div>
                </div>

                {{-- Titulok + text na ľavej strane; dátum vpravo --------------------- --}}
                <div class="d-flex justify-content-between align-items-end mx-3">
                    <div>
                        <p class="fw-bold mb-1">{{ $review->title }}</p>
                        <p class="mb-0">{{ $review->text }}</p>
                    </div>
                    <small class="text-muted ms-3">{{ $review->created_at->format('d.m.Y') }}</small>
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

@include('layout.partials.edit_popup')
@include('layout.partials.confirm_popup')
