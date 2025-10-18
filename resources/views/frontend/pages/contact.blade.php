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
            font-weight: 400 !important;    /* quita negrita */
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

        .contact-card .social-cta .fb { color: #1877F2; }
        .contact-card .social-cta .ig { color: #E4405F; }
        .contact-card .social-cta .tt { color: #111; }
        .contact-card .social-cta .tt .tt-ico { width: 16px; height: 16px; fill: #111; }

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
        .field-error:empty { display: none; }

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
        .char-counter.warning { color: #c77d00; }
        .char-counter.danger  { color: #d93025; }

        /* Reafirmar bordes/colores del form */
        #contact-form input[type="text"],
        #contact-form input[type="email"],
        #contact-form textarea {
            border: 1px solid rgba(0,0,0,0.12);
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
    </style>

    <header class="page-header like-parallax"
            style="background-image: url('{{ asset('images/inner_parallax.jpg') }}');
               background-size: cover;
               background-position: center;
               background-repeat: no-repeat;">
        <div class="container" bis_skin_checked="1"><h1>{{ __('meta.contact') }}</h1>
            <ul class="breadcrumbs" typeof="BreadcrumbList" vocab="https://schema.org/">
                <li class="home"><span property="itemListElement" typeof="ListItem"><a property="item" typeof="WebPage"
                                                                                       title="{{ __('meta.go_to_finca3pinos') }}"
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
                                                     id="like_sc_header_1005731420"><h4
                                                        class="header">{{ __('meta.contact_v2') }}</h4>
                                                </div>
                                                <div class="wpb_text_column wpb_content_element ">
                                                    <div class="wpb_wrapper">
                                                        <p>{{ __('meta.contact_v1') }}</p>

                                                    </div>
                                                </div>
                                                <div class="align-default ">
                                                    <ul class="social-icons-list   vc_custom_1581347997161 icon-weight-bold"
                                                        id="like_sc_header_424390489">
                                                        <li class=""><span class="fa fa-phone"></span><span
                                                                class="head">(+503) 7620-6851</span></li>
                                                        <li class=""><span class="fa fa-envelope"></span><span
                                                                class="head" style="text-transform: lowercase">info@finca3pinos.com</span>
                                                        </li>
                                                        <li class=""><span class="fa fa-map-marker"></span><span
                                                                class="head">{{ __('meta.contact_v3') }}</span>
                                                        </li>
                                                    </ul>
                                                </div>
                                                <div
                                                    class="heading  color-black transform-header-up   vc_custom_1502236442786"
                                                    id="like_sc_header_1764788083"><h6
                                                        class="header">{{ __('meta.contact_v4') }}</h6></div>
                                                <div class="align-default ">
                                                    <ul class="social-big icon-weight-bold"
                                                        id="like_sc_header_1697844067">
                                                        <li><a href="#" class="fa fa-facebook"></a></li>
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

                                                            <form id="contact-form">
                                                                @csrf

                                                                <p>
                                                                    <label>{{ __('meta.contact_v5') }}<br>
                                                                        <input size="40" maxlength="100"
                                                                               id="nombre-form"
                                                                               class="wpcf7-form-control wpcf7-text wpcf7-validates-as-required"
                                                                               aria-required="true" type="text">
                                                                    </label>
                                                                    <label class="field-error"
                                                                           data-error-for="your-name"></label>
                                                                </p>

                                                                <p>
                                                                    <label>{{ __('meta.contact_v6') }}<br>
                                                                        <input size="40" maxlength="100"
                                                                               id="correo-form"
                                                                               class="wpcf7-form-control wpcf7-email wpcf7-validates-as-required wpcf7-text wpcf7-validates-as-email"
                                                                               aria-required="true" type="email"
                                                                               style="text-transform: lowercase;">
                                                                    </label>
                                                                    <label class="field-error"
                                                                           data-error-for="your_email"></label>
                                                                </p>


                                                                <p>
                                                                    <label>{{ __('meta.contact_v7') }}<br>
                                                                        <textarea cols="40" rows="6" maxlength="2000"
                                                                                  id="mensaje-form"
                                                                                  class="wpcf7-form-control wpcf7-textarea"
                                                                        ></textarea>
                                                                    </label>
                                                                <div class="char-counter"><span id="msg-count">0</span>/2000
                                                                </div>
                                                                <label class="field-error"
                                                                       data-error-for="your-message"></label>
                                                                </p>

                                                                <p class="contact-card__actions">
                                                                    <input
                                                                        class="wpcf7-form-control wpcf7-submit has-spinner"
                                                                        type="button" onclick="enviarFormulario()"
                                                                        id="btn-enviar"
                                                                        value="{{ __('meta.contact_v8') }}">
                                                                </p>

                                                                {{-- zona de mensajes --}}
                                                                <div class="wpcf7-response-output"
                                                                     aria-hidden="true"></div>
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

    <script src="{{ asset('js/toastr.min.js') }}"></script>
    <script src="{{ asset('js/axios.min.js') }}"></script>
    <script src="{{ asset('js/sweetalert2.all.min.js') }}"></script>
    <script src="{{ asset('js/alertaPersonalizada.js') }}"></script>

    <script>


        document.addEventListener("DOMContentLoaded", function () {
            const textarea = document.getElementById("mensaje-form");
            const counter = document.getElementById("msg-count");
            const maxChars = 2000;

            textarea.addEventListener("input", function () {
                let currentLength = textarea.value.length;

                // Evita que supere el límite
                if (currentLength > maxChars) {
                    textarea.value = textarea.value.substring(0, maxChars);
                    currentLength = maxChars;
                }

                // Actualiza contador
                counter.textContent = currentLength;

                // Opcional: cambia color cuando se acerca al límite
                if (currentLength > maxChars * 0.9) {
                    counter.style.color = "#d9534f"; // rojo
                } else {
                    counter.style.color = "#666"; // gris normal
                }
            });
        });

        const currentLocale = "{{ app()->getLocale() }}"; // 'sv', 'en', 'es', etc.

        function showError(el, msg) {
            el.textContent = msg || '';
            el.style.display = msg ? 'block' : 'none';
        }

        function getContactMessages() {
            let msgCampoRequerido = "";
            let msgCorreoNoValido = "";
            let msgEnviado = "";

            switch (currentLocale) {
                case 'sv':
                    msgCampoRequerido = "{{ __('meta.contact_v9') }}"; // 'Campo requerido' en sv
                    msgCorreoNoValido = "{{ __('meta.contact_v10') }}"; // 'Correo no válido' en sv
                    msgEnviado = "{{ __('meta.contact_v11') }}";
                    break;
                case 'en':
                    msgCampoRequerido = "{{ __('meta.contact_v9', [], 'en') }}";
                    msgCorreoNoValido = "{{ __('meta.contact_v10', [], 'en') }}";
                    msgEnviado = "{{ __('meta.contact_v11', [], 'en') }}";
                    break;
                case 'ko':
                    msgCampoRequerido = "{{ __('meta.contact_v9', [], 'ko') }}";
                    msgCorreoNoValido = "{{ __('meta.contact_v10', [], 'ko') }}";
                    msgEnviado = "{{ __('meta.contact_v11', [], 'ko') }}";
                    break;
                default: // español u otro
                    msgCampoRequerido = "{{ __('meta.contact_v9', [], 'es') }}";
                    msgCorreoNoValido = "{{ __('meta.contact_v10', [], 'es') }}";
                    msgEnviado = "{{ __('meta.contact_v11', [], 'es') }}";
                    break;
            }

            return { msgCampoRequerido, msgCorreoNoValido, msgEnviado };
        }

        function enviarFormulario() {
            const nameInput  = document.getElementById('nombre-form');
            const emailInput = document.getElementById('correo-form');
            const msgInput   = document.getElementById('mensaje-form');

            const errorName  = document.querySelector('[data-error-for="your-name"]');
            const errorEmail = document.querySelector('[data-error-for="your_email"]');
            const errorMsg   = document.querySelector('[data-error-for="your-message"]');

            // limpia mensajes previos
            errorName.textContent  = '';
            errorEmail.textContent = '';
            errorMsg.textContent   = '';

            const { msgCampoRequerido, msgCorreoNoValido, msgEnviado } = getContactMessages();

            let valido = true;

            // --- Validar nombre ---
            if (!nameInput.value.trim()) {
                showError(errorName, msgCampoRequerido);
                valido = false;
            }

            // --- Validar correo ---
            if (!emailInput.value.trim()) {
                showError(errorEmail, msgCampoRequerido);
                valido = false;
            } else {
                const regexCorreo = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!regexCorreo.test(emailInput.value.trim())) {
                    showError(errorEmail, msgCorreoNoValido);
                    valido = false;
                }
            }

            // --- Validar mensaje ---
            if (!msgInput.value.trim()) {
                showError(errorMsg, msgCampoRequerido);
                valido = false;
            }

            // Si no es válido, no continúa
            if (!valido) return;


            // Desactiva botón (opcional)
            const btn = document.querySelector('#btn-enviar');
            btn.disabled = true;

            // Prepara datos
            const data = {
                name: nameInput.value.trim(),
                email: emailInput.value.trim(),
                message: msgInput.value.trim(),
            };

            // Limpia mensajes previos
            errorName.textContent  = '';
            errorEmail.textContent = '';
            errorMsg.textContent   = '';


            // Enviar con Axios
            axios.post('{{ route('contact.send') }}', data)
                .then(response => {
                    if (response.data.ok) {
                        nameInput.value = '';
                        emailInput.value = '';
                        msgInput.value = '';

                        mensajeEnviado(msgEnviado)
                    }
                })
                .catch(error => {
                    if (error.response && error.response.status === 422) {
                        const errores = error.response.data.errors;
                        if (errores.name)    showError(errorName, errores.name[0]);
                        if (errores.email)   showError(errorEmail, errores.email[0]);
                        if (errores.message) showError(errorMsg, errores.message[0]);

                    } else {
                        console.error(error);
                        alert('Error');
                    }
                })
                .finally(() => {
                    btn.disabled = false;
                });
        }

        function mensajeEnviado(mensaje){

            Swal.fire({
                position: "top-end",
                icon: "success",
                title: mensaje,
                showConfirmButton: false,
                timer: 1500
            });
        }

    </script>


    {{-- Superior (Newsletter) block --}}
    @include('frontend.partials.superior')
@endsection


