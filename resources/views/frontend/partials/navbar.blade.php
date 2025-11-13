@php
    $is = fn ($pattern) => request()->routeIs($pattern) ? 'is-active' : '';
@endphp
<style>


    /* ===== Enlaces base ===== */
    .f3p-nav .nav-main > li > a {
        font-size: 14px;
        font-weight: 700;
        color: #222 !important;
        padding: 18px 14px;
        letter-spacing: .2px;
        transition: color .25s ease;
        text-decoration: none;
    }

    /* ===== Hover / activo ===== */
    .f3p-nav nav.navbar .navbar-nav.nav-main > li > a:hover,
    .f3p-nav .nav-main > li > a.is-active {
        color: #c8b083 !important;
    }

    /* ===== Íconos ===== */
    .f3p-nav .nav-user a,
    .f3p-nav .cart-navbar a {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        text-decoration: none;
        color: #222 !important;
        transition: color .25s ease;
    }

    .f3p-nav .nav-user a:hover,
    .f3p-nav .cart-navbar a:hover {
        color: #c8b083 !important;
    }

    .f3p-nav .fa-user, .f3p-nav .fa-shopping-cart {
        font-size: 18px;
    }

    /* ===== Badge carrito ===== */
    .f3p-nav .header-cart-count.count {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 18px;
        height: 18px;
        min-width: 18px;
        font-size: 11px;
        font-weight: 700;
        background: #c86e2a;
        color: #fff;
        border-radius: 50%;
    }

    /* ===== Responsivo ===== */
    .f3p-nav .only-desktop {
        display: none;
    }

    .f3p-nav .only-mobile {
        display: block;
    }

    @media (min-width: 1200px) {
        .f3p-nav .only-desktop {
            display: inline-block;
        }

        .f3p-nav .only-mobile {
            display: none;
        }

        .f3p-nav .nav-main {
            display: flex;
            align-items: center;
        }
    }

    /* ===== Color blanco cuando está activo SOLO en móviles ===== */
    @media (max-width: 1199px) {
        .f3p-nav .navbar-nav.nav-main > li > a.is-active,
        .f3p-nav .nav-mob a.is-active {
            color: #fff !important;
        }
    }


    /* ===== Dropdown usuario (compacto) ===== */
    /* === Compacta fuerte el menú de usuario === */
    .f3p-nav .dropdown .dropdown-menu.menu-compact {
        padding: 2px 0 !important; /* reduce padding del contenedor */
        margin-top: 8px !important;
        min-width: 168px !important;
    }

    .f3p-nav .dropdown .dropdown-menu.menu-compact > li {
        margin: 0 !important; /* quita "aire" entre <li> */
    }

    .f3p-nav .dropdown .dropdown-menu.menu-compact > li + li {
        margin-top: 0 !important; /* no separación entre ítems */
    }

    .f3p-nav .dropdown .dropdown-menu.menu-compact > li > a {
        display: flex !important;
        align-items: center !important;
        gap: 6px !important; /* espacio icono-texto */
        padding: 6px 12px !important; /* 🔻 altura del ítem */
        line-height: 1.15 !important; /* 🔻 compresión vertical */
        font-weight: 600 !important;
        border-radius: 6px !important;
        white-space: nowrap !important; /* evita 2 líneas */
    }

    .f3p-nav .dropdown .dropdown-menu.menu-compact i {
        font-size: 14px !important; /* icono un poco más pequeño */
    }

    .f3p-nav .dropdown .dropdown-menu.menu-compact > li > a:hover {
        background: #f6f6f6 !important;
        color: #c8b083 !important;
    }

    /* ===== Selector de idioma ===== */
    .f3p-nav .language-selector {
        position: relative;
    }

    .f3p-nav .language-selector .lang-toggle {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 8px 10px;
        font-size: 14px;
        font-weight: 700;
        color: #222 !important;
        background: transparent;
        border: 1px solid #ddd;
        border-radius: 6px;
        cursor: pointer;
        transition: all .25s ease;
        text-decoration: none;
    }

    .f3p-nav .language-selector .lang-toggle .fa-globe {
        font-size: 16px;
    }

    .f3p-nav .language-selector .lang-toggle:hover {
        color: #c8b083 !important;
        border-color: #c8b083;
    }

    .f3p-nav .language-selector .lang-flag {
        width: 20px;
        height: 15px;
        object-fit: cover;
        border-radius: 2px;
    }

    .f3p-nav .language-selector .lang-dropdown {
        position: absolute;
        top: calc(100% + 8px);
        right: 0;
        background: #fff;
        border: 1px solid #ddd;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        min-width: 140px;
        opacity: 0;
        visibility: hidden;
        transform: translateY(-10px);
        transition: all .25s ease;
        z-index: 1000;
    }

    .f3p-nav .language-selector:hover .lang-dropdown {
        opacity: 1;
        visibility: visible;
        transform: translateY(0);
    }

    .f3p-nav .language-selector .lang-dropdown a {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 8px 12px;
        color: #222 !important;
        text-decoration: none;
        font-size: 13px;
        font-weight: 600;
        transition: background .2s ease;
    }

    .f3p-nav .language-selector .lang-dropdown a:first-child {
        border-radius: 8px 8px 0 0;
    }

    .f3p-nav .language-selector .lang-dropdown a:last-child {
        border-radius: 0 0 8px 8px;
    }

    .f3p-nav .language-selector .lang-dropdown a:hover {
        background: #f6f6f6;
        color: #c8b083 !important;
    }

    .f3p-nav .language-selector .lang-dropdown a.active {
        background: #f9f5f0;
        color: #c8b083 !important;
    }

    /* Versión móvil del selector */
    .f3p-nav .lang-mobile {
        padding: 15px 0;
        border-top: 1px solid rgba(255,255,255,0.15);
        margin-top: 15px;
    }

    .f3p-nav .lang-mobile .lang-mobile-title {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 8px 15px 12px 15px;
        color: rgba(255,255,255,0.6) !important;
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .f3p-nav .lang-mobile .lang-mobile-title i {
        font-size: 14px;
    }

    .f3p-nav .lang-mobile .lang-options {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 8px;
        padding: 0 15px;
    }

    .f3p-nav .lang-mobile .lang-options a {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 6px;
        padding: 12px 8px;
        background: rgba(255,255,255,0.08);
        border: 1px solid rgba(255,255,255,0.15);
        border-radius: 8px;
        color: #fff !important;
        text-decoration: none;
        font-size: 13px;
        font-weight: 600;
        transition: all .2s ease;
        min-height: 65px;
    }

    .f3p-nav .lang-mobile .lang-options a .lang-emoji {
        font-size: 24px;
        line-height: 1;
    }

    .f3p-nav .lang-mobile .lang-options a .lang-code {
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        opacity: 0.7;
    }

    .f3p-nav .lang-mobile .lang-options a:hover,
    .f3p-nav .lang-mobile .lang-options a:active {
        background: rgba(255,255,255,0.15);
        border-color: rgba(200,176,131,0.5);
        transform: translateY(-2px);
    }

    .f3p-nav .lang-mobile .lang-options a.active {
        background: rgba(34,34,34,0.3);
        border-color: rgba(34,34,34,0.5);
        color: #222 !important;
    }

    .f3p-nav .lang-mobile .lang-options a.active .lang-code {
        opacity: 1;
        color: #222 !important;
    }

    @media (max-width: 1199px) {
        .f3p-nav .language-selector {
            display: none;
        }
    }

    @media (min-width: 1200px) {
        .f3p-nav .lang-mobile {
            display: none;
        }
    }

</style>


<div id="nav-wrapper" class="wrapper- f3p-nav">
    <nav data-spy="" data-offset-top="0" class="navbar">
        <div class="container">

            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar top-bar"></span>
                    <span class="icon-bar middle-bar"></span>
                    <span class="icon-bar bottom-bar"></span>
                </button>
                <a class="logo" href="{{ url('/' . session('region', config('region.default'))) }}">
                    <img style="width: 150px;" src="{{ asset('images/logoindex.png') }}" alt="Logo" decoding="async">
                </a>
            </div>

            <div id="navbar" class="navbar-collapse collapse">
                <ul id="menu-main-menu" class="nav navbar-nav nav-main">
                    <li><a class="{{ $is('user.index') }}"
                           href="{{ LaravelLocalization::getLocalizedURL(app()->getLocale(), route('user.index', [], false)) }}">{{ __('meta.home') }}</a>
                    </li>
                    <li><a class="{{ $is('user.ourcoffee') }}"
                           href="{{ LaravelLocalization::getLocalizedURL(app()->getLocale(), route('user.ourcoffee', [], false)) }}">{{ __('meta.our_coffee') }}</a>
                    </li>
                    <li><a class="{{ $is('user.products') }}"
                           href="{{ LaravelLocalization::getLocalizedURL(app()->getLocale(), route('user.products', [], false)) }}">{{ __('meta.products') }}</a>
                    </li>
                    <li><a class="{{ $is('user.gallery') }}"
                           href="{{ LaravelLocalization::getLocalizedURL(app()->getLocale(), route('user.gallery', [], false)) }}">{{ __('meta.gallery') }}</a>
                    </li>
                    <li><a class="{{ $is('user.contact') }}"
                           href="{{ LaravelLocalization::getLocalizedURL(app()->getLocale(), route('user.contact', [], false)) }}">{{ __('meta.contact') }}</a>
                    </li>
                    <li><a class="{{ $is('user.quote') }}"
                           href="{{ LaravelLocalization::getLocalizedURL(app()->getLocale(), route('user.quote', [], false)) }}">{{ __('meta.quote') }}</a>
                    </li>

                    {{-- Carrito --}}
                    <li class="only-desktop">
                        <div class="cart-navbar">
                            <a href="{{ LaravelLocalization::getLocalizedURL(app()->getLocale(), route('user.cart', [], false)) }}"
                               class="shop_table cart" title="{{ __('meta.view_cart') }}">
                                <span class="header-cart-count count">0</span>
                                <i class="fa fa-shopping-cart" aria-hidden="true"></i>
                            </a>
                        </div>
                    </li>

                    {{-- Usuario --}}
                    @auth('web')
                        <li id="menu-item-user"
                            class="menu-item menu-item-type-custom menu-item-object-custom menu-item-has-children only-desktop">
                            <a><i class="fa fa-user"></i> <span>{{ __('meta.my_account') }}</span></a>

                            <ul class="sub-menu">
                                <li id="menu-item-account"
                                    class="menu-item menu-item-type-post_type menu-item-object-page">
                                    <a href="{{ LaravelLocalization::getLocalizedURL(app()->getLocale(), route('user.orders', [], false)) }}">
                                        <i class="fa fa-id-badge"></i> <span>{{ __('meta.my_account') }}</span>
                                    </a>
                                </li>
                                <li id="menu-item-logout"
                                    class="menu-item menu-item-type-post_type menu-item-object-page">
                                    <a href="#" id="logoutLink">
                                        <i class="fa fa-sign-out"></i> <span>{{ __('meta.logout') }}</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    @else
                        <li id="menu-item-login" class="menu-item menu-item-type-custom menu-item-object-custom only-desktop">
                            <a href="{{ LaravelLocalization::getLocalizedURL(app()->getLocale(), route('user.login', [], false)) }}">
                                <i class="fa fa-user"></i> <span>{{ __('meta.login') }}</span>
                            </a>
                        </li>
                    @endauth

                    {{-- Selector de idioma DESKTOP --}}
                    <li class="only-desktop language-selector">
                        <a class="lang-toggle">
                            @php
                                $currentLang = app()->getLocale();
                                $flags = [
                                    'es' => '🇸🇻',
                                    'sv' => '🇸🇻',
                                    'en' => '🇺🇸',
                                    'ko' => '🇰🇷'
                                ];
                                $langNames = [
                                    'es' => 'Español',
                                    'sv' => 'Español',
                                    'en' => 'English',
                                    'ko' => '한국어'
                                ];
                            @endphp
                            <i class="fa fa-globe"></i>
                            <span style="font-size: 13px;">{{ strtoupper($currentLang) }}</span>
                            <i class="fa fa-chevron-down" style="font-size: 9px; margin-left: -2px;"></i>
                        </a>

                        <div class="lang-dropdown">
                            @foreach(LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
                                <a href="{{ LaravelLocalization::getLocalizedURL($localeCode, null, [], true) }}"
                                   class="{{ $localeCode === $currentLang ? 'active' : '' }}">
                                    <span class="lang-flag">{{ $flags[$localeCode] ?? '🌐' }}</span>
                                    <span>{{ $langNames[$localeCode] ?? $properties['native'] }}</span>
                                </a>
                            @endforeach
                        </div>
                    </li>

                </ul>

                {{-- Móvil --}}
                <div class="nav-mob">
                    <ul class="nav navbar-nav">
                        <li class="only-mobile">
                            <a href="{{ LaravelLocalization::getLocalizedURL(app()->getLocale(), route('user.cart', [], false)) }}"
                               class="shop_table cart" title="{{ __('meta.view_cart') }}">
                                <span class="header-cart-count count">0</span>
                                <i class="fa fa-shopping-cart" aria-hidden="true"></i>
                                <span>{{ __('meta.view_cart') }}</span>
                            </a>
                        </li>

                        @auth('web')
                            <li class="only-mobile">
                                <a href="{{ LaravelLocalization::getLocalizedURL(app()->getLocale(), route('user.orders', [], false)) }}">
                                    <i class="fa fa-user"></i> {{ __('meta.my_account') }}
                                </a>
                            </li>
                            <li class="only-mobile">
                                <a href="#" id="logoutLinkMobile">
                                    <i class="fa fa-sign-out"></i> {{ __('meta.logout') }}
                                </a>
                            </li>
                        @else
                            <li class="only-mobile">
                                <a href="{{ LaravelLocalization::getLocalizedURL(app()->getLocale(), route('user.login', [], false)) }}">
                                    <i class="fa fa-user"></i> {{ __('meta.login') }}
                                </a>
                            </li>
                        @endauth

                        {{-- Selector de idioma MÓVIL --}}
                        <div class="lang-mobile only-mobile">
                            <div class="lang-mobile-title">
                                <i class="fa fa-globe"></i>
                                <span>{{ __('meta.language') ?? 'Language' }}</span>
                            </div>
                            <div class="lang-options">
                                @foreach(LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
                                    <a href="{{ LaravelLocalization::getLocalizedURL($localeCode, null, [], true) }}"
                                       class="{{ $localeCode === $currentLang ? 'active' : '' }}">
                                        <span class="lang-emoji">{{ $flags[$localeCode] ?? '🌐' }}</span>
                                        <span>{{ $langNames[$localeCode] ?? $properties['native'] }}</span>
                                        <span class="lang-code">{{ strtoupper($localeCode) }}</span>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    </ul>
                </div>
            </div>
        </div>
        @auth('web')
            <form id="logout-form" action="{{ route('user.logout') }}" method="POST" style="display:none;">
                @csrf
            </form>
        @endauth
    </nav>
</div>


<script>
    document.addEventListener('DOMContentLoaded', async () => {
        try {
            const res = await fetch(`{{ route('cart.count') }}`, {
                headers: { 'Accept': 'application/json' }
            });
            const data = await res.json();
            if (typeof data.count !== 'undefined') {
                document.querySelectorAll('.header-cart-count.count')
                    .forEach(el => el.textContent = data.count);
            }
        } catch (e) {

        }
    });
</script>


<script>
    document.addEventListener('DOMContentLoaded', function () {
        var logoutLink = document.getElementById('logoutLink');
        var logoutLinkMobile = document.getElementById('logoutLinkMobile');
        var form = document.getElementById('logout-form');

        function doLogout(e) {
            e.preventDefault();
            if (form) form.submit();
        }

        if (logoutLink) logoutLink.addEventListener('click', doLogout);
        if (logoutLinkMobile) logoutLinkMobile.addEventListener('click', doLogout);
    });
</script>

<script>
    // Actualiza badges del navbar (desktop + móvil)
    function updateCartBadges(count) {
        document.querySelectorAll('.header-cart-count.count')
            .forEach(el => el.textContent = (typeof count === 'number' ? count : 0));
    }

    // Escucha evento global
    window.addEventListener('cart:updated', (e) => {
        const { count } = e.detail || {};
        updateCartBadges(count);
    });
</script>
