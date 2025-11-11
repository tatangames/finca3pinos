@extends('frontend.layouts.app')

@section('title', __('meta.title'))

@section('content')
    <link rel="stylesheet" href="{{ asset('css/toastr.min.css') }}">
    <style>

        .auth-wrapper {
            max-width: 840px;
            margin: 32px auto 64px;
            padding: 0 16px
        }

        .auth-card {
            background: #111;
            border-radius: 18px;
            box-shadow: 0 18px 45px rgba(0, 0, 0, .35);
            overflow: hidden;
            border: 1px solid rgba(255, 255, 255, .06)
        }

        .auth-tabs {
            display: flex;
            background: #0b0b0b;
            border-bottom: 1px solid rgba(255, 255, 255, .08)
        }

        .auth-tab {
            flex: 1;
            padding: 14px 16px;
            font-weight: 600;
            letter-spacing: .02em;
            text-transform: uppercase;
            color: #cfcfcf;
            background: transparent;
            border: none;
            cursor: pointer;
            transition: all .25s ease
        }

        .auth-tab.is-active {
            color: #111;
            background: #d2aa6d
        }

        .auth-alert {
            margin: 16px;
            padding: 12px 14px;
            border-radius: 10px;
            font-size: .95rem
        }

        .auth-alert.success {
            background: rgba(55, 195, 142, .15);
            color: #aef0d6;
            border: 1px solid rgba(55, 195, 142, .35)
        }

        .auth-alert.error {
            background: rgba(255, 83, 83, .12);
            color: #ffb0b0;
            border: 1px solid rgba(255, 83, 83, .35)
        }

        .auth-panel {
            padding: 20px 20px 28px
        }

        .form-row {
            margin-bottom: 14px
        }

        .form-row label {
            display: block;
            margin: 0 0 6px;
            color: #e8e8e8;
            font-size: .95rem
        }

        .req {
            color: #ff8080
        }

        .input, .auth-panel input[type="text"], .auth-panel input[type="email"], .auth-panel input[type="password"] {
            width: 100%;
            background: #171717;
            border: 1px solid rgba(255, 255, 255, .08);
            color: #f1f1f1;
            border-radius: 12px;
            padding: 12px 14px;
            outline: none;
            transition: border-color .25s ease, box-shadow .25s ease
        }

        .auth-panel input:focus {
            border-color: #d2aa6d;
            box-shadow: 0 0 0 3px rgba(210, 170, 109, .15)
        }

        .password-wrap { position: relative; }
        .password-wrap input { padding-right: 44px; }     /* deja espacio para el botón */
        .toggle-pass {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            background: transparent;
            border: none;
            cursor: pointer;
            z-index: 2;                                     /* asegura que quede por encima */
        }


        .field-error {
            display: block;
            margin-top: 6px;
            color: #ff9f9f;
            font-size: .85rem
        }

        .form-meta {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin: 6px 0 16px
        }

        .checkbox {
            display: flex;
            gap: 8px;
            align-items: center;
            color: #dcdcdc;
            font-size: .95rem
        }

        .link {
            color: #d2aa6d;
            text-decoration: none
        }

        .link:hover {
            text-decoration: underline
        }

        .btn-primary {
            display: block;
            width: 100%;
            text-align: center;
            background: #d2aa6d;
            color: #111;
            font-weight: 700;
            border: none;
            border-radius: 12px;
            padding: 12px 16px;
            cursor: pointer;
            transition: filter .25s ease, transform .06s ease
        }

        .btn-primary:hover {
            filter: brightness(1.05)
        }

        .btn-primary:active {
            transform: translateY(1px)
        }

        .divider {
            display: flex;
            align-items: center;
            gap: 10px;
            color: #bdbdbd;
            margin: 18px 0
        }

        .divider:before, .divider:after {
            content: "";
            flex: 1;
            height: 1px;
            background: rgba(255, 255, 255, .12)
        }

        .social-grid {
            display: grid;
            grid-template-columns:repeat(2, 1fr);
            gap: 10px
        }

        .btn-social {
            display: block;
            text-align: center;
            padding: 10px;
            border-radius: 10px;
            background: #1b1b1b;
            border: 1px solid rgba(255, 255, 255, .08);
            color: #f0f0f0;
            text-decoration: none
        }

        @media (max-width: 640px) {
            .auth-card {
                border-radius: 16px
            }

            .auth-panel {
                padding: 16px
            }

            .form-meta {
                flex-direction: column;
                gap: 8px;
                align-items: flex-start
            }
        }


        /* ====== Tema claro tipo Woo ====== */
        :root {
            --f3p-accent: #d2aa6d;
            --f3p-accent-hover: #c39a57;
            --f3p-label: #2f2f2f;
            --f3p-muted: #8a8a8a;
            --f3p-panel: #f6f6f7;
            --f3p-border: #e6e6ea;
        }

        /* Card y tabs */
        .auth-card {
            background: var(--f3p-panel) !important;
            border: 1px solid var(--f3p-border) !important;
            box-shadow: 0 8px 28px rgba(0, 0, 0, .14) !important;
            border-radius: 14px !important;
        }

        .auth-tabs {
            background: #fff !important;
            border-bottom: 1px solid var(--f3p-border) !important;
        }

        .auth-tab {
            color: #444 !important;
            font-weight: 700 !important;
        }

        .auth-tab.is-active {
            background: var(--f3p-accent) !important;
            color: #121212 !important;
        }

        /* Panel interior */
        .auth-panel {
            background: #f4f4f5;
            border: 1px solid var(--f3p-border);
            margin: 20px;
            border-radius: 10px;
            padding: 22px 22px 26px !important;
        }

        /* Labels y ayudas */
        .form-row label {
            color: var(--f3p-label) !important;
            font-weight: 700 !important;
            opacity: 1 !important; /* evita que se vea “apagado” */
        }

        .req {
            color: #cc4b4b !important;
        }

        /* Inputs */
        .auth-panel input[type="text"],
        .auth-panel input[type="email"],
        .auth-panel input[type="password"] {
            background: #fff !important;
            border: 1px solid #dcdde1 !important;
            color: #111 !important;
            border-radius: 8px !important;
            padding: 11px 12px !important;
        }

        .auth-panel input::placeholder {
            color: #9a9a9a;
        }

        .auth-panel input:focus {
            border-color: var(--f3p-accent) !important;
            box-shadow: 0 0 0 3px rgba(210, 170, 109, .18) !important;
            outline: none;
        }

        /* Mostrar/ocultar contraseña */
        .password-wrap {
            position: relative;
        }

        .toggle-pass {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            background: transparent;
            border: none;
            cursor: pointer;
            color: #6b6b6b;
            font-size: 1rem;
        }

        .toggle-pass:hover {
            color: #2e2e2e;
        }

        /* Checkbox + enlaces */
        .checkbox {
            color: #444 !important;
        }

        .link {
            color: var(--f3p-accent);
        }

        .link:hover {
            text-decoration: underline;
        }

        /* Botón principal */
        .btn-primary {
            background: var(--f3p-accent) !important;
            color: #fff !important; /* ← blanco */
            border-radius: 10px !important;
            border: 1px solid rgba(0, 0, 0, .1) !important;
            font-weight: 700 !important;
            padding: 12px 16px !important;
            box-shadow: 0 2px 0 rgba(0, 0, 0, .06);
            transition: filter .25s ease, transform .06s ease;
        }

        .btn-primary:hover {
            background: var(--f3p-accent-hover) !important;
        }

        .btn-primary:active {
            transform: translateY(1px);
        }

        /* Alertas más suaves */
        .auth-alert.success {
            background: #eefaf3 !important;
            color: #216e4e !important;
            border-color: #c7eed8 !important;
        }

        .auth-alert.error {
            background: #fdf2f2 !important;
            color: #b42318 !important;
            border-color: #f2b8b5 !important;
        }

        /* Responsive */
        @media (max-width: 640px) {
            .auth-panel {
                margin: 14px;
                padding: 18px !important;
            }
        }



        .form-row { position: relative; }
        .field-error {
            color: #e53935;            /* rojo */
            font-size: 12px;
            line-height: 1.2;
            margin-top: 6px;
            display: block;
        }
        /* opcional: borde rojo cuando hay error */
        input[aria-invalid="true"] {
            border-color: #e53935 !important;
            outline: none;
        }



        .btn-primary.loading {
            position: relative;
            color: transparent !important; /* ahora sí se oculta el texto */
        }
        .btn-primary.loading::after {
            content: "";
            position: absolute;
            top: 50%; left: 50%;
            width: 18px; height: 18px;
            border: 2px solid rgba(255,255,255,0.4);
            border-top-color: #fff;
            border-radius: 50%;
            transform: translate(-50%, -50%);
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: translate(-50%, -50%) rotate(0deg); }
            100% { transform: translate(-50%, -50%) rotate(360deg); }
        }


        /* === Tabs activos: texto blanco === */
        .auth-tab.is-active {
            background: var(--f3p-accent) !important; /* ya lo tenías */
            color: #fff !important;                   /* ← blanco */
        }

        /* Opcional: mejor hover/focus en tabs no activos */
        .auth-tab:not(.is-active):hover,
        .auth-tab:not(.is-active):focus-visible {
            color: #111 !important;
        }

        /* Accesibilidad: anillo de enfoque visible */
        .auth-tab:focus-visible {
            outline: 3px solid rgba(210,170,109,.35);
            outline-offset: 2px;
        }


        /* ——— Botón Google (estilo oficial) ——— */
        .social-wrap { margin-top: 14px; }
        .btn-google{
            display:flex; align-items:center; justify-content:center; gap:10px;
            width:100%;
            background:#fff;
            color:#3c4043;                 /* texto gris oscuro */
            border:1px solid #dadce0;      /* borde Google */
            border-radius: 8px;
            padding: 12px 14px;
            font-weight:600;
            text-decoration:none;
            box-shadow: 0 1px 1px rgba(0,0,0,.04);
            transition: box-shadow .2s ease, transform .06s ease, border-color .2s ease;
        }
        .btn-google:hover{
            box-shadow: 0 1px 2px rgba(0,0,0,.08), 0 2px 6px rgba(60,64,67,.15);
            border-color:#c9ccd1;
        }
        .btn-google:active{ transform: translateY(1px); }
        .btn-google:focus-visible{
            outline: 3px solid rgba(66,133,244,.35); /* azul Google */
            outline-offset: 2px;
        }

        /* Contenedor actual para que sea 1 columna (100% ancho) */
        .social-grid{ display:grid; grid-template-columns:1fr; gap:12px; }

        /* Oculta el botón genérico si lo dejas en el HTML */
        .btn-social{ display:none; }


    </style>

    <header class="page-header like-parallax"
            style="background-image: url('{{ asset('images/inner_parallax.jpg') }}');
               background-size: cover; background-position: center; background-repeat: no-repeat;">
        <div class="container"><h1>{{ __('meta.my_account') }}</h1>
            <ul class="breadcrumbs" typeof="BreadcrumbList" vocab="https://schema.org/">
                <li class="home"><span property="itemListElement" typeof="ListItem">
                    <a property="item" typeof="WebPage" title="{{ __('meta.go_to_finca3pinos') }}"
                       href="{{ LaravelLocalization::getLocalizedURL(app()->getLocale(), '/') }}" class="home">
                        <span property="name">{{ __('meta.finca3pinos') }}</span></a>
                    <meta property="position" content="1"></span>
                </li>
                <li class="post post-page current-item"><span property="itemListElement" typeof="ListItem">
                    <span property="name">{{ __('meta.my_account') }}</span>
                    <meta property="position" content="2"></span>
                </li>
            </ul>
        </div>
    </header>


    <div class="container">
        <div class="auth-wrapper">
            <div class="auth-card">
                <div class="auth-tabs" role="tablist" aria-label="Auth Tabs">
                    <button class="auth-tab is-active" data-tab="login" id="tab-login" role="tab" aria-selected="true"
                            aria-controls="panel-login">
                        {{ __('meta.login') }}
                    </button>
                    <button class="auth-tab" data-tab="register" id="tab-register" role="tab" aria-selected="false"
                            aria-controls="panel-register">
                        {{ __('meta.create_account') }}
                    </button>
                </div>

                {{-- Mensajes flash (opcional) --}}
                @if (session('error'))
                    <div class="auth-alert error" role="alert">
                        {{ session('error') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="auth-alert error" role="alert">
                        <ul style="margin:0;padding-left:18px">
                            @foreach ($errors->all() as $err)
                                <li>{{ $err }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif


                {{-- PANE: LOGIN --}}
                <div id="panel-login" class="auth-panel is-active" role="tabpanel" aria-labelledby="tab-login">
                    <form id="form-login" method="POST" action="">
                        @csrf
                        <input type="hidden" name="form_type" value="login">

                        <div class="form-row">
                            <label for="email">{{ __('meta.email_address') }} <span class="req">*</span></label>
                            <input id="email" type="email" maxlength="200" name="email" value="" required autocomplete="email" autofocus>
                            <!-- los errores se inyectan aquí -->
                        </div>

                        <div class="form-row">
                            <label for="password">{{ __('meta.password') }} <span class="req">*</span></label>
                            <div class="password-wrap">
                                <input id="password" type="password" maxlength="100" name="password" required autocomplete="current-password">
                                <button type="button" class="toggle-pass" data-target="password" aria-label="">👁️</button>
                            </div>
                            <!-- los errores se inyectan aquí -->
                        </div>

                        <div class="form-meta">
                            <a class="link" href="{{ LaravelLocalization::getLocalizedURL(app()->getLocale(), route('user.password.request', [], false)) }}">{{ __('meta.forgot_your_password') }}</a>
                        </div>

                        <button id="btn-login" type="button" class="btn-primary">
                            {{ __('meta.login_v2') }}
                        </button>

                        <div class="divider"><span>{{ __('meta.o') }}</span></div>
                        <div class="social-grid">
                            <div class="social-wrap">
                                <a href="{{ route('google.login') }}" class="btn-google" rel="nofollow">
                                    <!-- Logo “G” oficial SVG -->
                                    <svg class="g-logo" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 48 48" aria-hidden="true">
                                        <path fill="#FFC107" d="M43.6 20.5h-1.9V20H24v8h11.3C33.8 31.9 29.3 35 24 35c-6.6 0-12-5.4-12-12s5.4-12 12-12c3.1 0 5.9 1.2 8 3.1l5.7-5.7C34.9 5.1 29.7 3 24 3 12.3 3 3 12.3 3 24s9.3 21 21 21c10.5 0 20-7.6 20-21 0-1.3-.1-2.2-.4-3.5z"/>
                                        <path fill="#FF3D00" d="M6.3 14.7l6.6 4.8C14.8 16.3 19.1 13 24 13c3.1 0 5.9 1.2 8 3.1l5.7-5.7C34.9 5.1 29.7 3 24 3 16.1 3 9.2 7.6 6.3 14.7z"/>
                                        <path fill="#4CAF50" d="M24 45c5.2 0 10.1-2 13.7-5.3l-6.3-5.3C29.4 35.5 26.9 36 24 36c-5.2 0-9.7-3.1-11.6-7.5l-6.5 5C8.7 40.2 15.8 45 24 45z"/>
                                        <path fill="#1976D2" d="M43.6 20.5H24v8h11.3c-1.1 3.1-3.6 5.5-6.6 6.5l6.3 5.3C38.5 36.8 41 31.1 41 24c0-1.3-.1-2.2-.4-3.5z"/>
                                    </svg>
                                    <span>{{ __('meta.google') }}</span>
                                </a>
                            </div>
                        </div>
                    </form>
                </div>

                {{-- PANE: REGISTER --}}
                <div id="panel-register" class="auth-panel" role="tabpanel" aria-labelledby="tab-register" hidden>
                    <form id="form-register" method="POST" action="" novalidate>
                        @csrf
                        <input type="hidden" name="form_type" value="register">

                        <div class="form-row">
                            <label for="name">{{ __('meta.contact_v5') }} <span class="req">*</span></label>
                            <input id="name" type="text" name="name" value="{{ old('name') }}" required autocomplete="name">
                            <!-- errores aquí -->
                        </div>

                        <div class="form-row">
                            <label for="email_reg">{{ __('meta.email_address') }} <span class="req">*</span></label>
                            <input id="email_reg" type="email" name="email" value="{{ old('email') }}" required autocomplete="email">
                            <!-- errores aquí -->
                        </div>

                        <div class="form-row">
                            <label for="password_reg">{{ __('meta.password') }} <span class="req">*</span></label>
                            <div class="password-wrap">
                                <input id="password_reg" type="password" name="password" required autocomplete="new-password" minlength="8">
                                <button type="button" class="toggle-pass" data-target="password_reg" aria-label="{{ __('meta.show_or_hide_password') }}">👁️</button>
                            </div>
                            <!-- errores aquí -->
                        </div>

                        <div class="form-row">
                            <label for="password_confirmation">{{ __('meta.confirm_password') }} <span class="req">*</span></label>
                            <div class="password-wrap">
                                <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password">
                                <button type="button" class="toggle-pass" data-target="password_confirmation" aria-label="{{ __('meta.show_or_hide_password') }}">👁️</button>
                            </div>
                            <!-- errores aquí -->
                        </div>

                        <button id="btn-register" type="button" class="btn-primary">
                            {{ __('meta.create_account') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="{{ asset('js/axios.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/toastr.min.js') }}"></script>
    <script src="{{ asset('js/alertaPersonalizada.js') }}"></script>


    <script>
        const loginUrl = "{{ LaravelLocalization::localizeURL('/login') }}";
        const registerUrl = "{{ LaravelLocalization::localizeURL('/register') }}";
    </script>

    <script>

        (function () {
            const form    = document.getElementById('form-login');
            const btn     = document.getElementById('btn-login');
            const emailEl = document.getElementById('email');
            const passEl  = document.getElementById('password');

            const messages = {
                emailRequired: "{{ __('meta.contact_v12') }}",
                emailInvalid : "{{ __('meta.contact_v13') }}",
                passRequired : "{{ __('meta.contact_v14') }}",
                generalError : "{{ __('meta.unknown_error') }}",
            };

            const csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            axios.defaults.headers.common['X-CSRF-TOKEN'] = csrf;
            axios.defaults.headers.common['Accept'] = 'application/json';

            // --- Helpers ---
            function isEmailValid(value) {
                return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value);
            }

            function getRow(input) {
                return input.closest('.form-row') || input.parentElement;
            }

            function showError(input, msg) {
                const row = getRow(input);
                let help = row.querySelector('.field-error');
                if (!help) {
                    help = document.createElement('small');
                    help.className = 'field-error';
                    help.style.color = '#e53935';
                    help.style.fontSize = '12px';
                    row.appendChild(help);
                }
                help.textContent = msg;
                input.setAttribute('aria-invalid', 'true');
            }

            function clearError(input) {
                const row = getRow(input);
                const help = row.querySelector('.field-error');
                if (help) help.remove();
                input.removeAttribute('aria-invalid');
            }

            function showLoadingButton() {
                btn.classList.add('loading');
                btn.disabled = true;
            }
            function closeLoadingButton() {
                btn.classList.remove('loading');
                btn.disabled = false;
            }

            function validate() {
                let ok = true;
                const email = emailEl.value.trim();
                const pass  = passEl.value.trim();

                if (email === '') {
                    showError(emailEl, messages.emailRequired);
                    ok = false;
                } else if (!isEmailValid(email)) {
                    showError(emailEl, messages.emailInvalid);
                    ok = false;
                } else {
                    clearError(emailEl);
                }

                if (pass === '') {
                    showError(passEl, messages.passRequired);
                    ok = false;
                } else {
                    clearError(passEl);
                }

                return ok;
            }

            // --- Limpiar error al tipear ---
            [emailEl, passEl].forEach(el => {
                el.addEventListener('input', () => clearError(el));
            });

            // --- Envío con Axios ---
            btn.addEventListener('click', () => {
                if (!validate()) return;

                const formData = new FormData();
                formData.append('email', emailEl.value.trim());
                formData.append('password', passEl.value.trim());

                showLoadingButton();

                axios.post(loginUrl, formData)
                    .then(response => {
                        closeLoadingButton()

                        const data = response.data;
                        if (data.success === 1) {
                            const data = response.data;

                            window.location.href = data.ruta;
                        } else {
                            // error de credenciales incorrectas
                            showError(passEl, data.message)
                        }
                    })
                    .catch(error => {
                        closeLoadingButton()
                        showError(passEl, messages.generalError)
                    });
            });

            // --- Enter para enviar ---
            form.addEventListener('keydown', (e) => {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    btn.click();
                }
            });

            // --- Mostrar / ocultar contraseña ---
            document.querySelectorAll('.toggle-pass').forEach(tg => {
                tg.addEventListener('click', () => {
                    const id = tg.getAttribute('data-target');
                    const input = document.getElementById(id);
                    if (!input) return;
                    input.type = (input.type === 'password') ? 'text' : 'password';
                    input.focus();
                });
            });
        })();

    </script>




    <script>
        (function () {
            // ====== Rutas ======
            // Si ya tienes una variable global registerUrl, úsala.
            // Si no, define aquí la ruta a tu endpoint de registro:


            // ====== Elementos ======
            const form   = document.getElementById('form-register');
            const btn    = document.getElementById('btn-register');

            const nameEl   = document.getElementById('name');
            const emailEl  = document.getElementById('email_reg');
            const passEl   = document.getElementById('password_reg');
            const pass2El  = document.getElementById('password_confirmation');

            // ====== Mensajes (usa tus keys existentes) ======
            const messages = {
                nameRequired: "{{ __('meta.name_required') }}", // el nombre es requerido
                emailRequired: "{{ __('meta.contact_v12') }}", // el correo es requerido
                emailInvalid : "{{ __('meta.contact_v13') }}", // ingresar un correo valido
                passRequired : "{{ __('meta.contact_v14') }}", // la contrasena es requerida
                passMin8     : "{{ __('meta.password_min8') }}", // La contraseña debe tener al menos 8 caracteres.
                passConfirmRequired: "{{ __('meta.password_confirmation_required') }}", // Debes confirmar la contraseña.
                passMatch    : "{{ __('meta.passwords_must_match') }}", // Las contraseñas deben coincidir
                generalError : "{{ __('meta.unknown_error') }}",
                emailRepetido : "{{ __('meta.email_repetido') }}",
            };

            // ====== Axios / CSRF ======
            const csrfMeta = document.querySelector('meta[name="csrf-token"]');
            if (csrfMeta) {
                axios.defaults.headers.common['X-CSRF-TOKEN'] = csrfMeta.getAttribute('content');
            }
            axios.defaults.headers.common['Accept'] = 'application/json';

            // ====== Helpers (mismos del login) ======
            function isEmailValid(value) {
                return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value);
            }
            function getRow(input) {
                return input.closest('.form-row') || input.parentElement;
            }
            function showError(input, msg) {
                const row = getRow(input);
                let help = row.querySelector('.field-error');
                if (!help) {
                    help = document.createElement('small');
                    help.className = 'field-error';
                    help.style.color = '#e53935';
                    help.style.fontSize = '12px';
                    row.appendChild(help);
                }
                help.textContent = msg;
                input.setAttribute('aria-invalid', 'true');
            }
            function clearError(input) {
                const row = getRow(input);
                const help = row.querySelector('.field-error');
                if (help) help.remove();
                input.removeAttribute('aria-invalid');
            }
            function showLoadingButton() {
                btn.classList.add('loading');
                btn.disabled = true;
            }
            function closeLoadingButton() {
                btn.classList.remove('loading');
                btn.disabled = false;
            }

            // ====== Validación cliente ======
            function validate() {
                let ok = true;
                const name  = nameEl.value.trim();
                const email = emailEl.value.trim();
                const pass  = passEl.value.trim();
                const pass2 = pass2El.value.trim();

                // Nombre
                if (name === '') {
                    showError(nameEl, messages.nameRequired);
                    ok = false;
                } else {
                    clearError(nameEl);
                }

                // Email
                if (email === '') {
                    showError(emailEl, messages.emailRequired);
                    ok = false;
                } else if (!isEmailValid(email)) {
                    showError(emailEl, messages.emailInvalid);
                    ok = false;
                } else {
                    clearError(emailEl);
                }

                // Password
                if (pass === '') {
                    showError(passEl, messages.passRequired);
                    ok = false;
                } else if (pass.length < 8) {
                    showError(passEl, messages.passMin8);
                    ok = false;
                } else {
                    clearError(passEl);
                }

                // Confirmación
                if (pass2 === '') {
                    showError(pass2El, messages.passConfirmRequired);
                    ok = false;
                } else if (pass !== pass2) {
                    showError(pass2El, messages.passMatch);
                    ok = false;
                } else {
                    clearError(pass2El);
                }

                return ok;
            }

            // Limpiar errores al tipear
            [nameEl, emailEl, passEl, pass2El].forEach(el => {
                el.addEventListener('input', () => clearError(el));
            });

            // ====== Envío por Axios ======
            btn.addEventListener('click', () => {
                if (!validate()) return;
                const formData = new FormData();
                formData.append('name', nameEl.value.trim());
                formData.append('email', emailEl.value.trim());
                formData.append('password', passEl.value.trim());
                formData.append('password_confirmation', pass2El.value.trim());

                showLoadingButton();

                axios.post(registerUrl, formData)
                    .then(response => {
                        closeLoadingButton();

                        const data = response.data || {};

                        if(data.success === 1){ // correo repetido
                            showError(emailEl, messages.emailRepetido);
                        }
                        else if (data.success === 2) {
                            // Si tu backend devuelve 'ruta' como en login, reusa esa convención
                            const next = data.ruta || "{{ url('/') }}";
                            window.location.href = next;
                        }
                        else {
                            toastr.error(messages.generalError);
                        }
                    })
                    .catch(error => {
                        closeLoadingButton();
                        toastr.error(messages.generalError);
                    });
            });

            // Enter para enviar
            form.addEventListener('keydown', (e) => {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    btn.click();
                }
            });

            // Mostrar / ocultar contraseña (reusa la misma lógica del login)
            document.querySelectorAll('.toggle-pass').forEach(tg => {
                tg.addEventListener('click', () => {
                    const id = tg.getAttribute('data-target');
                    const input = document.getElementById(id);
                    if (!input) return;
                    input.type = (input.type === 'password') ? 'text' : 'password';
                    input.focus();
                });
            });
        })();
    </script>


    <script>
        /* ============================================================
           TOGGLE PASSWORDS (funciona en ambos paneles)
        ============================================================ */
        document.addEventListener('click', function (e) {
            const btn = e.target.closest('.toggle-pass');
            if (!btn) return;

            e.preventDefault();

            const targetId = btn.getAttribute('data-target');
            const input = document.getElementById(targetId);
            if (!input) return;

            const showing = input.type === 'text';
            input.type = showing ? 'password' : 'text';
            btn.setAttribute('aria-pressed', String(!showing));
            btn.textContent = showing ? '👁️' : '🙈'; // alterna el icono
            input.focus({ preventScroll: true });
        });
    </script>


    <script>
        (function(){
            const tabs  = document.querySelectorAll('.auth-tab');
            const panes = document.querySelectorAll('.auth-panel');

            function activate(tabName) {
                // Tabs
                tabs.forEach(t => {
                    const isActive = t.dataset.tab === tabName;
                    t.classList.toggle('is-active', isActive);
                    t.setAttribute('aria-selected', String(isActive));
                });

                // Panels
                panes.forEach(p => {
                    const isActive = p.id === `panel-${tabName}`;
                    p.classList.toggle('is-active', isActive);
                    p.toggleAttribute('hidden', !isActive);
                });

                // Quitar cualquier hash/cambio de URL
                const url = location.pathname + location.search;
                history.replaceState(null, '', url);
            }

            // Click handler
            tabs.forEach(t => {
                t.addEventListener('click', (e) => {
                    e.preventDefault(); // por si acaso
                    activate(t.dataset.tab);
                });
            });

            // Por defecto (si quieres abrir login)
            activate('login');
        })();
    </script>


    {{-- Superior (Newsletter) block --}}
@endsection
