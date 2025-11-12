@extends('frontend.layouts.app')

@section('title', __('meta.title'))

@section('content')
    <style>

        /* ============================================================
   FORMULARIO DE CONTACTO — FINCA 3 PINOS
   ============================================================ */

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

        /* WPBakery custom bg */
        #like_sc_contact_form_7_122453212 .vc_custom_1505582392596 {
            background: transparent !important;
            padding: 0 !important;
            border-radius: 0 !important;
        }

        /* =========================
           CARD PRINCIPAL DEL FORMULARIO
           ========================= */
        .contact-card {
            background: #ffffff;
            border-radius: 16px;
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.06);
            padding: 26px;
            overflow: hidden;
            font-size: 16.5px;
            line-height: 1.6;
            letter-spacing: 0.35px;
        }

        /* Limpia fondos de envoltorios CF7 */
        .contact-card p,
        .contact-card label,
        .contact-card .wpcf7-form-control-wrap {
            background: transparent !important;
            margin-bottom: 12px;
        }

        /* =========================
           TIPOGRAFÍA BASE INPUTS
           ========================= */
        .contact-card .wpcf7-form-control {
            font-weight: 400 !important;
            font-family: "Roboto", system-ui, -apple-system, Segoe UI, Arial, sans-serif;
            color: #333;
            letter-spacing: 0.2px;
        }

        /* =========================
           LABELS DE CAMPOS
           ========================= */
        .contact-card label {
            display: block;
            margin-bottom: 8px;
            font-weight: 700; /* títulos de campo bold */
            color: #222;
            letter-spacing: 0.25px;
        }

        /* =========================
           INPUTS Y TEXTAREAS
           ========================= */
        .contact-card input[type="text"],
        .contact-card input[type="email"],
        .contact-card textarea {
            width: 100%;
            background: #fafafa;
            border: 1px solid rgba(0, 0, 0, 0.12);
            border-radius: 10px;
            padding: 13px 14px;
            outline: none;
            font-size: 16px;
            line-height: 1.45;
            transition: all 0.2s ease;
        }

        /* Placeholders más suaves */
        .contact-card input::placeholder,
        .contact-card textarea::placeholder {
            color: #9a9a9a;
            font-weight: 400;
        }

        /* Forzar minúsculas en email */
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
            min-height: 190px;
            resize: vertical;
        }

        /* Autofill Chrome */
        .contact-card input:-webkit-autofill,
        .contact-card textarea:-webkit-autofill {
            -webkit-box-shadow: 0 0 0 1000px #fff inset;
            box-shadow: 0 0 0 1000px #fff inset;
            -webkit-text-fill-color: #333;
        }

        /* =========================
           BOTÓN ENVIAR
           ========================= */
        .contact-card input[type="submit"],
        .contact-card input[type="button"],
        .contact-card .wpcf7-submit,
        #btn-enviar {
            background: #c6a471 !important;
            color: #fff !important;
            border: none !important;
            font-weight: 700 !important;
            padding: 14px 32px !important;
            border-radius: 10px !important;
            cursor: pointer !important;
            transition: all 0.25s ease !important;
            display: inline-block !important;
            font-size: 16px;
        }

        .contact-card input[type="submit"]:hover,
        .contact-card input[type="button"]:hover,
        .contact-card .wpcf7-submit:hover,
        #btn-enviar:hover {
            background: #b8935e !important;
            transform: translateY(-2px);
            box-shadow: 0 6px 14px rgba(0, 0, 0, 0.15);
        }

        #btn-enviar:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        /* =========================
           MENSAJE DE ÉXITO / ERROR CF7
           ========================= */
        .contact-card .wpcf7-response-output {
            margin: 14px 0 0 !important;
            border-radius: 10px !important;
            background: #f7f7f7;
            border: 1px solid rgba(0, 0, 0, 0.1);
        }

        /* =========================
           DISTRIBUCIÓN DOS COLUMNAS
           ========================= */
        .contact-two-col {
            display: grid !important;
            grid-template-columns: 1fr;
            gap: 24px;
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
           SOCIAL / ÍCONOS DE CONTACTO
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
           MINI BOTONES REDES
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
           ACCIONES
           ========================= */
        .contact-card__actions {
            margin-top: 10px;
            display: flex;
            justify-content: center;
        }

        /* =========================
           ERRORES DE CAMPO (NO BOLD)
           ========================= */
        .field-error {
            color: #e60000 !important; /* rojo brillante */
            font-weight: 400 !important;
            margin-top: 6px;
            font-size: 0.9rem;
            line-height: 1.3;
            letter-spacing: .2px;
        }

        .field-error:empty { display: none; }

        .is-invalid {
            border-color: #d93025 !important;
            box-shadow: none !important;
        }

        /* =========================
           CONTADOR DE CARACTERES
           ========================= */
        .char-counter {
            font-size: .9rem;
            text-align: right;
            opacity: .85;
            margin-top: 6px;
            letter-spacing: .25px;
        }
        .char-counter.warning { color: #c77d00; }
        .char-counter.danger  { color: #d93025; }

        /* =========================
           SPINNER / ESTADO DE ENVÍO
           ========================= */
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
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        button[disabled] {
            opacity: 0.7;
            cursor: not-allowed;
        }

        /* =========================
           SOCIAL LIST (PARTE IZQUIERDA)
           ========================= */
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
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
            transition: transform .3s ease, box-shadow .3s ease;
        }

        .social-list li a:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(0,0,0,0.2);
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



    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="{{ asset('js/toastr.min.js') }}"></script>
    <script src="{{ asset('js/axios.min.js') }}"></script>
    <script src="{{ asset('js/sweetalert2.all.min.js') }}"></script>
    <script src="{{ asset('js/alertaPersonalizada.js') }}"></script>


    {{-- Superior (Newsletter) block --}}
@endsection


