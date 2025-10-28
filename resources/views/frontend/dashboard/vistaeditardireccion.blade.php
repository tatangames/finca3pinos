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
        .address-form .form-group select{
            color: #111 !important;                /* valor real */
            background-image: none !important;     /* sin flechas raras */
            -webkit-appearance: auto !important;
            appearance: auto !important;
        }
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
                        <span class="label"><span>🧾</span><span>{{ __('meta.orders') }}</span></span><span class="hint">→</span>
                    </a>
                    <a class="{{ $is('addresses') }}" href="{{ LaravelLocalization::getLocalizedURL(app()->getLocale(), route('user.address', [], false)) }}">
                        <span class="label"><span>📍</span><span>{{ __('meta.addresses') }}</span></span><span class="hint">→</span>
                    </a>
                    <a class="{{ $is('profile') }}" href="#"><span class="label"><span>⚙️</span><span>{{ __('meta.profile') }}</span></span><span class="hint">→</span></a>
                </nav>
            </aside>

            {{-- Content --}}
            <div class="account-content" role="region" aria-label="{{ __('meta.addresses') }}">
                <div class="head"><h3>{{ __('meta.edit_address') }}</h3></div>

                <div class="address-wrap">
                    <form id="address-form" class="address-form" method="POST"
                          action="{{ LaravelLocalization::getLocalizedURL(app()->getLocale(), route('user.update.direction', [], false)) }}"
                          novalidate>
                        @csrf
                        <input type="hidden" name="address_id" value="{{ $address->id }}">

                        {{-- País --}}
                        <div class="form-group" id="bloque-pais">
                            <label for="pais">{{ __('meta.country') }}</label>
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
                            <label for="municipio">{{ __('meta.municipality') }}</label>
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
                            <label for="nombre-usuario">{{ __('meta.name_and_lastname') }}</label>
                            <input id="nombre-usuario" name="nombre" type="text" maxlength="50"
                                   value="{{ old('nombre', $address->nombre) }}"
                                   placeholder="{{ __('meta.name_and_lastname') }}">
                        </div>

                        {{-- Dirección --}}
                        <div class="form-group">
                            <label for="direccion-usuario">{{ __('meta.direction') }}</label>
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

                        {{-- Teléfono --}}
                        <div class="form-group">
                            <label for="telefono-usuario">{{ __('meta.phone_number') }}</label>
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

    <script>
        (function(){
            const form = document.getElementById('address-form');
            if(!form) return;

            const selPais = form.querySelector('#pais');
            const selDep  = form.querySelector('#departamento');
            const selMun  = form.querySelector('#municipio');

            const boxDep  = document.getElementById('bloque-departamento');
            const boxMun  = document.getElementById('bloque-municipio');

            const show = (el, yes) => el && el.classList.toggle('hidden', !yes);
            const disable = (el, yes) => { if(!el) return; el.disabled = !!yes; if(yes && el.tagName==='SELECT') el.selectedIndex = 0; };
            const requireF = (el, yes) => { if(el) el.required = !!yes; };
            const resetSelect = (el) => { if(el) el.selectedIndex = 0; };

            const filterDeps = (paisId) => {
                [...selDep.options].forEach(opt=>{
                    if(opt.value==='') return opt.hidden=false;
                    opt.hidden = parseInt(opt.dataset.pais||0,10) !== paisId;
                });
                // si el seleccionado no pertenece al país actual, resetea
                const match = [...selDep.options].some(o => !o.hidden && o.value === selDep.value);
                if(!match) resetSelect(selDep);
            };

            const filterMuni = (depId) => {
                [...selMun.options].forEach(opt=>{
                    if(opt.value==='') return opt.hidden=false;
                    opt.hidden = parseInt(opt.dataset.departamento||0,10) !== depId;
                });
                const match = [...selMun.options].some(o => !o.hidden && o.value === selMun.value);
                if(!match) resetSelect(selMun);
            };

            function applyRules(init=false){
                const paisId = parseInt(selPais.value||0,10);
                const isSV = paisId === 1;
                const isUS = paisId === 2;

                // Estado base: ocultar ambos
                show(boxDep, false); disable(selDep, true); requireF(selDep, false);
                show(boxMun, false); disable(selMun, true); requireF(selMun, false);

                if(!paisId) return;

                if(isSV){
                    filterDeps(1);
                    show(boxDep, true);  disable(selDep, false); requireF(selDep, true);

                    const depId = parseInt(selDep.value||0,10);
                    if(depId) filterMuni(depId);

                    show(boxMun, true);  disable(selMun, false); requireF(selMun, true);
                    return;
                }

                if(isUS){
                    filterDeps(2);
                    show(boxDep, true);  disable(selDep, false); requireF(selDep, true);

                    // Municipio NO aplica
                    resetSelect(selMun);
                    show(boxMun, false); disable(selMun, true); requireF(selMun, false);
                    return;
                }

                // Otros países: solo país
                resetSelect(selDep);
                resetSelect(selMun);
            }

            // Eventos
            selPais.addEventListener('change', ()=>{
                // limpiar dependencias
                resetSelect(selDep);
                resetSelect(selMun);
                applyRules(false);
            });

            selDep.addEventListener('change', ()=>{
                const paisId = parseInt(selPais.value||0,10);
                if(paisId === 1){
                    const depId = parseInt(selDep.value||0,10);
                    filterMuni(depId);
                    show(boxMun, true); disable(selMun, false); requireF(selMun, true);
                    resetSelect(selMun); // fuerza selección válida
                }else{
                    // para US u otros, no hay municipio
                    resetSelect(selMun);
                    show(boxMun, false); disable(selMun, true); requireF(selMun, false);
                }
            });

            // Init con valores precargados
            document.addEventListener('DOMContentLoaded', ()=>{
                applyRules(true);
                // Si es ES y ya hay depto, filtra municipios para que salga pre-seleccionado correctamente
                const paisId = parseInt(selPais.value||0,10);
                if(paisId === 1 && selDep.value){
                    filterMuni(parseInt(selDep.value,10));
                }
            });
        })();
    </script>

    <script>
        (function(){
            // Marca el <select> con clase "is-placeholder" cuando está en la opción vacía
            function paintPlaceholder(sel){
                if(!sel) return;
                const empty = !sel.value || sel.value === '';
                sel.classList.toggle('is-placeholder', empty);
            }
            // País / Departamento / Municipio
            ['pais','departamento','municipio'].forEach(id=>{
                const el = document.getElementById(id);
                if(!el) return;
                paintPlaceholder(el);
                el.addEventListener('change', ()=>paintPlaceholder(el));
            });
        })();
    </script>


@endsection
