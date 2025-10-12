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
                                                <div class="visible-xs" style="height: 64px;"></div>
                                            </div>
                                            <div id="like_sc_sliders_87130229">
                                                <div class="slider-sc swiper-container" data-autoplay="4000"
                                                     data-arrows="" data-pagination="1" data-effect="fade">
                                                    <div class="swiper-wrapper">


                                                        {{-- Slide 1 --}}
                                                        {{-- Slide 1 --}}
                                                        <div class="swiper-slide">
                                                            <section class="hero-geisha">
                                                                <div class="hero-container">
                                                                    <!-- Izquierda: Card de texto -->
                                                                    <div class="hero-card hero-text">
                                                                        <span class="eyebrow">Café Geisha • Metapán, El Salvador</span>
                                                                        <h1>Finca 3 Pinos</h1>
                                                                        <h2>Coffee family</h2>
                                                                        <p>
                                                                            En la Cordillera Alotepec–Metapán cultivamos
                                                                            una de las variedades más
                                                                            exclusivas del mundo: el Café Geisha. Un
                                                                            café de altura con notas florales,
                                                                            frutales y un carácter elegante que refleja
                                                                            la esencia de nuestra tierra.
                                                                        </p>
                                                                        <div class="btn-group">
                                                                            <a href="" class="btn btn-primary">Conoce
                                                                                más</a>
                                                                            <a href="" class="btn btn-outline">Ver
                                                                                productos</a>
                                                                        </div>
                                                                    </div>

                                                                    <!-- Derecha: Card con imagen -->
                                                                    <div class="hero-card hero-image">
                                                                        <img
                                                                            src="{{ asset('images/presentacion1.jpg') }}"


                                                                            alt="Café Geisha Finca 3 Pinos"
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
                                                    <h5 class="subheader" style="color: #D2AA6DFF">Quiénes somos</h5>
                                                    <h2 class="header">Finca 3 Pinos</h2>
                                                </div>

                                                <div class="wpb_text_column wpb_content_element ">
                                                    <div class="wpb_wrapper">
                                                        <p style="text-align:left;">
                                                                <span class="text-large" style="color:#D2AA6D; text-align: justify">
                                                                    Producimos Café Geisha de altura en Metapán, El Salvador.
                                                                    Un café de origen único, cultivado con pasión, tecnología y respeto por la tierra.
                                                                </span>
                                                        </p>
                                                    </div>
                                                </div>

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
                                <img src="{{ asset('images/beans.png') }}" style="width: 60px; height: 60px"
                                     class="icon-image" alt="Origen y prestigio">
                            </span>
                                                        <h5>Origen y prestigio</h5>
                                                        <div class="descr">
                                                            Cultivado en la Cordillera Alotepec–Metapán, cuna del mejor
                                                            café salvadoreño.
                                                        </div>
                                                    </li>

                                                    <li class="icon-image">
                            <span class="icon-image bg-transparent">
                                <img src="{{ asset('images/sensorial.png') }}" style="width: 60px; height: 60px"
                                     class="icon-image" alt="Perfil sensorial">
                            </span>
                                                        <h5>Perfil sensorial</h5>
                                                        <div class="descr">
                                                            Notas florales, frutales y una acidez brillante con cuerpo
                                                            sedoso.
                                                        </div>
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
                                <img src="{{ asset('images/tuerca.png') }}" style="width: 60px" height="60px"
                                     class="icon-image" alt="Tecnología y trazabilidad">
                            </span>
                                                        <h5>Tecnología</h5>
                                                        <div class="descr">
                                                            Controlamos cada etapa para garantizar pureza y consistencia
                                                            en taza.
                                                        </div>
                                                    </li>

                                                    <li class="icon-image">
                            <span class="icon-image bg-transparent">
                                <img src="{{ asset('images/hoja.png') }}" style="width: 50px; height: 55px"
                                     class="icon-image" alt="Sostenibilidad">
                            </span>
                                                        <h5>Sostenibilidad</h5>
                                                        <div class="descr">
                                                            Respetamos la naturaleza y preservamos la biodiversidad de
                                                            Metapán.
                                                        </div>
                                                    </li>
                                                </ul>

                                                <div class="vc_empty_space" style="height:100px"><span
                                                        class="vc_empty_space_inner"></span></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </section>


                            <div class="vc_row-full-width vc_clearfix"></div>


                            {{-- Products header (static placeholder) --}}
                            <section data-vc-full-width="true" data-vc-full-width-init="false"
                                     class="vc_section bg-color-gray">
                                <div class="vc_row wpb_row vc_row-fluid">
                                    <div class="wpb_column vc_column_container vc_col-sm-12">
                                        <div class="vc_column-inner">
                                            <div class="wpb_wrapper">
                                                <div class="es-resp">
                                                    <div class="hidden-sm hidden-ms hidden-xs"
                                                         style="height:16px;"></div>
                                                    <div class="hidden-xl hidden-lg hidden-md hidden-xs"
                                                         style="height:16px;"></div>
                                                    <div class="visible-xs" style="height:16px;"></div>
                                                </div>
                                                <div
                                                    class="heading head-subheader align-center subcolor-main text-bg transform-default">
                                                    <h5 class="subheader">Choose your coffee</h5>
                                                    <h2 class="header">Recent Products</h2>
                                                    <p class="header-text">Products</p>
                                                </div>
                                                <div class="btn-wrap align-center"><a href="#"
                                                                                      class="btn btn-default-bordered transform-default color-text-default color-hover-default align-center">view
                                                        all products</a></div>
                                                <div class="es-resp">
                                                    <div class="hidden-sm hidden-ms hidden-xs"
                                                         style="height:80px;"></div>
                                                    <div class="hidden-xl hidden-lg hidden-md hidden-xs"
                                                         style="height:50px;"></div>
                                                    <div class="visible-xs" style="height:50px;"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </section>

                            <div class="vc_row-full-width vc_clearfix"></div>



                        </div>
                    </article>
                </div>
            </div>
        </div>
    </div>

    {{-- Superior (Newsletter) block --}}
    @include('frontend.partials.superior')
@endsection
