@extends('frontend.layouts.app')

@section('title', __('meta.title'))

@section('content')

    <style>

        /* ---- Quita fondo/padding del wrapper de CF7 ---- */
        #like_sc_contact_form_7_122453212,
        #like_sc_contact_form_7_122453212.form-bg-default,
        #like_sc_contact_form_7_122453212 .wpcf7,
        #like_sc_contact_form_7_122453212 .wpcf7-form {
            background: transparent !important;
            border: 0 !important;
            box-shadow: none !important;
            padding: 0 !important;
        }

        /* WPBakery custom bg que suele meter gris */
        #like_sc_contact_form_7_122453212 .vc_custom_1505582392596 {
            background: transparent !important;
            padding: 0 !important;
            border-radius: 0 !important;
        }

        /* =========================
           Card principal del formulario
           ========================= */
        .contact-card {
            background: #ffffff;
            border-radius: 16px;
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.06);
            padding: 22px;
            overflow: hidden;
        }

        /* Limpia fondos de envoltorios CF7 */
        .contact-card p,
        .contact-card label,
        .contact-card .wpcf7-form-control-wrap {
            background: transparent !important;
            margin-bottom: 12px;
        }

        /* =========================
           Tipografía base inputs
           ========================= */
        .contact-card .wpcf7-form-control {
            font-weight: 400 !important; /* quita negrita */
            font-family: "Roboto", system-ui, -apple-system, Segoe UI, Arial, sans-serif;
            color: #333;
            letter-spacing: 0.2px;
        }

        /* =========================
           Inputs y Textareas
           ========================= */
        .contact-card input[type="text"],
        .contact-card input[type="email"],
        .contact-card textarea {
            width: 100%;
            background: #fafafa;
            border: 1px solid rgba(0, 0, 0, 0.12);
            border-radius: 10px;
            padding: 10px 12px;
            outline: none;
            font-size: 15px;
            transition: all 0.2s ease;
        }

        /* Placeholders más suaves */
        .contact-card input::placeholder,
        .contact-card textarea::placeholder {
            color: #9a9a9a;
            font-weight: 400;
        }

        /* Forzar minúsculas en email sin inline */
        #correo-form {
            text-transform: lowercase;
        }

        /* Foco elegante */
        .contact-card input[type="text"]:focus,
        .contact-card input[type="email"]:focus,
        .contact-card textarea:focus {
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

        /* =========================
           Botón Enviar (aplica a submit y button)
           ========================= */
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

        /* =========================
           Mensaje de éxito / error CF7
           ========================= */
        .contact-card .wpcf7-response-output {
            margin: 14px 0 0 !important;
            border-radius: 10px !important;
            background: #f7f7f7;
            border: 1px solid rgba(0, 0, 0, 0.1);
        }

        /* =========================
           Distribución dos columnas
           ========================= */
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

        /* =========================
           Social / Íconos de contacto
           ========================= */
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

        /* =========================
           Mini botones redes
           ========================= */
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

        /* =========================
           Acciones
           ========================= */
        .contact-card__actions {
            margin-top: 10px;
            display: flex;
            justify-content: center;
        }

        /* =========================
           Errores de campo
           ========================= */
        .field-error {
            color: #d93025;
            font-size: .85rem;
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

        /* =========================
           Contador de caracteres
           ========================= */
        .char-counter {
            font-size: .85rem;
            text-align: right;
            opacity: .85;
            margin-top: 4px;
        }

        .char-counter.warning {
            color: #c77d00;
        }

        .char-counter.danger {
            color: #d93025;
        }

        /* Reafirmar bordes/colores del form */
        #contact-form input[type="text"],
        #contact-form input[type="email"],
        #contact-form textarea {
            border: 1px solid rgba(0, 0, 0, 0.12);
            background: #fafafa;
            transition: all 0.2s ease;
        }

        #contact-form input:focus,
        #contact-form textarea:focus {
            border-color: #c6a471;
            background: #fff;
        }

        #btn-enviar:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        .spinner {
            border: 3px solid #f3f3f3; /* gris claro */
            border-top: 3px solid #d2aa6d; /* dorado marca */
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

        button[disabled] {
            opacity: 0.7;
            cursor: not-allowed;
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
            transition: transform .3s ease, box-shadow .3s ease;
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
                                <form class="woocommerce-cart-form" action="https://coffeeking.like-themes.com/cart/"
                                      method="post">
                                    <table class="shop_table shop_table_responsive cart woocommerce-cart-form__contents"
                                           cellspacing="0">
                                        <thead>
                                        <tr>
                                            <th class="product-remove"><span
                                                    class="screen-reader-text">Remove item</span></th>
                                            <th class="product-thumbnail"><span class="screen-reader-text">Thumbnail image</span>
                                            </th>
                                            <th class="product-name">Product</th>
                                            <th class="product-price">Price</th>
                                            <th class="product-quantity">Quantity</th>
                                            <th class="product-subtotal">Subtotal</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr class="woocommerce-cart-form__cart-item cart_item">
                                            <td class="product-remove"><a
                                                    href="https://coffeeking.like-themes.com/cart/?remove_item=d6deddc2d42271e71c038b636b2044d0&amp;_wpnonce=1ddef4ce79"
                                                    class="remove" aria-label="Remove Ephiopian Aroma - 100g from cart"
                                                    data-product_id="2071" data-product_sku="">×</a></td>
                                            <td class="product-thumbnail"><a
                                                    href="https://coffeeking.like-themes.com/product/ephiopian-aroma/?attribute_weight=100g"><img
                                                        fetchpriority="high" decoding="async" width="300" height="300"
                                                        src="https://coffeeking.like-themes.com/wp-content/uploads/2017/09/coffee_item3-300x300.jpg"
                                                        class="attachment-woocommerce_thumbnail size-woocommerce_thumbnail"
                                                        alt="Ephiopian Aroma - 100g"
                                                        srcset="https://coffeeking.like-themes.com/wp-content/uploads/2017/09/coffee_item3-300x300.jpg 300w, https://coffeeking.like-themes.com/wp-content/uploads/2017/09/coffee_item3-150x150.jpg 150w, https://coffeeking.like-themes.com/wp-content/uploads/2017/09/coffee_item3-600x600.jpg 600w, https://coffeeking.like-themes.com/wp-content/uploads/2017/09/coffee_item3-100x100.jpg 100w, https://coffeeking.like-themes.com/wp-content/uploads/2017/09/coffee_item3.jpg 623w"
                                                        sizes="(max-width: 300px) 100vw, 300px"></a></td>
                                            <td class="product-name" data-title="Product"><a
                                                    href="https://coffeeking.like-themes.com/product/ephiopian-aroma/?attribute_weight=100g">Ephiopian
                                                    Aroma - 100g</a></td>
                                            <td class="product-price" data-title="Price"><span
                                                    class="woocommerce-Price-amount amount"><bdi><span
                                                            class="woocommerce-Price-currencySymbol">$</span>9.00</bdi></span>
                                            </td>
                                            <td class="product-quantity" data-title="Quantity">
                                                <div class="quantity"><label class="screen-reader-text"
                                                                             for="quantity_6902555ad57bf">Ephiopian
                                                        Aroma - 100g quantity</label> <input type="number"
                                                                                             id="quantity_6902555ad57bf"
                                                                                             class="input-text qty text"
                                                                                             name="cart[d6deddc2d42271e71c038b636b2044d0][qty]"
                                                                                             value="1"
                                                                                             aria-label="Product quantity"
                                                                                             min="0" max="" step="1"
                                                                                             placeholder=""
                                                                                             inputmode="numeric"
                                                                                             autocomplete="off"><span
                                                        class="more"></span><span class="less"></span></div>
                                            </td>
                                            <td class="product-subtotal" data-title="Subtotal"><span
                                                    class="woocommerce-Price-amount amount"><bdi><span
                                                            class="woocommerce-Price-currencySymbol">$</span>9.00</bdi></span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="6" class="actions">
                                                <div class="coupon"><label for="coupon_code" class="screen-reader-text">Coupon:</label>
                                                    <input type="text" name="coupon_code" class="input-text"
                                                           id="coupon_code" value="" placeholder="Coupon code">
                                                    <button type="submit" class="button btn" name="apply_coupon"
                                                            value="Apply coupon">Apply coupon
                                                    </button>
                                                </div>
                                                <button type="submit" class="button btn" name="update_cart"
                                                        value="Update cart" disabled="">Update cart
                                                </button>
                                                <input type="hidden" id="woocommerce-cart-nonce"
                                                       name="woocommerce-cart-nonce" value="1ddef4ce79"><input
                                                    type="hidden" name="_wp_http_referer" value="/cart/"></td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </form>
                                <div class="cart-collaterals">
                                    <div class="cart_totals "><h2>Cart totals</h2>
                                        <table cellspacing="0" class="shop_table shop_table_responsive">
                                            <tbody>
                                            <tr class="cart-subtotal">
                                                <th>Subtotal</th>
                                                <td data-title="Subtotal"><span class="woocommerce-Price-amount amount"><bdi><span
                                                                class="woocommerce-Price-currencySymbol">$</span>9.00</bdi></span>
                                                </td>
                                            </tr>
                                            <tr class="order-total">
                                                <th>Total</th>
                                                <td data-title="Total"><strong><span
                                                            class="woocommerce-Price-amount amount"><bdi><span
                                                                    class="woocommerce-Price-currencySymbol">$</span>9.00</bdi></span></strong>
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>
                                        <div class="wc-proceed-to-checkout"><a
                                                href="https://coffeeking.like-themes.com/checkout/"
                                                class="checkout-button button alt wc-forward btn"> Proceed to
                                                checkout</a></div>
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


    </script>


    {{-- Superior (Newsletter) block --}}
    @include('frontend.partials.superior')
@endsection


