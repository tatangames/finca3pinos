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

                    {{-- Carrito --}}
                    <li class="only-desktop">
                        <div class="cart-navbar">
                            <a href="{{ LaravelLocalization::getLocalizedURL(app()->getLocale(), route('user.contact', [], false)) }}"
                               class="shop_table cart" title="Ver carrito">
                                <span class="header-cart-count count">0</span>
                                <i class="fa fa-shopping-cart" aria-hidden="true"></i>
                            </a>
                        </div>
                    </li>




                    {{-- Usuario --}}
                    <li class="only-desktop nav-user dropdown">
                    {{-- ====== USUARIO ====== --}}
                    <li id="menu-item-user" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-has-children only-desktop">
                        @auth('web')
                            <a><i class="fa fa-user"></i> <span>{{ __('meta.my_account') }}</span></a>

                            <ul class="sub-menu">
                                <li id="menu-item-account" class="menu-item menu-item-type-post_type menu-item-object-page">
                                    <a href="{{ LaravelLocalization::getLocalizedURL(app()->getLocale(), route('user.index', [], false)) }}">
                                        <i class="fa fa-id-badge"></i> <span>{{ __('meta.my_account') }}</span>
                                    </a>
                                </li>
                                <li id="menu-item-logout" class="menu-item menu-item-type-post_type menu-item-object-page">
                                    <a href="#" id="logoutLink">
                                        <i class="fa fa-sign-out"></i> <span>{{ __('meta.logout') }}</span>
                                    </a>
                                </li>
                            </ul>
                        @else
                            <a href="{{ LaravelLocalization::getLocalizedURL(app()->getLocale(), route('user.login', [], false)) }}">
                                <i class="fa fa-user"></i> <span>{{ __('meta.login') }}</span>
                            </a>
                        @endauth
                    </li>

                    @auth('web')
                        <form id="logout-form" action="{{ route('user.logout') }}" method="POST" style="display:none;">
                            @csrf
                        </form>
                        @endauth

                </ul>

                {{-- Móvil --}}
                <div class="nav-mob">
                    <ul class="nav navbar-nav">
                        <li class="only-mobile">
                            <a href="{{ LaravelLocalization::getLocalizedURL(app()->getLocale(), route('user.contact', [], false)) }}"
                               class="cart-icon mob cart-link">
                                <span class="header-cart-count count">0</span>
                                <i class="fa fa-shopping-cart"></i>
                                <span>Carrito</span>
                            </a>
                        </li>

                        @auth('web')
                            <li class="only-mobile">
                                <a href="{{ LaravelLocalization::getLocalizedURL(app()->getLocale(), route('user.index', [], false)) }}">
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
        margin: 0 !important; /* quita “aire” entre <li> */
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




</style>


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

