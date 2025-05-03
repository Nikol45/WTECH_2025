@extends('layout.app')
@section('title','Váš košík')
@section('content')


@include('layout.partials.nav_steps', [
    'context' => 'cart',
    'active'  => 'items'
])

@if($cartByFarm->isEmpty())
  	<p>Váš košík je prázdny.</p>
@else

	<div class="row mb-2">
		<div class="col-12">
			<a href="{{ route('homepage') }}" class="d-flex align-items-center gap-2 text-decoration-none">
				<span class="material-icons">arrow_back</span>
				<span>Späť do obchodu</span>
			</a>
		</div>
	</div>

	<form method="POST" action="{{ route('cart.destroy') }}" id="removeSelectedForm">
		@csrf
		@method('DELETE')

		<div class="oznacenie d-flex justify-content-between align-items-center mb-3">
			<div class="form-check">
				<input class="form-check-input" type="checkbox" id="checkAllCart">
				<label class="form-check-label ms-2" for="checkAllCart">Označiť všetko</label>
			</div>
			<button type="submit" class="btn btn-link custom-button text-danger d-flex align-items-center p-0 text-decoration-none">Odstrániť označené<span class="material-icons ms-2">delete</span></button>
		</div>

		@foreach($cartByFarm as $farmId => $items)

			@php
				$farm = $items->first()['fp']->farm;
			@endphp

			<div class="package p-3 mb-4">
				<h5 class="fw-bold mb-3">Zásielka z {{ $farm->name }}</h5>

				@foreach($items as $item)

				@php
					$fp = $item['fp'];
					$qty = $item['quantity'];
					$prod = $fp->product;
					$unitPrice = $fp->discount_percentage ? $fp->price_sell_quantity * (100 - $fp->discount_percentage)/100 : $fp->price_sell_quantity;
					$lineTotal = number_format($unitPrice * $qty,2).' €';
				@endphp

				<div class="row cart-product align-items-center mb-3">
					<div class="col-auto">
						<input class="form-check-input" type="checkbox" name="items[]" value="{{ $fp->id }}">
					</div>
					<div class="col-auto">
						<img src="{{ asset($prod->image->path) }}" alt="{{ $prod->image->name }}" width="60" height="35">
					</div>
					<div class="col">
						<a href="{{ route('products.show',$fp->id) }}" class="mb-1 fw-bold">{{ $prod->name }}, {{ $fp->sell_quantity }}{{ $fp->unit }}</a>
					</div>
					<div class="col-auto mx-auto mt-1 gap-3 justify-content-center d-flex no-wrap">
						<span class="quantity me-3 d-flex align-items-center">
							<button type="button" class="quantity-button minus btn custom-button" data-fpid="{{ $fp->id }}">−</button>
							<div class="input-group">
								<input type="number" class="quantity-input form-control text-center" data-fpid="{{ $fp->id }}" data-unit-price="{{ $unitPrice }}" value="{{ $qty }}" min="1" step="1">
							</div>
							<button type="button" class="quantity-button plus btn custom-button" data-fpid="{{ $fp->id }}">+</button>
						</span>
						<span class="fw-bold inline-center line-total" data-fpid="{{ $fp->id }}">{{ $lineTotal }}</span>
					</div>
				</div>
				@endforeach

				<div class="d-flex justify-content-end">
					@if(! $farm->delivery_available)
						<div class="warning py-1 px-2 d-inline-flex align-items-center gap-2 w-auto" role="alert"><span class="material-icons ms-2">warning</span>LEN OSOBNÉ VYZDVIHNUTIE</div>
					@elseif($farm->min_delivery_price !== null)
						<div class="text-end text-muted">Doručenie od {{ number_format($farm->min_delivery_price,2) }} €</div>
					@endif
				</div>
			</div>
		@endforeach

		@php
		$grand = collect($cartByFarm)
			->flatten(1)
			->sum(fn($item) => (
			($item['fp']->discount_percentage ? $item['fp']->price_sell_quantity * (100-$item['fp']->discount_percentage)/100 : $item['fp']->price_sell_quantity) * $item['quantity']
		));

		$noDPH = $grand/1.2
		@endphp

		<div class="suhrn d-flex flex-column flex-sm-row align-items-sm-center justify-content-sm-between gap-3 mb-4">
			<div>
				<h4 class="fw-bold">Celkom na úhradu:<span id="cart-grand-total">{{ number_format($grand,2) }}</span> €</h4>
				<div class="text-muted fs-5">Cena bez DPH: <span id="cart-no-dph">{{ number_format($noDPH,2) }}</span> €</div>
			</div>
			<a href="{{ route('cart-form.index') }}" class="btn custom-button btn-pridat-do-kosika px-4 py-2">Prejsť k údajom</a>
		</div>

	</form>
@endif

@endsection
