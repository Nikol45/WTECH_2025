@php
    /** @var \Illuminate\Pagination\LengthAwarePaginator|\App\Models\Favourite[] $favourites */
@endphp

@extends('layout.app')

@section('title', 'Obľúbené produkty')

@section('content')
    {{-- Tabs / krokovník --}}
    @include('layout.partials.nav_steps', [
        'context' => 'profile',
        'active'  => 'favourites',
    ])

    {{-- Späť do obchodu --}}
    <a href="{{ route('homepage') }}"
       class="d-inline-flex align-items-center text-decoration-none text-dark">
        <span class="me-2">←</span> Späť do obchodu
    </a>

    <h2 class="fw-bold my-4">Obľúbené produkty</h2>

    @if ($favourites->isEmpty())
        <p class="text-muted">Zatiaľ nemáš žiadne obľúbené produkty.</p>
    @else
        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4 mb-5">
            @foreach ($favourites as $item)
                @php
                    /* -----------------------------------------------------------
                       Príprava pre kartičku
                    ----------------------------------------------------------- */
                    $fp   = $item->farm_product;            // skratka pre prehľadnosť
                    if (!$fp) continue;                    // ak by chýbal vzťah

                    $id        = $fp->id;
                    $farmId    = $fp->farm->id ?? null;
                    $imgPath = $fp->product->image?->path
                                    ? asset($fp->product->image->path)
                                    : asset('images/placeholder.png');
                    $imgAlt    = $fp->product->image->name ?? $fp->product->name;
                    $name      = $fp->product->name .
                                 ($fp->unit === 'ks' && (int)$fp->sell_quantity === 1
                                     ? ''
                                     : ' ' . ($fp->unit === 'ks'
                                             ? (int)$fp->sell_quantity
                                             : $fp->sell_quantity) . ' ' . $fp->unit);

                    /* Cena + prípadná zľava */
                    $original  = number_format($fp->price_sell_quantity, 2, ',', ' ') . ' €';
                    if ($fp->discount_percentage) {
                        $price = number_format(
                                    $fp->price_sell_quantity * (100 - $fp->discount_percentage) / 100,
                                    2, ',', ' '
                                 ) . ' €';
                        $orig  = $original;
                    } else {
                        $price = $original;
                        $orig  = null;
                    }
                    $unitPrice = number_format($fp->price_per_unit, 2, ',', ' ') . ' €/' . $fp->unit;

                    /* Hodnotenie */
                    $rating      = $fp->rating ?? 0;
                    $rounded     = round($rating * 2) / 2;
                    $fullStars   = floor($rounded);
                    $halfStars   = ($rounded - $fullStars) == 0.5 ? 1 : 0;
                    $emptyStars  = 5 - $fullStars - $halfStars;

                    /* Url adresy */
                    $detailUrl   = route('products.show', $id);
                    $farmUrl     = $farmId ? route('farms.show', $farmId) : '#';
                    $delFavUrl   = route('profile.favourites.destroy', $item->id);   // ← Favourite ID
                    $addCartUrl  = route('cart-items.store');
                @endphp

                <div class="col col-listing">
                    <div class="card text-start p-3 h-100 position-relative">

                        {{-- Srdiečko – odstránenie z obľúbených --}}
                        <form action="{{ $delFavUrl }}" method="POST"
                              class="position-absolute" style="top:10px; right:10px; z-index:100;">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    class="d-flex justify-content-center align-items-center rounded"
                                    title="Odstrániť z obľúbených"
                                    style="
                                        width: 60px; height: 45px;
                                        background-color: var(--green-very-light);
                                        border: 2px solid #2f5c45;
                                    ">
                                <span class="material-icons" style="font-size: 3rem !important; color: var(--green-dark-muted);">
                                    favorite
                                </span>
                            </button>
                        </form>


                        {{-- Obrázok + badge so zľavou --}}
                        <div class="mb-3 position-relative">
                            <a href="{{ $detailUrl }}">
                                <img src="{{ $imgPath }}" alt="{{ $imgAlt }}" class="img-half-cover">
                            </a>
                        </div>

                        <div class="card-body d-flex flex-column">

                            {{-- Názov --}}
                            <a href="{{ $detailUrl }}"
                               class="truncate-ellipsis text-decoration-none mb-2">
                                <h5 class="card-title truncate-ellipsis px-2">{{ $name }}</h5>
                            </a>

                            {{-- Cena --}}
                            <div class="price-container mb-1">
                                <span class="price fw-bold px-2">{{ $price }}</span>
                                @if ($orig)
                                    <span class="price-before"><del>{{ $orig }}</del></span>
                                @endif
                                <span class="price-per">{{ $unitPrice }}</span>
                            </div>

                            {{-- Hviezdičky --}}
                            <div class="stars stars-listing mb-2 mt-2">
                                @for ($i = 0; $i < $fullStars; $i++)
                                    <span class="material-icons star filled">star</span>
                                @endfor
                                @if ($halfStars)
                                    <span class="material-icons star half-filled">star_half</span>
                                @endif
                                @for ($i = 0; $i < $emptyStars; $i++)
                                    <span class="material-icons star empty">star_outline</span>
                                @endfor
                                <span class="star-count ms-2">({{ $rating }})</span>
                            </div>

                            {{-- Farma / mesto --}}
                            @if ($fp->farm && $fp->farm->address)
                                <div class="d-flex align-items-center gap-2 mt-auto mb-3">
                                    <a href="{{ $farmUrl }}"
                                       class="d-flex align-items-center truncate-ellipsis gap-2 text-decoration-none">
                                        <span class="material-icons">location_on</span>
                                        <span class="card-location truncate-ellipsis">
                                            {{ $fp->farm->name }},
                                            {{ optional($fp->farm->address)->city }}
                                        </span>
                                    </a>
                                </div>
                            @endif

                            {{-- Pridať do košíka --}}
                            <form action="{{ $addCartUrl }}" method="POST" class="w-100">
                                @csrf
                                <input type="hidden" name="farm_product_id" value="{{ $id }}">
                                <input type="hidden" name="quantity"        value="1">
                                <button type="submit"
                                        class="btn custom-button btn-pridat-do-kosika w-100">
                                    Pridať do košíka
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Stránkovanie --}}
        <div class="d-flex justify-content-center">
            {{ $favourites->links() }}
        </div>
    @endif

    {{-- Reklamné bannery --}}
    @include('layout.partials.ads-section')
@endsection
