@extends('frontend.layouts.app')

@section('title', __('meta.title'))

@section('content')

    <style>

        /* ============================================================
   LIMPIEZA DE FONDOS Y ENVOLTURAS
   ============================================================ */

        #like_sc_contact_form_7_122453212,
        #like_sc_contact_form_7_122453212.form-bg-default,
        #like_sc_contact_form_7_122453212 .wpcf7,
        #like_sc_contact_form_7_122453212 .wpcf7-form {
            background: transparent !important;
            border: 0 !important;
            box-shadow: none !important;
            padding: 0 !important;
        }

        #like_sc_contact_form_7_122453212 .vc_custom_1505582392596 {
            background: transparent !important;
            padding: 0 !important;
            border-radius: 0 !important;
        }

        /* ============================================================
           CARD PRINCIPAL DEL FORMULARIO
           ============================================================ */

        .contact-card {
            background: #ffffff;
            border-radius: 16px;
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.06);
            padding: 22px;
            overflow: hidden;
        }

        .contact-card p,
        .contact-card label,
        .contact-card .wpcf7-form-control-wrap {
            background: transparent !important;
            margin-bottom: 12px;
        }

        /* ============================================================
           TIPOGRAFÍA Y INPUTS
           ============================================================ */

        .contact-card .wpcf7-form-control {
            font-weight: 400 !important;
            font-family: "Roboto", system-ui, -apple-system, Segoe UI, Arial, sans-serif;
            color: #333;
            letter-spacing: 0.2px;
        }

        .contact-card input[type="text"],
        .contact-card input[type="email"],
        .contact-card textarea,
        #contact-form input[type="text"],
        #contact-form input[type="email"],
        #contact-form textarea {
            width: 100%;
            background: #fafafa;
            border: 1px solid rgba(0, 0, 0, 0.12);
            border-radius: 10px;
            padding: 10px 12px;
            outline: none;
            font-size: 15px;
            transition: all 0.2s ease;
        }

        .contact-card input::placeholder,
        .contact-card textarea::placeholder {
            color: #9a9a9a;
            font-weight: 400;
        }

        #correo-form {
            text-transform: lowercase;
        }

        /* Foco elegante */
        .contact-card input[type="text"]:focus,
        .contact-card input[type="email"]:focus,
        .contact-card textarea:focus,
        #contact-form input:focus,
        #contact-form textarea:focus {
            border-color: #c6a471;
            background: #fff;
            box-shadow: 0 0 0 3px rgba(198, 164, 113, 0.15);
        }

        /* Ajuste textarea */
        .contact-card textarea {
            min-height: 160px;
            resize: vertical;
        }

        /* Autofill (Chrome) */
        .contact-card input:-webkit-autofill,
        .contact-card textarea:-webkit-autofill {
            -webkit-box-shadow: 0 0 0 1000px #fff inset;
            box-shadow: 0 0 0 1000px #fff inset;
            -webkit-text-fill-color: #333;
        }

        /* ============================================================
           BOTONES - ENVIAR Y SUBMIT
           ============================================================ */

        .contact-card input[type="submit"],
        .contact-card input[type="button"],
        .contact-card .wpcf7-submit {
            background: #c6a471 !important;
            color: #fff !important;
            border: none !important;
            font-weight: 700 !important;
            padding: 12px 28px !important;
            border-radius: 10px !important;
            cursor: pointer !important;
            transition: all 0.25s ease !important;
            display: inline-block !important;
        }

        .contact-card input[type="submit"]:hover,
        .contact-card input[type="button"]:hover,
        .contact-card .wpcf7-submit:hover {
            background: #b8935e !important;
            transform: translateY(-2px);
            box-shadow: 0 6px 14px rgba(0, 0, 0, 0.15);
        }

        #btn-enviar:disabled,
        button[disabled] {
            opacity: 0.7;
            cursor: not-allowed;
        }

        /* ============================================================
           MENSAJES DE ÉXITO / ERROR
           ============================================================ */

        .contact-card .wpcf7-response-output {
            margin: 14px 0 0 !important;
            border-radius: 10px !important;
            background: #f7f7f7;
            border: 1px solid rgba(0, 0, 0, 0.1);
        }

        /* ============================================================
           DISTRIBUCIÓN DOS COLUMNAS
           ============================================================ */

        .contact-two-col {
            display: grid !important;
            grid-template-columns: 1fr;
            gap: 20px;
            align-items: stretch;
        }

        @media (min-width: 992px) {
            .contact-two-col {
                grid-template-columns: 1fr 1fr;
            }
        }

        .contact-two-col > .vc_column_container {
            width: 100% !important;
            float: none !important;
            padding-left: 0 !important;
            padding-right: 0 !important;
        }

        .contact-two-col .vc_column-inner {
            padding-top: 0 !important;
            padding-bottom: 0 !important;
        }

        /* ============================================================
           DATOS DE CONTACTO E ÍCONOS
           ============================================================ */

        .contact-card .fa {
            color: #d2aa6d;
            font-size: 18px;
            width: 22px;
            text-align: center;
        }

        .contact-card .contact-data {
            margin: 14px 0;
            padding: 0;
            list-style: none;
        }

        .contact-card .contact-data li {
            display: flex;
            align-items: center;
            gap: 10px;
            margin: 10px 0;
        }

        /* ============================================================
           BOTONES REDES SOCIALES
           ============================================================ */

        .contact-card .social-cta {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            padding: 0;
            margin: 10px 0 0;
            list-style: none;
        }

        .contact-card .social-cta .scb {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 14px;
            border-radius: 999px;
            text-decoration: none;
            font-weight: 600;
            background: #fff;
            border: 1px solid rgba(0, 0, 0, 0.08);
            transition: transform 0.12s ease, box-shadow 0.2s ease, border-color 0.2s ease;
        }

        .contact-card .social-cta .scb:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.12);
        }

        .contact-card .social-cta .fb {
            color: #1877F2;
        }

        .contact-card .social-cta .ig {
            color: #E4405F;
        }

        .contact-card .social-cta .tt {
            color: #111;
        }

        .contact-card .social-cta .tt .tt-ico {
            width: 16px;
            height: 16px;
            fill: #111;
        }

        .social-list {
            display: flex;
            gap: 10px;
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .social-list li a {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 60px;
            height: 60px;
            background: #ffffff;
            border-radius: 50%;
            overflow: hidden;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .social-list li a:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }

        .social-icon {
            width: 35px;
            height: 35px;
            object-fit: contain;
        }

        /* ============================================================
           ACCIONES Y VALIDACIÓN
           ============================================================ */

        .contact-card__actions {
            margin-top: 10px;
            display: flex;
            justify-content: center;
        }

        .field-error {
            color: #d93025;
            font-size: 0.85rem;
            margin-top: 6px;
            line-height: 1.3;
        }

        .field-error:empty {
            display: none;
        }

        .is-invalid {
            border-color: #d93025 !important;
            box-shadow: none !important;
        }

        /* ============================================================
           CONTADOR DE CARACTERES
           ============================================================ */

        .char-counter {
            font-size: 0.85rem;
            text-align: right;
            opacity: 0.85;
            margin-top: 4px;
        }

        .char-counter.warning {
            color: #c77d00;
        }

        .char-counter.danger {
            color: #d93025;
        }

        /* ============================================================
           SPINNER LOADING
           ============================================================ */

        .spinner {
            border: 3px solid #f3f3f3;
            border-top: 3px solid #d2aa6d;
            border-radius: 50%;
            width: 16px;
            height: 16px;
            animation: spin 0.8s linear infinite;
            display: inline-block;
            vertical-align: middle;
            margin-right: 8px;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }
            100% {
                transform: rotate(360deg);
            }
        }

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

        .shop_table.cart td.product-quantity {
            text-align: center;
            vertical-align: middle;
        }

        .shop_table.cart .quantity {
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .shop_table.cart .quantity .input-text.qty {
            width: 60px;
            height: 36px;
            padding: 4px 8px;
            text-align: center;
            border: 1px solid rgba(0, 0, 0, 0.15);
            border-radius: 6px;
            outline: none;
            font-weight: 500;
            font-size: 15px;
            color: #333;
            transition: all 0.2s ease;
        }

        .shop_table.cart .quantity .input-text.qty:focus {
            border-color: #d2aa6d;
            box-shadow: 0 0 0 2px rgba(210, 170, 109, 0.15);
        }

        /* ============================================================
           BOTÓN ELIMINAR (X)
           ============================================================ */

        .shop_table.cart td.product-remove {
            width: 60px;
            text-align: center;
            vertical-align: middle;
            padding: 12px 10px;
        }

        .shop_table.cart td.product-remove .remove {
            display: inline-flex !important;
            align-items: center;
            justify-content: center;
            width: 30px;
            height: 30px;
            border-radius: 50% !important;
            background: #fff3e0;
            color: #d62d20;
            font-size: 18px;
            font-weight: bold;
            text-decoration: none !important;
            border: 1px solid rgba(0, 0, 0, 0.1);
            line-height: 1;
            transition: all 0.2s ease;
            box-sizing: border-box;
        }

        .shop_table.cart td.product-remove .remove:hover {
            background: #e74c3c;
            color: #fff;
            transform: scale(1.05);
            box-shadow: 0 3px 8px rgba(0, 0, 0, 0.15);
        }

        /* ============================================================
           TOTALES DEL CARRITO
           ============================================================ */

        .cart-collaterals {
            display: flex;
            justify-content: flex-end;
        }

        .cart_totals {
            width: 420px;
            max-width: 100%;
        }

        .cart_totals h2 {
            display: none;
        }

        .cart_totals .shop_table {
            width: 100%;
            border: none;
            border-radius: 0;
            overflow: visible;
        }

        .cart_totals .shop_table th,
        .cart_totals .shop_table td {
            padding: 12px 16px;
        }

        .cart_totals .order-total th,
        .cart_totals .order-total td {
            font-size: 1.4rem;
            font-weight: 800;
        }

        .cart_totals .cart-subtotal td {
            text-align: right;
        }

        .cart_totals .cart-subtotal td .price-badge {
            display: inline-block;
            padding: 8px 12px;
            background: #fff;
            border: 1px solid rgba(0, 0, 0, 0.15);
            border-radius: 8px;
            font-weight: 700;
            letter-spacing: 0.1px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.08);
        }

        /* ============================================================
           BOTÓN CHECKOUT
           ============================================================ */

        .wc-proceed-to-checkout .checkout-button {
            display: inline-block;
            width: auto;
            margin-top: 14px;
            background: #d2aa6d !important;
            border-radius: 10px !important;
            padding: 12px 24px !important;
            font-weight: 700 !important;
            color: #fff !important;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.06);
            transition: transform 0.15s ease, box-shadow 0.2s ease;
        }

        .wc-proceed-to-checkout .checkout-button:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.12);
        }

        /* ============================================================
           RESPONSIVE - MOBILE
           ============================================================ */

        @media (max-width: 991px) {
            .cart-collaterals {
                justify-content: center;
            }

            .cart_totals {
                width: 100%;
            }

            .shop_table.cart td.product-thumbnail img {
                width: 100px;
            }
        }




        /* Botón X perfectamente circular, desktop y móvil */
        .woocommerce table.shop_table.cart td.product-remove > a.remove,
        table.shop_table.cart td.product-remove > a.remove {
            display: inline-grid !important;
            place-items: center !important;
            width: 30px !important;
            height: 30px !important;
            aspect-ratio: 1 / 1;
            padding: 0 !important;
            margin: 0 auto !important;
            border-radius: 50% !important;
            line-height: 1 !important;
            background: #fff3e0 !important;
            color: #d62d20 !important;
            border: 1px solid rgba(0,0,0,.1) !important;
            font-size: 18px !important;
            text-decoration: none !important;
            box-sizing: border-box !important;
        }

        /* Algunos themes dibujan la X con :before y añaden medidas/padding */
        .woocommerce a.remove:before {
            position: static !important;  /* evita offsets raros */
            display: inline !important;
            width: auto !important;
            height: auto !important;
            line-height: 1 !important;
            margin: 0 !important;
            padding: 0 !important;
        }

        /* Opcional: hover */
        .woocommerce table.shop_table.cart td.product-remove > a.remove:hover {
            background: #e74c3c !important;
            color: #fff !important;
            transform: scale(1.05);
            box-shadow: 0 3px 8px rgba(0,0,0,.15);
        }

        /* QTY sin flechas nativas */
        .shop_table.cart .quantity .input-text.qty,
        .woocommerce .quantity .qty,
        input[type="number"] {
            -moz-appearance: textfield;   /* Firefox */
            appearance: textfield;        /* Estándar */
        }

        /* Chrome / Edge / Safari (WebKit) */
        .shop_table.cart .quantity .input-text.qty::-webkit-outer-spin-button,
        .shop_table.cart .quantity .input-text.qty::-webkit-inner-spin-button,
        input[type="number"]::-webkit-outer-spin-button,
        input[type="number"]::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }


        /* Elimina el borde cuadrado del contenedor de totales */
        .cart_totals .shop_table,
        .cart_totals .shop_table td,
        .cart_totals .shop_table th {
            border: none !important;
            background: transparent !important;
            box-shadow: none !important;
        }

        /* Asegura bordes redondeados limpios */
        .cart_totals {
            border: none !important;
            border-radius: 12px !important;
            overflow: hidden !important;
            background: transparent !important;
        }



        /* ===========================
   CART — MOBILE OPTIMIZADO
   =========================== */
        @media (max-width: 640px) {

            /* 1) Layout tipo card por fila */
            .shop_table.cart thead { display: none !important; }

            .shop_table.cart tbody tr.cart_item {
                display: grid !important;
                grid-template-columns: 36px 64px 1fr;     /* remove | thumb | info */
                grid-template-areas:
    "remove thumb name"
    "remove thumb qty"
    "remove thumb subtotal";
                gap: 8px 10px;
                padding: 10px 8px !important;
                border: 1px solid rgba(0,0,0,.06);
                border-radius: 12px;
                margin-bottom: 10px;
            }

            /* 2) Asignar áreas */
            .shop_table.cart td.product-remove   { grid-area: remove; align-self: start; text-align: left; }
            .shop_table.cart td.product-thumbnail{ grid-area: thumb; }
            .shop_table.cart td.product-name     { grid-area: name; }
            .shop_table.cart td.product-quantity { grid-area: qty; }
            .shop_table.cart td.product-subtotal { grid-area: subtotal; text-align: right; }

            /* 3) Miniaturas y textos */
            .shop_table.cart td.product-thumbnail img {
                width: 64px; height: auto; border-radius: 8px;
            }
            .shop_table.cart td { padding: 0 !important; }
            .shop_table.cart td.product-name { font-size: 15px; line-height: 1.25; }

            /* 4) Cantidad compacta */
            .shop_table.cart .quantity { justify-content: flex-start; }
            .shop_table.cart .quantity .input-text.qty {
                width: 56px; height: 34px; font-size: 14px;
            }

            /* 5) Botón X clicable y alineado */
            .woocommerce table.shop_table.cart td.product-remove > a.remove {
                width: 32px !important; height: 32px !important; font-size: 18px !important;
            }

            /* 6) Quitar labels automáticos de Woo en mobile (para no duplicar) */
            table.shop_table_responsive tr td::before { display: none !important; }

            /* 7) Totales a ancho completo + botón grande */
            .cart-collaterals { justify-content: stretch; }
            .cart_totals { width: 100%; max-width: 100%; }
            .cart_totals .shop_table td,
            .cart_totals .shop_table th { padding: 8px 10px; }

            .wc-proceed-to-checkout .checkout-button {
                width: 100% !important;
                padding: 14px 18px !important;
                font-size: 16px !important;
                border-radius: 12px !important;
            }

            /* 8) Respiro general del contenedor */
            .text-page .entry-content .woocommerce { padding: 0 8px; }
        }

        @media (max-width: 640px) {

            /* ===== Diferenciar precio vs subtotal ===== */
            .shop_table.cart td.product-price {
                color: #777 !important;
                font-size: 14px !important;
                font-weight: 500;
                margin-top: 4px;
            }

            .shop_table.cart td.product-subtotal {
                color: #111 !important;
                font-size: 16px !important;
                font-weight: 700 !important;
                margin-top: 2px;
            }

            /* Orden y alineación más clara */
            .shop_table.cart td.product-price,
            .shop_table.cart td.product-subtotal {
                display: block;
                text-align: right;
            }

            /* Separar visualmente los valores */
            .shop_table.cart tbody tr.cart_item {
                padding-bottom: 14px !important;
            }

            /* (Opcional) añadir etiqueta sutil al precio */
            .shop_table.cart td.product-price::before {
                content: "Precio:";
                color: #999;
                font-size: 12px;
                font-weight: 400;
                margin-right: 4px;
            }

            /* (Opcional) añadir etiqueta sutil al subtotal */
            .shop_table.cart td.product-subtotal::before {
                content: "Subtotal:";
                color: #999;
                font-size: 12px;
                font-weight: 400;
                margin-right: 4px;
            }
        }


    </style>

    <header class="page-header like-parallax"
            style="background-image: url('{{ asset('images/inner_parallax.jpg') }}');
               background-size: cover;
               background-position: center;
               background-repeat: no-repeat;">
        <div class="container" bis_skin_checked="1"><h1>{{ __('meta.cart') }}</h1>
            <ul class="breadcrumbs" typeof="BreadcrumbList" vocab="https://schema.org/">
                <li class="home"><span property="itemListElement" typeof="ListItem"><a property="item" typeof="WebPage"
                                                                                       title="{{ __('meta.go_to_finca3pinos') }}"
                                                                                       href="{{ LaravelLocalization::getLocalizedURL(app()->getLocale(), '/') }}"
                                                                                       class="home"
                                                                                       bis_skin_checked="1">
                            <span property="name">{{ __('meta.finca3pinos') }}</span></a><meta property="position"
                                                                                               content="1"></span></li>
                <li class="post post-page current-item"><span property="itemListElement" typeof="ListItem"><span
                            property="name">{{ __('meta.cart') }}</span><meta property="position" content="2"></span>
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
                                            <th class="product-name">Product</th>
                                            <th class="product-price">Price</th>
                                            <th class="product-quantity">Quantity</th>
                                            <th class="product-subtotal">Subtotal</th>
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
                                            <tr><td colspan="6" class="text-center">Tu carrito está vacío.</td></tr>
                                        @endforelse

                                        <tr>
                                            <td colspan="6" class="actions" style="text-align:right;">
                                                <button type="button" class="button btn" id="btn-update-all">Update cart</button>
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
                                                <th>Subtotal</th>
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

            axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
            axios.defaults.headers.common['X-CSRF-TOKEN'] =
                document.querySelector('meta[name="csrf-token"]').getAttribute('content');




            // Eliminar
            body.addEventListener('click', async (e) => {
                const btn = e.target.closest('.remove');
                if (!btn) return;
                e.preventDefault();
                const tr = btn.closest('tr[data-row]');
                const rowId = tr.dataset.row;

                try {
                    const { data } = await axios.post("{{ route('cart.remove') }}", { row_id: rowId });

                    tr.remove();
                    subtotalBadge.textContent = '$' + Number(data.subtotal).toFixed(2);

                    // Si el carrito quedó vacío
                    if (!body.querySelector('tr[data-row]')) {
                        body.insertAdjacentHTML(
                            'afterbegin',
                            '<tr><td colspan="6" class="text-center">Tu carrito está vacío.</td></tr>'
                        );
                    }

                    // 🔹 Dispara evento global para actualizar el navbar
                    window.dispatchEvent(new CustomEvent('cart:updated', {
                        detail: { count: data.count, subtotal: data.subtotal }
                    }));

                } catch {
                    toastr.error('No se pudo eliminar.');
                }
            });

            // === BOTÓN "UPDATE CART" ===
            document.getElementById('btn-update-all')?.addEventListener('click', async () => {
                const rows = body.querySelectorAll('tr[data-row]');
                if (!rows.length) return toastr.info('Tu carrito está vacío.');

                let subtotalAcum = 0;
                let totalItems = 0;

                try {
                    // Recorremos todas las filas visibles
                    for (const tr of rows) {
                        const input = tr.querySelector('.qty-input');
                        if (!input) continue;
                        const rowId = tr.dataset.row;
                        const qty = parseInt(input.value || '0', 10);

                        // Llamada individual para cada fila
                        const { data } = await axios.post("{{ route('cart.update') }}", { row_id: rowId, qty });

                        // Actualiza subtotal de la fila
                        tr.querySelector('.row-total bdi').textContent = '$' + Number(data.rowTotal).toFixed(2);

                        subtotalAcum = Number(data.subtotal); // se actualiza con cada respuesta
                        totalItems   = data.count;
                    }

                    // Actualiza subtotal global y notificación
                    subtotalBadge.textContent = '$' + subtotalAcum.toFixed(2);
                    toastr.success('Carrito actualizado correctamente.');

                    // 🔹 Actualiza el contador del navbar (evento global)
                    window.dispatchEvent(new CustomEvent('cart:updated', {
                        detail: { count: totalItems, subtotal: subtotalAcum }
                    }));

                } catch (err) {
                    console.error(err);
                    toastr.error('Ocurrió un error al actualizar el carrito.');
                }
            });


            document.getElementById('btn-update-all')?.addEventListener('click', () => toastr.success('Carrito actualizado'));
        });
    </script>




    {{-- Superior (Newsletter) block --}}
    @include('frontend.partials.superior')
@endsection


