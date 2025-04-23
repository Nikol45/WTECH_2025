@extends('layout.app')

@section('title', 'Domov')

@section('content')
    {{-- Hero Section --}}
    <div class="hero-section py-5">
        <div class="container-fluid custom-fluid">
            <div class="row align-items-center">
                <div class="col-md-6 mb-4 mb-md-0">
                    <div class="hero-text">
                        <h2 class="hero-subtitle mb-5">Podporte lokálnu výrobu a vychutnajte si skutočnú kvalitu.</h2>
                        <h1 class="hero-title mb-5">Objavte farmy vo svojom okolí a nakupujte priamo z prvej ruky!</h1>
                        <div class="d-flex flex-grow-1 my-2 my-md-0 mb-5 custom-search-container-2">
                            <form class="position-relative w-100">
                                <input type="text" class="form-control pe-5" placeholder="Zadajte svoju polohu...">
                                <button class="btn custom-button position-absolute top-50 end-0 translate-middle-y me-2 p-0" type="submit">
                                    <span class="material-icons">search</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="hero-image text-center text-md-end">
                        <img src="{{ asset('images/big/hero-placeholder.png') }}" alt="Košík s ovocím a zeleninou" class="img-fluid">
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('layout.partials.product-section', [
    'title' => 'Odporúčané produkty',
    'id' => 'carouselRow',
    'id_arrow_left' => 'arrowLeft',
    'id_arrow_right' => 'arrowRight',
    'products' => $recommendedProducts
    ])

    @include('layout.partials.product-section', [
        'title' => 'Novinky',
        'id' => 'carouselRow1',
        'id_arrow_left' => 'arrowLeft1',
        'id_arrow_right' => 'arrowRight1',
        'products' => $newestProducts
    ])

    @include('layout.partials.product-section', [
        'title' => 'Výhodné ponuky',
        'id' => 'carouselRow2',
        'id_arrow_left' => 'arrowLeft2',
        'id_arrow_right' => 'arrowRight2',
        'products' => $discountedProducts
    ])

    @include('layout.partials.product-section', [
        'title' => 'Sezónne produkty',
        'id' => 'carouselRow3',
        'id_arrow_left' => 'arrowLeft3',
        'id_arrow_right' => 'arrowRight3',
        'products' => $seasonalProducts
    ])

    {{-- Articles Section --}}
    @include('layout.partials.article-section', [
        'title' => 'Mohlo by vás zaujímať',
        'id' => 'carouselRow4',
        'id_arrow_left' => 'arrowLeft4',
        'id_arrow_right' => 'arrowRight4',
    ])

    {{-- Ads Section --}}
    @include('layout.partials.ads-section')
@endsection
