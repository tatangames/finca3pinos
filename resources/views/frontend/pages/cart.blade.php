@extends('frontend.layouts.app')

@section('title', __('meta.title'))

@section('content')
    <link rel="stylesheet" href="{{ asset('css/toastr.min.css') }}">
    <style>
        /* ============================================================
           WOOCOMMERCE - CARRITO
           ============================================================ */

        /* Ocultar cupón */
        .woocommerce .actions .coupon,
        .woocommerce .coupon,
        .woocommerce-cart-form .coupon {
            display: none !important;
        }

        /* Acciones - Botón Update Cart */
        .woocommerce .actions {
            text-align: right;
            padding-top: 10px;
        }

        .woocommerce .actions .button[name="update_cart"],
        .button.btn[name="update_cart"] {
            background: #d2aa6d !important;
            border: none !important;
            color: #fff !important;
            font-weight: 600 !important;
            padding: 10px 18px !important;
            border-radius: 8px !important;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.06);
            transition: transform 0.15s ease, box-shadow 0.2s ease, opacity 0.2s ease;
        }

        .woocommerce .actions .button[name="update_cart"]:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.12);
        }

        /* ============================================================
           TABLA DEL CARRITO
           ============================================================ */
        .shop_table.cart thead th {
            background: #c0a277;
            color: #fff;
            font-weight: 700;
        }
        .shop_table.cart tbody tr.cart_item td {
            padding: 12px 10px;
            vertical-align: middle;
        }
        .shop_table.cart tbody tr.cart_item {
            border-bottom: 1px solid rgba(0, 0, 0, 0.06);
        }
        .shop_table.cart td.product-thumbnail img {
            width: 120px;
            height: auto;
            border-radius: 6px;
        }

        /* ============================================================
           CANTIDAD (INPUT)
           ============================================================ */
        .shop_table.cart td.product-quantity { text-align: center; vertical-align: middle; }
        .shop_table.cart .quantity { display: inline-flex; align-items: center; justify-content: center; }
        .shop_table.cart .quantity .input-text.qty {
            width: 60px; height: 36px; padding: 4px 8px; text-align: center;
            border: 1px solid rgba(0, 0, 0, 0.15); border-radius: 6px; outline: none;
            font-weight: 500; font-size: 15px; color: #333; transition: all 0.2s ease;
        }
        .shop_table.cart .quantity .input-text.qty:focus {
            border-color: #d2aa6d;
            box-shadow: 0 0 0 2px rgba(210, 170, 109, 0.15);
        }

        /* QTY sin flechas nativas */
        .shop_table.cart .quantity .input-text.qty,
        .woocommerce .quantity .qty,
        input[type="number"] {
            -moz-appearance: textfield;
            appearance: textfield;
        }
        .shop_table.cart .quantity .input-text.qty::-webkit-outer-spin-button,
        .shop_table.cart .quantity .input-text.qty::-webkit-inner-spin-button,
        input[type="number"]::-webkit-outer-spin-button,
        input[type="number"]::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        /* ============================================================
           BOTÓN ELIMINAR (X)
           ============================================================ */
        .shop_table.cart td.product-remove { width: 60px; text-align: center; vertical-align: middle; padding: 12px 10px; }
        .shop_table.cart td.product-remove .remove {
            display: inline-flex !important; align-items: center; justify-content: center;
            width: 30px; height: 30px; border-radius: 50% !important;
            background: #fff3e0; color: #d62d20; font-size: 18px; font-weight: bold;
            text-decoration: none !important; border: 1px solid rgba(0, 0, 0, 0.1);
            line-height: 1; transition: all 0.2s ease; box-sizing: border-box;
        }
        .shop_table.cart td.product-remove .remove:hover {
            background: #e74c3c; color: #fff; transform: scale(1.05);
            box-shadow: 0 3px 8px rgba(0, 0, 0, 0.15);
        }

        /* Botón X consistente en temas */
        .woocommerce table.shop_table.cart td.product-remove > a.remove,
        table.shop_table.cart td.product-remove > a.remove {
            display: inline-grid !important; place-items: center !important;
            width: 30px !important; height: 30px !important; aspect-ratio: 1/1;
            padding: 0 !important; margin: 0 auto !important; border-radius: 50% !important;
            line-height: 1 !important; background: #fff3e0 !important; color: #d62d20 !important;
            border: 1px solid rgba(0,0,0,.1) !important; font-size: 18px !important;
            text-decoration: none !important; box-sizing: border-box !important;
        }
        .woocommerce a.remove:before {
            position: static !important; display: inline !important;
            width: auto !important; height: auto !important; line-height: 1 !important;
            margin: 0 !important; padding: 0 !important;
        }
        .woocommerce table.shop_table.cart td.product-remove > a.remove:hover {
            background: #e74c3c !important; color: #fff !important;
            transform: scale(1.05); box-shadow: 0 3px 8px rgba(0,0,0,.15);
        }

        /* ============================================================
           TOTALES DEL CARRITO
           ============================================================ */
        .cart-collaterals { display: flex; justify-content: flex-end; }
        .cart_totals { width: 420px; max-width: 100%; }
        .cart_totals h2 { display: none; }
        .cart_totals .shop_table { width: 100%; border: none; border-radius: 0; overflow: visible; }
        .cart_totals .shop_table th, .cart_totals .shop_table td { padding: 12px 16px; }
        .cart_totals .order-total th, .cart_totals .order-total td { font-size: 1.4rem; font-weight: 800; }
        .cart_totals .cart-subtotal td { text-align: right; }
        .cart_totals .cart-subtotal td .price-badge {
            display: inline-block; padding: 8px 12px; background: #fff;
            border: 1px solid rgba(0, 0, 0, 0.15); border-radius: 8px;
            font-weight: 700; letter-spacing: 0.1px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.08);
        }

        /* Elimina bordes/ fondo heredados en totales */
        .cart_totals .shop_table,
        .cart_totals .shop_table td,
        .cart_totals .shop_table th {
            border: none !important;
            background: transparent !important;
            box-shadow: none !important;
        }
        .cart_totals {
            border: none !important; border-radius: 12px !important;
            overflow: hidden !important; background: transparent !important;
        }

        /* ============================================================
           BOTÓN CHECKOUT
           ============================================================ */
        .wc-proceed-to-checkout .checkout-button {
            display: inline-block; width: auto; margin-top: 14px;
            background: #d2aa6d !important; border-radius: 10px !important;
            padding: 12px 24px !important; font-weight: 700 !important; color: #fff !important;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.06);
            transition: transform 0.15s ease, box-shadow 0.2s ease;
        }
        .wc-proceed-to-checkout .checkout-button:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.12);
        }

        /* ============================================================
           RESPONSIVE
           ============================================================ */
        @media (max-width: 991px) {
            .cart-collaterals { justify-content: center; }
            .cart_totals { width: 100%; }
            .shop_table.cart td.product-thumbnail img { width: 100px; }
        }

        /* ===========================
           CART — MOBILE OPTIMIZADO
           =========================== */
        @media (max-width: 640px) {
            .shop_table.cart thead { display: none !important; }
            .shop_table.cart tbody tr.cart_item {
                display: grid !important;
                grid-template-columns: 36px 64px 1fr;
                grid-template-areas:
                    "remove thumb name"
                    "remove thumb qty"
                    "remove thumb subtotal";
                gap: 8px 10px; padding: 10px 8px !important;
                border: 1px solid rgba(0,0,0,.06); border-radius: 12px; margin-bottom: 10px;
            }
            .shop_table.cart td.product-remove   { grid-area: remove; align-self: start; text-align: left; }
            .shop_table.cart td.product-thumbnail{ grid-area: thumb; }
            .shop_table.cart td.product-name     { grid-area: name; }
            .shop_table.cart td.product-quantity { grid-area: qty; }
            .shop_table.cart td.product-subtotal { grid-area: subtotal; text-align: right; }

            .shop_table.cart td.product-thumbnail img { width: 64px; height: auto; border-radius: 8px; }
            .shop_table.cart td { padding: 0 !important; }
            .shop_table.cart td.product-name { font-size: 15px; line-height: 1.25; }

            .shop_table.cart .quantity { justify-content: flex-start; }
            .shop_table.cart .quantity .input-text.qty { width: 56px; height: 34px; font-size: 14px; }

            .woocommerce table.shop_table.cart td.product-remove > a.remove {
                width: 32px !important; height: 32px !important; font-size: 18px !important;
            }
            table.shop_table_responsive tr td::before { display: none !important; }

            .cart-collaterals { justify-content: stretch; }
            .cart_totals { width: 100%; max-width: 100%; }
            .cart_totals .shop_table td, .cart_totals .shop_table th { padding: 8px 10px; }

            .wc-proceed-to-checkout .checkout-button {
                width: 100% !important; padding: 14px 18px !important;
                font-size: 16px !important; border-radius: 12px !important;
            }

            .text-page .entry-content .woocommerce { padding: 0 8px; }
        }

        @media (max-width: 640px) {
            .shop_table.cart td.product-price {
                color: #777 !important; font-size: 14px !important; font-weight: 500; margin-top: 4px;
            }
            .shop_table.cart td.product-subtotal {
                color: #111 !important; font-size: 16px !important; font-weight: 700 !important; margin-top: 2px;
            }
            .shop_table.cart td.product-price,
            .shop_table.cart td.product-subtotal { display: block; text-align: right; }

            .shop_table.cart tbody tr.cart_item { padding-bottom: 14px !important; }
            .shop_table.cart td.product-price::before {
                content: "Precio:"; color: #999; font-size: 12px; font-weight: 400; margin-right: 4px;
            }
            .shop_table.cart td.product-subtotal::before {
                content: "Subtotal:"; color: #999; font-size: 12px; font-weight: 400; margin-right: 4px;
            }
        }




        /* ——— Header compacto con máxima prioridad ——— */
        #heroProducts.page-header--compact{
            height: 140px !important;
            min-height: 0 !important;
            max-height: 140px !important;
            padding: 0 !important;
            margin: 0 !important;
            display: flex !important;
            align-items: center !important;
            background-size: cover !important;
            background-position: center !important;
            background-repeat: no-repeat !important;
            position: relative !important;
            overflow: clip; /* evita colapsos y márgenes raros */
        }

        /* Muchos temas inflan con ::before/::after */
        #heroProducts.page-header--compact::before,
        #heroProducts.page-header--compact::after{
            content: none !important;
            display: none !important;
        }

        /* Evita padding interno heredado */
        #heroProducts.page-header--compact > .container{
            height: 100% !important;
            display: flex !important;
            flex-direction: column !important;
            justify-content: center !important;
            padding: 0 !important;
            margin: 0 !important;
        }

        /* Evita que el margen del H1 “empuje” la altura */
        /* ===== Título del hero más pequeño, liviano y con margen ===== */
        #heroProducts.page-header--compact h1{
            margin: 0 0 8px 24px !important;   /* ← margen izquierdo agregado */
            color: #fff;
            font-weight: 200;                  /* menos grueso */
            line-height: 1.1;
            font-size: clamp(24px, 4vw, 38px); /* más pequeño */
            letter-spacing: 0;
            text-shadow: 0 1px 2px rgba(0,0,0,.25);
        }

        /* breadcrumbs también alineadas con el margen del título */



        /* Miga de pan sin margen extra */
        #heroProducts.page-header--compact .breadcrumbs{
            margin-left: 24px !important;      /* alinea con el título */
        }

        /* (opcional) en móvil aún más bajo */
        @media (max-width: 575.98px){
            #heroProducts.page-header--compact{ height: 120px !important; max-height:120px !important; }
        }

        /* ===== HERO COMPACTO CON ESTILO ===== */
        #heroProducts.page-header--compact{
            /* altura controlada */
            height: 180px !important;         /* ← ajusta 160–200 según quieras */
            min-height: 0 !important;
            max-height: 180px !important;

            /* reset y layout */
            padding: 0 !important;
            margin: 0 !important;
            display: flex !important;
            align-items: center !important;
            position: relative !important;
            overflow: clip;

            /* fondo */
            background-size: cover !important;
            background-position: center !important;
            background-repeat: no-repeat !important;
        }

        /* Overlay para recuperar contraste del texto sobre la foto */
        #heroProducts.page-header--compact::after{
            content:"";
            position:absolute; inset:0;
            background: linear-gradient(
                to bottom,
                rgba(0,0,0,.45),
                rgba(0,0,0,.45)
            );
            pointer-events:none;
        }

        /* Contenido centrado verticalmente */
        #heroProducts.page-header--compact > .container{
            position: relative;              /* por encima del overlay */
            z-index: 1;
            height: 100%;
            display: flex; flex-direction: column;
            justify-content: center;
            padding: 0 16px !important;
        }

        /* Título grande y pesado como en el diseño */
        #heroProducts.page-header--compact h1{
            margin: 0 0 10px !important;
            color: #fff;
            font-weight: 900;
            line-height: 1.05;
            font-size: clamp(32px, 6vw, 56px); /* grande en desktop, adaptable en móvil */
            letter-spacing: .2px;
            text-shadow: 0 1px 2px rgba(0,0,0,.25);
        }

        /* ===== Breadcrumbs estilo imagen ===== */
        #heroProducts.page-header--compact .breadcrumbs{
            margin: 0 !important;
            padding: 0;
            list-style: none;
            display: flex; flex-wrap: wrap; align-items: center;
            gap: 12px;
            font-size: clamp(16px, 2.2vw, 28px); /* grande como en la captura */
            font-weight: 800;
        }

        /* items */
        #heroProducts.page-header--compact .breadcrumbs li{
            display: inline-flex; align-items: center;
            color: #fff;
        }

        /* enlace “Finca 3 Pinos” en dorado de marca */
        #heroProducts.page-header--compact .breadcrumbs a{
            color: #d2aa6d; text-decoration: none;
        }

        /* separador › */
        #heroProducts.page-header--compact .breadcrumbs li + li::before{
            content: "›";
            margin: 0 10px;
            color: rgba(255,255,255,.8);
            font-weight: 700;
        }

        /* último item en blanco */
        #heroProducts.page-header--compact .breadcrumbs .current-item span[property="name"]{
            color: #fff;
        }

        /* Responsive: un poco más bajo en móvil si quieres */
        @media (max-width: 575.98px){
            #heroProducts.page-header--compact{
                height: 150px !important;
                max-height: 150px !important;
            }
            #heroProducts.page-header--compact .breadcrumbs{
                gap: 8px;
            }
        }



        /* ===== HERO COMPACTO — DEFINITIVO (pegar al FINAL) ===== */
        #heroProducts{
            height: 160px !important;             /* ← ajusta 140–200 según gusto */
            padding: 0 !important;
            margin: 0 !important;
            display: flex !important;
            align-items: center !important;
            position: relative !important;
            background-position: center !important;
            background-size: cover !important;
            background-repeat: no-repeat !important;
            overflow: clip;
        }

        /* anula pseudoelementos/parallax del tema */
        #heroProducts::before,
        #heroProducts::after{ content: none !important; display: none !important; }

        /* overlay suave para contraste */
        #heroProducts::after{
            content:"";
            position:absolute; inset:0;
            background: rgba(0,0,0,.35);
            display:block;
        }

        /* contenido centrado y con sangría a la izquierda */
        #heroProducts .container{
            position: relative;
            z-index: 1;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 0 !important;
            margin-left: 125px;   /* ← aquí controlas el margen a la izquierda */
        }

        /* Título: más pequeño y menos bold */
        #heroProducts h1{
            margin: 0 0 10px !important;
            color: #fff;
            font-weight: 700;
            line-height: 1.1;
            font-size: clamp(28px, 5vw, 42px);   /* tamaño final del título */
        }

        /* Breadcrumbs como en tu imagen */
        #heroProducts .breadcrumbs{
            margin: 0 !important;
            padding: 0;
            list-style: none;
            display: flex; align-items: center; gap: 12px;
            font-weight: 700;
            font-size: clamp(14px, 2vw, 22px);
            color: #fff;
        }
        #heroProducts .breadcrumbs a{ color:#d2aa6d; text-decoration:none; }
        #heroProducts .breadcrumbs li + li::before{
            content:"›"; margin: 0 10px; color: rgba(255,255,255,.9); font-weight:700;
        }

        @media (max-width:575.98px){
            #heroProducts{ height: 140px !important; }
        }

        .margin-default {
            padding-top: 65px !important;   /* mantienes el padding interno si lo deseas */
            padding-bottom: 110px;          /* puedes dejarlo igual */
            margin-top: -40px !important;   /* esto lo sube visualmente */
        }
        #btn-update-all {
            border-radius: 50px;     /* hace el botón redondo */
            padding: 10px 28px;      /* ajusta el tamaño visual */
            font-weight: 600;
            background-color: #d2aa6d;  /* color dorado de tu marca */
            color: #fff;
            border: none;
            transition: background 0.3s;
        }

        #btn-update-all:hover {
            background-color: #c0975f;  /* tono más oscuro al pasar el mouse */
        }


        /* Botón "Actualizar carrito" en forma píldora */
        .woocommerce .actions #btn-update-all,
        #btn-update-all.button.btn {
            border-radius: 9999px !important;   /* píldora */
            padding: 10px 28px !important;
            background-color: #d2aa6d !important;
            color: #fff !important;
            border: none !important;
            transition: background .3s;
            display: inline-block;               /* por si algún tema usa display raro */
        }
        .woocommerce .actions #btn-update-all:hover,
        #btn-update-all.button.btn:hover {
            background-color: #c0975f !important;
        }

    </style>


    <!-- ===== HEADER (sin like-parallax) ===== -->
    <header id="heroProducts"
            style="background-image:url('{{ asset('images/inner_parallax.jpg') }}');">
        <div class="container">
            <h1>{{ __('meta.cart')}}</h1>
            <ul class="breadcrumbs" typeof="BreadcrumbList" vocab="https://schema.org/">
                <li class="home">
        <span property="itemListElement" typeof="ListItem">
          <a property="item" typeof="WebPage"
             href="{{ LaravelLocalization::getLocalizedURL(app()->getLocale(), '/') }}">
            <span property="name">{{ __('meta.finca3pinos')}}</span>
          </a>
          <meta property="position" content="1">
        </span>
                </li>
                <li class="post post-page current-item">
        <span property="itemListElement" typeof="ListItem">
          <span property="name">{{ __('meta.cart') }}</span>
          <meta property="position" content="2">
        </span>
                </li>
            </ul>
        </div>
    </header>


    <div class="container">
        <div class="margin-default">
            <div class="row">
                <div class=" col-md-12 text-page">
                    <article id="post-615" class="post-615 page type-page status-publish hentry">
                        <div class="entry-content clearfix" id="entry-div">
                            <div class="woocommerce">
                                <div class="woocommerce-notices-wrapper"></div>
                                <form class="woocommerce-cart-form" action="https://coffeeking.like-themes.com/cart/" method="post">
                                    <table class="shop_table shop_table_responsive cart woocommerce-cart-form__contents" cellspacing="0">
                                        <thead>
                                        <tr>
                                            <th class="product-remove"><span class="screen-reader-text">Remove item</span></th>
                                            <th class="product-thumbnail"><span class="screen-reader-text">Thumbnail image</span></th>
                                            <th class="product-name">{{ __('meta.products')}}</th>
                                            <th class="product-price">{{ __('meta.price')}}</th>
                                            <th class="product-quantity">{{ __('meta.quantity')}}</th>
                                            <th class="product-subtotal">{{ __('meta.subtotal')}}</th>
                                        </tr>
                                        </thead>

                                        <tbody id="cart-body">
                                        @forelse($items as $item)
                                            <tr class="woocommerce-cart-form__cart-item cart_item"
                                                data-row="{{ $item['row_id'] }}">
                                                <td class="product-remove">
                                                    <a href="#" class="remove" aria-label="Remove item">×</a>
                                                </td>
                                                <td class="product-thumbnail">
                                                    <img src="{{ $item['image'] }}"
                                                         alt="{{ $item['name'] }}"
                                                         width="120"
                                                         height="120"
                                                         style="border-radius: 8px; object-fit: cover;">
                                                </td>
                                                <td class="product-name">{{ $item['name'] }}</td>
                                                <td class="product-price"><span class="amount"><bdi>${{ number_format($item['price'],2) }}</bdi></span></td>
                                                <td class="product-quantity">
                                                    <div class="quantity">
                                                        <input type="number" class="input-text qty text qty-input"
                                                               value="{{ $item['qty'] }}" min="0" step="1">
                                                    </div>
                                                </td>
                                                <td class="product-subtotal">
                                                    <span class="amount row-total"><bdi>${{ number_format($item['row_total'],2) }}</bdi></span>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr><td colspan="6" class="text-center">{{ __('meta.your_cart_empty')}}</td></tr>
                                        @endforelse

                                        <tr>
                                            <td colspan="6" class="actions" style="text-align:right;">


                                                <button type="button" class="button btn" id="btn-update-all">{{ __('meta.update_cart')}}</button>



                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </form>

                                <!-- Totales -->
                                <div class="cart-collaterals">
                                    <div class="cart_totals">
                                        <table cellspacing="0" class="shop_table shop_table_responsive">
                                            <tbody>
                                            <tr class="cart-subtotal">
                                                <th>{{ __('meta.subtotal')}}</th>
                                                <td data-title="Subtotal"><span class="price-badge" id="subtotal-badge">${{ number_format($subtotal,2) }}</span></td>
                                            </tr>
                                            </tbody>
                                        </table>
                                        <div class="wc-proceed-to-checkout">
                                            <a href="{{ route('checkout.show') }}"
                                               class="checkout-button button alt wc-forward btn">
                                                {{ __('meta.proceed_to_checkout') }}
                                            </a>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </article>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="{{ asset('js/toastr.min.js') }}"></script>
    <script src="{{ asset('js/axios.min.js') }}"></script>
    <script src="{{ asset('js/sweetalert2.all.min.js') }}"></script>
    <script src="{{ asset('js/alertaPersonalizada.js') }}"></script>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const body = document.getElementById('cart-body');
            const subtotalBadge = document.getElementById('subtotal-badge');
            const btnUpdate = document.getElementById('btn-update-all');

            axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
            axios.defaults.headers.common['X-CSRF-TOKEN'] =
                document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            const i18n = {
                errorDesconocidoMessage: "{{ __('meta.unknown_error') }}",
                cartEmpty: "{{ __('meta.your_cart_empty') }}",
                carritoActualizadoMessage: "{{ __('meta.update') }}",
            };

            // Eliminar
            body.addEventListener('click', async (e) => {
                const btn = e.target.closest('.remove');
                if (!btn) return;
                e.preventDefault();

                const tr = btn.closest('tr[data-row]');
                const rowId = tr?.dataset.row;
                if (!rowId) return;

                try {
                    const { data } = await axios.post("{{ route('cart.remove') }}", { row_id: rowId });

                    tr.remove();
                    subtotalBadge.textContent = '$' + Number(data.subtotal).toFixed(2);

                    // Si el carrito quedó vacío -> usar template literal
                    if (!body.querySelector('tr[data-row]')) {
                        body.insertAdjacentHTML(
                            'afterbegin',
                            `<tr><td colspan="6" class="text-center">${i18n.cartEmpty}</td></tr>`
                        );
                    }

                    // Actualiza navbar
                    window.dispatchEvent(new CustomEvent('cart:updated', {
                        detail: { count: data.count, subtotal: data.subtotal }
                    }));
                } catch {
                    toastr.error(i18n.errorDesconocidoMessage);
                }
            });

            // === BOTÓN "UPDATE CART" === (único listener)
            btnUpdate?.addEventListener('click', async () => {
                const rows = body.querySelectorAll('tr[data-row]');
                if (!rows.length) {
                    return toastr.info(i18n.cartEmpty);
                }

                // Bloquear botón para evitar múltiples clics
                btnUpdate.disabled = true;

                try {
                    // Puedes hacerlo en serie o en paralelo; aquí en paralelo:
                    const requests = [];
                    for (const tr of rows) {
                        const input = tr.querySelector('.qty-input');
                        if (!input) continue;

                        const rowId = tr.dataset.row;
                        const qty = parseInt(input.value || '0', 10);

                        requests.push(
                            axios.post("{{ route('cart.update') }}", { row_id: rowId, qty })
                                .then(({ data }) => {
                                    // Actualiza total por fila
                                    const bdi = tr.querySelector('.row-total bdi');
                                    if (bdi) bdi.textContent = '$' + Number(data.rowTotal).toFixed(2);

                                    // Si qty llegó a 0 y el backend removió el item, quita la fila
                                    if (qty === 0) tr.remove();

                                    return data; // para colectar último subtotal / count
                                })
                        );
                    }

                    const results = await Promise.all(requests);

                    // Si se eliminaron todas las filas, agrega mensaje vacío
                    if (!body.querySelector('tr[data-row]')) {
                        body.insertAdjacentHTML(
                            'afterbegin',
                            `<tr><td colspan="6" class="text-center">${i18n.cartEmpty}</td></tr>`
                        );
                    }

                    // Toma el último estado agregado por el backend
                    const last = results[results.length - 1];
                    const subtotalAcum = Number(last?.subtotal ?? 0);
                    const totalItems   = Number(last?.count ?? 0);

                    subtotalBadge.textContent = '$' + subtotalAcum.toFixed(2);

                    // Un solo toast de éxito
                    toastr.success(i18n.carritoActualizadoMessage);

                    // Actualiza navbar una vez
                    window.dispatchEvent(new CustomEvent('cart:updated', {
                        detail: { count: totalItems, subtotal: subtotalAcum }
                    }));

                } catch (err) {
                    console.error(err);
                    toastr.error(i18n.errorDesconocidoMessage);
                } finally {
                    btnUpdate.disabled = false;
                }
            });

            // === BOTÓN "PROCEDER AL CHECKOUT" ===
            const btnCheckout = document.querySelector('.wc-proceed-to-checkout .checkout-button');

            btnCheckout?.addEventListener('click', (e) => {
                // ¿Hay filas de items?
                const hasItems = !!document.querySelector('#cart-body tr[data-row]');
                if (!hasItems) {
                    e.preventDefault();
                    toastr.info(i18n.cartEmpty); // "Tu carrito está vacío"
                }
            });

            // ⚠️ Eliminado el segundo listener que disparaba otro toastr.success
        });
    </script>


    {{-- Superior (Newsletter) block --}}
    @include('frontend.partials.superior')
@endsection
