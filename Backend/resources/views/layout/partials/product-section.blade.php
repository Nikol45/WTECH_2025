@php
    $canEdit = $canEdit ?? false;
@endphp
<section class="my-5">
    <h2>{{ $title }}</h2>

    <div class="arrows-around position-relative mt-4">
        <div class="arrows">
            <button class="btn custom-button arrow-left" id="{{ $id_arrow_left }}">
                <span class="material-icons">chevron_left</span>
            </button>
            <button class="btn custom-button arrow-right" id="{{ $id_arrow_right }}">
                <span class="material-icons">chevron_right</span>
            </button>
        </div>
        <div class="products-carousel-container">
            <div class="products-row" id="{{ $id }}">
                @foreach ($products ?? [] as $product)

                @php
                    $ratingVal = $product['rating'] ?? 0;
                    $rounded   = round($ratingVal * 2) / 2;
                    $fullStars = floor($rounded);
                    $halfStars = ($rounded - $fullStars) === 0.5 ? 1 : 0;
                    $emptyStars = 5 - $fullStars - $halfStars;
                @endphp

                    <div class="col">
                        <div class="card text-start p-3 {{ !$product['availability'] ? 'unavailable' : '' }}">
                            <div class="horna-polka mb-3">
                                <img src="{{ asset($product['image']) }}" alt="{{ $product['alt'] }}" class="img-half-cover">
                                @if (!empty($product['discount']) && !$canEdit)
                                    <div class="discount">
                                        <span class="discount-text">-{{ $product['discount'] }}%</span>
                                    </div>
                                @endif

                                @if($canEdit && !$product['availability'])
                                    <div class="badge bg-secondary position-absolute top-0 start-0 m-2">Nedostupné</div>
                                @endif

                                @if($canEdit && !empty($product['edit_route']))
                                    @php $productObj = (object) $product; @endphp
                                    <div class="edit">
                                        <button
                                            class="edit-button custom-button w-100"
                                            onclick="openEditProductModal({{ $productObj->id }}, '{{ $product['edit_route'] }}', '{{ $product['delete_route'] }}')"
                                            data-name="{{ $productObj->name }}"
                                            data-price="{{ $productObj->price_per_unit }}"
                                            data-sale-type="{{ $productObj->unit }}"
                                            data-amount="{{ $productObj->sell_quantity }}"
                                            data-discount="{{ $productObj->discount }}"
                                            data-description="{{ $productObj->description }}"
                                            data-unavailable="{{ $productObj->availability ? '0' : '1' }}"
                                            data-images='@json($productObj->images->map(fn($img) => ['id' => $img->id, 'path' => asset($img->path)]))'>
                                            <span class="material-icons">edit</span>
                                        </button>
                                    </div>
                                @endif
                            </div>

                            <div class="card-body">
                                <a href="{{ route('products.show', $product['id']) }}" class="text-decoration-none">
                                    <h5 class="card-title truncate-ellipsis px-2">
                                        {{ $product['name'] }}
                                        @if ($product['unit'] !== 'ks' || (int) $product['sell_quantity'] !== 1)
                                            {{ ' ' . ($product['unit'] === 'ks' ? (int) $product['sell_quantity'] : $product['sell_quantity']) . ' ' . $product['unit'] }}
                                        @endif
                                    </h5>
                                </a>

                                <div class="price-container">
                                    <span class="price fw-bold px-2">{{ $product['price'] }}</span>
                                    @if (!empty($product['original_price']))
                                        <span class="price-before"><del>{{ $product['original_price'] }}</del></span>
                                    @endif
                                    <span class="price-per">{{ $product['price_per'] }}</span>
                                </div>

                                <div class="stars mb-2 mt-2">
                                    @for($i = 0; $i < $fullStars; $i++)
                                    <span class="material-icons star filled">star</span>
                                    @endfor

                                    @if($halfStars)
                                    <span class="material-icons star half-filled">star_half</span>
                                    @endif

                                    @for($i = 0; $i < $emptyStars; $i++)
                                    <span class="material-icons star empty">star_outline</span>
                                    @endfor

                                    <span class="star-count ms-2">({{ $ratingVal }})</span>
                                </div>

                                @if(!$canEdit)
                                <div class="d-flex align-items-center gap-2 mt-2">
                                    <a href="{{ route('farms.show', $product['farm_id']) }}" class="d-flex align-items-center truncate-ellipsis gap-2 text-decoration-none">
                                        <span class="material-icons">location_on</span>
                                        <span class="card-location truncate-ellipsis">{{ $product['location'] }}</span>
                                    </a>
                                </div>
                                @endif

                                <div class="d-flex align-items-center gap-2">
                                    <form action="{{ route('cart-items.store') }}" method="POST" class="w-100">
                                        @csrf
                                        <input type="hidden" name="farm_product_id" value="{{ $product['id'] }}">
                                        <input type="hidden" name="quantity" value="1">
                                        <button class="btn custom-button btn-pridat-do-kosika w-100" type="submit">Pridať do košíka</button>
                                    </form>

                                    <form action="{{ route('profile.favourites.store') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="farm_product_id" value="{{ $product['id'] }}">
                                        <button type="submit" class="btn custom-button favorite-btn p-2"
                                                title="Pridať do obľúbených">
                                            <span class="material-icons">favorite_border</span>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>
