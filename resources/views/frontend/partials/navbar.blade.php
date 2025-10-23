@php
    $is = fn ($pattern) => request()->routeIs($pattern) ? 'is-active' : '';
@endphp

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
                    <li><a class="{{ $is('user.index') }}" href="{{ LaravelLocalization::getLocalizedURL(app()->getLocale(), route('user.index', [], false)) }}">{{ __('meta.home') }}</a></li>
                    <li><a class="{{ $is('user.ourcoffee') }}" href="{{ LaravelLocalization::getLocalizedURL(app()->getLocale(), route('user.ourcoffee', [], false)) }}">{{ __('meta.our_coffee') }}</a></li>
                    <li><a class="{{ $is('user.products') }}" href="{{ LaravelLocalization::getLocalizedURL(app()->getLocale(), route('user.products', [], false)) }}">{{ __('meta.products') }}</a></li>
                    <li><a class="{{ $is('user.gallery') }}" href="{{ LaravelLocalization::getLocalizedURL(app()->getLocale(), route('user.gallery', [], false)) }}">{{ __('meta.gallery') }}</a></li>
                    <li><a class="{{ $is('user.contact') }}" href="{{ LaravelLocalization::getLocalizedURL(app()->getLocale(), route('user.contact', [], false)) }}">{{ __('meta.contact') }}</a></li>

                    {{-- Carrito --}}
                    <li class="only-desktop">
                        <div class="cart-navbar">
                            <a href="{{ LaravelLocalization::getLocalizedURL(app()->getLocale(), route('user.contact', [], false)) }}" class="shop_table cart" title="Ver carrito">
                                <span class="header-cart-count count">0</span>
                                <i class="fa fa-shopping-cart" aria-hidden="true"></i>
                            </a>
                        </div>
                    </li>

                    {{-- Usuario --}}
                    <li class="only-desktop nav-user">
                        @auth
                            <a href="{{ LaravelLocalization::getLocalizedURL(app()->getLocale(), route('user.contact', [], false)) }}">
                                <i class="fa fa-user"></i> <span>{{ __('meta.my_account') }}</span>
                            </a>
                        @else
                            <a href="{{ LaravelLocalization::getLocalizedURL(app()->getLocale(), route('user.login', [], false)) }}">
                                <i class="fa fa-user"></i> <span>{{ __('meta.login') }}</span>
                            </a>
                        @endauth
                    </li>
                </ul>

                {{-- Móvil --}}
                <div class="nav-mob">
                    <ul class="nav navbar-nav">
                        <li class="only-mobile">
                            <a href="{{ LaravelLocalization::getLocalizedURL(app()->getLocale(), route('user.contact', [], false)) }}" class="cart-icon mob cart-link">
                                <span class="header-cart-count count">0</span>
                                <i class="fa fa-shopping-cart"></i>
                                <span>Carrito</span>
                            </a>
                        </li>
                        @auth
                            <li class="only-mobile"><a href="{{ LaravelLocalization::getLocalizedURL(app()->getLocale(), route('user.contact', [], false)) }}"><i class="fa fa-user"></i> {{ __('meta.my_account') }}</a></li>
                        @else
                            <li class="only-mobile"><a href="{{ LaravelLocalization::getLocalizedURL(app()->getLocale(), route('user.login', [], false)) }}"><i class="fa fa-user"></i> {{ __('meta.login') }}</a></li>
                        @endauth
                    </ul>
                </div>
            </div>
        </div>
    </nav>
</div>

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
    .f3p-nav .only-desktop { display: none; }
    .f3p-nav .only-mobile { display: block; }

    @media (min-width: 1200px) {
        .f3p-nav .only-desktop { display: inline-block; }
        .f3p-nav .only-mobile { display: none; }
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

</style>
