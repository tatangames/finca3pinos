@php
    // Acepta comodines, útil para subrutas (p. ej. user.products.show)
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
                    <img style="width: 150px;" src="{{ asset('images/logoindex.png') }}" class="attachment-full size-full" alt="" decoding="async">
                </a>
            </div>

            <div id="navbar" class="navbar-collapse collapse">
                <ul id="menu-main-menu" class="nav navbar-nav nav-main">
                    <li><a class="{{ $is('user.index') }}" href="{{ LaravelLocalization::getLocalizedURL(app()->getLocale(), route('user.index', [], false)) }}">{{ __('meta.home') }}</a></li>
                    <li><a class="{{ $is('user.ourcoffee') }}" href="{{ LaravelLocalization::getLocalizedURL(app()->getLocale(), route('user.ourcoffee', [], false)) }}">{{ __('meta.our_coffee') }}</a></li>
                    <li><a class="{{ $is('user.products') }}" href="{{ LaravelLocalization::getLocalizedURL(app()->getLocale(), route('user.products', [], false)) }}">{{ __('meta.products') }}</a></li>
                    <li><a class="{{ $is('user.gallery') }}" href="{{ LaravelLocalization::getLocalizedURL(app()->getLocale(), route('user.gallery', [], false)) }}">{{ __('meta.gallery') }}</a></li>
                    <li><a class="{{ $is('user.contact') }}" href="{{ LaravelLocalization::getLocalizedURL(app()->getLocale(), route('user.contact', [], false)) }}">{{ __('meta.contact') }}</a></li>

                    {{-- Carrito (escritorio) estilo "badge + icono" --}}
                    <li class="only-desktop">
                        <div class="cart-navbar">
                            <a href="{{ LaravelLocalization::getLocalizedURL(app()->getLocale(), route('user.contact', [], false)) }}" class="shop_table cart" title="Ver carrito">
                                <span class="header-cart-count count">0</span>
                                <i class="fa fa-shopping-cart" aria-hidden="true"></i>
                            </a>
                        </div>
                    </li>

                    {{-- Usuario con dropdown (escritorio) --}}
                    <li class="nav-user dropdown only-desktop">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false" title="Cuenta">
                            <i class="fa fa-user"></i>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a href="#">Login</a></li>
                            <li><a href="#">Registro</a></li>
                        </ul>
                    </li>
                </ul>

                {{-- Móvil: extras sin duplicar --}}
                <div class="nav-mob">
                    <ul class="nav navbar-nav">
                        <li class="only-mobile">
                            <a href="{{ LaravelLocalization::getLocalizedURL(app()->getLocale(), route('user.contact', [], false)) }}" class="cart-icon mob cart-link">
                                <span class="header-cart-count count">0</span>
                                <i class="fa fa-shopping-cart"></i>
                                <span>Carrito</span>
                            </a>
                        </li>
                        <li class="only-mobile"><a href="#"><i class="fa fa-user"></i> Login</a></li>
                        <li class="only-mobile"><a href="#"><i class="fa fa-user-plus"></i> Registro</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>
</div>



