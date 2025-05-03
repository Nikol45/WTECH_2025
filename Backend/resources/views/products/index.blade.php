@extends('layout.app')

@section('title', 'Zoznam produktov')

@section('content')
        <!-- Back to homepage -->
        <div class="row mb-2">
            <div class="col-12 d-flex align-items-center flex-wrap gap-2">
                <a href="{{ route('homepage') }}"
                class="m-2 d-flex align-items-center gap-2 text-decoration-none">
                    <span class="material-icons">arrow_back</span>
                    <span class="px-2">Späť do obchodu</span>
                </a>

                @isset($headline)
                    <span class="fs-5 fw-semibold">{{ $headline }}</span>
                @endisset
            </div>
        </div>

        <!-- Page Content -->
        <div class="row">

            <aside class="filter-section col-12 col-sm-4 col-lg-3 col-xl-2 mb-4 mb-md-0">

                <form method="GET" action="{{ route('products.index') }}" id="filterForm">
                    <h2 class="filter-title mb-3 mt-3">Filtrovanie</h2>

                    @if($filterSubsubs->isNotEmpty())
                        <div class="filter-block mb-3">
                            <h3 class="mb-3 mt-4 fs-5 fw-bold">Kategórie</h3>
                            <ul class="filter-list list-unstyled">
                                @foreach ($filterSubsubs as $deep)
                                    <li class="mb-1">
                                        <input  type="checkbox"
                                                id="deep{{ $deep->id }}"
                                                name="subcategories[]"
                                                value="{{ $deep->id }}"
                                                @checked(in_array($deep->id, $selectedSubs)) >
                                        <label for="deep{{ $deep->id }}">{{ $deep->name }}</label>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="filter-block mb-3">
                        <h3 class="mb-3 mt-4 fs-5 fw-bold">Cena</h3>
                        <div class="price-range d-flex align-items-center gap-2">
                            <div class="input-group">
                                <input type="number" step="0.01" name="price_min"
                                    class="form-control min-price"
                                    placeholder="Min" value="{{ $priceMin }}">
                                <span class="input-group-text">€</span>
                            </div>
                            <span>–</span>
                            <div class="input-group">
                                <input type="number" step="0.01" name="price_max"
                                    class="form-control max-price"
                                    placeholder="Max" value="{{ $priceMax }}">
                                <span class="input-group-text">€</span>
                            </div>
                        </div>
                    </div>

                    <div class="filter-block mb-3">
                        <h3 class="mb-3 mt-4 fs-5 fw-bold">Hodnotenie</h3>
                        <ul class="list-unstyled">
                            @for ($i = 5; $i >= 1; $i--)
                                <li class="mb-1 d-flex align-items-center gap-2">
                                    <input type="radio" name="rating"
                                        id="rating{{ $i }}"
                                        value="{{ $i }}"
                                        @checked($rating == $i)>
                                    {{-- inline star row --}}
                                    <div class="stars mb-0">
                                        @for ($j = 1; $j <= 5; $j++)
                                            <span class="material-icons star {{ $j <= $i ? 'filled' : 'empty' }}">
                                                {{ $j <= $i ? 'star' : 'star_outline' }}
                                            </span>
                                        @endfor
                                        <span class="fw-bold">+</span>
                                    </div>
                                </li>
                            @endfor
                        </ul>
                    </div>

                    <div class="filter-block mb-4">
                        <h3 class="mb-3 mt-4 fs-5 fw-bold">Zľava</h3>
                        <ul class="filter-list list-unstyled">
                            <li class="mb-1">
                                <input type="radio" id="only_disc" name="discount_only" value="1" @checked($discountOnly)>
                                <label for="only_disc">Iba zľavnené</label>
                            </li>
                            <li class="mb-1">
                                <input type="radio" id="all_prod" name="discount_only" value="0" @checked(!$discountOnly)>
                                <label for="all_prod">Všetky produkty</label>
                            </li>
                        </ul>
                    </div>

                    <div class="filter-buttons mb-2 d-flex gap-3 flex-wrap">
                        <button class="btn custom-button filter-button" type="submit">
                            Použiť filter
                        </button>

                        <a href="{{ route('products.index', request()->only(['category','subcategory','q'])) }}"
                        class="btn custom-button filter-button">
                            Zrušiť
                        </a>
                    </div>

                    @if(request('category'))
                        <input type="hidden" name="category" value="{{ request('category') }}">
                    @endif
                    @if(request('subcategory'))
                        <input type="hidden" name="subcategory" value="{{ request('subcategory') }}">
                    @endif
                    @if(request('q'))
                        <input type="hidden" name="q" value="{{ request('q') }}">
                    @endif
                </form>
            </aside>

            <!-- Product Grid -->
            <div class="product-listing col-12 col-lg-9 col-xl-10 px-4">
                <div class="d-flex flex-wrap position-relative justify-content-between mb-4 mt-3 gap-2">
                    <h2 class="fw-bold">Zoznam produktov</h2>
                    <button class="btn custom-button filter-toggle">Filtre</button>

                    <form method="GET" action="{{ route('products.index') }}"
                        class="d-flex align-items-center gap-2"
                        id="sortForm">
                        <label for="sortSelect" class="m-0">Zoradiť podľa:</label>
                        <select name="sort" id="sortSelect" class="form-select form-select-sm w-auto" onchange="this.form.submit()">
                            <option value="" {{ request('sort')=='' ? 'selected' : '' }}>Predvolené</option>
                            <option value="priceAsc" {{ request('sort')=='priceAsc' ? 'selected' : '' }}>Cena (od najnižšej)</option>
                            <option value="priceDesc" {{ request('sort')=='priceDesc' ? 'selected' : '' }}>Cena (od najvyššej)</option>
                            <option value="ratingAsc" {{ request('sort')=='ratingAsc' ? 'selected' : '' }}>Hodnotenie (od najnižšieho)</option>
                            <option value="ratingDesc" {{ request('sort')=='ratingDesc' ? 'selected' : '' }}>Hodnotenie (od najvyššieho)</option>
                        </select>

                        @foreach(request()->except(['sort','page']) as $key => $value)
                            @if(is_array($value))
                                @foreach($value as $v)
                                    <input type="hidden" name="{{ $key }}[]" value="{{ $v }}">
                                @endforeach
                            @else
                                <input type="hidden" name="{{ $key }}"  value="{{ $value }}">
                            @endif
                        @endforeach
                    </form>
                </div>

                <section>
                    @if($products->count())
                        <div class="product-grid justify-content-between mb-4">
                            @foreach ($products as $product)
                                @include('layout.partials.card', ['item' => $product])
                            @endforeach
                        </div>

                        @if ($products->hasPages())
                        <nav class="pagination-container d-flex justify-content-center align-items-center mt-4">
                            {{ $products->onEachSide(1)->links('vendor.pagination.bootstrap-5') }}
                        </nav>
                        @endif
                    @else
                        <p class="text-center py-5 fs-4">
                            Neboli nájdené žiadne produkty.
                        </p>
                    @endif
                </section>
            </div>
        </div>

        @include('layout.partials.article-section', [
        'title' => 'Mohlo by vás zaujímať',
        'id' => 'carouselRow4',
        'id_arrow_left' => 'arrowLeft4',
        'id_arrow_right' => 'arrowRight4',
        ])

        @include('layout.partials.ads-section')
@endsection
