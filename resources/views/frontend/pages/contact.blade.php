@extends('frontend.layouts.app')

@section('title', __('meta.title'))

@section('content')

    <style>

        /* =========================
       CONTACTO — FIX DE LAYOUT
       ========================= */

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
            overflow: hidden; /* recorta cualquier fondo interno */
        }

        /* Limpia fondos de envoltorios CF7 */
        .contact-card p,
        .contact-card label,
        .contact-card .wpcf7-form-control-wrap {
            background: transparent !important;
            margin-bottom: 12px;
        }

        /* =========================
           Inputs y Textareas
           ========================= */
        .contact-card input[type="text"],
        .contact-card input[type="email"],
        .contact-card textarea {
            width: 100%;
            background: #f7f7f7;
            border: 1px solid rgba(0, 0, 0, 0.12);
            border-radius: 10px;
            padding: 10px 12px;
            outline: none;
            font-size: 15px;
            color: #333;
            transition: all 0.2s ease;
        }

        .contact-card input[type="text"]:focus,
        .contact-card input[type="email"]:focus,
        .contact-card textarea:focus {
            border-color: #c6a471;
            background: #fff;
            box-shadow: 0 0 0 3px rgba(198, 164, 113, 0.15);
        }

        .contact-card textarea {
            min-height: 160px;
            resize: vertical;
        }

        /* =========================
           Botón Enviar (redondo)
           ========================= */


        .contact-card input[type="submit"] {
            background: #c6a471 !important;
            color: #fff !important;
            border: none !important;
            font-weight: 700 !important;
            padding: 12px 28px !important;
            border-radius: 10px !important; /* 🔸 fuerza las esquinas redondeadas */
            cursor: pointer !important;
            transition: all 0.25s ease !important;
            display: inline-block !important;
        }

        .contact-card input[type="submit"]:hover {
            background: #b8935e !important;
            transform: translateY(-2px);
            box-shadow: 0 6px 14px rgba(0, 0, 0, 0.15);
        }



        /* =========================
           Mensaje de éxito / error
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
           Mini botones redes (si los usas)
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

        .contact-card .social-cta .fb { color: #1877F2; }
        .contact-card .social-cta .ig { color: #E4405F; }
        .contact-card .social-cta .tt { color: #111; }
        .contact-card .social-cta .tt .tt-ico { width: 16px; height: 16px; fill: #111; }

        /* =========================
           Botón Enviar (esquinas redondeadas)
           ========================= */

        .contact-card__actions {
            margin-top: 10px;
            display: flex;
            justify-content: center;
        }



    </style>

    <header class="page-header like-parallax"
            style="background-image: url('{{ asset('images/inner_parallax.jpg') }}');
               background-size: cover;
               background-position: center;
               background-repeat: no-repeat;">
        <div class="container" bis_skin_checked="1"><h1>{{ __('meta.contact') }}</h1>
            <ul class="breadcrumbs" typeof="BreadcrumbList" vocab="https://schema.org/">
                <li class="home"><span property="itemListElement" typeof="ListItem"><a property="item" typeof="WebPage"
                                                                                       title="Go to Finca3pinos.com"
                                                                                       href="{{ LaravelLocalization::getLocalizedURL(app()->getLocale(), '/') }}"
                                                                                       class="home"
                                                                                       bis_skin_checked="1">
                            <span property="name">{{ __('meta.finca3pinos') }}</span></a><meta property="position"
                                                                                               content="1"></span></li>
                <li class="post post-page current-item"><span property="itemListElement" typeof="ListItem"><span
                            property="name">{{ __('meta.contact') }}</span><meta property="position" content="2"></span>
                </li>
            </ul>
        </div>
    </header>








    <div class="container">
        <!-- Content -->
        <div class="margin-top">
            <div class="row">
                <div class=" col-md-12 text-page">
                    <article id="post-25" class="post-25 page type-page status-publish hentry">
                        <div class="entry-content clearfix" id="entry-div">
                            <section data-vc-full-width="true" data-vc-full-width-init="false" class="vc_section">
                                <div class="vc_row wpb_row vc_row-fluid vc_row-o-content-top vc_row-flex">
                                    <div class="wpb_column vc_column_container vc_col-sm-12 vc_col-lg-4 vc_col-md-6">
                                        <div class="vc_column-inner">
                                            <div class="wpb_wrapper">
                                                <div class="heading  transform-header-up   vc_custom_1505581880747"
                                                     id="like_sc_header_1005731420"><h4 class="header">Contáctanos</h4>
                                                </div>
                                                <div class="wpb_text_column wpb_content_element ">
                                                    <div class="wpb_wrapper">
                                                        <p>¿Tienes preguntas sobre nuestros cafés o deseas realizar un
                                                            pedido especial?
                                                            En Finca 3 Pinos estamos para atenderte. Escríbenos y uno de
                                                            nuestros especialistas te responderá lo antes posible.</p>

                                                    </div>
                                                </div>
                                                <div class="align-default ">
                                                    <ul class="social-icons-list   vc_custom_1581347997161 icon-weight-bold"
                                                        id="like_sc_header_424390489">
                                                        <li class=""><span class="fa fa-phone"></span><span
                                                                class="head">(+503) 7620-6851</span></li>
                                                        <li class=""><span class="fa fa-envelope"></span><span
                                                                    class="head">info@finca3pinos.com</span></li>
                                                        <li class=""><span class="fa fa-map-marker"></span><span
                                                                class="head">El Pinar, Cantón Montenegro Metapán, Santa Ana Norte.</span>
                                                        </li>
                                                    </ul>
                                                </div>
                                                <div
                                                    class="heading  color-black transform-header-up   vc_custom_1502236442786"
                                                    id="like_sc_header_1764788083"><h6 class="header">Social:</h6></div>
                                                <div class="align-default ">
                                                    <ul class="social-big icon-weight-bold"
                                                        id="like_sc_header_1697844067">
                                                        <li><a href="#" class="fa fa-facebook"></a></li>
                                                        <li><a href="#" class="fa fa-twitter"></a></li>
                                                        <li><a href="#" class="fa fa-youtube"></a></li>
                                                        <li><a href="#" class="fa fa-instagram"></a></li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="wpb_column vc_column_container vc_col-sm-12 vc_col-lg-4 vc_col-md-12">
                                        <div class="vc_column-inner">
                                            <div class="wpb_wrapper">
                                                <div class="es-resp">
                                                    <div class="hidden-sm hidden-ms hidden-xs"
                                                         style="height: 0px;"></div>
                                                    <div class="hidden-xl hidden-lg hidden-md hidden-xs"
                                                         style="height: 32px;"></div>
                                                    <div class="visible-xs" style="height: 32px;"></div>
                                                </div>
                                                <div
                                                    class="like-contact-form-7 transform-default form-bg-default form-style-default form-btn-default form-btn-default form-padding-none    vc_custom_1505582392596"
                                                    id="like_sc_contact_form_7_122453212">
                                                    <div class="wpcf7 no-js" id="wpcf7-f1551-p25-o1" lang="en-US"
                                                         dir="ltr" data-wpcf7-id="1551">
                                                        <div class="screen-reader-response"><p role="status"
                                                                                               aria-live="polite"
                                                                                               aria-atomic="true"></p>
                                                            <ul></ul>
                                                        </div>




                                                        <!-- CARD CONTACT -->
                                                        <section class="contact-card">
                                                            <!-- Pega aquí tu formulario CF7 tal cual -->
                                                            <form
                                                                action="/wordpress/contacts/?simply_static_page=11518#wpcf7-f1551-p25-o1"
                                                                method="post" class="wpcf7-form init" aria-label="Contact form" novalidate data-status="init">
                                                                <!-- …tus hidden fields… -->
                                                                <p><label> Your Name<br>
                                                                        <span class="wpcf7-form-control-wrap" data-name="your-name">
        <input size="40" maxlength="400"
               class="wpcf7-form-control wpcf7-text wpcf7-validates-as-required"
               aria-required="true" type="text" name="your-name">
      </span>
                                                                    </label></p>

                                                                <p><label> Your Email<br>
                                                                        <span class="wpcf7-form-control-wrap" data-name="your-email">
        <input size="40" maxlength="400"
               class="wpcf7-form-control wpcf7-email wpcf7-validates-as-required wpcf7-text wpcf7-validates-as-email"
               aria-required="true" type="email" name="your-email">
      </span>
                                                                    </label></p>

                                                                <p><label> Your Message<br>
                                                                        <span class="wpcf7-form-control-wrap" data-name="your-message">
        <textarea cols="40" rows="6" maxlength="2000"
                  class="wpcf7-form-control wpcf7-textarea" name="your-message"></textarea>
      </span>
                                                                    </label></p>

                                                                <p class="contact-card__actions">
                                                                    <input class="wpcf7-form-control wpcf7-submit has-spinner" type="submit" value="Enviar">
                                                                </p>
                                                                <div class="wpcf7-response-output" aria-hidden="true"></div>
                                                            </form>
                                                        </section>







                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="vc_row wpb_row vc_row-fluid">
                                    <div class="wpb_column vc_column_container vc_col-sm-12">
                                        <div class="vc_column-inner">
                                            <div class="wpb_wrapper">
                                                <div class="vc_empty_space" style="height: 100px"><span
                                                        class="vc_empty_space_inner"></span></div>
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
    </div>











    {{-- Superior (Newsletter) block --}}
    @include('frontend.partials.superior')
@endsection