<style>
    /* =====================================================
     ===== Navbar Finca 3 Pinos — CSS Final Unificado =====
     ===================================================== */

    /* =====================================================
    ===== Navbar Finca 3 Pinos — CSS Final Funcional =====
    ===================================================== */

    /* ===== Base enlaces ===== */
    .f3p-nav .nav-main > li > a {
        font-size: 14px;
        font-weight: 700;
        color: #222 !important;
        padding: 18px 14px;
        letter-spacing: .2px;
        transition: color .25s ease;
        text-decoration: none;
    }

    /* ===== Colores base íconos/enlaces ===== */
    .f3p-nav .only-desktop .fa,
    .f3p-nav .cart-navbar a,
    .f3p-nav .cart-icon.mob,
    .f3p-nav .nav-user > a,
    .f3p-nav .nav-user > a i {
        color: #222 !important;
        transition: color .25s ease;
        text-decoration: none;
    }

    /* ===== Hover dorado ===== */
    .f3p-nav nav.navbar .navbar-nav.nav-main > li > a:hover,
    .f3p-nav nav.navbar .navbar-nav.nav-main > li > a:focus,
    .f3p-nav .only-desktop a:hover i,
    .f3p-nav .cart-navbar a:hover,
    .f3p-nav .cart-icon.mob:hover,
    .f3p-nav .nav-user > a:hover,
    .f3p-nav .nav-user > a:hover i {
        color: #c8b083 !important;
    }

    /* ===== Activo por ruta ===== */
    .f3p-nav .navbar-nav.nav-main > li > a.is-active {
        color: #ffffff !important;
    }

    /* ===== Tamaños de íconos ===== */
    .f3p-nav .only-desktop .fa { font-size: 18px; }

    /* ===== Carrito (badge + icono) ===== */
    .f3p-nav .cart-navbar a,
    .f3p-nav .cart-icon.mob {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        position: relative;
        text-decoration: none;
        transition: color .25s ease;
    }

    .f3p-nav .header-cart-count.count {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 18px;
        height: 18px;
        min-width: 18px;
        font-size: 11px;
        font-weight: 700;
        line-height: 18px;
        background: #c86e2a;
        color: #fff;
        border-radius: 50%;
    }

    .f3p-nav .cart-navbar i.fa-shopping-cart,
    .f3p-nav .cart-icon.mob i.fa-shopping-cart {
        font-size: 18px;
    }

    /* =====================================================
       ===== Dropdown usuario compacto y cerrado =====
       ===================================================== */

    .f3p-nav .nav-user { position: relative; }

    /* Reset de listas */
    .f3p-nav .nav-user .dropdown-menu,
    .f3p-nav .nav-user .dropdown-menu ul,
    .f3p-nav .nav-user .dropdown-menu li {
        margin: 0 !important;
        padding: 0 !important;
        list-style: none !important;
    }

    /* Caja del dropdown (cerrada por defecto) */
    .f3p-nav .nav-user .dropdown-menu {
        position: absolute;
        right: 0;
        left: auto;
        top: calc(100% + 8px);
        min-width: 180px;
        max-width: 240px;
        max-height: 260px;
        overflow-y: auto;
        background: #fff;
        border: 1px solid rgba(0,0,0,.06);
        border-radius: 12px;
        box-shadow: 0 12px 32px rgba(0,0,0,.12);
        padding: 6px !important;
        display: block !important;
        opacity: 0 !important;
        visibility: hidden !important;
        pointer-events: none !important;
        transform: translateY(6px) !important;
        transition: opacity .18s ease, transform .18s ease !important;
    }

    /* Flechita decorativa */
    .f3p-nav .nav-user .dropdown-menu::before {
        content: "";
        position: absolute;
        right: 16px;
        top: -7px;
        width: 12px; height: 12px;
        background: #fff;
        border-left: 1px solid rgba(0,0,0,.06);
        border-top: 1px solid rgba(0,0,0,.06);
        transform: rotate(45deg);
    }

    /* Mostrar cuando está abierto */
    .f3p-nav .nav-user.open .dropdown-menu,
    .f3p-nav .nav-user .dropdown-menu.show {
        opacity: 1 !important;
        visibility: visible !important;
        pointer-events: auto !important;
        transform: translateY(0) !important;
    }

    /* Ítems */
    .f3p-nav .nav-user .dropdown-menu li { display: block; }
    .f3p-nav .nav-user .dropdown-menu li + li { margin-top: 2px !important; }

    .f3p-nav .nav-user .dropdown-menu li a {
        display: flex !important;
        align-items: center !important;
        gap: 8px !important;
        padding: 8px 10px !important;
        line-height: 1.25 !important;
        font-size: 14px !important;
        font-weight: 600 !important;
        color: #222 !important;
        background: transparent !important;
        border-radius: 8px !important;
        text-decoration: none !important;
    }

    .f3p-nav .nav-user .dropdown-menu li a:hover {
        background: #f6f6f6 !important;
        color: #000 !important;
    }

    /* Compacto en pantallas menores */
    @media (max-width: 1280px) {
        .f3p-nav .nav-user .dropdown-menu {
            min-width: 170px;
            padding: 6px !important;
        }
        .f3p-nav .nav-user .dropdown-menu li a {
            padding: 7px 9px !important;
            font-size: 13.5px !important;
        }
    }

    /* =====================================================
       ===== Móvil: carrito visible y estilizado =====
       ===================================================== */

    .f3p-nav .nav-mob .cart-link {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
    }
    .f3p-nav .nav-mob .cart-link i.fa-shopping-cart { font-size: 18px; }
    .f3p-nav .nav-mob .cart-link .header-cart-count {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 18px;
        height: 18px;
        min-width: 18px;
        line-height: 18px;
        font-size: 11px;
        font-weight: 700;
        border-radius: 50%;
        background: #c86e2a;
        color: #fff;
    }

    /* Hover móvil */
    .f3p-nav .nav-mob a:hover { color: #c8b083 !important; }

    /* =====================================================
       ===== Breakpoints y disposición general =====
       ===================================================== */

    .f3p-nav .only-desktop { display: none; }
    .f3p-nav .only-mobile { display: block; }

    @media (min-width: 1200px) {
        .f3p-nav .only-desktop { display: inline-block; }
        .f3p-nav .only-mobile { display: none; }

        /* Alinear UL y empujar el carrito */
        .f3p-nav .nav-main {
            display: flex;
            align-items: center;
        }
        .f3p-nav .nav-main > li.only-desktop:first-of-type {
            margin-left: auto;
        }
    }

    /* ===== (Opcional) subrayado elegante ===== */
    /*
    .f3p-nav .nav-main > li > a:hover,
    .f3p-nav .nav-main > li > a.is-active {
      border-bottom: 2px solid #c8b083;
    }
    */

</style>



<script>
    (function () {
        var $u  = document.querySelector('.f3p-nav .nav-user > a');
        var $li = document.querySelector('.f3p-nav .nav-user');
        if (!$u || !$li) return;

        var hasJQ = typeof window.jQuery !== 'undefined';
        var hasBsDropdown = hasJQ && typeof jQuery.fn.dropdown === 'function';
        if (hasBsDropdown) return; // si Bootstrap maneja el dropdown

        $u.addEventListener('click', function (e) {
            e.preventDefault(); e.stopPropagation();
            var isOpen = $li.classList.toggle('open');
            $u.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
        });

        document.addEventListener('click', function (e) {
            if (!$li.contains(e.target)) {
                $li.classList.remove('open');
                $u.setAttribute('aria-expanded', 'false');
            }
        });

        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') {
                $li.classList.remove('open');
                $u.setAttribute('aria-expanded', 'false');
                $u.focus();
            }
        });
    })();
</script>





