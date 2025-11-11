@extends('frontend.layouts.app')

@section('title', __('meta.title'))

@section('content')
    <link rel="stylesheet" href="{{ asset('css/toastr.min.css') }}">

    <style>
        .theme-light{--bg:#f7f7f8;--card:#fff;--text:#222;--muted:#7a7a7a;--border:rgba(0,0,0,.08);--brand:#d2aa6d;--primary:#2a58ff}
        .account-wrap{background:var(--bg);color:var(--text);padding:32px 12px}
        .account-container{max-width:1200px;margin:0 auto;display:grid;grid-template-columns:280px 1fr;gap:24px}
        @media (max-width:992px){.account-container{grid-template-columns:1fr}}

        .account-sidebar{background:var(--card);border:1px solid var(--border);border-radius:18px;overflow:hidden}
        .account-sidebar .box-head{padding:18px;border-bottom:1px solid var(--border)}
        .account-sidebar .box-head h4{margin:0;font-size:15px;letter-spacing:.06em;color:var(--brand);text-transform:uppercase}
        .account-menu{display:flex;flex-direction:column;padding:8px}
        .account-menu a{display:flex;align-items:center;justify-content:space-between;gap:12px;padding:14px 12px;border-radius:12px;color:var(--text);text-decoration:none;border:1px solid transparent}
        .account-menu a:hover{background:#fafafa;border-color:var(--border)}

        .account-content{background:var(--card);border:1px solid var(--border);border-radius:18px;overflow:hidden}
        .account-content .head{display:flex;align-items:center;justify-content:center;padding:18px 20px;border-bottom:1px solid var(--border)}
        .account-content .head h3{margin:0;font-size:22px;font-weight:800}

        .address-wrap{padding:18px}
        .address-form{background:#fff;border:1px solid var(--border);border-radius:16px;padding:18px}
        .form-group{display:block;margin:0 0 14px 0}
        .form-group label{display:block;margin:0 0 6px 0;font-size:12px;font-weight:700}
        .form-group input,.form-group select,.form-group textarea{
            display:block;width:100%;max-width:680px;height:40px;padding:8px 12px;margin:0;
            font-size:14px;line-height:1.2;border:1px solid #d5d9d9;border-radius:10px;background:#fff;box-sizing:border-box
        }
        .form-actions{margin-top:12px;display:flex;gap:12px}
        .btn{display:inline-flex;align-items:center;justify-content:center;gap:8px;padding:10px 16px;border-radius:10px;font-weight:800;border:1px solid transparent;cursor:pointer;text-decoration:none}
        .btn-primary{background:var(--primary);border-color:var(--primary);color:#fff}
        .btn-ghost{background:#fff;border-color:var(--primary);color:var(--primary)}

        /* Quitar “adornos” de wrappers y flechas custom */
        .select-wrap{position:static!important;display:block!important;width:100%!important;padding:0!important;margin:0!important;border:0!important;box-shadow:none!important}
        .select-wrap::before,.select-wrap::after,.select-wrap i,.select-wrap svg,.select-wrap span[class*="icon"]{display:none!important}
        .select-wrap select{width:100%!important}
        .address-form select{-webkit-appearance:auto!important;-moz-appearance:auto!important;appearance:auto!important;background-image:none!important;padding-right:10px!important}

        .hidden{display:none!important}

        /* === Alinear inputs y selects al borde izquierdo === */
        .address-form .form-group input,
        .address-form .form-group select,
        .address-form .form-group textarea{
            margin: 0 !important;
            text-align: left !important;
            padding-left: 10px !important;   /* sin “sangría” exagerada */
            padding-right: 10px !important;
            box-shadow: none !important;
            border-width: 1px !important;
        }

        /* === Asegurar color de texto visible en SELECT === */

        /* Cuando el select está en “Seleccionar” (option vacía) pon color tenue */
        .address-form .form-group select.is-placeholder { color:#9aa3af !important; }

        /* iOS/Safari a veces aplica text-indent; lo anulamos */
        .address-form .form-group select,
        .address-form .form-group input{
            text-indent: 0 !important;
        }

        /* Que labels y controles partan del mismo borde */
        .address-form .form-group{ margin-left:0 !important; }


        /* === ARREGLO DEFINITIVO PARA TEXTO DE SELECT INVISIBLE === */
        .address-form select,
        .select-wrap select {
            color: #111 !important;                /* color visible */
            background-color: #fff !important;     /* fondo blanco */
            opacity: 1 !important;                 /* quita opacidad heredada */
            text-shadow: none !important;          /* sin sombras */
            mix-blend-mode: normal !important;     /* por si algún blend lo oculta */
        }

        /* Cuando no hay selección (placeholder vacío) */
        .address-form select option[value=""] {
            color: #9aa3af !important; /* gris tenue */
        }

        /* Algunos navegadores aplican “transparent” en selects inactivos */
        .address-form select:disabled {
            color: #555 !important;
            background-color: #f9f9f9 !important;
            opacity: 1 !important;
        }

        /* Quitar iconos de fondo o pseudo-elementos que tapan el texto */
        .select-wrap::before,
        .select-wrap::after {
            display: none !important;
            content: none !important;
        }


        .address-form .form-group select,
        .form-group select{
            height: auto;               /* liberar la altura fija */
            min-height: 44px;           /* alto cómodo y consistente */
            padding-block: 10px;        /* espacio arriba/abajo para que no “toque” bordes */
            padding-inline: 12px 36px;  /* margen derecho para la flecha nativa */
            line-height: normal;        /* nativos rinden mejor así */
            font-size: 14px;
            box-sizing: border-box;
            -webkit-appearance: auto;
            appearance: auto;
        }


        /* ===== COMPACTO (basado en tu 2º bloque) ===== */
        .address-wrap{ padding:14px !important; }

        /* Caja del form más pequeña y centrada */
        .address-form.address-form--compact{
            padding:12px !important;
            border-radius:12px !important;
            max-width:660px !important;
            margin:0 !important;              /* ← elimina el centrado */
            text-align:left !important;       /* asegura alineación izquierda */
        }

        /* Una sola columna y poca separación vertical */
        .address-form.address-form--compact .form-row{
            display:block !important;
            margin:0 !important;
            padding:0 !important;
        }
        .address-form.address-form--compact .form-group{
            display:block !important;
            margin:0 0 8px 0 !important;
            padding:0 !important;
        }

        /* Labels más chicos */
        .address-form.address-form--compact .form-group label{
            font-size:12px !important;
            font-weight:700;
            line-height:1.1 !important;
            margin:0 0 4px 0 !important;
        }

        /* Inputs/Selects compactos y angostos (tipo Amazon) */
        .address-form.address-form--compact .form-group input,
        .address-form.address-form--compact .form-group select,
        .address-form.address-form--compact .form-group textarea{
            height:32px !important;
            min-height:32px !important;
            line-height:normal !important;
            padding:6px 10px !important;
            font-size:13px !important;
            border:1px solid #d5d9d9 !important;
            border-radius:8px !important;
            background:#fff !important;
            color:#111 !important;
            width:70% !important;         /* controla lo “angosto” del control */
            max-width:380px !important;   /* límite en desktop */
            min-width:220px !important;
            box-sizing:border-box;
            text-align:left !important;
        }

        /* Textarea sí crece un poco más */
        .address-form.address-form--compact textarea{
            height:auto !important;
            min-height:80px !important;
            resize:vertical !important;
        }

        /* Select sin flecha y sin hueco a la derecha */
        .address-form.address-form--compact select{
            -webkit-appearance:none !important;
            -moz-appearance:none !important;
            appearance:none !important;
            background-image:none !important;
            padding-right:10px !important;   /* evita corte del texto */
        }
        /* IE heredado */
        .address-form.address-form--compact select::-ms-expand{ display:none !important; }

        /* Neutralizar adornos del wrapper si existen */
        .address-form.address-form--compact .select-wrap{
            position:static !important;
            display:block !important;
            width:auto !important;
            max-width:100% !important;
            background:none !important;
            border:0 !important;
            padding:0 !important;
            margin:0 !important;
        }
        .address-form.address-form--compact .select-wrap::before,
        .address-form.address-form--compact .select-wrap::after{
            content:none !important; display:none !important;
        }
        .address-form.address-form--compact .select-wrap select{
            height:32px !important; min-height:32px !important;
            font-size:13px !important; color:#111 !important;
            padding:6px 10px !important; background:#fff !important;
            border:1px solid #d5d9d9 !important; border-radius:8px !important;
        }

        /* Botones compactos */
        .btn,
        .address-form.address-form--compact .btn {
            height: 30px !important;
            padding: 0 10px !important;
            font-size: 12px !important;
            font-weight: 500 !important;
            border-radius: 8px !important;
            line-height: 1 !important;
        }


        .address-form.address-form--compact .btn-primary{
            width:auto !important; /* evita que se estire al 100% */
        }

        /* Acciones con poco margen */
        .form-actions{
            margin-top:10px !important;
            gap:8px !important;
            justify-content:flex-start !important;
        }

        /* Responsivo: en móviles sí tomar todo el ancho */
        @media (max-width:600px){
            .address-form.address-form--compact .form-group input,
            .address-form.address-form--compact .form-group select,
            .address-form.address-form--compact .form-group textarea{
                width:100% !important;
                max-width:none !important;
                min-width:0 !important;
            }
        }


        /* ===== FIX ESPECÍFICO DEL SIDEBAR (restaurar estilos) ===== */
        .account-sidebar{
            background: var(--card) !important;
            border: 1px solid var(--border) !important;
            border-radius: 18px !important;
            overflow: hidden !important;
            color: var(--text) !important;
        }

        .account-sidebar .box-head{
            padding: 18px 18px 8px !important;
            border-bottom: 1px solid var(--border) !important;
            background: #fff !important;
        }
        .account-sidebar .box-head h4{
            margin:0 !important;
            font-size:15px !important;
            letter-spacing:.06em !important;
            color: var(--brand) !important;
            text-transform: uppercase !important;
        }

        /* Lista del menú */
        .account-sidebar .account-menu{
            display:flex !important;
            flex-direction:column !important;
            padding:8px !important;
        }

        /* Enlaces base */
        .account-sidebar .account-menu a{
            display:flex !important;
            align-items:center !important;
            justify-content:space-between !important;
            gap:12px !important;
            padding:14px 12px !important;
            border-radius:12px !important;
            text-decoration:none !important;
            border:1px solid transparent !important;
            background:transparent !important;
            color: var(--text) !important;
        }

        /* Label (icono + texto) */
        .account-sidebar .account-menu a .label{
            display:flex !important;
            align-items:center !important;
            gap:10px !important;
            flex:1 !important;
            min-width:0 !important;
        }
        .account-sidebar .account-menu a .label span:last-child{
            color: var(--text) !important;
            font-weight: 700 !important;
        }

        /* Flecha/hint derecha */
        .account-sidebar .account-menu .hint{
            font-size:12px !important;
            color: var(--muted) !important;
        }

        /* Hover */
        .account-sidebar .account-menu a:hover{
            background:#fafafa !important;
            border-color: var(--border) !important;
        }

        /* ACTIVO: píldora beige con texto blanco (como captura) */
        .account-sidebar .account-menu a.is-active{
            background: var(--brand) !important;   /* #d2aa6d */
            color:#fff !important;
            border-color: transparent !important;
        }
        .account-sidebar .account-menu a.is-active .label span:last-child{
            color:#fff !important;
        }
        .account-sidebar .account-menu a.is-active .hint{
            color:#fff !important;
            opacity:.9 !important;
        }

        /* 1) Si la cabecera está vacía, no la muestres */
        .account-content .head:empty {
            display: none !important;
            border: 0 !important;
            padding: 0 !important;
        }

        /* 2) Menos aire arriba */
        .address-wrap {
            padding-top: 8px !important;
        }
        .address-form {
            padding-top: 10px !important;
        }

        /* 3) Título sin margen superior */
        .address-form h4 {
            margin-top: 0 !important;
            margin-bottom: 10px !important;
        }


        .hidden-hard{display:none!important;visibility:hidden!important;height:0!important;margin:0!important;padding:0!important;overflow:hidden!important}

        /* Paleta */
        :root, .theme-light { --error:#e53935; }

        /* Mensaje de error que agregas con JS */
        .address-form .form-group .error-text{
            color: var(--error) !important;
            font-weight: 500 !important;
            font-size: 12px !important;
            margin-top: 4px !important;
            display: block !important;
        }

        /* Pinta el label cuando hay error en el grupo */
        .address-form .form-group.has-error > label{
            color: var(--error) !important;
        }

        /* Borde rojo del control con error */
        .address-form .form-group.has-error input,
        .address-form .form-group.has-error select,
        .address-form .form-group.has-error textarea{
            border-color: var(--error) !important;
            box-shadow: 0 0 0 1px rgba(229,57,53,.15) !important;
        }

        /* (Opcional) si alguna librería usa label.error */
        .address-form label.error{
            color: var(--error) !important;
            font-weight:600 !important;
            font-size:12px !important;
            margin-top:4px !important;
            display:block !important;
        }


    </style>

    @php
        $active = 'addresses';
        $is = fn ($key) => $active === $key ? 'is-active' : '';
    @endphp

    <section class="account-wrap theme-light">
        <div class="account-container">

            {{-- Sidebar --}}
            <aside class="account-sidebar" aria-label="Sidebar">
                <div class="box-head"><h4>{{ __('meta.my_account') }}</h4></div>
                <nav class="account-menu" role="navigation">
                    <a class="{{ $is('orders') }}" href="{{ LaravelLocalization::getLocalizedURL(app()->getLocale(), route('user.orders', [], false)) }}">
                        <span class="label"><span>🧾</span><span>{{ __('meta.orders') }}</span></span>
                        <span class="hint">→</span>
                    </a>
                    <a class="{{ $is('addresses') }}" href="{{ LaravelLocalization::getLocalizedURL(app()->getLocale(), route('user.address', [], false)) }}">
                        <span class="label"><span>📍</span><span>{{ __('meta.addresses') }}</span></span>
                        <span class="hint">→</span>
                    </a>
                    <a class="{{ $is('profile') }}" href="{{ LaravelLocalization::getLocalizedURL(app()->getLocale(), route('user.view.perfil', [], false)) }}">
                        <span class="label"><span>⚙️</span><span>{{ __('meta.profile') }}</span></span>
                        <span class="hint">→</span>
                    </a>
                    <a href="#" id="logoutLink">
                        <span class="label"><span>🚪</span><span>{{ __('meta.logout') }}</span></span>
                        <span class="hint">→</span>
                    </a>
                    <form id="logoutForm" method="POST" action="#" style="display:none">@csrf</form>
                </nav>
            </aside>

            {{-- Content --}}
            <div class="account-content" role="region" aria-label="{{ __('meta.addresses') }}">
                <div class="head">

                </div>

                <div class="address-wrap">
                    <form id="address-form"
                          class="address-form address-form--compact"
                          method="POST"
                          action="#"
                          novalidate>
                        @csrf
                        <h4>{{ __('meta.edit_address') }}</h4>

                        <input type="hidden" name="address_id" value="{{ $address->id }}">

                        {{-- País --}}
                        <div class="form-group" id="bloque-pais">
                            <label for="pais">{{ __('meta.country') }} <span style="color: red">*</span></label>
                            <div class="select-wrap">
                                <select id="pais" name="pais">
                                    <option value="">{{ __('meta.select') }}</option>
                                    @foreach($paises as $p)
                                        <option value="{{ $p->id }}" {{ (int)$p->id === (int)$address->id_paises ? 'selected' : '' }}>
                                            {{ $p->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        {{-- Departamento (ES y US) --}}
                        <div class="form-group" id="bloque-departamento">
                            <label for="departamento">{{ __('meta.department') }}</label>
                            <div class="select-wrap">
                                <select id="departamento" name="departamento">
                                    <option value="">{{ __('meta.select') }}</option>
                                    @foreach($departamentos as $d)
                                        <option value="{{ $d->id }}" data-pais="{{ $d->id_paises }}"
                                            {{ (int)$d->id === (int)$address->id_departamento ? 'selected' : '' }}>
                                            {{ $d->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        {{-- Municipio (solo ES) --}}
                        <div class="form-group" id="bloque-municipio">
                            <label for="municipio">{{ __('meta.municipality') }} <span style="color: red">*</span></label>
                            <div class="select-wrap">
                                <select id="municipio" name="municipio">
                                    <option value="">{{ __('meta.select') }}</option>
                                    @foreach($municipios as $m)
                                        <option value="{{ $m->id }}" data-departamento="{{ $m->id_departamentos }}"
                                            {{ (int)$m->id === (int)$address->id_municipio ? 'selected' : '' }}>
                                            {{ $m->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        {{-- Nombre --}}
                        <div class="form-group">
                            <label for="nombre-usuario">{{ __('meta.name_and_lastname') }} <span style="color: red">*</span></label>
                            <input id="nombre-usuario" name="nombre" type="text" maxlength="50"
                                   value="{{ old('nombre', $address->nombre) }}"
                                   placeholder="{{ __('meta.name_and_lastname') }}">
                        </div>

                        {{-- Dirección --}}
                        <div class="form-group">
                            <label for="direccion-usuario">{{ __('meta.direction') }} <span style="color: red">*</span></label>
                            <input id="direccion-usuario" name="direccion" type="text" maxlength="100"
                                   value="{{ old('direccion', $address->direccion) }}"
                                   placeholder="{{ __('meta.input_direction') }}">
                        </div>

                        {{-- Referencia opcional --}}
                        <div class="form-group">
                            <label for="direccionopcional-usuario">{{ __('meta.input_directionv2') }}</label>
                            <input id="direccionopcional-usuario" name="direccion_opcional" type="text" maxlength="100"
                                   value="{{ old('direccion_opcional', $address->direccion_opcional) }}"
                                   placeholder="{{ __('meta.input_directionv2') }}">
                        </div>


                        {{-- Ciudad (para otros países y EE.UU.) --}}
                        <div class="form-group hidden" id="bloque-ciudad" style="margin-top:15px">
                            <label for="ciudad-usuario">{{ __('meta.city') }} </label>
                            <input id="ciudad-usuario" name="ciudad" type="text" maxlength="50"
                                   value="{{ old('ciudad', $address->ciudad) }}"
                                   placeholder="{{ __('meta.city') }}" disabled>
                        </div>


                        {{-- Provincia / Estado / Región --}}
                        <div class="form-group hidden" id="bloque-provincia" style="margin-top:15px">
                            <label for="provincia-usuario">{{ __('meta.state_province') }}</label>
                            <input id="provincia-usuario" name="estado" type="text" maxlength="50"
                                   value="{{ old('estado', $address->estado) }}"
                                   placeholder="{{ __('meta.state_province') }}" disabled>
                        </div>

                        {{-- Código Postal --}}
                        <div class="form-group hidden" id="bloque-postal" style="margin-top:15px">
                            <label for="postal-usuario">{{ __('meta.postal_code') }}</label>
                            <input id="postal-usuario" name="zipcode" type="text" maxlength="20"
                                   value="{{ old('zipcode', $address->zipcode) }}"
                                   placeholder="{{ __('meta.postal_code') }}" disabled>
                        </div>

                        {{-- Teléfono --}}
                        <div class="form-group">
                            <label for="telefono-usuario">{{ __('meta.phone_number') }} <span style="color: red">*</span></label>
                            <input id="telefono-usuario" name="telefono" type="text" maxlength="20"
                                   value="{{ old('telefono', $address->telefono) }}"
                                   placeholder="{{ __('meta.phone_number') }}">
                        </div>

                        <div class="form-actions">
                            <a href="{{ LaravelLocalization::getLocalizedURL(app()->getLocale(), route('user.address', [], false)) }}" class="btn btn-ghost">
                                {{ __('meta.cancel') }}
                            </a>
                            <button type="submit" class="btn btn-primary">{{ __('meta.save_changes') }}</button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </section>

    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="{{ asset('js/toastr.min.js') }}"></script>
    <script src="{{ asset('js/axios.min.js') }}"></script>


    <style>
        #bloque-municipio.hidden,
        #bloque-departamento.hidden,
        .form-group.hidden { display:none !important; }
    </style>
    <script>
        (function () {
            const form   = document.getElementById('address-form');

            // Controles
            const selPais = document.getElementById('pais');
            const selDep  = document.getElementById('departamento');
            const selMun  = document.getElementById('municipio');

            const inpNombre    = document.getElementById('nombre-usuario');
            const inpDireccion = document.getElementById('direccion-usuario');
            const inpDirOpt    = document.getElementById('direccionopcional-usuario');
            const inpTelefono  = document.getElementById('telefono-usuario');
            const inpCiudad    = document.getElementById('ciudad-usuario');
            const inpProvincia = document.getElementById('provincia-usuario');
            const inpPostal    = document.getElementById('postal-usuario');
            const inpAddressId = form?.querySelector('input[name="address_id"]');

            // Wrappers
            const boxDep       = document.getElementById('bloque-departamento');
            const boxMun       = document.getElementById('bloque-municipio');
            const boxCity      = document.getElementById('bloque-ciudad');
            const boxProvincia = document.getElementById('bloque-provincia');
            const boxPostal    = document.getElementById('bloque-postal');

            // Form-groups
            const fgPais   = selPais?.closest('.form-group');
            const fgDep    = selDep?.closest('.form-group');
            const fgMun    = selMun?.closest('.form-group');
            const fgNombre = inpNombre?.closest('.form-group');
            const fgDir    = inpDireccion?.closest('.form-group');
            const fgTel    = inpTelefono?.closest('.form-group');

            // Refs directos
            const grpDep = selDep?.closest('.form-group');
            const grpMun = selMun?.closest('.form-group');

            const i18n = {
                countryRequired:      "{{ __('meta.country_required') }}",
                nameRequired:         "{{ __('meta.name_required') }}",
                addressRequired:      "{{ __('meta.address_required') }}",
                phoneRequired:        "{{ __('meta.phone_required') }}",
                departmentRequired:   "{{ __('meta.department_required') }}",
                municipalityRequired: "{{ __('meta.municipality_required') }}",
                genericError:         "{{ __('meta.error_v1') }}",
                savedOk:              "{{ __('meta.saved_successfully') }}",
                saving:               "{{ __('meta.saving') }}"
            };

            function isVisibleControl(ctrl){
                if(!ctrl) return false;
                const group = ctrl.closest('.form-group') || ctrl;
                const style = window.getComputedStyle(group);
                return !group.classList.contains('hidden') && style.display !== 'none' && !ctrl.disabled;
            }

            function clearError(fg){
                if(!fg) return;
                fg.classList.remove('has-error');
                fg.querySelector('.error-text')?.remove();
            }
            function setError(fg, msg){
                if(!fg) return;
                clearError(fg);
                const span = document.createElement('span');
                span.className = 'error-text';
                span.textContent = msg;
                fg.appendChild(span);
            }
            function attachAutoClear(control, fg, isValidFn){
                if(!control || !fg) return;
                const handler = () => { if(isValidFn()) clearError(fg); };
                control.addEventListener('input', handler);
                control.addEventListener('change', handler);
                control.addEventListener('blur', handler);
            }

            function toggleBlockNoClear(wrap, control, visible, required = false){
                if(!wrap || !control) return;
                wrap.classList.toggle('hidden', !visible);
                wrap.style.display = visible ? '' : 'none';
                control.disabled   = !visible;
                control.required   = !!(visible && required);
                if(!visible && control.tagName === 'INPUT'){
                    control.value = '';
                }
            }

            const resetSelect = (el) => { if (el) el.selectedIndex = 0; };

            function clearSelectToPlaceholder(select){
                if(!select) return;
                const first = select.options[0] ? select.options[0].cloneNode(true) : null;
                select.innerHTML = '';
                if(first) select.appendChild(first);
                resetSelect(select);
                select.classList.add('is-placeholder');
            }

            function toggleMunicipio(visible, required = false){
                if (boxMun){
                    boxMun.classList.toggle('hidden', !visible);
                    boxMun.style.display = visible ? '' : 'none';
                }
                if (grpMun){
                    grpMun.classList.toggle('hidden', !visible);
                    grpMun.style.display = visible ? '' : 'none';
                }
                if (selMun){
                    selMun.disabled = !visible;
                    selMun.required = !!(visible && required);
                    if (!visible) clearSelectToPlaceholder(selMun);
                }
            }
            function toggleDepartamento(visible, required = false){
                if (boxDep){
                    boxDep.classList.toggle('hidden', !visible);
                    boxDep.style.display = visible ? '' : 'none';
                }
                if (grpDep){
                    grpDep.classList.toggle('hidden', !visible);
                    grpDep.style.display = visible ? '' : 'none';
                }
                if (selDep){
                    selDep.disabled = !visible;
                    selDep.required = !!(visible && required);
                    if (!visible) resetSelect(selDep);
                }
            }

// IDs iniciales que vienen de la vista (si existen)
            const INITIAL = {
                pais: parseInt(selPais?.value || '0', 10),
                dep:  parseInt(selDep?.value  || '0', 10),
                mun:  (function(){
                    if(!selMun) return 0;
                    const v = parseInt(selMun.value || '0', 10);
                    return Number.isNaN(v) ? 0 : v;
                })()
            };

            // ===== MASTER de Municipios =====
            const MUNICIPIO_MASTER = (() => {
                if(!selMun) return [];
                return Array.from(selMun.options).map(o => ({
                    value: o.value,
                    label: o.textContent,
                    dep:   o.getAttribute('data-departamento') || o.dataset.departamento || '',
                    isPlaceholder: o.value === ''
                }));
            })();

            function fillMunicipiosByDepartamento(depId, preselectId = null){
                if(!selMun) return;
                clearSelectToPlaceholder(selMun);
                const frag = document.createDocumentFragment();
                let hasPreselect = false;

                MUNICIPIO_MASTER
                    .filter(o => !o.isPlaceholder && parseInt(o.dep || '0',10) === depId)
                    .forEach(o => {
                        const opt = document.createElement('option');
                        opt.value = o.value;
                        opt.textContent = o.label;
                        opt.setAttribute('data-departamento', o.dep);
                        if(preselectId && String(preselectId) === String(o.value)){
                            opt.selected = true;
                            hasPreselect = true;
                        }
                        frag.appendChild(opt);
                    });

                selMun.appendChild(frag);

                // Si no hay preselect válido, deja el placeholder
                if(!hasPreselect){
                    resetSelect(selMun);
                    selMun.classList.add('is-placeholder');
                } else {
                    selMun.classList.remove('is-placeholder');
                }
            }

            // ===== Filtro de departamentos por país (require data-pais en <option>) =====
            const filtrarDepartamentos = (idPais) => {
                if (!selDep) return;
                [...selDep.options].forEach(opt => {
                    if (opt.value === '') return (opt.hidden = false);
                    opt.hidden = parseInt(opt.dataset.pais || '0', 10) !== idPais;
                });
                const ok = [...selDep.options].some(o => !o.hidden && o.value === selDep.value);
                if (!ok) resetSelect(selDep);
                selDep.classList.toggle('is-placeholder', !selDep.value);
            };

            // ===== Validación =====
            function validate(){
                let ok = true;

                [fgPais, fgDep, fgMun, fgNombre, fgDir, fgTel].forEach(clearError);

                const paisId = parseInt(selPais?.value || 0, 10);

                if(!selPais || selPais.value === ''){
                    setError(fgPais, i18n.countryRequired); ok = false;
                }
                if(!inpNombre || !inpNombre.value.trim()){
                    setError(fgNombre, i18n.nameRequired); ok = false;
                }
                if(!inpDireccion || !inpDireccion.value.trim()){
                    setError(fgDir, i18n.addressRequired); ok = false;
                }
                if(!inpTelefono || !inpTelefono.value.trim()){
                    setError(fgTel, i18n.phoneRequired); ok = false;
                }

                // Reglas por país:
                if(paisId === 1){
                    // El Salvador: Departamento + Municipio requeridos
                    if(selDep && isVisibleControl(selDep)){
                        if(selDep.value === '' || selDep.selectedIndex === 0){
                            setError(fgDep, i18n.departmentRequired); ok = false;
                        }
                    }
                    if(selMun && isVisibleControl(selMun)){
                        if(selMun.value === '' || selMun.selectedIndex === 0){
                            setError(fgMun, i18n.municipalityRequired); ok = false;
                        }
                    }
                } else if (paisId === 2){
                    // Estados Unidos: solo Departamento (estado)
                    if(selDep && isVisibleControl(selDep)){
                        if(selDep.value === '' || selDep.selectedIndex === 0){
                            setError(fgDep, i18n.departmentRequired); ok = false;
                        }
                    }
                }
                return ok;
            }

            // Auto-clear
            attachAutoClear(selPais,      fgPais,   () => selPais.value !== '');
            attachAutoClear(inpNombre,    fgNombre, () => !!inpNombre.value.trim());
            attachAutoClear(inpDireccion, fgDir,    () => !!inpDireccion.value.trim());
            attachAutoClear(inpTelefono,  fgTel,    () => !!inpTelefono.value.trim());
            attachAutoClear(selDep,       fgDep,    () => selDep.value !== '' && selDep.selectedIndex > 0);
            attachAutoClear(selMun,       fgMun,    () => selMun.value !== '' && selMun.selectedIndex > 0);

            // ===== URLS / CSRF =====
            const CSRF = (document.querySelector('meta[name=\"csrf-token\"]')?.getAttribute('content'))
                || (form?.querySelector('input[name=_token]')?.value) || '';

            function setSubmitting(state){
                if(!form) return;
                const btn = form.querySelector('[type=\"submit\"], button.submit');
                if(btn){
                    if(state){
                        btn.dataset.prevText = btn.innerHTML;
                        btn.setAttribute('disabled', 'disabled');
                        btn.innerHTML = '<span class=\"spinner\" aria-hidden=\"true\"></span> ' + i18n.saving;
                    } else {
                        btn.removeAttribute('disabled');
                        if(btn.dataset.prevText) btn.innerHTML = btn.dataset.prevText;
                    }
                }
            }
            function getVal(el){ return (el?.value ?? '').trim(); }

                // === Helpers "duros" para mostrar/ocultar ===
                function hardHide(wrap, ctrl){
                if(!wrap || !ctrl) return;
                wrap.classList.add('hidden','hidden-hard');
                wrap.setAttribute('hidden','hidden');
                wrap.style.display   = 'none';
                wrap.style.visibility= 'hidden';
                ctrl.disabled = true;
                ctrl.required = false;
                if (ctrl.tagName === 'INPUT') ctrl.value = '';
            }
                function hardShow(wrap, ctrl, required=false){
                if(!wrap || !ctrl) return;
                wrap.classList.remove('hidden','hidden-hard');
                wrap.removeAttribute('hidden');
                wrap.style.display   = '';       // deja que el CSS natural aplique
                wrap.style.visibility= '';
                ctrl.disabled = false;
                ctrl.required = !!required;
            }

            function aplicarEstadoPorPais(rawId){
                const id  = parseInt(rawId, 10) || 0;
                const isES = id === 1;
                const isUS = id === 2;

                if (isES){
                    // 🇸🇻 El Salvador: Dep + Mun visibles, ciudad/estado/postal ocultos
                    hardShow(boxDep, selDep, true);
                    hardShow(boxMun, selMun, true);

                    hardHide(boxCity,      inpCiudad);
                    hardHide(boxProvincia, inpProvincia); // ← Estado
                    hardHide(boxPostal,    inpPostal);

                    filtrarDepartamentos(1);
                    return;
                }

                if (isUS){
                    // 🇺🇸 Estados Unidos: Dep visible, Mun oculto
                    hardShow(boxDep, selDep, true);
                    hardHide(boxMun, selMun);

                    // Ciudad y Postal visibles, Estado oculto
                    hardShow(boxCity,      inpCiudad,    true);
                    hardHide(boxProvincia, inpProvincia);   // ← ocultar solo Estado
                    hardShow(boxPostal,    inpPostal,    true);

                    filtrarDepartamentos(2);
                    clearSelectToPlaceholder(selMun);
                    return;
                }

                // 🌎 Otros países: ocultar Dep y Mun, mostrar todos los demás
                hardHide(boxDep, selDep);
                hardHide(boxMun, selMun);

                hardShow(boxCity,      inpCiudad,    true);
                hardShow(boxProvincia, inpProvincia, true);  // ← Estado visible solo aquí
                hardShow(boxPostal,    inpPostal,    true);

                resetSelect(selDep);
                clearSelectToPlaceholder(selMun);
            }

                // === Refuerzos en los listeners (pequeño ajuste) ===
                selPais?.addEventListener('change', function(){
                aplicarEstadoPorPais(this.value);
                const paisId = parseInt(this.value || '0', 10);

                if (paisId === 1){
                const depId = parseInt(selDep?.value || '0', 10);
                if (depId) { fillMunicipiosByDepartamento(depId); }
                else { clearSelectToPlaceholder(selMun); }
            } else {
                clearSelectToPlaceholder(selMun);
            }

                // En caso de que alguna hoja de estilo “reactive” algo, refuerza en el siguiente frame
                requestAnimationFrame(()=>aplicarEstadoPorPais(this.value));
            });


            // ===== Listeners =====
            selPais?.addEventListener('change', function(){
                aplicarEstadoPorPais(this.value);
                const paisId = parseInt(this.value || '0', 10);

                if (paisId === 1){
                    const depId = parseInt(selDep?.value || '0', 10);
                    toggleMunicipio(true, true);
                    if (depId){
                        fillMunicipiosByDepartamento(depId);
                    } else {
                        clearSelectToPlaceholder(selMun);
                    }
                } else {
                    // Cualquier país ≠ 1: asegúrate de limpiar municipio para no arrastrar valor viejo
                    toggleMunicipio(paisId === 2 ? false : false);
                    clearSelectToPlaceholder(selMun);
                }
            });

            selDep?.addEventListener('change', function(){
                const idPais = parseInt(selPais?.value || '0', 10);
                const depId  = parseInt(this.value || '0', 10);

                if (idPais === 1){
                    toggleMunicipio(true, true);
                    if (depId){
                        fillMunicipiosByDepartamento(depId);
                    } else {
                        clearSelectToPlaceholder(selMun);
                    }
                } else if (idPais === 2){
                    toggleMunicipio(false);
                } else {
                    toggleMunicipio(false);
                    toggleDepartamento(false);
                }
            });

            // Placeholder visual
            (function(){
                function paintPlaceholder(sel){
                    if(!sel) return;
                    const empty = !sel.value || sel.value === '';
                    sel.classList.toggle('is-placeholder', empty);
                }
                ['pais','departamento','municipio'].forEach(id=>{
                    const el = document.getElementById(id);
                    if(!el) return;
                    paintPlaceholder(el);
                    el.addEventListener('change', ()=>paintPlaceholder(el));
                });
            })();

            // ===== INIT =====
            document.addEventListener('DOMContentLoaded', () => {
                aplicarEstadoPorPais(selPais?.value || '0');

                const idPais = parseInt(selPais?.value || '0', 10);

                if (idPais === 1){
                    const depId = parseInt(selDep?.value || '0', 10);
                    toggleDepartamento(true, true);
                    toggleMunicipio(true, true);

                    if (depId){
                        // ← Usa el municipio inicial SÓLO en el init
                        fillMunicipiosByDepartamento(depId, INITIAL.mun || null);
                    } else {
                        clearSelectToPlaceholder(selMun);
                    }

                    toggleBlockNoClear(boxCity,      inpCiudad,    false, false);
                    toggleBlockNoClear(boxProvincia, inpProvincia, false, false);
                    toggleBlockNoClear(boxPostal,    inpPostal,    false, false);
                } else if (idPais === 2){
                    toggleDepartamento(true, true);
                    toggleMunicipio(false);
                    toggleBlockNoClear(boxCity,      inpCiudad,    true,  true);
                    toggleBlockNoClear(boxProvincia, inpProvincia, true,  true);
                    toggleBlockNoClear(boxPostal,    inpPostal,    true,  true);
                } else {
                    toggleDepartamento(false);
                    toggleMunicipio(false);
                    toggleBlockNoClear(boxCity,      inpCiudad,    true,  true);
                    toggleBlockNoClear(boxProvincia, inpProvincia, true,  true);
                    toggleBlockNoClear(boxPostal,    inpPostal,    true,  true);
                }
            });

            // ===== Submit por Axios (EDITAR) =====
            async function sendUpdate(){
                if(typeof window.axios === 'undefined'){
                    alert(i18n.genericError);
                    return;
                }

                const fd = new FormData(form);

                fd.set('address_id',        inpAddressId?.value || '{{ $address->id }}');
                fd.set('pais',              selPais?.value || '');
                fd.set('departamento',      selDep?.value || '');
                fd.set('municipio',         selMun?.value || '');
                fd.set('nombre',            getVal(inpNombre));
                fd.set('direccion',         getVal(inpDireccion));
                fd.set('direccion_opcional',getVal(inpDirOpt));
                fd.set('telefono',          getVal(inpTelefono));
                fd.set('ciudad',            getVal(inpCiudad));
                fd.set('provincia',         getVal(inpProvincia));
                fd.set('postal',            getVal(inpPostal));

                try{
                    setSubmitting(true);
                    const res = await axios.post(
                        "{{ LaravelLocalization::getLocalizedURL(app()->getLocale(), route('user.update.direction', ['id' => $address->id], false)) }}",
                        fd,
                        { headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' } }
                    );
                    const data = res?.data || {};
                    if (data.success === 1){
                        [fgPais, fgDep, fgMun, fgNombre, fgDir, fgTel].forEach(clearError);
                        const url = data.ruta || "{{ LaravelLocalization::getLocalizedURL(app()->getLocale(), route('user.address', [], false)) }}";
                        window.location.assign(url);
                    } else {
                        toastr.error(i18n.genericError);
                    }
                } catch(e){
                    toastr.error(i18n.genericError);
                } finally {
                    setSubmitting(false);
                }
            }

            form?.addEventListener('submit', function(e){
                e.preventDefault();
                if(!validate()){
                    const firstErr = form.querySelector('.has-error select, .has-error input, .has-error textarea');
                    firstErr?.focus();
                    return;
                }
                sendUpdate();
            });
        })();
    </script>




@endsection
