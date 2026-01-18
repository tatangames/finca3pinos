@extends('frontend.layouts.app')

@section('title', __('meta.title'))

@section('content')

    <style>
        .hero-geisha {
            --bg-dark: #0d0d0d;
            --text-light: #f4f4f4;
            --accent: #d2aa6d;
            background: var(--bg-dark);
            color: var(--text-light);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px 0 64px;
        }

        /* === CONTENEDOR GENERAL === */
        .hero-container {
            display: grid;
            grid-template-columns:1.1fr 0.9fr;
            gap: 36px;
            max-width: 1320px;
            width: 100%;
            padding: 0 20px;
            align-items: stretch;
        }

        /* === CARDS BASE === */
        .hero-card {
            border-radius: 20px;
            overflow: hidden;
            min-height: clamp(440px, 65vh, 700px);
        }

        /* === CARD IZQUIERDA (Texto) === */
        .hero-card.hero-text {
            background: #000;
            padding: 56px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            box-shadow: 0 6px 20px rgba(0, 0, 0, .4);
        }

        .hero-card.hero-text .eyebrow {
            text-transform: uppercase;
            letter-spacing: .1em;
            color: var(--accent);
            font-weight: 700;
            font-size: .9rem;
            margin-bottom: 12px;
        }

        .hero-card.hero-text h1 {
            font-size: clamp(2.2rem, 3.4vw, 3rem);
            margin: 0;
        }

        .hero-card.hero-text h2 {
            font-size: clamp(1.4rem, 2.2vw, 2rem);
            font-weight: 400;
            color: var(--accent);
            margin-bottom: 1.2rem;
        }

        .hero-card.hero-text p {
            color: #ddd;
            line-height: 1.6;
            margin-bottom: 1.5rem;
        }

        /* === BOTONES === */
        .btn-group {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
        }

        .btn {
            padding: 12px 22px;
            border-radius: 10px;
            text-decoration: none;
            font-weight: 600;
            transition: all .3s ease;
        }

        .btn-primary {
            background: var(--accent);
            color: #000;
        }

        .btn-primary:hover {
            background: #e9c27b;
        }

        .btn-outline {
            border: 2px solid var(--accent);
            color: var(--accent);
        }

        .btn-outline:hover {
            background: var(--accent);
            color: #000;
        }

        /* === CARD DERECHA (Imagen) === */
        .hero-card.hero-image {
            background: #000;
            padding: 0; /* 🔹 se elimina el margen interno negro */
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 16px 40px rgba(0, 0, 0, .35);
        }

        /* Imagen menos horizontal (más cuadrada y centrada) */
        .hero-card.hero-image img {
            display: block;
            width: 100%;
            height: 100%;
            object-fit: cover; /* llena todo el card */
            object-position: center center; /* centrada */
            aspect-ratio: 4/3; /* 🔹 menos horizontal */
            border-radius: 0;
            box-shadow: none;
            transition: transform .35s ease;
        }

        /* Hover suave (opcional) */
        .hero-card.hero-image:hover img {
            transform: scale(1.03);
        }

        /* === SLIDE GENERAL === */
        .swiper-slide {
            padding: 0 !important;
        }

        /* === RESPONSIVE === */
        @media (max-width: 992px) {
            .hero-container {
                grid-template-columns:1fr;
            }

            .hero-card.hero-image {
                order: -1;
                min-height: 360px;
            }

            .hero-geisha {
                padding: 16px 0 56px;
            }

            .hero-card.hero-text {
                padding: 40px 28px;
            }

            .hero-card.hero-image img {
                aspect-ratio: auto;
                object-fit: contain;
            }
        }


        /* === Alinear "Sostenibilidad" con "Perfil sensorial" === */
        @media (min-width: 992px) {

            /* 1) La fila como contenedor flex */
            .vc_row.vc_custom_1506640683138 {
                display: flex;
                flex-wrap: wrap;
                align-items: stretch; /* columnas igual altura */
            }

            /* 2) Estirar el contenido interno de cada columna */
            .vc_row.vc_custom_1506640683138 > .vc_column_container,
            .vc_row.vc_custom_1506640683138 > .vc_column_container > .vc_column-inner {
                display: flex;
                flex-direction: column;
                height: 100%;
            }

            /* 3) Quitar espacios fantasma y márgenes que desalinean */
            .vc_row.vc_custom_1506640683138 .vc_empty_space {
                display: none !important;
            }

            .vc_row.vc_custom_1506640683138 ul.block-icon {
                margin: 0 !important;
                padding: 0 !important;
                display: flex;
                flex-direction: column;
                justify-content: space-between; /* el 2.º ítem baja al mismo nivel en ambas columnas */
                height: 100%;
                gap: 28px; /* controla separación vertical entre los 2 ítems */
            }

            .vc_row.vc_custom_1506640683138 ul.block-icon > li {
                margin: 0 !important; /* neutraliza márgenes del tema */
            }

            /* 4) Clave: dar la MISMA altura mínima al PRIMER ítem de cada columna
                  para que el segundo (Sostenibilidad/Perfil) arranque a la misma línea */
            .vc_row.vc_custom_1506640683138 .vc_col-lg-3 ul.block-icon > li.icon-image:first-child {
                min-height: 0px; /* AJUSTA este valor si queda 1–2px fuera */
                display: flex;
                flex-direction: column;
                justify-content: flex-start;
            }
        }

        .gold-separator {
            height: 3px;
            width: 100%;
            background-color: #c0aa83; /* dorado */
        }


        /* === Separador dorado debajo de cada párrafo === */
        .block-icon li.icon-image .gold-separator {
            height: 3px;
            width: 100%; /* ocupa todo el ancho del contenedor del texto */
            max-width: 280px; /* límite opcional para que no sea demasiado largo */
            background-color: #c0aa83; /* dorado */
            margin-top: 10px;
            border-radius: 2px;
        }

        /* === Alineación y espaciado uniforme de cada item === */
        .block-icon.icon-top.align-left.i-transparent.layout-col1 {
            margin: 0 !important;
            padding: 0 !important;
        }

        .block-icon.icon-top.align-left.i-transparent.layout-col1 li.icon-image {
            list-style: none;
            display: flex;
            flex-direction: column;
            align-items: flex-start; /* todo a la izquierda */
            text-align: left;
            margin: 0 0 32px 0; /* separación entre items */
        }

        /* Ícono pegado a la izquierda y sin offsets raros */
        .block-icon li.icon-image > span.icon-image {
            display: inline-block;
            margin: 0 0 10px 0; /* espacio bajo el ícono */
            padding: 0;
        }

        .block-icon li.icon-image img {
            display: block;
            margin: 0;
        }

        /* Títulos y textos: normalizar márgenes para que las filas queden a la misma altura */
        .block-icon li.icon-image h5 {
            margin: 0 0 8px 0 !important;
            line-height: 1.15;
        }

        .block-icon li.icon-image .descr {
            margin: 0; /* evita saltos diferentes entre columnas */
        }

        /* Quitar espacios fantasma del tema en esta fila */
        .vc_row.vc_custom_1506640683138 .vc_empty_space {
            display: none !important;
        }

        /* Responsive: un poco menos de separación */
        @media (max-width: 767px) {
            .block-icon li.icon-image {
                margin-bottom: 24px;
            }

            .block-icon li.icon-image .gold-separator {
                width: 56px;
            }
        }


        /* ===== Sección general ===== */
        /* ===== Sección a ancho completo con líneas doradas ===== */
        .geisha-product {
            position: relative;
            width: 100vw;
            left: 50%;
            right: 50%;
            margin-left: -50vw;
            margin-right: -50vw;
            background: #1a1a1a;
            padding: 60px 0 80px;
            overflow: hidden;
        }

        .geisha-product::before,
        .geisha-product::after {
            content: "";
            position: absolute;
            left: 0;
            width: 100%;
            height: 2px;
            background: #D2AA6DFF;
        }

        .geisha-product::before {
            top: 0;
        }

        .geisha-product::after {
            bottom: 0;
        }

        /* ===== Título ===== */
        .geisha-product .header {
            font-weight: 800;
            font-size: clamp(26px, 4vw, 40px);
            color: #f9f9f9;
            margin: 0 0 36px;
            text-align: center;
        }

        /* ===== Layout 2 columnas ===== */
        .geisha-product .product-grid {
            display: grid;
            grid-template-columns:1fr 1fr;
            gap: 36px;
            align-items: center;
            justify-content: center;
            max-width: 900px;
            margin: 0 auto;
            text-align: left;
        }

        /* ===== Imagen izquierda (card) ===== */
        .geisha-product .product-media {
            background: #ffffff;
            border-radius: 16px;
            padding: 18px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, .6);
            display: flex;
            justify-content: center;
            align-items: center;

            /* transición y rendimiento */
            transition: transform .35s ease, box-shadow .35s ease, filter .35s ease;
            will-change: transform;
            transform: translateZ(0);
        }

        .geisha-product .product-media img {
            display: block;
            max-width: 70%;
            height: auto;
            object-fit: contain;
            border-radius: 10px;
            transition: transform .4s ease;
        }

        /* ===== Card de detalles (derecha) ===== */
        .geisha-product .product-details.card {
            background: #fff;
            border-radius: 14px;
            padding: 28px 32px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, .25);

            /* transición y rendimiento */
            transition: transform .35s ease, box-shadow .35s ease, background-color .35s ease;
            will-change: transform;
            transform: translateZ(0);
        }

        /* ===== Hover / Focus: Zoom suave en cada card ===== */
        .geisha-product .product-media:hover,
        .geisha-product .product-media:focus-within {
            transform: scale(1.04);
            box-shadow: 0 18px 40px rgba(0, 0, 0, .55);
            filter: brightness(1.02);
        }

        .geisha-product .product-media:hover img,
        .geisha-product .product-media:focus-within img {
            transform: scale(1.08);
        }

        .geisha-product .product-details.card:hover,
        .geisha-product .product-details.card:focus-within {
            transform: scale(1.04);
            box-shadow: 0 18px 44px rgba(0, 0, 0, .35);
        }

        /* ===== Texto y bloques internos ===== */
        .geisha-product .excerpt {
            color: #333;
            line-height: 1.6;
            margin-bottom: 16px;
        }

        .geisha-product .product-info {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
            color: #222;
            font-weight: 600;
            margin-bottom: 12px;
        }

        .geisha-product .product-info .label {
            color: #777;
            font-weight: 700;
            margin-right: 4px;
        }

        .geisha-product .price {
            font-size: 1.4rem;
            font-weight: 800;
            color: #b8914b;
            margin: 10px 0 16px;
        }

        /* ===== Botón ===== */
        .geisha-product .btn-cart {
            display: inline-flex;
            align-items: center;
            gap: 5px; /* espacio entre ícono y texto */
            background: #b8914b;
            color: #fff;
            padding: 6px 12px; /* 🔹 más compacto */
            border-radius: 5px; /* 🔹 esquinas más rectas */
            font-weight: 600;
            font-size: 0.8rem; /* 🔹 texto más pequeño */
            text-decoration: none;
            box-shadow: 0 2px 8px rgba(184, 145, 75, .25);
            transition: transform .25s ease, box-shadow .25s ease, background-color .25s ease;
        }

        .geisha-product .btn-cart:hover {
            background: #a37a30;
            transform: translateY(-1px);
            box-shadow: 0 4px 10px rgba(184, 145, 75, .35);
        }

        .geisha-product .btn-cart i {
            font-size: 0.8em; /* 🔹 ícono más pequeño */
            margin-right: 3px;
        }

        /* ===== Responsive ===== */
        @media (max-width: 900px) {
            .geisha-product .product-grid {
                grid-template-columns:1fr;
                gap: 28px;
                text-align: center;
            }

            .geisha-product .product-media img {
                max-width: 60%;
            }

            .geisha-product .product-details.card {
                text-align: center;
            }

            .geisha-product .product-info {
                justify-content: center;
            }
        }

        /* Respeta usuarios con movimiento reducido */
        @media (prefers-reduced-motion: reduce) {
            .geisha-product .product-media,
            .geisha-product .product-details.card,
            .geisha-product .product-media img,
            .geisha-product .btn-cart {
                transition: none !important;
            }
        }

        html {
            scroll-behavior: smooth;
        }


        /* ===== Márgenes laterales en móviles/tablets ===== */
        @media (max-width: 900px) {
            /* Aplica espacio lateral a toda la zona del producto */
            .geisha-product .container {
                padding-left: 24px;
                padding-right: 24px;
            }

            /* También un poco de margen interno en los cards */
            .geisha-product .product-media,
            .geisha-product .product-details.card {
                margin-left: 8px;
                margin-right: 8px;
            }

            /* Ajuste opcional para que la imagen no se vea tan grande */
            .geisha-product .product-media img {
                max-width: 75%;
            }
        }



        /* ===== BLOQUE DE VIDEO A PANTALLA COMPLETA (sin desborde) ===== */
        .video-hero{
            position: relative;
            /* fallback primero */
            width: 100vw;
            margin-left: calc(50% - 50vw);
            margin-right: calc(50% - 50vw);

            /* override preciso (si el navegador soporta dvw) */
            width: 100dvw;
            margin-left: calc(50% - 50dvw);
            margin-right: calc(50% - 50dvw);

            background: #000;
            overflow: hidden;
            padding: 0;
        }

        /* contenedor 16:9 */
        .video-wrapper{
            position: relative;
            width: 100%;
            padding-top: 56.25%;
            background: #000;
        }

        /* iframe YouTube */
        .video-hero__iframe{
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            border: 0;
            display: block;
        }




    </style>

    <div class="container">
        <!-- Content -->
        <div class="margin-disabled">
            <div class="row">
                <div class="col-md-12 text-page">
                    <article class="page type-page status-publish hentry">
                        <div class="entry-content clearfix" id="entry-div">
                            {{-- Slider Hero --}}
                            <div data-vc-full-width="true" data-vc-full-width-init="false"
                                 data-vc-stretch-content="true" class="vc_row wpb_row vc_row-fluid vc_row-no-padding">
                                <div class="wpb_column vc_column_container vc_col-sm-12">
                                    <div class="vc_column-inner">
                                        <div class="wpb_wrapper">
                                            <div class="es-resp">
                                                <div class="hidden-xl hidden-lg hidden-md hidden-xs"
                                                     style="height: 64px;"></div>
                                            </div>
                                            <div id="like_sc_sliders_87130229">
                                                <div class="slider-sc swiper-container" data-autoplay="4000"
                                                     data-arrows="" data-pagination="1" data-effect="fade">
                                                    <div class="swiper-wrapper">
                                                        {{-- Slide 1 --}}
                                                        <div class="swiper-slide">
                                                            <section class="hero-geisha">
                                                                <div class="hero-container">
                                                                    <!-- Izquierda: Card de texto -->
                                                                    <div class="hero-card hero-text">
                                                                        <span
                                                                            class="eyebrow">{{ __('meta.coffee_v0') }}</span>
                                                                        <h1>{{ __('meta.finca3pinos') }}</h1>
                                                                        <h2>{{ __('meta.coffee_v14') }}</h2>
                                                                        <p>
                                                                            {{ __('meta.coffee_v13') }}
                                                                        </p>
                                                                        <div class="btn-group">


                                                                            <a href="{{ LaravelLocalization::getLocalizedURL(app()->getLocale(),
                                                                    route('user.ourcoffee', [], false)) }}"
                                                                               class="btn btn-primary"
                                                                               style="color: white">{{ __('meta.coffee_v6') }}</a>

                                                                            <a href="{{ LaravelLocalization::getLocalizedURL(app()->getLocale(),
                                                                    route('user.products', [], false)) }}"
                                                                               class="btn btn-primary js-scroll"
                                                                               style="color:white">
                                                                                {{ __('meta.coffee_v5') }}
                                                                            </a></div>
                                                                    </div>

                                                                    <!-- Derecha: Card con imagen -->
                                                                    <div class="hero-card hero-image">
                                                                        <img
                                                                            src="{{ asset('images/presentacion1.jpg') }}"

                                                                            alt="{{ __('meta.title') }}"
                                                                            loading="lazy"
                                                                            decoding="async"
                                                                        /></div>
                                                                </div>
                                                            </section>
                                                        </div>
                                                    </div>
                                                    <div class="swiper-pagination"></div>
                                                </div>
                                            </div>
                                            <div class="gold-separator"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="vc_row-full-width"></div>

                            {{-- BLOQUE DE VIDEO --}}
                            <section class="video-hero" aria-label="Video Finca 3 Pinos">
                                <div class="video-wrapper">
                                    <iframe
                                        class="video-hero__iframe"
                                        src="https://www.youtube.com/embed/PUEFTm_jjpo?autoplay=1&mute=1&loop=1&playlist=PUEFTm_jjpo&controls=1&playsinline=1"
                                        title="Video Finca 3 Pinos"
                                        frameborder="0"
                                        allow="autoplay; encrypted-media; picture-in-picture"
                                        allowfullscreen
                                    ></iframe>
                                </div>
                            </section>


                            <div class="vc_row-full-width"></div>

                            {{-- About section --}}
                            <section data-vc-full-width="true" data-vc-full-width-init="false"
                                     class="vc_section vc_section-has-fill bg-color-black bg-pos-left-center">
                                <div
                                    class="vc_row wpb_row vc_row-fluid vc_custom_1506640683138 vc_row-has-fill bg-pos-left-center">

                                    <!-- IZQUIERDA -->
                                    <div
                                        class="wpb_animate_when_almost_visible wpb_zoomIn zoomIn wpb_column vc_column_container vc_col-sm-12 vc_col-lg-5 vc_col-md-5">
                                        <div class="vc_column-inner">
                                            <div class="wpb_wrapper">
                                                <div
                                                    class="heading head-subheader align-left color-white subcolor-main transform-default">
                                                    <h5 class="subheader"
                                                        style="color: #D2AA6DFF">{{ __('meta.coffee_v7') }}</h5>
                                                    <h2 class="header">{{ __('meta.finca3pinos') }}</h2>
                                                </div>

                                                <div class="wpb_text_column wpb_content_element ">
                                                    <div class="wpb_wrapper">
                                                        <p style="text-align:left;">
                                                                <span class="text-large"
                                                                      style="color:#D2AA6D; text-align: justify">
                                                                    {{ __('meta.coffee_v8') }}
                                                                </span>
                                                        </p>
                                                    </div>
                                                </div>
                                                <!-- Separador dorado -->
                                                <div class="gold-separator"></div>

                                                <div class="vc_empty_space" style="height:20px"><span
                                                        class="vc_empty_space_inner"></span></div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="wpb_column vc_column_container vc_col-sm-1 vc_hidden-sm vc_hidden-xs">
                                        <div class="vc_column-inner">
                                            <div class="wpb_wrapper"></div>
                                        </div>
                                    </div>

                                    <!-- DERECHA -->
                                    <div
                                        class="wpb_animate_when_almost_visible wpb_slideInRight slideInRight wpb_column vc_column_container vc_col-sm-6 vc_col-lg-3 vc_col-md-3">
                                        <div class="vc_column-inner">
                                            <div class="wpb_wrapper">
                                                <ul class="block-icon icon-top align-left i-transparent layout-col1">

                                                    <li class="icon-image">
                                                        <span class="icon-image bg-transparent">
                                                            <img src="{{ asset('images/beans.png') }}"
                                                                 style="width: 60px; height: 60px"
                                                                 class="icon-image" alt="{{ __('meta.coffee_v9') }}">
                                                        </span>
                                                        <h5>{{ __('meta.coffee_v9') }}</h5>
                                                        <div class="descr">
                                                            {{ __('meta.coffee_v10') }}
                                                        </div>
                                                        <div class="gold-separator"></div>
                                                    </li>

                                                    <li class="icon-image">
                                                        <span class="icon-image bg-transparent">
                                                            <img src="{{ asset('images/sensorial.png') }}"
                                                                 style="width: 60px; height: 55px"
                                                                 class="icon-image" alt="{{ __('meta.coffee_v17') }}">
                                                        </span>
                                                        <h5>{{ __('meta.coffee_v15') }}</h5>
                                                        <div class="descr">
                                                            {{ __('meta.coffee_v16') }}
                                                        </div>
                                                        <div class="gold-separator"></div>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>

                                    <div
                                        class="wpb_animate_when_almost_visible wpb_slideInRight slideInRight wpb_column vc_column_container vc_col-sm-6 vc_col-lg-3 vc_col-md-3">
                                        <div class="vc_column-inner">
                                            <div class="wpb_wrapper">
                                                <ul class="block-icon icon-top align-left i-transparent layout-col1">

                                                    <li class="icon-image">
                                                        <span class="icon-image bg-transparent">
                                                            <img src="{{ asset('images/tuerca.png') }}"
                                                                 style="width: 60px" height="60px"
                                                                 class="icon-image" alt="{{ __('meta.coffee_v11') }}">
                                                        </span>
                                                        <h5>{{ __('meta.coffee_v11') }}</h5>
                                                        <div class="descr">
                                                            {{ __('meta.coffee_v12') }}
                                                        </div>
                                                        <div class="gold-separator"></div>
                                                    </li>

                                                    <li class="icon-image">
                                                        <span class="icon-image bg-transparent">
                                                            <img src="{{ asset('images/hoja.png') }}"
                                                                 style="width: 50px; height: 55px"
                                                                 class="icon-image" alt="{{ __('meta.coffee_v17') }}">
                                                        </span>
                                                        <h5>{{ __('meta.coffee_v17') }}</h5>
                                                        <div class="descr">
                                                            {{ __('meta.coffee_v18') }}
                                                        </div>
                                                        <div class="gold-separator"></div>
                                                    </li>
                                                </ul>

                                                <div class="vc_empty_space" style="height:100px"><span
                                                        class="vc_empty_space_inner"></span></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div style="margin-top: 3px"></div>
                            </section>
                            <div class="vc_row-full-width vc_clearfix"></div>
                        </div>
                    </article>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            function getOffset() {
                let off = 0;
                const sticky = document.querySelector('.navbar-fixed-top, .site-header.sticky, .header-sticky');
                const admin = document.querySelector('#wpadminbar');
                if (sticky) off += sticky.offsetHeight || 0;
                if (admin) off += admin.offsetHeight || 0;
                // fallback si no hay sticky
                if (!off) off = 80; // ajusta a tu diseño
                return off;
            }

            document.querySelectorAll('a.js-scroll[href^="#"]').forEach(function (a) {
                a.addEventListener('click', function (e) {
                    const id = this.getAttribute('href');
                    if (!id || id === '#') return;
                    const target = document.querySelector(id);
                    if (!target) return;

                    e.preventDefault();

                    const offset = getOffset();
                    const y = target.getBoundingClientRect().top + window.pageYOffset - offset;

                    window.scrollTo({top: y, behavior: 'smooth'});

                    // Actualiza el hash sin “saltar” (permite re-clics)
                    history.pushState(null, '', id);
                });
            });
        });
    </script>

    {{-- Superior (Newsletter) block --}}
@endsection
