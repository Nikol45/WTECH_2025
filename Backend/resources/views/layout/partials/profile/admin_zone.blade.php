<div class="admin-zone p-5 border rounded bg-white">
    <h3 class="fw-bold mb-4">Administrátorská zóna</h3>

    {{-- ===================== FARMS ===================== --}}
    <div class="mb-4 admin-products">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <h4 class="mb-1">Vaše farmy</h4>

            {{-- ✚ modal: pridať farmu --}}
            @if(!$farms->isEmpty())
            <button class="btn btn-secondary btn-sm d-flex align-items-center gap-1"
                    onclick="openEditModal({
                        title: 'Pridať farmu',
                        submitUrl: '{{ route('admin.farm.store') }}',
                        enctype: 'multipart/form-data',
                        fields: [
                            { label: 'Obrázok', name: 'image', type: 'file', required: false },
                            { label: 'Názov farmy', name: 'name', required: true },
                            { label: 'Popis', name: 'description', type: 'textarea' },

                            { label: 'Ulica', name: 'street', required: true },
                            { label: 'Číslo domu', name: 'street_number', required: true },
                            { label: 'Mesto', name: 'city', required: true },
                            { label: 'PSČ', name: 'zip_code', required: true },
                            { label: 'Krajina', name: 'country', required: true }
                        ]
                    })">
                <span class="material-icons">add</span> Pridať farmu
            </button>
            @endif
        </div>

        @if($farms->isEmpty())
            {{-- Empty‑state --}}
            <div class="text-center text-muted py-5">
                <i class="material-icons fs-1 d-block mb-2">house</i>
                <p class="mb-3">Zatiaľ nemáte žiadne farmy.</p>
                <button class="btn btn-outline-primary"
                        onclick="openEditModal({
                        title: 'Pridať farmu',
                        submitUrl: '{{ route('admin.farm.store') }}',
                        enctype: 'multipart/form-data',

                        fields: [
                            { label: 'Obrázok', name: 'image', type: 'file', required: false },
                            { label: 'Názov farmy', name: 'name', required: true },
                            { label: 'Popis', name: 'description', type: 'textarea' },

                            { label: 'Ulica', name: 'street', required: true },
                            { label: 'Číslo domu', name: 'street_number', required: true },
                            { label: 'Mesto', name: 'city', required: true },
                            { label: 'PSČ', name: 'zip_code', required: true },
                            { label: 'Krajina', name: 'country', required: true }
                        ]
                    })">
                    Pridať farmu
                </button>
            </div>

            <button class="btn btn-outline-primary"
                onclick="openEditModal({
                    title: 'Pridať farmu',
                    submitUrl: '{{ route('admin.farm.store') }}',
                    enctype: 'multipart/form-data',
                    fields: [
                        { label: 'Obrázok', name: 'image', type: 'file', accept: 'image/*', required: true },
                        { label: 'Názov farmy', name: 'name', required: true },
                        { label: 'Popis', name: 'description', type: 'textarea' },
                        { label: 'Ulica', name: 'street', required: true },
                        { label: 'Číslo domu', name: 'street_number', required: true },
                        { label: 'Mesto', name: 'city', required: true },
                        { label: 'PSČ', name: 'zip_code', required: true },
                        { label: 'Krajina', name: 'country', required: true }
                    ]
                })">
                Pridať farmu
            </button>


        @else
            {{-- Carousel --}}
            <div class="arrows-around position-relative mt-4">
                <div class="arrows {{ $farms->count() < 3 ? 'd-none' : '' }}">
                    <button class="btn custom-button arrow-left" type="button" id="arrowLeftFarms">
                        <span class="material-icons">chevron_left</span>
                    </button>
                    <button class="btn custom-button arrow-right" type="button" id="arrowRightFarms">
                        <span class="material-icons">chevron_right</span>
                    </button>
                </div>
                <div class="products-carousel-container px-4" id="carouselContainerFarms">
                    <div class="no-scrollbar row row-cols-1 row-cols-xl-2 flex-nowrap" id="carouselRowFarms">
                        @foreach($farms as $farm)
                            <div class="col flex-shrink-0">
                                <div class="d-flex gap-2">
                                    <!-- Karta farmy -->
                                    <a href="{{ route('farms.show', $farm->id) }}" class="admin-card p-3 flex-grow-1">
                                        <div class="row g-sm-4">
                                            <div class="col-sm-5">
                                                <img src="{{ asset(optional($farm->image)->path) }}" alt="{{ $farm->name }}" class="img-fluid rounded">
                                            </div>
                                            <div class="col-sm-7 d-flex flex-column justify-content-center overflow-hidden">
                                                <h5 class="card-title truncate-ellipsis mt-sm-2 mb-1">{{ $farm->name }}</h5>
                                                <h6 class="card-location truncate-ellipsis text-muted m-0">{{ $farm->address->city }}</h6>
                                            </div>
                                        </div>
                                    </a>

                                    <!-- Akcie -->
                                    <div class="d-flex flex-column gap-2 align-items-center">
                                        {{-- DELETE - potvrdenie --}}
                                        <button class="btn custom-button" title="Zmazať"
                                                onclick="openConfirmModal({
                                                    title: 'Zmazať farmu',
                                                    text : 'Naozaj chcete zmazať farmu &quot;{{ $farm->name }}&quot;?',
                                                    submitUrl: '{{ route('farms.destroy', $farm->id) }}'
                                                })">
                                            <span class="material-icons">delete</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif
    </div>

    {{-- ===================== ARTICLES ===================== --}}
    <div class="mb-4 package-products">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <h4 class="mb-1">Vaše články</h4>

            @if(!$articles->isEmpty())
            <button class="btn btn-secondary btn-sm d-flex align-items-center gap-1"
                    onclick="openEditModal({
                        title: 'Pridať článok',
                        submitUrl: '{{ route('admin.article.store') }}',
                        enctype: 'multipart/form-data',
                        fields: [
                            { label: 'Nadpis',   name: 'title', required: true },
                            { label: 'Text',     name: 'text',  type: 'textarea', required: true },
                            { label: 'Obrázok',  name: 'image', type: 'file' }
                        ]
                    })">
                <span class="material-icons">add</span> Pridať článok
            </button>
            @endif
        </div>

        @if($articles->isEmpty())
            <div class="text-center text-muted py-5">
                <i class="material-icons fs-1 d-block mb-2">menu_book</i>
                <p class="mb-3">Zatiaľ nemáte žiadne články.</p>
                <button class="btn btn-outline-primary"
                        onclick="openEditModal({
                        title: 'Pridať článok',
                        submitUrl: '{{ route('admin.article.store') }}',
                        enctype: 'multipart/form-data',
                        fields: [
                            { label: 'Nadpis',   name: 'title', required: true },
                            { label: 'Text',     name: 'text',  type: 'textarea', required: true },
                            { label: 'Obrázok',  name: 'image', type: 'file' }
                        ]
                    })">
                    Pridať článok
                </button>
            </div>
        @else
            <div class="arrows-around position-relative mt-4">
                <div class="arrows {{ $articles->count() < 3 ? 'd-none' : '' }}">
                    <button class="btn custom-button arrow-left" type="button" id="arrowLeftArticles">
                        <span class="material-icons">chevron_left</span>
                    </button>
                    <button class="btn custom-button arrow-right" type="button" id="arrowRightArticles">
                        <span class="material-icons">chevron_right</span>
                    </button>
                </div>
                <div class="products-carousel-container px-4" id="carouselContainerArticles">
                    <div class="no-scrollbar row row-cols-1 row-cols-sm-2 row-cols-xl-3 g-3 mb-3 flex-nowrap" id="carouselRowArticles">
                        @foreach($articles as $article)
                            <div class="col flex-shrink-0">
                                <div class="d-flex align-items-start gap-2">
                                    <div class="card text-start p-3">
                                        <img src="{{ $article->image ? asset($article->image->path) : asset('images/empty.png') }}" alt="article image" class="card-img">
                                        <div class="card-body mt-2">
                                            <a href="{{ route('articles.show', $article->id) }}" class="text-decoration-none">
                                                <h5 class="card-title truncate-ellipsis">{{ $article->title }}</h5>
                                            </a>
                                            <div class="d-flex align-items-center gap-2">
                                                <span class="material-icons">account_circle</span>
                                                @php
                                                    $fullName = $article->user->name ?? 'Autor neznámy';
                                                    $parts = explode(' ', $fullName);
                                                    $firstLetter = mb_substr($parts[0], 0, 1);
                                                    $lastName = end($parts);
                                                @endphp

                                                <span class="text-muted">{{ $firstLetter }}. {{ $lastName }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="d-flex flex-column gap-2 align-items-center">
                                        {{-- MODAL EDIT – článok --}}
                                        <button class="btn btn-light border"
                                                title="Upraviť"
                                                onclick="openEditModal({
                                                    title: 'Upraviť článok',
                                                    submitUrl: '{{ route('articles.update', $article->id) }}',
                                                    method: 'PUT',
                                                    enctype: 'multipart/form-data',
                                                    fields: [
                                                        { label: 'Nadpis',  name: 'title',
                                                          value: '{{ $article->title }}',  required: true },

                                                        { label: 'Text',    name: 'text',  type: 'textarea',
                                                          value: @json($article->text),    required: true },

                                                        { label: 'Obrázok', name: 'image', type: 'file' }
                                                    ]
                                                })">
                                            <span class="material-symbols-outlined">edit_square</span>
                                        </button>

                                        {{-- DELETE - potvrdenie --}}
                                        <button class="btn btn-light border" title="Zmazať"
                                                onclick="openConfirmModal({
                                                    title: 'Zmazať článok',
                                                    text : 'Naozaj chcete zmazať článok &quot;{{ $article->title }}&quot;?',
                                                    submitUrl: '{{ route('articles.destroy', $article->id) }}'
                                                })">
                                            <span class="material-icons">delete</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif
    </div>

    {{-- ===================== REVIEWS ===================== --}}
    <div class="mb-4 admin-products">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <h4 class="mb-1">Najnovšie recenzie</h4>
        </div>

        @if($reviews->isEmpty())
            <div class="text-center text-muted py-5">
                <i class="material-icons fs-1 d-block mb-2">reviews</i>
                <p class="mb-3">Zatiaľ nemáte žiadne recenzie na svoje produkty.</p>
            </div>
        @else
            <div class="arrows-around position-relative mt-4">
                <div class="arrows {{ $reviews->count() < 3 ? 'd-none' : '' }}">
                    <button class="btn custom-button arrow-left" type="button" id="arrowLeftReviews">
                        <span class="material-icons">chevron_left</span>
                    </button>
                    <button class="btn custom-button arrow-right" type="button" id="arrowRightReviews">
                        <span class="material-icons">chevron_right</span>
                    </button>
                </div>
                <div class="products-carousel-container px-4" id="carouselContainerReviews">
                    <div class="no-scrollbar row row-cols-2 row-cols-sm-1 row-cols-xxl-2 flex-nowrap" id="carouselRowReviews">
                        @foreach($reviews as $review)
                            {{-- Mobile view --}}
                            <div class="col flex-shrink-0 mobile-only">
                                <div class="admin-card p-3 flex-column align-items-center text-center position-relative">
                                    <div class="w-100 text-center">
                                        <small class="text-muted">{{ $review->created_at->format('j. n. Y') }}</small>
                                    </div>
                                    <img src="{{ asset($review->farm_product->product->image->path) }}" alt="{{ $review-> farm_product->product->name }}" class="img-fluid rounded mt-2" style="width: 80px;">
                                    <a href="{{ route('products.show', $review->farm_product_id) }}" class="card-title truncate-ellipsis d-block">
                                        {{ $review-> farm_product->product->name }} {{ $review->quantity }}
                                    </a>
                                    <p>{{ $review->text }}</p>
                                    <div class="d-flex justify-content-center align-items-center">
                                        @for($i=1; $i<=5; $i++)
                                            <span class="material-icons star {{ $i <= $review->rating ? 'filled' : 'empty' }}">
                                                {{ $i <= $review->rating ? 'star' : 'star_outline' }}
                                            </span>
                                        @endfor
                                    </div>
                                    @if(!$review->reply && $review->farm_product->farm->user_id === auth()->id())
                                        <a href="#" class="btn btn-outline-primary btn-sm my-2"
                                           onclick="openEditModal({
                                           title: 'Odpovedať na recenziu',
                                           submitUrl: '{{ route('admin.review.reply', $review->id) }}',
                                           fields: [
                                               { label: 'Vaša odpoveď', name: 'reply', type: 'textarea', required: true }
                                           ]
                                       })">
                                            Odpovedať
                                        </a>
                                        <small class="text-danger">Čaká na odpoveď</small>
                                    @elseif($review->reply)
                                        <small class="text-success">Zodpovedané.</small>
                                    @else
                                        <small class="text-muted">Zatiaľ bez odpovede</small>
                                    @endif
                                </div>
                            </div>
                            {{-- Desktop view --}}
                            <div class="col flex-shrink-0 desktop-only">
                                <div class="admin-card p-3 d-flex flex-row align-items-center">
                                    <img src="{{ asset($review->farm_product->product->image->path) }}" alt="{{ $review-> farm_product->product->name }}" class="img-fluid rounded me-1" style="width: 80px;">
                                    <div class="flex-grow-1">
                                        <a href="{{ route('products.show', $review->farm_product_id) }}" class="card-title truncate-ellipsis d-block">
                                            {{ $review-> farm_product->product->name }}
                                        </a>
                                        <p class="mb-2">{{ $review->title }}</p>
                                        <div class="d-flex align-items-center mb-2">
                                            @for($i=1; $i<=5; $i++)
                                                <span class="material-icons star {{ $i <= $review->rating ? 'filled' : 'empty' }}">
                                                    {{ $i <= $review->rating ? 'star' : 'star_outline' }}
                                                </span>
                                            @endfor
                                        </div>
                                    </div>
                                    <div class="d-flex flex-column align-items-end text-end ms-auto my-2">
                                        @if(!$review->reply && $review-> farm_product->farm->user_id === auth()->id())
                                            <a href="{{ route('products.show', $review->farm_product_id) }}#review{{ $review->id }}" class="btn btn-outline-primary btn-sm my-2">
                                                 Odpovedať
                                            </a>
                                            <small class="text-danger">Čaká na odpoveď</small>
                                        @elseif($review->reply)
                                            <small class="text-success">Zodpovedané.</small>
                                        @else
                                            <small class="text-muted">Zatiaľ bez odpovede</small>
                                        @endif

                                        <small class="text-muted mt-2">{{ $review->created_at->format('j. n. Y') }}</small>
                                    </div>

                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

@include('layout.partials.confirm_popup')
