@extends('layout.app')

@section('title', $farmProduct->product->name.' – '.$farmProduct->farm->name)

@section('content')
	{{-- Breadcrumb --}}
	<div class="breadcrumb align-items-center mb-3">
        <a href="{{ route('homepage') }}" class="d-flex align-items-center me-1">
            <span class="material-icons">home</span>
        </a>

        &gt;

        <a href="{{ route('products.index', ['category'=>$farmProduct->product->category_id]) }}" class="mx-1">
        {{ $farmProduct->product->category->name }}
        </a>

        @if($farmProduct->product->subsubcategory != null)
        &gt;

        <a href="{{ route('products.index', ['subcategory'=>$farmProduct->product->subcategory_id]) }}" class="mx-1">
            {{ $farmProduct->product->subcategory->name }}
        </a>

        @endif

        &gt;
        <strong class="ms-1">{{ $farmProduct->product->name }}</strong>
    </div>

	{{-- Link naspäť --}}
	<div class="row mb-2">
		<div class="col-12">
			<a href="{{ route('homepage') }}" class="d-flex align-items-center gap-2 text-decoration-none">
				<span class="material-icons">arrow_back</span>
				<span>Späť do obchodu</span>
			</a>
		</div>
  	</div>

	<div class="row product-detail">
		{{-- Galéria --}}
		<div class="col-md-5 product-gallery">
			<div class="main-image mb-3 position-relative">
				<img id="mainImage" src="{{ asset($photos[0]) }}" class="img-fluid" alt="{{ $farmProduct->product->name }}">
			</div>
			<div class="d-flex align-items-center">
				<div class="arrows-around position-relative mt-4">
					<div class="arrows">
						<button class="btn custom-button arrow-left" id="arrowLeft5">
							<span class="material-icons">chevron_left</span>
						</button>
						<button class="btn custom-button arrow-right" id="arrowRight5">
							<span class="material-icons">chevron_right</span>
						</button>
					</div>
					<div class="images-carousel-container">
						<div class="images-row" id="carouselRow5">
							<div class="thumbnail-list d-flex w-100">
								@foreach($photos as $path)
								<div class="col">
									<img src="{{ asset($path) }}" class="img-fluid rounded thumbnail-item" onclick="document.getElementById('mainImage').src='{{ asset($path) }}';">
								</div>
								@endforeach
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		{{-- Popis --}}
		<div class="col-12 col-md-7 px-4">
			<h1 class="mb-4">{{ $farmProduct->product->name }}</h1>

			{{-- Hodnotenie --}}
			<div class="d-flex align-items-center mb-3 gap-3">
				<div class="d-flex align-items-center gap-1">
					<span class=fs-5>{{ number_format($avgRating,1) }}</span>
					<div class="stars">
						@php
						$rounded = round($avgRating * 2) / 2;
						$full = floor($rounded);
						$half = $rounded - $full >= .5 ? 1 : 0;
						$empty = 5 - $full - $half;
						@endphp

						@for($i=0;$i<$full;$i++)
							<span class="material-icons star filled">star</span>
						@endfor

						@if($half)
							<span class="material-icons star half-filled">star_half</span>
						@endif

						@for($i=0;$i<$empty;$i++)
							<span class="material-icons star empty">star_outline</span>
						@endfor
					</div>
				</div>
				<div class="d-flex gap-2">
					<span class="fs-5 no-wrap">{{ $allReviews->count() }} recenzií</span>
					<span class="fs-5">|</span>
					<span class="fs-5 no-wrap">{{ $selectedQuantity }} {{ $units }}</span>
				</div>
			</div>

			{{-- Cena --}}
			<div class="fs-3 fw-bold mb-4">
				{{ number_format($farmProduct->price_sell_quantity * (100 - ($farmProduct->discount_percentage ?? 0))/ 100, 2) }} €
			</div>

			{{-- Popis produktu --}}
			<p>{{ $farmProduct->product->description }}</p>

			@if($farmProduct->farm_specific_description != null)
			<div class="mb-4">
				<a href="#productDescription" class="detailne-info d-flex align-items-center gap-2">
					<span class="modal-link">Detailné informácie</span>
					<span class="material-icons">keyboard_arrow_down</span>
				</a>
			</div>
			@endif

			{{-- Pridanie do košíka --}}
			<div class="quantity add-to-cart-container d-flex align-items-center gap-2 flex-wrap">
				<button class="quantity-button minus btn custom-button" id="qtyMinus8">−</button>
				<input type="number" id="productQty8" min="1" step="1" value="1" class="form-control text-center integer-only">
				<button class="quantity-button plus btn custom-button" id="qtyPlus8">+</button>
				<span class="fs-5 mx-3">Počet balení</span>
				<button class="btn custom-button favorite-btn">
					<span class="material-icons">favorite_border</span>
				</button>

				<form action="{{ route('cart-items.store') }}" method="POST">
					@csrf
					<input type="hidden" name="farm_product_id" value="{{ $farmProduct->id }}">
					<button class="btn custom-button btn-pridat-do-kosika mt-0" type="submit">
						Pridať do košíka
					</button>
				</form>
			</div>
		</div>
	</div>

	{{-- Farms carousel --}}
	<div class="row mt-5">
		<h2 class="mb-3">Vyberte si farmu</h2>
		<div class="arrows-around position-relative mt-4">
			<div class="arrows">
				<button class="btn custom-button arrow-left" id="arrowLeft6">
					<span class="material-icons">chevron_left</span>
				</button>
				<button class="btn custom-button arrow-right" id="arrowRight6">
					<span class="material-icons">chevron_right</span>
				</button>
			</div>
			<div class="articles-carousel-container">
				<div class="articles-row" id="carouselRow6">
					@foreach($farmOptions as $opt)
						@php
						$price = number_format($opt->price_sell_quantity * (100 - ($opt->discount_percentage ?? 0)) / 100, 2);
						@endphp

						<div class="col">
							<a href="{{ route('products.show',$opt->id) }}">
								<div class="farm-col card text-start p-3 {{ $opt->id === $farmProduct->id ? 'selected' : '' }}">
									<img src="{{ asset($opt->farm->image->path) }}" class="img-half-cover">
									<div class="card-body">
										<h5 class="card-title truncate-ellipsis">{{ $opt->farm->name }}, {{ $opt->farm->address->city }}</h5>
										<p>Vzdialenosť: <strong>{{ $opt->distance }}</strong> km</p>
										<p>Cena: <strong>{{ $price }} €</strong></p>
										<p class="mb-1 inline-center">{{ $opt->farm->delivery_available ? 'Aj s dovozom' : 'Iba osobný odber'}}
											<span class="material-icons ms-2">{{ $opt->farm->delivery_available ? 'directions_car' : 'directions_walk' }}</span>
										</p>
									</div>
								</div>
							</a>
						</div>
					@endforeach
				</div>
			</div>
		</div>
	</div>

	{{-- Popis od farmy --}}
	@if($farmProduct->farm_specific_description != null)
	<section class="mt-5" id="productDescription">
		<h2 class="mb-3">Popis</h2>
		<div class="description">
		{!! nl2br(e($farmProduct->farm_specific_description)) !!}
		</div>
	</section>
	@endif

	{{-- Recenzie --}}
	<section class="mt-5">
    	<h2 class="mb-3">Hodnotenia</h2>
    	<div class="ratings">
      		<div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
        		{{-- Priemer --}}
        		<div class="d-flex flex-wrap align-items-center gap-5">
          			<div class="d-flex align-items-center gap-4">
            			<div class="rating-tile display-4 text-center fw-bold">
              				{{ number_format($avgRating, 1, ',', '.') }}
            			</div>
            			<div>
              				<div class="stars mb-0">
								@for($i = 0; $i < $fullStars; $i++)
								<span class="material-icons star filled">star</span>
								@endfor

								@if($halfStars)
								<span class="material-icons star half-filled">star_half</span>
								@endif

								@for($i = 0; $i < $emptyStars; $i++)
								<span class="material-icons star empty">star_outline</span>
								@endfor
              				</div>
              				<div>Počet recenzií: {{ $allReviews->count() }}</div>
            			</div>
          			</div>

          			{{-- Prehľad hodnotení --}}
          			<div class="d-flex flex-column gap-2">
						@foreach([5,4,3,2,1] as $star)
						<div class="d-flex justify-content-between">
							<div class="stars mb-0">
							@for($i = 0; $i < $star; $i++)
								<span class="material-icons star filled">star</span>
							@endfor

							@for($i = 0; $i < 5 - $star; $i++)
								<span class="material-icons star empty">star_outline</span>
							@endfor
							</div>
							<span class="text-muted px-2">({{ $countByStar[$star] }})</span>
						</div>
						@endforeach
          			</div>
        		</div>

        		{{-- Napísať recenziu --}}
        		<div class="vertical-bottom">
					<button class="btn mt-2 custom-button btn-napisat-recenziu">Napísať recenziu</button>
				</div>
      		</div>

      		{{-- Zoznam recenzií --}}
			@foreach($allReviews as $rev)
			<div class="border-top pt-3 mt-3">
				<div class="d-flex justify-content-between align-items-center mb-1">
					<h3 class="review-title fw-bold">{{ $rev->title }}</h3>
					@php
					$rounded   = round($rev->rating * 2) / 2;
					$fullStars = floor($rounded);
					$halfStar  = ($rounded - $fullStars) === 0.5 ? 1 : 0;
					$emptyStars= 5 - $fullStars - $halfStar;
					@endphp

					<div class="stars mb-0">
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
				</div>
				<div class="d-flex gap-2">
					<img src="{{ asset(optional($rev->user->icon)->path ?? 'images/profile.png') }}" alt="Profilová fotka" height="21" width="21">
					<h4 class="text-muted mb-2">
						<small>{{ $rev->user->name }} | {{ $rev->created_at->format('j.n.Y') }}</small>
					</h4>
				</div>
				<p class="review-text">{{ $rev->text }}</p>
			</div>
			@endforeach

      		{{-- “Show more” if you need pagination here --}}
    	</div>
  	</section>

	@include('layout.partials.article-section', [
		'title' => 'Mohlo by vás zaujímať',
		'id' => 'carouselRow4',
		'id_arrow_left' => 'arrowLeft4',
		'id_arrow_right' => 'arrowRight4',
	])

	@include('layout.partials.ads-section')
@endsection