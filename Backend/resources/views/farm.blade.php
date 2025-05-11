@extends('layout.app')

@section('title', $farm->name)

@section('content')
    {{-- Banner --}}
    <div class="farm-banner mb-5">
        <img src="{{ asset($farm->image->path ?? null) }}"
             class="w-100" alt="{{ $farm->name }}">
    </div>

    {{-- Farm Info --}}
    <div class="farm-info mb-4">
        <h2 class="fw-bold d-inline-block me-3 mb-3">{{ $farm->name }}</h2>

        @if($deliveryAvailable)
            {{-- delivery info --}}
            <span class="me-3 no-wrap">
                <strong>Predpokladaná doba doručenia:</strong>
                {{ $avgDeliveryTime }} min
            </span>
            <span class="me-3 no-wrap">
                <strong>Doručenie od:</strong>
                {{ number_format($minDeliveryPrice, 2, ',', ' ') }} €
            </span>
        @endif
        <span class="me-3 no-wrap">
            <strong>Adresa:</strong>
            {{ $farm->address->city }}, {{ $distance }} km
        </span>

        {{-- star rating --}}
        <div class="d-flex yes-wrap h-gaps align-items-center mb-3">
            {{-- (you can drop the location icon if you don’t have it) --}}
            <div class="d-flex align-items-center gap-2">
                <span><strong>Hodnotenie:</strong></span>
                <div class="stars mb-0">
                    @php
                        $full   = floor($farmRating);
                        $half   = (($farmRating - $full) >= 0.5) ? 1 : 0;
                        $empty  = 5 - $full - $half;
                    @endphp

                    @for ($i = 0; $i < $full; $i++)
                        <span class="material-icons star filled">star</span>
                    @endfor

                    @if ($half)
                        <span class="material-icons star half-filled">star_half</span>
                    @endif

                    @for ($i = 0; $i < $empty; $i++)
                        <span class="material-icons star empty">star_outline</span>
                    @endfor

                    <span class="star-count ms-2">({{ $farmRating }})</span>
                </div>
            </div>
        </div>

        {{-- description --}}
        <p>{{ $farm->description }}</p>
    </div>

    {{-- Category Tabs --}}
    <div class="mb-5 d-flex flex-wrap gap-2">
        @foreach($tags as $tag)
            @php
                $n = $loop->iteration;
                if ($n === 1) { $i = ''; }
                elseif ($n < 5) { $i = $n - 1; }
                else { $i = $n + 1; }
            @endphp
            <a href="#carouselRow{{ $i }}">
            <button class="btn custom-button subcategory-btn">
                {{ $tag->name }}
            </button>
            </a>
        @endforeach
    </div>

    {{-- Admin “New” --}}
    @if(auth()->check() && auth()->user()->is_admin)
    <div class="new mb-4">
        <button class="edit-button custom-button w-100" onclick="openModal('newProductModal')">
            <span class="material-icons">add</span>
            <span class="px-3">Pridať produkt</span>
        </button>
    </div>
    @endif

    @php
        $isAdmin = auth()->check() && auth()->user()->is_admin;
    @endphp

    {{-- Each category section --}}
    @foreach($tags as $tag)
        @php
            // Massage your FarmProduct models into the shape the partial expects
            $products = collect($byTag[$tag->id] ?? [])->map(function($fp) use($canEdit) {
                $image = $fp->product->image;

                $effective = $fp->price_sell_quantity * (100 - ($fp->discount_percentage ?? 0)) / 100;
                $price = number_format($effective, 2) . ' €';
                $original = $fp->discount_percentage ? number_format($fp->price_sell_quantity, 2) . ' €' : null;

                return [
                    'id' => $fp->id,
                    'name' => $fp->product->name,
                    'description' => $fp->farm_specific_description,
                    'image' => $image->path,
                    'alt' => $image->name,
                    'price' => $price,
                    'original_price' => $original,
                    'price_per' => number_format($fp->price_per_unit, 2) . ' €/' . $fp->unit,
                    'price_per_unit'   => $fp->price_per_unit,
                    'discount' => $fp->discount_percentage,
                    'rating' => $fp->rating ?? 0,
                    'location' => $fp->farm->name . ', ' . ($fp->farm->address->city ?? ''),
                    'farm_id' => $fp->farm->id,
                    'sell_quantity' => $fp->sell_quantity,
                    'unit' => $fp->unit,
                    'availability' => $fp->availability,
                    'images' => $fp->images,
                    'edit_route'      => $canEdit
                                        ? route('products.update', $fp)
                                        : null,
                    'delete_route' => route('products.destroy', $fp)
                ];
            })->toArray();

            $n = $loop->iteration;
            if ($n === 1) { $i = ''; }
            elseif ($n < 5) { $i = $n - 1; }
            else { $i = $n + 1; }
        @endphp


        @include('layout.partials.product-section', [
            'title'          => $tag->name,
            'id'              => 'carouselRow'   . $i,
            'id_arrow_left'   => 'arrowLeft'     . $i,
            'id_arrow_right'  => 'arrowRight'    . $i,
            'products'       => $products,
            'canEdit'        => $canEdit,
        ])
    @endforeach


    @if($canEdit)
    {{-- NEW PRODUCT MODAL --}}
    <div id="newProductModal" class="custom-modal d-none">
        <div class="modal-content p-4 shadow position-relative">
        <button type="button"
                class="btn material-icons position-absolute end-0 top-0 m-2"
                aria-label="Close"
                onclick="closeModal('newProductModal')">
            close
        </button>

        <h2 class="text-center fw-bold my-4">PRIDAŤ PRODUKT</h2>

        @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
        @endif

        <form action="{{ route('farms.products.store', $farm) }}"
                method="POST"
                enctype="multipart/form-data">
            @csrf

            {{-- Photos --}}
            <h5 class="fw-bold text-center mb-3">Nahrať fotky produktu</h5>

            {{-- Preview of selected images --}}
            <div id="image-preview" class="d-flex flex-wrap gap-2 mb-3"></div>

            {{-- Hidden real file input --}}
            <input type="file"
                id="hiddenFileInput"
                accept="image/*"
                multiple
                class="d-none">

            {{-- Add image button --}}
            <div class="upload-section d-flex align-items-center gap-2 mb-4">
                <button class="btn custom-button subcategory-btn" type="button" id="addImageBtn">
                    Pridať
                </button>
            </div>

            {{-- Place hidden copies of all selected files here before submit --}}
            <div id="hiddenInputsContainer"></div>

            {{-- Show error (in case validation fails) --}}
            @error('images.*')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror

            @error('images')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror

            {{-- Name --}}
            <div class="mb-3">
                <label class="form-label">Názov produktu</label>
                <select name="name"
                        class="form-select @error('name') is-invalid @enderror">
                    <option value="" disabled {{ old('name')?'':'selected' }}>
                    — Vyberte existujúci názov —
                    </option>
                    @foreach($productNames as $pn)
                    <option value="{{ $pn }}"
                        {{ old('name')== $pn ? 'selected':'' }}>
                        {{ $pn }}
                    </option>
                    @endforeach
                </select>
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Price --}}
            <div class="mb-3">
            <label class="form-label">Cena (€/jednotka)</label>
            <input type="number"
                    name="price_per_unit"
                    step="0.01"
                    value="{{ old('price_per_unit') }}"
                    class="form-control @error('price_per_unit') is-invalid @enderror">
                    @error('price_per_unit')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
            </div>

            {{-- Sale type --}}
            <div class="mb-3">
            <label class="form-label">Typ predaja</label>
            <select name="sale_type" class="form-select">
                <option value="kg" {{ old('sale_type')=='kg'?'selected':'' }}>Na hmotnosť (kg)</option>
                <option value="ks" {{ old('sale_type')=='ks'?'selected':'' }}>Po kusoch (ks)</option>
                <option value="l"  {{ old('sale_type')=='l' ?'selected':'' }}>Na objem (l)</option>
            </select>
            </div>

            {{-- Amount --}}
            <div class="mb-3">
            <label class="form-label">Predávané množstvo</label>
            <input type="number"
                    name="amount"
                    step="0.01"
                    value="{{ old('amount') }}"
                    class="form-control @error('amount') is-invalid @enderror"
                    placeholder="g / l / ks">
                    @error('amount')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
            </div>

            {{-- Discount --}}
            <div class="mb-3">
            <label class="form-label">Zľava (%)</label>
            <input type="number"
                    name="discount_percentage"
                    value="{{ old('discount_percentage',0) }}"
                    class="form-control @error('discount_percentage') is-invalid @enderror">
                    @error('discount_percentage')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
            </div>

            {{-- Description --}}
            <div class="mb-3">
            <label class="form-label">Popis produktu</label>
            <textarea name="description"
                        class="form-control @error('description') is-invalid @enderror"
                        rows="4"
                        placeholder="Zadajte popis...">@error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror{{ old('description') }}</textarea>
            </div>

            <div class="text-center mt-4">
            <button type="submit"
                    class="btn btn-confirm custom-button subcategory-btn w-100">
                Vytvoriť produkt
            </button>
            </div>
        </form>
        </div>
    </div>
    @endif

    @if($canEdit)
    {{-- EDIT PRODUCT MODAL --}}
    <div id="editProductModal" class="custom-modal d-none">
        <div class="modal-content p-4 shadow position-relative">
        <button type="button"
                class="btn material-icons position-absolute end-0 top-0 m-2"
                aria-label="Close"
                onclick="closeModal('editProductModal')">
            close
        </button>

        <h2 class="text-center fw-bold my-4">UPRAVIŤ PRODUKT</h2>

        @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
        @endif

        <form id="editProductForm"
                method="POST"
                enctype="multipart/form-data">
            @csrf
            @method('PUT')

            {{-- Photos: you can fill existing previews with JS if you like --}}
            <h5 class="fw-bold text-center mb-3">Upraviť fotky produktu</h5>

            <div id="existing-image-preview" class="d-flex flex-wrap gap-2 mb-3">
                {{-- Will be populated with JS --}}
            </div>

            {{-- Preview of newly selected images --}}
            <div id="edit-image-preview" class="d-flex flex-wrap gap-2 mb-3"></div>

            {{-- Hidden real input --}}
            <input type="file"
                id="editHiddenFileInput"
                accept="image/*"
                multiple
                class="d-none">

            {{-- Button to trigger input --}}
            <div class="upload-section d-flex align-items-center gap-2 mb-4">
                <button class="btn custom-button subcategory-btn" type="button" id="editAddImageBtn">
                    Pridať
                </button>
            </div>

            {{-- Container for hidden inputs --}}
            <div id="editHiddenInputsContainer"></div>

            {{-- Name --}}
            <div class="mb-3">
            <label class="form-label">Názov produktu</label>
            <input id="edit_name"
                    type="text"
                    name="name"
                    class="form-control @error('name') is-invalid @enderror"
                    readonly
                    value="{{ old('name') }}">
                    @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
            </div>

            {{-- Price --}}
            <div class="mb-3">
            <label class="form-label">Cena (€/jednotka)</label>
            <input id="edit_price"
                    type="number"
                    name="price_per_unit"
                    value="{{ old('price_per_unit') }}"
                    step="0.01"
                    class="form-control @error('price_per_unit') is-invalid @enderror">
                    @error('price_per_unit')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
            </div>

            {{-- Sale type --}}
            <div class="mb-3">
            <label class="form-label">Typ predaja</label>
            <select id="edit_sale_type" name="sale_type" class="form-select">
                <option value="kg" {{ old('sale_type')=='kg'?'selected':'' }}>Na hmotnosť (kg)</option>
                <option value="ks" {{ old('sale_type')=='ks'?'selected':'' }}>Po kusoch (ks)</option>
                <option value="l" {{ old('sale_type')=='l'?'selected':'' }}>Na objem (l)</option>
            </select>
            </div>

            {{-- Amount --}}
            <div class="mb-3">
            <label class="form-label">Predávané množstvo</label>
            <input id="edit_amount"
                    type="number"
                    name="amount"
                    value="{{ old('amount') }}"
                    step="0.01"
                    class="form-control @error('amount') is-invalid @enderror">
                    @error('amount')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
            </div>

            {{-- Discount --}}
            <div class="mb-3">
            <label class="form-label">Zľava (%)</label>
            <input id="edit_discount"
                    type="number"
                    name="discount_percentage"
                    value="{{ old('discount_percentage') }}"
                    class="form-control @error('discount_percentage') is-invalid @enderror">
                    @error('discount_percentage')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
            </div>

            {{-- Description --}}
            <div class="mb-3">
            <label class="form-label">Popis produktu</label>
            <textarea id="edit_description"
                        name="description"
                        class="form-control  @error('description') is-invalid @enderror"
                        rows="4"
                        placeholder="Zadajte popis...">@error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror{{ old('description') }}</textarea>
            </div>

            <div class="d-flex justify-content-between align-items-center mb-3">
            <div class="form-check">
                <input id="edit_unavailable" type="checkbox" name="unavailable" class="form-check-input">
                <label class="form-check-label" for="edit_unavailable">
                Produkt nedostupný
                </label>
            </div>
            <button type="button"
                class="btn btn-danger"
                id="editDeleteBtn">
            Odstrániť produkt
            </button>
            </div>

            <div class="text-center mt-4">
            <button type="submit"
                    class="btn btn-confirm custom-button subcategory-btn w-100">
                Uložiť zmeny
            </button>
            </div>
        </form>
        </div>
    </div>
    @endif

    @include('layout.partials.confirm_popup')
@endsection
