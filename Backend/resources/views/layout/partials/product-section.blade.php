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
                    <div class="col">
                        <div class="card text-start p-3">
                            <div class="mb-3">
                                <img src="{{ asset($product['image']) }}" alt="{{ $product['alt'] }}" class="img-half-cover">
                                @if (!empty($product['discount']))
                                    <div class="discount">
                                        <span class="discount-text">-{{ $product['discount'] }}%</span>
                                    </div>
                                @endif
                            </div>

                            <div class="card-body">
                            <a href="{{ route('farm-products.show', $product['id']) }}" class="text-decoration-none">
                                <h5 class="card-title truncate-ellipsis px-2">{{ $product['name'] }}</h5>
                            </a>

                                <div class="price-container">
                                    <span class="price fw-bold px-2">{{ $product['price'] }}</span>
                                    @if (!empty($product['original_price']))
                                        <span class="price-before"><del>{{ $product['original_price'] }}</del></span>
                                    @endif
                                    <span class="price-per">{{ $product['price_per'] }}</span>
                                </div>

                                <div class="stars mb-3 mt-3">
                                    @for ($i = 0; $i < 5; $i++)
                                        <span class="material-icons star {{ $i < $product['rating'] ? 'filled' : 'empty' }}">
                                            {{ $i < $product['rating'] ? 'star' : 'star_outline' }}
                                        </span>
                                    @endfor
                                    <span class="star-count ms-2">({{ $product['rating'] }})</span>
                                </div>

                                <div class="d-flex align-items-center gap-2 mt-2">
                                    <a href="{{ route('farms.show', $product['farm_id']) }}" class="d-flex align-items-center truncate-ellipsis gap-2 text-decoration-none">
                                        <span class="material-icons">location_on</span>
                                        <span class="card-location truncate-ellipsis">{{ $product['location'] }}</span>
                                    </a>
                                </div>

                                <div class="d-flex align-items-center gap-2">
                                    <form action="{{ route('cart-items.store') }}" method="POST" class="w-100">
                                        @csrf
                                        <input type="hidden" name="farm_product_id" value="{{ $product['id'] }}">
                                        <input type="hidden" name="quantity" value="1">
                                        <button class="btn custom-button btn-pridat-do-kosika w-100" type="submit">Pridať do košíka</button>
                                    </form>
                                    <button class="btn custom-button favorite-btn">
                                        <span class="material-icons">favorite_border</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>
