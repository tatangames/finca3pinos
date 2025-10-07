<!DOCTYPE html>
<html lang="en">
<head>
    @php
        // Mantén todos los CSS/JS originales, sin usar helpers de Laravel para rutas.
    @endphp

    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width"/>
    <meta name="format-detection" content="telephone=no"/>
    <link rel="profile" href="https://gmpg.org/xfn/11"/>

    <!-- CSS Principal -->
    <link rel="stylesheet"
          href="{{ asset('frontend/css/autoptimize_2573919cda1422e0b1d148b679601d21.css') }}">


    <!-- CSS Responsive -->
    <link rel="stylesheet"
          href="{{ asset('frontend/css/autoptimize_6fe211f8bb15af76999ce9135805d7af.css') }}"
          media="only screen and (max-width: 768px)">

    <title>Finca 3 Pinos</title>

    <meta name="robots" content="max-image-preview:large"/>
    <link rel="dns-prefetch" href="http://fonts.googleapis.com/"/>



    <script src="https://twemoji.maxcdn.com/v/latest/twemoji.min.js" crossorigin="anonymous"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            twemoji.parse(document.body); // Reemplaza emojis por SVG de Twemoji
        });
    </script>

    <!-- Dashicons -->
      <link href="{{ asset('frontend/css/dashicons.min.css') }}"
          id="dashicons-css"
          media="all"
          rel="stylesheet"
          type="text/css"
    />


    <!-- Inline CSS Theme Styles -->
    <style id="coffeeking-theme-style-inline-css" type="text/css">
        .page-header {
            background-image: url("{{ asset('images/inner_parallax.jpg') }}") !important;
        }

        #block-footer {
            background-image: url("{{ asset('images/footer_map.png') }}") !important;
        }

        body.error404 {
            background-image: url("{{ asset('images/404.png') }}") !important;
        }

        /* Color Principal */
        nav.navbar.navbar-black #navbar ul.navbar-nav > li:not(.current-menu-parent):not(.current-menu-ancestor) > a:hover,
        nav.navbar.navbar-transparent #navbar ul.navbar-nav > li:not(.current-menu-parent):not(.current-menu-ancestor) > a:hover,
        a,
        a.black:hover,
        a.black:focus,
        .color-main {
            color: #c0aa83;
        }

        @media (min-width: 1199px) {
            nav.navbar #navbar ul.navbar-nav > li.current-menu-ancestor > a,
            nav.navbar #navbar ul.navbar-nav > li.current-menu-item > a,
            nav.navbar #navbar ul.navbar-nav > li.current-menu-parent > a,
            nav.navbar #navbar ul.navbar-nav > li.current_page_parent > a,
            nav.navbar #navbar ul.navbar-nav > li.current_page_item > a,
            nav.navbar #navbar ul.navbar-nav a:hover {
                color: #c0aa83;
            }
        }

        @media (min-width: 991px) {
            nav.navbar.navbar-transparent #navbar ul.navbar-nav > li > a:hover,
            nav.navbar.navbar-transparent #navbar ul.navbar-nav > li.page_item_has_children > a:hover:after,
            nav.navbar.navbar-transparent #navbar ul.navbar-nav > li.menu-item-has-children > a:hover:after,
            nav.navbar.navbar-transparent #navbar ul.navbar-nav > li.hasSub > a:hover:after {
                color: #c0aa83;
            }
        }

        /* Backgrounds */
        .woocommerce div.product .woocommerce-tabs ul.tabs li,
        .testimonials-list .arrow-left,
        .testimonials-list .arrow-right,
        footer .go-top {
            background-color: #c0aa83;
        }

        @media (max-width: 1199px) {
            nav.navbar #navbar {
                background-color: #c0aa83;
            }
        }

        /* Color Secundario */
        .color-second {
            color: #B34204;
        }

        .btn.btn-second,
        .btn.btn-add {
            background-color: #B34204;
        }

        /* Fuentes */
        html, body, div, table {
            font-family: 'Kanit';
            font-weight: 300;
        }

        h1, h2, h3, h4, h5, h6, .header, .subheader {
            font-family: 'Kanit';
            font-weight: 700;
        }
    </style>

    <!-- Google Fonts -->
    <link
        href="http://fonts.googleapis.com/css?family=Kanit:300,900,700&subset=latin-ext"
        id="coffeeking-google-fonts-css"
        media="all"
        rel="stylesheet"
        type="text/css"
    />

    <!-- jQuery -->
    <script
        id="jquery-core-js"
        src="{{ asset('frontend/js/jquery.min.js') }}"
        type="text/javascript">
    </script>


    <!--[if lt IE 9]>
    <script
        type="text/javascript"
        src="{{ asset('frontend/js/html5shiv.js') }}"
        id="html5shiv-js">
    </script>

    <![endif]-->





    <!-- Page Builder Meta -->

    <!-- Favicons -->
    <link
        href="{{ asset('images/favicon/cropped-favicon-100x100.png') }}"
        rel="icon"
        sizes="32x32"
    />
    <link
        href="{{ asset('images/favicon/cropped-favicon-300x300.png') }}"
        rel="apple-touch-icon"
    />
    <meta
        content="{{ asset('images/favicon/cropped-favicon-300x300.png') }}"
        name="msapplication-TileImage"
    />

    <!-- Visual Composer Custom CSS -->
    <style data-type="vc_shortcodes-custom-css" type="text/css">
        .vc_custom_1567017657143 {
            background-image: url("{{ asset('images/coffee-parallax-2.png') }}") !important;
            background-position: center !important;
            background-repeat: no-repeat !important;
            background-size: cover !important;
        }

        .vc_custom_1567018110813 {
            margin-top: -40px !important;
            background-image: url("{{ asset('images/coffee_cut_long.png') }}") !important;
        }

        .vc_custom_1505315020980 {
            background-image: url("{{ asset('images/coffee_parallax.jpg') }}") !important;
            background-position: 0 0 !important;
            background-repeat: no-repeat !important;
        }
    </style>

    <noscript>
        <style>
            .wpb_animate_when_almost_visible {
                opacity: 1;
            }
        </style>
    </noscript>
</head>

<body>
@include('frontend.partials.navbar')

@yield('content')

@include('frontend.partials.footer')
</body>
</html>
