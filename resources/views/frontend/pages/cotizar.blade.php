@extends('frontend.layouts.app')

@section('title', __('meta.title'))

@section('content')
    <style>
        /* Fondo con blur para la sección de cotización */
        .quote-bg{
            position:relative;
            padding:40px 0 60px; /* menos padding arriba para subir el formulario */
            margin-top:-10px;    /* lo sube un poco más hacia el header */
            overflow:hidden;
        }
        .quote-bg::before{
            content:'';
            position:absolute;
            inset:0;
            background:url('{{ asset('images/finca3.jpg') }}') center center / cover no-repeat fixed;
            filter:blur(6px);
            transform:scale(1.05); /* evita bordes duros del blur */
            z-index:0;
        }
        .quote-bg::after{
            content:'';
            position:absolute;
            inset:0;
            background:rgba(0,0,0,0.35); /* oscurece un poco el fondo para resaltar el card */
            z-index:1;
        }

        .quote-card{
            background:#fff;
            border-radius:16px;
            box-shadow:0 6px 16px rgba(0,0,0,.12);
            padding:28px 26px;
            max-width:560px;        /* un poco más grande */
            margin:0 auto 40px;     /* centrado */
            position:relative;
            z-index:2;              /* por encima del fondo borroso */
        }
        .quote-logo{
            text-align:center;
            margin-bottom:14px;
        }
        .quote-logo img{
            width:80px;
            height:80px;
            border-radius:50%;
            object-fit:cover;
            box-shadow:0 0 0 3px #fff,0 0 12px rgba(0,0,0,.18);
        }
        .quote-card label{
            display:block;
            margin:0 0 6px;
            font-weight:700;
            color:#222;
            font-size:.95rem;
        }
        .quote-card .form-control,
        .quote-card select,
        .quote-card textarea{
            width:100%;
            background:#fafafa;
            border:1px solid rgba(0,0,0,.12);
            border-radius:10px;
            padding:10px 12px;
            font-size:15px;
            outline:0;
            transition:.2s;
            color:#333;              /* asegura que se vea el texto */
            box-sizing:border-box;
        }
        .quote-card textarea{
            resize:vertical;         /* solo vertical, no crece a lo ancho */
            max-height:320px;
        }
        .quote-card .form-control:focus,
        .quote-card select:focus,
        .quote-card textarea:focus{
            border-color:#c6a471;
            background:#fff;
            box-shadow:0 0 0 3px rgba(198,164,113,.15);
        }
        .btn{
            display:inline-flex;
            align-items:center;
            gap:8px;
            border-radius:10px;
            padding:10px 16px;
            font-weight:700;
            border:0;
            cursor:pointer;
            transition:.2s;
            font-size:.95rem;
        }
        .btn-primary{background:#c6a471;color:#fff}
        .btn-primary:hover{background:#b8935e;transform:translateY(-1px)}
        .field-error{
            color:#e60000;
            font-weight:400;
            margin-top:6px;
            font-size:.85rem;
        }
        .field-error:empty{display:none}

        .char-counter{
            display:block;
            margin-top:4px;
            font-size:.8rem;
            color:#666;
            text-align:right;
        }

        @keyframes spin{
            0%{transform:rotate(0deg)}
            100%{transform:rotate(360deg)}
        }
    </style>

    <header class="page-header like-parallax"
            style="background-image:url('{{ asset('images/inner_parallax.jpg') }}');background-size:cover;background-position:center;background-repeat:no-repeat;">
        <div class="container">
            <h1>{{ __('meta.quote') ?? 'Solicitar cotización' }}</h1>
            <ul class="breadcrumbs" typeof="BreadcrumbList" vocab="https://schema.org/">
                <li class="home">
                <span property="itemListElement" typeof="ListItem">
                    <a property="item" typeof="WebPage" href="{{ LaravelLocalization::getLocalizedURL(app()->getLocale(), '/') }}" class="home">
                        <span property="name">{{ __('meta.finca3pinos') }}</span>
                    </a>
                    <meta property="position" content="1">
                </span>
                </li>
                <li class="post post-page current-item">
                <span property="itemListElement" typeof="ListItem">
                    <span property="name">{{ __('meta.quote') ?? 'Cotización' }}</span>
                    <meta property="position" content="2">
                </span>
                </li>
            </ul>
        </div>
    </header>

    {{-- Fondo con blur envolviendo el formulario --}}
    <div class="quote-bg">
        <div class="container">
            <div class="row">
                <div class="col-md-12 text-page">
                    <section class="quote-card">

                        <div class="quote-logo">
                            <img src="{{ asset('images/logo.jpg') }}" alt="Logo Finca 3 Pinos">
                        </div>

                        <form id="quote-form">
                            @csrf

                            <div style="margin-bottom:14px">
                                <label>{{ __('meta.country') }} <span style="color:red">*</span></label>
                                <select name="pais" id="q-pais" class="form-control" required>
                                    @foreach($paises as $pais)
                                        <option value="{{ $pais->id }}">{{ $pais->nombre }}</option>
                                    @endforeach
                                </select>
                                <div class="field-error" data-error-for="pais"></div>
                            </div>

                            <div style="margin-bottom:14px">
                                <label>{{ __('meta.name_and_lastname') }} <span style="color:red">*</span></label>
                                <input type="text" name="nombre" id="q-nombre" class="form-control" maxlength="100">
                                <div class="field-error" data-error-for="nombre"></div>
                            </div>

                            <div style="margin-bottom:14px">
                                <label>{{ __('meta.email_address') }} <span style="color:red">*</span></label>
                                <input type="email" name="email" id="q-email" class="form-control" maxlength="120" style="text-transform:lowercase">
                                <div class="field-error" data-error-for="email"></div>
                            </div>

                            <div style="margin-bottom:14px">
                                <label>{{ __('meta.phone') }}</label>
                                <input type="text" name="telefono" id="q-telefono" class="form-control" maxlength="30">
                                <div class="field-error" data-error-for="telefono"></div>
                            </div>

                            <div style="margin-bottom:14px">
                                <label>{{ __('meta.message') }} <span style="color:red">*</span></label>
                                <textarea name="mensaje" id="q-mensaje" class="form-control" rows="6" maxlength="2000"></textarea>
                                <small id="mensaje-counter" class="char-counter">
                                    2000 {{ __('meta.chars_remaining') ?? 'caracteres restantes' }}
                                </small>
                                <div class="field-error" data-error-for="mensaje"></div>
                            </div>

                            <button type="button" class="btn btn-primary" id="btn-enviar" onclick="enviarCotizacion()">
                                <span id="btn-spinner" class="spinner"
                                      style="display:none;border:3px solid #f3f3f3;border-top:3px solid #d2aa6d;border-radius:50%;width:16px;height:16px;animation:spin .8s linear infinite"></span>
                                <span id="btn-text">{{ __('meta.send_quote') }}</span>
                            </button>

                            <div class="wpcf7-response-output" aria-hidden="true" style="margin-top:12px"></div>
                        </form>
                    </section>
                </div>
            </div>
        </div>
    </div>

    {{-- Si no están cargadas globalmente, incluye tus libs --}}
    <script src="{{ asset('js/axios.min.js') }}"></script>
    <script src="{{ asset('js/sweetalert2.all.min.js') }}"></script>
    <script src="{{ asset('js/toastr.min.js') }}"></script>

    <script>
        const i18nQ = {
            requerido: "{{ __('meta.required') }}",
            correoNoValido: "{{ __('meta.contact_v10') }}",
            enviado: "{{ __('meta.quote_sent') }}",
            error: "{{ __('meta.msg_not_send') }}"
        };

        const MAX_MSG_LEN = 2000;

        function showErr(field, msg){
            const el = document.querySelector(`[data-error-for="${field}"]`);
            if (el){ el.textContent = msg || ''; }
        }

        function validarEmail(v){
            return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(String(v||'').trim());
        }

        function actualizarContadorMensaje(){
            const ta   = document.getElementById('q-mensaje');
            const ctr  = document.getElementById('mensaje-counter');
            if (!ta || !ctr) return;

            const len = ta.value.length;
            const remaining = MAX_MSG_LEN - len;
            ctr.textContent = remaining + ' {{ __('meta.chars_remaining') ?? 'caracteres restantes' }}';
        }

        document.addEventListener('DOMContentLoaded', function(){
            const ta = document.getElementById('q-mensaje');
            if (ta){
                ta.addEventListener('input', actualizarContadorMensaje);
                actualizarContadorMensaje();
            }
        });

        async function enviarCotizacion(){
            // limpia errores
            ['pais','nombre','email','mensaje'].forEach(k=>showErr(k,''));

            const btn = document.getElementById('btn-enviar');
            const spn = document.getElementById('btn-spinner');
            const btx = document.getElementById('btn-text');

            const pais     = document.getElementById('q-pais').value;
            const nombre   = document.getElementById('q-nombre').value.trim();
            const email    = document.getElementById('q-email').value.trim().toLowerCase();
            const telefono = document.getElementById('q-telefono').value.trim();
            const mensaje  = document.getElementById('q-mensaje').value.trim();

            // Validación
            let ok = true;
            if (!pais){ showErr('pais', i18nQ.requerido); ok=false; }
            if (!nombre){ showErr('nombre', i18nQ.requerido); ok=false; }
            if (!email){ showErr('email', i18nQ.requerido); ok=false; }
            else if(!validarEmail(email)){ showErr('email', i18nQ.correoNoValido); ok=false; }
            if (!mensaje){ showErr('mensaje', i18nQ.requerido); ok=false; }
            if (!ok) return;

            btn.disabled = true;
            spn.style.display='inline-block';
            btx.textContent = "{{ __('meta.loading') ?? 'Enviando...' }}";

            try{
                const payload = { pais, nombre, email, telefono, mensaje };
                const resp = await axios.post('', payload);

                if (resp.data && resp.data.success){
                    Swal.fire({position:"top-end",icon:"success",title:i18nQ.enviado,showConfirmButton:false,timer:1500});
                    document.getElementById('quote-form').reset();
                    actualizarContadorMensaje();
                }else{
                    Swal.fire({position:"top-end",icon:"error",title:i18nQ.error,showConfirmButton:false,timer:1800});
                }
            }catch(e){
                if (e.response && e.response.status === 422 && e.response.data.errors){
                    const errs = e.response.data.errors;
                    Object.keys(errs).forEach(k=>{
                        const el = document.querySelector(`[data-error-for="${k}"]`);
                        if (el) el.textContent = errs[k][0];
                    });
                }else{
                    toastr.error(i18nQ.error);
                }
            }finally{
                btn.disabled = false;
                spn.style.display='none';
                btx.textContent = "{{ __('meta.send_quote') }}";
            }
        }
    </script>
@endsection
