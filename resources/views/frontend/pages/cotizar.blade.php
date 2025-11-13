@extends('frontend.layouts.app')

@section('title', __('meta.title'))

@section('content')

    <style>
        /* Fondo con blur para la sección de cotización */
        .quote-bg{
            position: relative;
            padding: 40px 0 60px;   /* menos padding arriba para subir el formulario */
            margin-top: -10px;      /* lo sube un poco más hacia el header */
            overflow: hidden;

            /* >>> Fuerza ancho completo de la pantalla <<< */
            width: 100vw;
            margin-left: calc(50% - 50vw);
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
            width:100px;
            height:auto;         /* mantiene proporción */
            border-radius:0;     /* cuadrado */
            object-fit:contain;
            box-shadow:none;
        }
        .quote-title{
            text-align:center;
            font-size:22px;
            font-weight:bold;
            margin-top:15px;
            margin-bottom:10px;
            color:#222;
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

        /* Botón base */
        .quote-card .btn{
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

        /* Forzamos TODOS los estados del btn-primary dentro de quote-card */
        .quote-card .btn-primary,
        .quote-card .btn-primary:hover,
        .quote-card .btn-primary:focus,
        .quote-card .btn-primary:active,
        .quote-card .btn-primary:focus-visible {
            background:#c6a471 !important;
            color:#fff !important;
            border:0 !important;
            border-radius:10px !important;
            opacity:1 !important;
            box-shadow:none !important;
            transform:none !important;
            text-decoration:none !important;
        }

        /* Estado deshabilitado (cuando está enviando) */
        .quote-card .btn-primary:disabled,
        .quote-card .btn-primary[disabled],
        .quote-card .btn-primary.disabled {
            background:#c6a471 !important;
            color:#fff !important;
            border:0 !important;
            opacity:1 !important;      /* que no se aclare */
            cursor:wait !important;    /* cursor de espera */
            box-shadow:none !important;
            transform:none !important;
        }

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

        /* ============================
           RESET COMPLETO PARA #q-pais
           ============================= */
        .quote-card select#q-pais.form-control {
            /* Color y fondo */
            color: #333 !important;
            background-color: #fafafa !important;
            background-image: none !important;

            /* Tipografía */
            font-size: 15px !important;
            font-family: inherit !important;
            font-weight: 400 !important;
            line-height: 1.5 !important;

            /* Espaciado de texto */
            text-indent: 0 !important;
            text-shadow: none !important;
            letter-spacing: normal !important;
            text-align: left !important;

            /* Visibilidad */
            opacity: 1 !important;
            visibility: visible !important;

            /* WebKit specific */
            -webkit-text-fill-color: #333 !important;
            -webkit-appearance: none !important;

            /* Padding ajustado sin flecha */
            padding: 10px 12px !important;
            padding-left: 12px !important;

            /* Otros resets */
            height: auto !important;
            min-height: 42px !important;

            /* Removemos todas las flechas */
            -moz-appearance: none !important;
            appearance: none !important;
            background-image: none !important;

            /* Evitar transformaciones */
            transform: none !important;
            filter: none !important;
        }

        .quote-card select#q-pais.form-control:focus {
            color: #333 !important;
            -webkit-text-fill-color: #333 !important;
        }

        .quote-card select#q-pais.form-control option {
            color: #333 !important;
            background: #fff !important;
            font-size: 15px !important;
            padding: 8px 12px !important;
        }

        .quote-card select#q-pais.form-control::before,
        .quote-card select#q-pais.form-control::after {
            display: none !important;
        }

        .quote-card select#q-pais.form-control::-ms-value {
            color: #333 !important;
            background: transparent !important;
        }
    </style>


    {{-- Fondo con blur envolviendo el formulario --}}
    <div class="quote-bg">
        <div class="container">
            <div class="row">

                <div class="col-md-12 text-page">
                    <section class="quote-card">


                        <div class="quote-logo">
                            <img src="{{ asset('images/logoindex.png') }}" alt="{{ __('meta.finca3pinos') }}">
                            <p class="quote-title">{{ __('meta.quote_shipping') }}</p>
                        </div>

                        <form id="quote-form">
                            @csrf

                            {{-- PAISES --}}
                            <div style="margin-bottom:14px">
                                <label>{{ __('meta.country') ?? 'País' }} <span style="color:red">*</span></label>
                                <select name="pais" id="q-pais" class="form-control" required>
                                    @foreach($paises as $pais)
                                        <option value="{{ $pais->id }}">{{ $pais->nombre }}</option>
                                    @endforeach
                                </select>
                                <div class="field-error" data-error-for="pais"></div>
                            </div>

                            <div style="margin-bottom:14px">
                                <label>{{ __('meta.name_and_lastname') }} <span style="color:red">*</span></label>
                                <input type="text" name="nombre" id="q-nombre" class="form-control" maxlength="50">
                                <div class="field-error" data-error-for="nombre"></div>
                            </div>

                            <div style="margin-bottom:14px">
                                <label>{{ __('meta.email_address') }} <span style="color:red">*</span></label>
                                <input type="email" name="email" id="q-email" class="form-control" maxlength="100" style="text-transform:lowercase">
                                <div class="field-error" data-error-for="email"></div>
                            </div>

                            <div style="margin-bottom:14px">
                                <label>{{ __('meta.phone') }}</label>
                                <input type="text" name="telefono" id="q-telefono" class="form-control" maxlength="20">
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
    <link href="{{ asset('css/toastr.min.css') }}" type="text/css" rel="stylesheet" />
    <script src="{{ asset('js/axios.min.js') }}"></script>
    <script src="{{ asset('js/sweetalert2.all.min.js') }}"></script>
    <script src="{{ asset('js/toastr.min.js') }}"></script>

    <script>
        const i18nQ = {
            paisRequerido: "{{ __('meta.country_required') }}", // pais es requerido
            nombreRequerido: "{{ __('meta.name_required') }}", // el nombre es requerido
            correoRequerido: "{{ __('meta.contact_v12') }}", // el correo es requerido
            correoNoValido: "{{ __('meta.contact_v10') }}", // correo no valido
            mensajeRequerido: "{{ __('meta.message_is_required') }}", // el mensaje es requerido
            enviado: "{{ __('meta.quote_sent') }}", // cotizacion enviada
            error: "{{ __('meta.msg_not_send') }}" // no pudimos procesar tu solicitud en este momento
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

            const paisEl = document.getElementById('q-pais');
            const pais     = paisEl ? paisEl.value : '';
            const nombre   = document.getElementById('q-nombre').value.trim(); // 500
            const email    = document.getElementById('q-email').value.trim().toLowerCase(); // 100
            const telefono = document.getElementById('q-telefono').value.trim(); // 20
            const mensaje  = document.getElementById('q-mensaje').value.trim(); // 2000

            // Validación
            let ok = true;
            if (!pais){ showErr('pais', i18nQ.paisRequerido); ok=false; }
            if (!nombre){ showErr('nombre', i18nQ.nombreRequerido); ok=false; }
            if (!email){ showErr('email', i18nQ.correoRequerido); ok=false; }
            else if(!validarEmail(email)){ showErr('email', i18nQ.correoNoValido); ok=false; }
            if (!mensaje){ showErr('mensaje', i18nQ.mensajeRequerido); ok=false; }
            if (!ok) return;

            btn.disabled = true;
            spn.style.display='inline-block';
            btx.textContent = "{{ __('meta.loading') ?? 'Enviando...' }}";

            try{
                const payload = { pais, nombre, email, telefono, mensaje };
                const resp = await axios.post('{{ route('enviar.cotizacion') }}', payload);

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
