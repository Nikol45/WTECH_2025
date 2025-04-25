@php use Illuminate\Support\Str; @endphp
<header>
    <script>
        window.isLoggedIn = @json(Auth::check()); // zatial najlepsie riesenie
        window.routes = {
            logout: @json(route('logout'))
        };
    </script>
    <form id="logoutForm" action="{{ route('logout') }}" method="POST" class="d-none">
        @csrf
    </form>

    <!-- Pop-up modály pre prihlásenie a registráciu -->
    <div class="modal-container">
        @auth
            <form id="logoutForm" action="{{ route('logout') }}" method="POST" class="d-none">
                @csrf
            </form>
        @endauth

        <div id="overlayBackground" class="modal-overlay d-none"></div>

        {{-- ================== MODÁL: PRIHLÁSENIE ================== --}}
        <div id="loginModal" class="custom-modal d-none">
                <div class="modal-content p-4 border-0 shadow position-relative">
                    <button type="button" class="btn material-icons position-absolute end-0 top-0 m-2"
                            aria-label="Close" onclick="closeModal('loginModal')">
                        close
                    </button>

                    <h2 class="mb-4 text-center text-uppercase modal-title">Prihlásenie</h2>

                    <form id="loginForm" method="POST" action="{{ route('login') }}">
                        @csrf

                        {{-- Používateľské meno alebo e-mail --}}
                        <div class="input-group mb-3">
                <span class="input-group-text icon-wrapper border-end-0">
                    <span class="material-icons">person_outline</span>
                </span>
                            <input
                                type="text"
                                name="email_or_username"
                                class="form-control border-start-0 input-custom"
                                placeholder="Používateľské meno alebo e-mail"
                                required
                            >
                        </div>

                        {{-- Heslo --}}
                        <div class="input-group mb-3">
                <span class="input-group-text icon-wrapper border-end-0">
                    <span class="material-icons">lock_outline</span>
                </span>
                            <input
                                type="password"
                                name="password"
                                class="form-control border-start-0 input-custom"
                                placeholder="Heslo"
                                required
                            >
                        </div>

                        {{-- Zapamätať prihlásenie --}}
                        <div class="form-check mb-3">
                            <input
                                class="form-check-input"
                                type="checkbox"
                                name="remember"
                                id="rememberMe"
                            >
                            <label class="form-check-label text-modal" for="rememberMe">
                                Zapamätať prihlásenie
                            </label>
                        </div>

                        {{-- Sem sa vypíšu prípadné AJAX chyby --}}
                        <div id="loginErrors" class="text-danger mb-2"></div>

                        <button type="submit" class="btn w-100 mb-3 btn-confirm">
                            Prihlásiť sa
                        </button>

                        <div class="text-center mb-2">
                            <a
                                href="#"
                                class="modal-link"
                                onclick="alert('Funkcia zabudnutého hesla nie je implementovaná.')"
                            >
                                Zabudnuté heslo
                            </a>
                        </div>

                        <div class="text-center">
                            <small class="text-modal">
                                Nemáte účet?
                                <a
                                    href="#"
                                    class="modal-link"
                                    onclick="switchModal('loginModal','registrationModal')"
                                >
                                    Zaregistrujte sa.
                                </a>
                            </small>
                        </div>
                    </form>
                </div>
            </div>


            {{-- ================== MODÁL: REGISTRÁCIA ================== --}}
        <div id="registrationModal" class="custom-modal d-none">
                <div class="modal-content p-4 border-0 shadow position-relative">
                    <button type="button" class="btn material-icons position-absolute end-0 top-0 m-2"
                            aria-label="Close" onclick="closeModal('registrationModal')">
                        close
                    </button>

                    <h2 class="mb-4 text-center text-uppercase modal-title">Registrácia</h2>

                    <form id="registrationForm" method="POST" action="{{ route('register') }}">
                        @csrf

                        {{-- meno --}}
                        <div class="input-group mb-3">
                <span class="input-group-text icon-wrapper border-end-0">
                    <span class="material-icons">person_outline</span>
                </span>
                            <input type="text" name="name"
                                   class="form-control border-start-0 input-custom"
                                   placeholder="Používateľské meno"
                                   required>
                        </div>

                        {{-- email --}}
                        <div class="input-group mb-3">
                <span class="input-group-text icon-wrapper border-end-0">
                    <span class="material-icons">mail_outline</span>
                </span>
                            <input type="email" name="email"
                                   class="form-control border-start-0 input-custom"
                                   placeholder="E-mail"
                                   required>
                        </div>

                        {{-- heslo --}}
                        <div class="input-group mb-3">
                <span class="input-group-text icon-wrapper border-end-0">
                    <span class="material-icons">lock_outline</span>
                </span>
                            <input type="password" name="password"
                                   class="form-control border-start-0 input-custom"
                                   placeholder="Heslo"
                                   required>
                        </div>

                        {{-- potvrdenie hesla --}}
                        <div class="input-group mb-3">
                <span class="input-group-text icon-wrapper border-end-0">
                    <span class="material-icons">lock</span>
                </span>
                            <input type="password" name="password_confirmation"
                                   class="form-control border-start-0 input-custom"
                                   placeholder="Potvrdenie hesla"
                                   required>
                        </div>

                        {{-- terms --}}
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" name="terms" id="termsCheck">
                            <label class="form-check-label text-modal" for="termsCheck">
                                Súhlasím s <a href="#" class="modal-link">podmienkami a zásadami</a>
                            </label>
                        </div>

                        {{-- sem vypíšeme AJAX chyby --}}
                        <div id="registrationErrors" class="text-danger mb-2"></div>

                        <button type="submit" class="btn w-100 mb-3 btn-confirm">
                            Registrovať sa
                        </button>

                        <div class="text-center">
                            <small class="text-modal">
                                Máte už účet?
                                <a href="#" class="modal-link"
                                   onclick="switchModal('registrationModal','loginModal')">
                                    Prihláste sa.
                                </a>
                            </small>
                        </div>
                    </form>
                </div>
            </div>

        {{-- ================== MODÁL: VÝBER PRIHLÁSENIA ================== --}}
        <div id="loginChoiceModal" class="custom-modal d-none">
            <div class="modal-content p-4 border-0 shadow position-relative">
                <button type="button" class="btn material-icons position-absolute end-0 top-0 m-2" aria-label="Close" onclick="closeModal('loginChoiceModal')">
                    close
                </button>

                <h2 class="mb-4 text-center text-uppercase modal-title">Chcete sa prihlásiť?</h2>

                <button type="button" class="btn w-100 mb-3 btn-confirm" onclick="switchModal('loginChoiceModal','loginModal')">
                    Prihlásiť sa do účtu
                </button>

                <div class="text-center">
                    <a href="{{ route('guest.browse') }}" class="modal-link">Nakupovať ako hosť</a>
                </div>
            </div>
        </div>


    </div>

    <!-- Top bar navigačný: logo, vyhľadávanie, ikonky -->
    <nav class="top-bar py-3">
        <div class="container-fluid custom-fluid">
            <div class="d-flex flex-wrap align-items-center justify-content-between">

                <!-- Logo -->
                <a href="{{ route('homepage') }}" class="d-flex align-items-center flex-shrink-0 me-3 text-decoration-none custom-logo">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo" width="30" height="30" class="me-2">
                    <h2 class="mb-0 custom-logo">Zdvora.sk</h2>
                </a>

                <!-- Search -->
                <div class="custom-search-container d-flex justify-content-start flex-grow-1 mx-3 my-2 my-md-0">
                    <form action="{{ route('products.index') }}" method="GET" class="position-relative">
                        <input type="text" name="q" class="form-control pe-5" placeholder="Vyhľadávanie..." value="{{ request('q') }}">
                        <button class="btn custom-button position-absolute top-50 end-0 translate-middle-y me-2 p-0" type="submit">
                            <span class="material-icons">search</span>
                        </button>
                    </form>
                </div>

                <!-- Ikonky -->
                <div class="custom-ikonky d-flex justify-content-end align-items-center gap-3 mt-2 mt-md-0 flex-shrink-0 ms-3">
                    <a href="{{ route('favourites.index') }}" class="btn custom-button p-0 ms-2">
                        <span class="material-icons">favorite</span>
                    </a>
                    <a href="{{ route('cart-items.index') }}" class="btn custom-button p-0 ms-2">
                        <span class="material-icons">shopping_cart</span>
                    </a>
                    <div class="dropdown text-center ms-2">
                        <button class="btn custom-button p-0" id="profileBtn">
                            <span class="material-icons">account_circle</span>
                        </button>
                        <ul class="dropdown-menu custom-dropdown-menu" id="profileMenu"></ul>
                    </div>
                </div>

                <!-- Hamburger menu -->
                <button class="btn custom-button d-md-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobileMenu" aria-controls="mobileMenu">
                    <span class="material-icons">menu</span>
                </button>
            </div>
        </div>
    </nav>

    <!-- Sidebar miesto ikoniek -->
    <div class="offcanvas offcanvas-end" tabindex="-1" id="mobileMenu" aria-labelledby="mobileMenuLabel">
        <div class="offcanvas-header">
            <h2 class="offcanvas-title fw-bold" id="mobileMenuLabel">Menu</h2>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <form action="{{ route('products.index') }}" method="GET" class="mb-3">
                <input type="text" name="q" class="form-control" placeholder="Vyhľadávanie..." value="{{ request('q') }}">
                <button class="btn custom-button subcategory-btn mt-2" type="submit">Hľadať</button>
            </form>
            <ul class="list-unstyled">
                <li class="mb-3 fw-bold fs-5"><a href="{{ route('favourites.index') }}">Obľúbené</a></li>
                <li class="mb-3 fw-bold fs-5"><a href="{{ route('cart-items.index') }}">Košík</a></li>
                <ul class="list-unstyled" id="mobileProfileMenu"></ul>
            </ul>
        </div>
    </div>

    <!-- Kategórie -->
    <div class="categories-bar custom-fluid" id="categoriesBar">
        <div class="categories-container">
            <ul class="categories-list list-unstyled">
                @foreach ($navCategories as $category)
                    <li class="category-item dropdown-holder">
                        <button  class="btn category-btn dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                            <img src="{{ asset('images/category/' . strtolower(Str::slug($category->name, '_')) . '.png') }}" alt="{{ $category->name }}">
                            <br>
                            <span>{{ mb_strtoupper($category->name) }}</span>
                        </button>

                        @if ($category->subcategories->isNotEmpty())
                            <ul class="dropdown-menu">
                                @foreach ($category->subcategories as $sub)
                                    <li><a  class="dropdown-item" href="{{ route('products.index', ['subcategory' => $sub->id]) }}">{{ $sub->name }}</a></li>
                                @endforeach
                            </ul>
                        @endif
                    </li>
                @endforeach
            </ul>
        </div>
    </div>

    <!-- Toggle button -->
    <button class="toggle-btn" id="toggleCategoriesBtn">
        <img src="{{ asset('images/icons/arrow-pull.png') }}" alt="↑" width="16" height="16">
    </button>
</header>
