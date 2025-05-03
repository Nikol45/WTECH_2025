@php
    $id = $item->id;
    $farmId = $item->farm->id;

    $imgPath = $item->product->image->path;
    $imgAlt = $item->product->image->name;
    $name = $item->product->name . (
            $item->unit === 'ks' && (int)$item->sell_quantity === 1
            ? '' : ' ' . ($item->unit === 'ks' ? (int)$item->sell_quantity : $item->sell_quantity) . ' ' . $item->unit
        );

    $original = number_format($item->price_sell_quantity, 2) . ' €';

    if ($item->discount_percentage) {
        $price = number_format(
            $item->price_sell_quantity * (100 - $item->discount_percentage) / 100, 2) . ' €';
        $orig = $original;
    }
    else {
        $price = $original;
        $orig = null;
    }

    $unitPrice = number_format($item->price_per_unit,2).' €/'.$item->unit;
    $disc = $item->discount_percentage;
    $rating = ($item->rating ?? 0);
    $location = $item->farm->name.', '.optional($item->farm->address)->city;

    $rounded = round($rating * 2) / 2;
    $fullStars = floor($rounded);
    $halfStars = ($rounded - $fullStars) == 0.5 ? 1 : 0;
    $emptyStars = 5 - $fullStars - $halfStars;

    $detailUrl = route('products.show', $id);
    $farmUrl   = route('farms.show', $farmId);
@endphp

<div class="col col-listing">
    <div class="card text-start p-3 h-100">

        <div class="mb-3 position-relative">
            <a href="{{ $detailUrl }}">
                <img src="{{ $imgPath }}" alt="{{ $imgAlt }}" class="img-half-cover">
            </a>
            @if($disc)
                <div class="discount"><span class="discount-text">-{{ $disc }}%</span></div>
            @endif
        </div>

        <div class="card-body d-flex flex-column">

            <a href="{{ $detailUrl }}" class="truncate-ellipsis text-decoration-none mb-2">
                <h5 class="card-title truncate-ellipsis px-2">{{ $name }}</h5>
            </a>

            <div class="price-container mb-1">
                <span class="price fw-bold px-2">{{ $price }}</span>
                @if($orig)<span class="price-before"><del>{{ $orig }}</del></span>@endif
                <span class="price-per">{{ $unitPrice }}</span>
            </div>

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

            <div class="d-flex align-items-center gap-2 mt-auto mb-3">
                <a href="{{ $farmUrl }}" class="d-flex align-items-center truncate-ellipsis gap-2 text-decoration-none">
                    <span class="material-icons">location_on</span>
                    <span class="card-location truncate-ellipsis">{{ $location }}</span>
                </a>
            </div>

            <div class="d-flex align-items-center gap-2">
                <form action="{{ route('cart-items.store') }}" method="POST" class="w-100">
                    @csrf
                    <input type="hidden" name="farm_product_id" value="{{ $id }}">
                    <input type="hidden" name="quantity" value="1">
                    <button type="submit" class="btn custom-button btn-pridat-do-kosika w-100">Pridať do košíka</button>
                </form>

                <form action="{{ route('profile.favourites.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="farm_product_id" value="{{ $id }}">
                    <button type="submit" class="btn custom-button favorite-btn p-2"
                            title="Pridať do obľúbených">
                        <span class="material-icons">favorite_border</span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
