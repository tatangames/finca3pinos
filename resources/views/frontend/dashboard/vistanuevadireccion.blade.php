@extends('frontend.layouts.app')

@section('title', __('meta.title'))

@section('content')
    <link rel="stylesheet" href="{{ asset('css/toastr.min.css') }}">

    <style>
        /* ===== THEME (claro tipo maqueta 2) ===== */
        .theme-light {
            --bg: #f7f7f8;
            --card: #ffffff;
            --text: #222;
            --muted: #7a7a7a;
            --border: rgba(0, 0, 0, .08);
            --brand: #d2aa6d;
            --brand-900: #b38948;
            --brand-contrast: #0e0e0e;
            --primary: #2a58ff;
            --success: #38c172;
            --pill: #f2f2f4
        }

        .account-wrap {
            background: var(--bg);
            color: var(--text);
            padding: 32px 12px
        }

        .account-container {
            max-width: 1200px;
            margin: 0 auto;
            display: grid;
            grid-template-columns:280px 1fr;
            gap: 24px
        }

        @media (max-width: 992px) {
            .account-container {
                grid-template-columns:1fr
            }
        }

        /* ===== Sidebar ===== */
        .account-sidebar {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 18px;
            overflow: hidden
        }

        .account-sidebar .box-head {
            padding: 18px 18px 8px;
            border-bottom: 1px solid var(--border);
            background: #fff
        }

        .account-sidebar .box-head h4 {
            margin: 0;
            font-size: 15px;
            letter-spacing: .06em;
            color: var(--brand);
            text-transform: uppercase
        }

        .account-menu {
            display: flex;
            flex-direction: column;
            padding: 8px
        }

        .account-menu a {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            padding: 14px 12px;
            border-radius: 12px;
            color: var(--text);
            text-decoration: none;
            border: 1px solid transparent;
            background: transparent
        }

        .account-menu a .label {
            display: flex;
            align-items: center;
            gap: 10px;
            flex: 1;
            min-width: 0
        }

        .account-menu a .label span:last-child {
            color: var(--text);
            font-weight: 600
        }

        .account-menu a:hover {
            background: #fafafa;
            border-color: var(--border)
        }

        .account-menu a.is-active {
            background: var(--brand);
            color: #fff;
            border-color: transparent
        }

        .account-menu a.is-active .label span:last-child {
            color: #fff
        }

        .account-menu .hint {
            font-size: 12px;
            color: var(--muted)
        }

        /* ===== Content ===== */
        .account-content {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 18px;
            overflow: hidden
        }

        .account-content .head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 18px 20px;
            border-bottom: 1px solid var(--border);
            background: #fff
        }

        .account-content .head h3 {
            margin: 0;
            font-size: 20px;
            font-weight: 700;
            letter-spacing: .02em
        }

        /* ===== Address ===== */
        .address-wrap {
            padding: 18px 18px 22px
        }

        .address-grid {
            display: grid;
            grid-template-columns:repeat(2, minmax(0, 1fr));
            gap: 18px
        }

        @media (max-width: 900px) {
            .address-grid {
                grid-template-columns:1fr
            }
        }

        .address-card {
            position: relative;
            background: #fff;
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: 16px 16px 16px 48px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, .02)
        }

        .address-card .radio {
            position: absolute;
            left: 14px;
            top: 18px
        }

        .address-title {
            display: flex;
            align-items: center;
            gap: 10px;
            margin: 0 0 8px;
            font-size: 16px;
            font-weight: 800
        }

        .chip {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 12px;
            font-weight: 800;
            border-radius: 999px;
            background: var(--pill);
            padding: 6px 10px
        }

        .address-tools {
            position: absolute;
            right: 10px;
            top: 10px;
            display: flex;
            gap: 10px
        }

        .address-tools button, .address-tools a {
            width: 32px;
            height: 32px;
            border-radius: 10px;
            border: 1px solid var(--border);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: #fff;
            text-decoration: none;
            color: inherit;
            cursor: pointer
        }

        .address-tools button:hover, .address-tools a:hover {
            background: #fafafa
        }

        .address-body {
            font-size: 14px;
            line-height: 1.5;
            color: #333
        }

        .muted {
            color: var(--muted)
        }

        .kv {
            margin-top: 6px
        }

        .kv b {
            font-weight: 800
        }

        .is-default .address-title::after {
            content: "";
            display: inline-block;
            margin-left: 6px;
            width: 8px;
            height: 8px;
            border-radius: 999px;
            background: var(--success)
        }

        .is-default {
            border-color: rgba(56, 193, 114, .35)
        }

        .address-radio {
            width: 18px;
            height: 18px
        }

        /* ===== Form ===== */
        .address-form {
            margin-top: 22px;
            background: #fff;
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: 18px
        }

        .form-row {
            display: grid;
            grid-template-columns:repeat(2, minmax(0, 1fr));
            gap: 14px
        }

        @media (max-width: 768px) {
            .form-row {
                grid-template-columns:1fr
            }
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 6px
        }

        .form-group label {
            font-size: 13px;
            font-weight: 700
        }

        .form-group select {
            border: 1px solid var(--border);
            border-radius: 10px;
            padding: 10px 12px;
            background: #fff
        }

        .row-span-2 {
            grid-column: span 2
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 16px;
            border-radius: 12px;
            border: none;
            cursor: pointer;
            text-decoration: none;
            font-weight: 700
        }

        .btn-primary {
            background: var(--primary);
            color: #fff
        }

        /* ======== MODO COMPACTO + SIN FLECHA ======== */
        .address-form.address-form--compact {
            padding: 12px !important;
            border-radius: 12px !important;
        }

        .address-form.address-form--compact h4 {
            margin: 0 0 8px !important;
            font-size: 18px;
            font-weight: 800;
        }

        /* Menos separación vertical real */
        .address-form.address-form--compact .form-row {
            grid-template-columns:1fr !important;
            row-gap: 6px !important; /* ↓↓↓ aquí se acorta la distancia */
            column-gap: 6px !important;
        }

        .address-form.address-form--compact .form-group {


        }

        .address-form.address-form--compact .form-group + .form-group {
            margin-top: 2px !important;
        }

        /* Labels e inputs compactos */
        .address-form.address-form--compact .form-group label {
            font-size: 12px !important;
            line-height: 1.1 !important;
            margin: 0 !important;
        }

        .address-form.address-form--compact select,
        .address-form.address-form--compact input,
        .address-form.address-form--compact textarea {
            height: 32px !important;
            padding: 6px 10px !important;
            font-size: 13px !important;
            border: 1px solid #d5d9d9 !important;
            border-radius: 8px !important;
            background: #fff !important;
            outline: none !important;
        }

        .address-form.address-form--compact textarea {
            height: auto !important;
            min-height: 80px !important;
            resize: vertical !important;
        }

        /* Quitar flecha nativa y posibles iconos de tema */
        .address-form.address-form--compact select {
            -webkit-appearance: none !important;
            -moz-appearance: none !important;
            appearance: none !important;
            background-image: none !important;
            background-position: right 10px center !important;
            background-repeat: no-repeat !important;
            padding-right: 10px !important; /* sin chevron */
        }

        .address-form.address-form--compact select::-ms-expand {
            display: none !important;
        }

        /* Si el tema dibuja una flecha con ::after en el contenedor, la apagamos */
        .address-form.address-form--compact .form-group::after {
            content: none !important;
            display: none !important;
        }

        /* Anchura de los controles (tipo Amazon) */
        .address-form.address-form--compact .form-group select,
        .address-form.address-form--compact .form-group input {
            width: 70% !important;
            max-width: 380px !important;
            min-width: 260px !important;
            margin-left: 4px !important;
        }

        @media (max-width: 600px) {
            .address-form.address-form--compact .form-group select,
            .address-form.address-form--compact .form-group input {
                width: 100% !important;
                max-width: none !important;
                margin-left: 0 !important;
            }
        }

        /* Botón compacto */
        .address-form.address-form--compact .btn {
            height: 36px !important;
            padding: 0 12px !important;
            border-radius: 8px !important;
            font-size: 13px !important;
        }

        .address-form.address-form--compact .btn-primary {
            width: 100% !important;
        }

        /* Quitar márgenes propios que puedan crear “huecos” */
        #bloque-departamento, #bloque-municipio {
            margin: 0 !important;
        }


        .btn-back, .btn-save {
            font-weight: 600;
            border-radius: 5px;
            padding: 5px 16px;
            font-size: 14px;
            transition: all .25s ease;
            display: inline-block;
        }

        .btn-back {
            background: transparent;
            color: #fff;
            border: 1px solid #4d6fff;
        }

        .btn-back:hover {
            background: #4d6fff;
            color: #fff;
        }

        .btn-save {
            background: #4d6fff;
            border: 1px solid #4d6fff;
            color: #fff;
        }

        .btn-save:hover {
            background: #3a56d9;
            border-color: #3a56d9;
        }

        /* Contenedor de acciones (no usar .form-group aquí) */
        .form-actions {
            margin-top: 10px;
            display: flex;
            gap: 8px;
            justify-content: flex-start;
            align-items: center;
        }

        /* Botones pequeños */
        .btn-back, .btn-save {
            font-weight: 600;
            border-radius: 6px;
            padding: 5px 16px;
            font-size: 14px;
            transition: all .25s ease;
            display: inline-flex; /* evita 100% */
            width: auto !important; /* fuerza tamaño contenido */
        }

        .btn-back {
            background: transparent;
            color: #fff;
            border: 1px solid #4d6fff;
        }

        .btn-back:hover {
            background: #4d6fff;
            color: #fff;
        }

        .btn-save {
            background: #4d6fff;
            border: 1px solid #4d6fff;
            color: #fff;
        }

        /* Anula cualquier width:100% heredado (ej. .btn-primary) */
        .address-form.address-form--compact .btn-save {
            width: auto !important;
        }

        .btn-save:hover {
            background: #3a56d9;
            border-color: #3a56d9;
        }

        /* Botón Regresar en fondo claro */
        .btn-back {
            background: #fff; /* o transparent */
            color: #4d6fff !important; /* azul visible */
            border: 1px solid #4d6fff;
            text-decoration: none !important; /* sin subrayado */
            box-shadow: none !important;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        /* estados */
        .btn-back:hover,
        .btn-back:focus {
            background: #4d6fff;
            color: #fff !important;
            outline: 0;
        }

        .btn-back:visited {
            color: #4d6fff !important;
        }

        /* === Alinear selects e inputs a la izquierda === */
        .address-form.address-form--compact .form-group select,
        .address-form.address-form--compact .form-group input,
        .address-form.address-form--compact .form-group textarea {
            width: auto !important;             /* tamaño según contenido */
            max-width: 380px !important;        /* límite razonable */
            min-width: 200px !important;
            margin-left: 0 !important;          /* sin margen izquierdo */
            text-align: left !important;        /* texto alineado */
            display: inline-block !important;   /* evita ocupar todo el ancho */
        }

        /* === Eliminar grid/flex wrap y alinear a la izquierda === */
        .address-form.address-form--compact .form-row {
            display: block !important; /* elimina grid */
            margin: 0 !important;
            padding: 0 !important;
        }

        .address-form.address-form--compact .form-group {
            display: block !important;
            margin: 0 0 8px 0 !important; /* reducir separación vertical */
            padding: 0 !important;
        }

        /* === Selects alineados a la izquierda, sin wrap === */
        .address-form.address-form--compact .form-group select,
        .address-form.address-form--compact .form-group input,
        .address-form.address-form--compact .form-group textarea {
            width: 100% !important;
            max-width: 600px !important; /* ancho máximo razonable */
            min-width: 0 !important;
            margin: 0 !important;
            display: block !important;
            text-align: left !important;
        }

        .address-form.address-form--compact .form-group label {
            display: block !important;
            text-align: left !important;
            margin: 0 0 4px 0 !important; /* pequeño espacio entre label y select */
        }

        /* === Reducir altura entre form-groups === */
        .address-form.address-form--compact .form-group + .form-group {
            margin-top: 0 !important;
        }

        /* === Ajustar contenedor de acciones === */
        .form-actions {
            margin-top: 12px !important;
            padding-top: 8px !important;
        }

        /* === Select sin flecha / sin espacio a la derecha === */

        /* 1) Quitar la flecha nativa (Chrome, Safari, Firefox, Edge) */
        .address-form select{
            -webkit-appearance: none !important;
            -moz-appearance: none !important;
            appearance: none !important;
            background-image: none !important;  /* por si el tema pone un ícono con background */
        }
        .address-form select::-ms-expand{      /* IE/Edge heredado */
            display: none !important;
        }

        /* 2) Hacer que el select ocupe solo lo necesario (sin hueco a la derecha) */
        .address-form .form-group{ position: relative; }
        .address-form .form-group select{
            display: inline-block !important;
            width: auto !important;         /* evita llenar todo el contenedor */
            max-width: 100% !important;
            min-width: 220px;               /* ajusta a gusto */
            padding-right: 10px !important; /* ya sin espacio para chevron */
            text-overflow: ellipsis;
            white-space: nowrap;
            overflow: hidden;
        }

        /* 3) Si el tema dibuja una flecha/línea con pseudo-elementos, apágala */
        .address-form .form-group::after,
        .address-form .form-group::before{
            content: none !important;
            display: none !important;
        }

        /* 4) En móviles, que el select sí use todo el ancho */
        @media (max-width: 600px){
            .address-form .form-group select{
                width: 100% !important;
                min-width: 0 !important;
            }
        }

        /* === Neutralizar .select-wrap que rompe el layout === */
        .select-wrap {
            position: static !important;
            display: block !important;
            width: auto !important;
            max-width: 100% !important;
            background: none !important;
            border: none !important;
            box-shadow: none !important;
            padding: 0 !important;
            margin: 0 !important;
        }

        /* Si agrega una pseudo-flecha con ::after o ::before, se elimina */
        .select-wrap::after,
        .select-wrap::before {
            content: none !important;
            display: none !important;
        }

        /* Asegura que el select dentro del wrap quede limpio y sin flecha extra */
        .select-wrap select {
            -webkit-appearance: none !important;
            -moz-appearance: none !important;
            appearance: none !important;
            background-image: none !important;
            background: #fff !important;
            border: 1px solid #d5d9d9 !important;
            border-radius: 8px !important;
            padding: 6px 10px !important;
            width: 100% !important;
            height: 32px !important;
            font-size: 13px !important;
            color: #111 !important;
        }

        /* En caso que haya un ícono dentro del wrap (SVG o span) lo ocultamos */
        .select-wrap svg,
        .select-wrap i,
        .select-wrap span[class*="icon"] {
            display: none !important;
            visibility: hidden !important;
        }

        /* Margen arriba para Departamento y Municipio */
        #bloque-departamento,
        #bloque-municipio{
            margin-top:12px !important;     /* ajusta 8–16px a gusto */
        }

        /* Select más pequeño y compacto */
        .address-form .form-group select{
            height: 28px !important;         /* antes 32px */
            padding: 4px 8px !important;     /* menos padding */
            font-size: 12.5px !important;    /* un poco más pequeño */
            max-width: 340px !important;     /* más angosto en desktop */
            width: 70% !important;
        }

        @media (max-width: 600px){
            .address-form .form-group select{
                width: 100% !important;
                max-width: none !important;
            }
        }

        /* Apariencia de select deshabilitado */
        .address-form select:disabled{
            background: #f2f2f2 !important;
            color: #9a9a9a !important;
            cursor: not-allowed !important;
        }


        /* Select/input deshabilitado */
        .address-form select:disabled{
            background:#f2f2f2 !important; color:#9a9a9a !important; cursor:not-allowed !important;
        }
        /* Input ciudad compacto como los select */
        #bloque-ciudad input{
            height:28px !important; padding:4px 8px !important; font-size:12.5px !important;
            border:1px solid #d5d9d9 !important; border-radius:8px !important; width:100%;
            max-width:600px;
        }


        /* Apariencia deshabilitado */
        .address-form select:disabled,
        #bloque-ciudad input:disabled{
            background:#f2f2f2 !important; color:#9a9a9a !important; cursor:not-allowed !important;
        }


        /* Utilidad para ocultar SIEMPRE (más fuerte que tu display:block !important) */
        .hidden{ display:none !important; }

        /* por si quieres ser explícito */
        #bloque-departamento.hidden,
        #bloque-municipio.hidden,
        #bloque-ciudad.hidden{ display:none !important; }

        /* Fuerza el ocultamiento cuando el form-group tenga .hidden */
        .address-form.address-form--compact .form-group.hidden,
        #bloque-provincia.hidden,
        #bloque-postal.hidden {
            display: none !important;
        }


        .error-text{
            display:block;
            margin:4px 0 0 2px;
            font-size:11.5px;   /* más pequeño */
            line-height:1.2;
            text-align:left;    /* alineado a la izquierda */
            color:#d93025;      /* rojo */
            font-weight:600;
        }
        .has-error select,
        .has-error input,
        .has-error textarea{
            border-color:#d93025 !important;
            outline:0 !important;
        }


        .address-form.address-form--compact .form-group:has(#nombre-usuario){
            margin-top: 20px !important;
        }

        .address-form.address-form--compact .form-group:has(#direccion-usuario){
            margin-top: 20px !important;
        }

        .address-form.address-form--compact .form-group:has(#ciudad-usuario){
            margin-top: 20px !important;
        }

        .address-form.address-form--compact .form-group:has(#telefono-usuario){
            margin-top: 20px !important;
        }

        .address-form.address-form--compact .form-group:has(#postal-usuario){
            margin-top: 20px !important;
        }

        .address-form.address-form--compact .form-group:has(#provincia-usuario){
            margin-top: 20px !important;
        }

        .address-form.address-form--compact .form-group:has(#munici){
            margin-top: 20px !important;
        }

    </style>

    @php
        $active = 'addresses';
        $is = fn ($key) => $active === $key ? 'is-active' : '';
    @endphp

    <section class="account-wrap theme-light">
        <div class="account-container">

            {{-- ===== Sidebar ===== --}}
            <aside class="account-sidebar" aria-label="Sidebar">
                <div class="box-head"><h4>{{ __('meta.my_account') }}</h4></div>
                <nav class="account-menu" role="navigation">
                    <a class="{{ $is('orders') }}" href="{{ LaravelLocalization::getLocalizedURL(app()->getLocale(), route('user.orders', [], false)) }}"><span
                            class="label"><span>🧾</span><span>{{ __('meta.orders') }}</span></span><span
                            class="hint">→</span></a>
                    <a class="{{ $is('addresses') }}" href="{{ LaravelLocalization::getLocalizedURL(app()->getLocale(), route('user.address', [], false)) }}"><span
                            class="label"><span>📍</span><span>{{ __('meta.addresses') }}</span></span><span
                            class="hint">→</span></a>
                    <a class="{{ $is('profile') }}" href="#"><span
                            class="label"><span>⚙️</span><span>{{ __('meta.profile') }}</span></span><span class="hint">→</span></a>
                    <a href="#" id="logoutLink"><span
                            class="label"><span>🚪</span><span>{{ __('meta.logout') }}</span></span><span
                            class="hint">→</span></a>
                    <form id="logoutForm" method="POST" action="#" style="display:none">@csrf</form>
                </nav>
            </aside>

            {{-- ===== Content (Addresses) ===== --}}
            <div class="account-content" role="region" aria-label="{{ __('meta.addresses') }}">
                <div class="address-wrap">

                    {{-- Tu grid de direcciones (si aplica) --}}

                    {{-- ===== Formulario ===== --}}
                    <form id="address-form"
                          class="address-form address-form--compact"
                          method="POST"
                          action="#"
                          novalidate>
                        @csrf
                        <h4>{{ __('meta.add_new_address') }}</h4>

                        <div class="form-row">
                            {{-- País --}}
                            <div class="form-group">
                                <label for="pais">{{ __('meta.country') }}</label>
                                <select id="pais" name="pais">
                                    <option value="">{{ __('meta.select') }}</option>
                                    @foreach(($paises ?? []) as $p)
                                        <option value="{{ $p->id }}">{{ $p->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Departamento -->
                            <div class="form-group" id="bloque-departamento" style="display:none; margin-top: 15px">
                                <label for="departamento">{{ __('meta.department') }}</label>
                                <select id="departamento" name="departamento" disabled>
                                    <option value="" disabled selected>{{ __('meta.select') }}</option>
                                    @foreach(($departamentos ?? []) as $d)
                                        <option value="{{ $d->id }}" data-pais="{{ $d->id_paises }}">{{ $d->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Municipio -->
                            <div class="form-group" id="bloque-municipio" style="display:none; margin-top: 15px">
                                <label for="municipio">{{ __('meta.municipality') }}</label>
                                <select id="municipio" name="municipio" disabled>
                                    <option value="" disabled selected>{{ __('meta.select') }}</option>
                                    @foreach(($municipios ?? []) as $m)
                                        <option value="{{ $m->id }}" data-departamento="{{ $m->id_departamentos }}">{{ $m->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>



                            <!-- Nombre -->
                            <div class="form-group">
                                <label>{{ __('meta.name_and_lastname') }}</label>
                                <input id="nombre-usuario" type="text" maxlength="50" placeholder="{{ __('meta.name_and_lastname') }}">
                            </div>

                            <!-- Dirección -->
                            <div class="form-group">
                                <label>{{ __('meta.direction') }}</label>
                                <input id="direccion-usuario" type="text" maxlength="100" placeholder="{{ __('meta.input_direction') }}">
                            </div>


                            <div class="form-group">
                                <input id="direccionopcional-usuario" style="margin-top: 10px" type="text" maxlength="100" placeholder="{{ __('meta.input_directionv2') }}">
                            </div>

                            <!-- Ciudad -->
                            <div class="form-group" id="bloque-ciudad" style="display:none;">
                                <label>{{ __('meta.city')}}</label>
                                <input id="ciudad-usuario" type="text" maxlength="50" placeholder="{{ __('meta.city') }}" disabled>
                            </div>

                            <!-- Provincia -->
                            <div class="form-group hidden" id="bloque-provincia" style="margin-top:15px">
                                <label>{{ __('meta.state_province') }}</label>
                                <input id="provincia-usuario" type="text" maxlength="50"
                                       placeholder="{{ __('meta.state_province') }}" disabled>
                            </div>

                            <!-- Código postal -->
                            <div class="form-group hidden" id="bloque-postal" style="margin-top:15px">
                                <label>{{ __('meta.postal_code') }}</label>
                                <input id="postal-usuario" type="text" maxlength="20"
                                       placeholder="{{ __('meta.postal_code') }}" disabled>
                            </div>


                            <!-- numero de telefono -->
                            <div class="form-group" id="bloque-telefono" style="display:none; margin-top: 15px">
                                <label>{{ __('meta.phone_number')}}</label>
                                <input id="telefono-usuario" type="text" maxlength="20" placeholder="{{ __('meta.phone_number') }}">
                            </div>




                            <!-- Acciones -->
                            <div class="form-actions">
                                <a href="#" class="btn-back" id="btn-back">Regresar</a>
                                <button type="submit" class="btn-save">Guardar</button>
                            </div>


                        </div>
                    </form>

                </div>
            </div>
        </div>
    </section>

    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="{{ asset('js/toastr.min.js') }}"></script>
    <script src="{{ asset('js/axios.min.js') }}"></script>
    <script src="{{ asset('js/sweetalert2.all.min.js') }}"></script>
    <script src="{{ asset('js/alertaPersonalizada.js') }}"></script>


    <script>
        document.getElementById('btn-back')?.addEventListener('click', e => {
            e.preventDefault();
            history.back();
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Oculta por si el HTML no trae la clase (seguro extra)
            document.getElementById('bloque-provincia')?.classList.add('hidden');
            document.getElementById('bloque-postal')?.classList.add('hidden');

            // Aplica lógica según país seleccionado
            const selPais = document.getElementById('pais');
            if (selPais) aplicarEstadoPorPais(parseInt(selPais.value || 0, 10));
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const form = document.getElementById('address-form');
            if (!form) return;

            // ya agregado en el HTML, pero aseguramos:
            form.setAttribute('novalidate', '');

            // evita tooltips nativos para cualquier campo inválido
            form.addEventListener('invalid', (e) => {
                e.preventDefault();
            }, true);

            // limpia cualquier "customValidity" por si algún tema lo usa
            form.querySelectorAll('input, select, textarea').forEach(el => {
                el.setCustomValidity && el.setCustomValidity('');
            });
        });
    </script>


    <script>
        const selPais = document.getElementById('pais');
        const selDep  = document.getElementById('departamento');
        const selMun  = document.getElementById('municipio');

        const boxDep  = document.getElementById('bloque-departamento');
        const boxMun  = document.getElementById('bloque-municipio');
        const boxCity = document.getElementById('bloque-ciudad');
        const boxPostal = document.getElementById('bloque-postal');
        const boxProvincia = document.getElementById('bloque-provincia');

        const inpCity = document.getElementById('ciudad-usuario');
        const inpPostal = document.getElementById('postal-usuario');
        const inpProvincia = document.getElementById('provincia-usuario');

        // ---- Funciones base ----
        const resetSelect = (el) => {
            if (!el) return;
            el.selectedIndex = 0; // deja “Seleccionar”
        };

        const toggleBlock = (wrap, control, visible, required = false) => {
            if (!wrap || !control) return;
            wrap.classList.toggle('hidden', !visible);
            control.disabled = !visible;
            control.required = visible && required;
            if (!visible) {
                if (control.tagName === 'SELECT') resetSelect(control);
                else control.value = '';
            }
        };

        const filtrarDepartamentos = (idPais) => {
            [...selDep.options].forEach(opt => {
                if (opt.value === '') return opt.hidden = false;
                opt.hidden = parseInt(opt.dataset.pais) !== idPais;
            });
            resetSelect(selDep);
        };

        const filtrarMunicipios = (idDep) => {
            [...selMun.options].forEach(opt => {
                if (opt.value === '') return opt.hidden = false;
                opt.hidden = parseInt(opt.dataset.departamento) !== idDep;
            });
            resetSelect(selMun);
        };

        // ---- Lógica principal ----
        function aplicarEstadoPorPais(id) {
            const isES   = id === 1;   // El Salvador
            const isUS   = id === 2;   // Estados Unidos
            const hasAny = !!id && id !== 0;

            // --- limpiar campos de ubicación cada vez que cambia el país ---
            resetSelect(selDep);
            resetSelect(selMun);
            inpCity.value = '';
            inpProvincia.value = '';
            inpPostal.value = '';

            if (!hasAny) {
                // sin país -> oculta todo
                toggleBlock(boxDep, selDep, false);
                toggleBlock(boxMun, selMun, false);
                toggleBlock(boxCity, inpCity, false);
                toggleBlock(boxProvincia, inpProvincia, false);
                toggleBlock(boxPostal, inpPostal, false);
                return;
            }

            if (isES) {
                // ES: Departamento + Municipio requeridos. Sin ciudad/provincia/postal
                toggleBlock(boxDep, selDep, true,  true);
                toggleBlock(boxMun, selMun, true,  true);
                toggleBlock(boxCity, inpCity, false);
                toggleBlock(boxProvincia, inpProvincia, false);
                toggleBlock(boxPostal, inpPostal, false);

                filtrarDepartamentos(1);
                // Oculta municipios hasta seleccionar departamento
                [...selMun.options].forEach((opt, i) => opt.hidden = i !== 0);
                return;
            }

            if (isUS) {
                // US: Departamento (para estados) requerido, Ciudad y Postal requeridos.
                //     NO mostrar "Estado / Provincia / Región"
                toggleBlock(boxDep, selDep, true,  true);
                toggleBlock(boxMun, selMun, false);
                toggleBlock(boxCity, inpCity, true,  true);
                toggleBlock(boxPostal, inpPostal, true,  true);
                toggleBlock(boxProvincia, inpProvincia, false); // <-- oculto para US

                filtrarDepartamentos(2);
                return;
            }

            // Otros países: Ciudad + Provincia/Estado requeridos. Sin Dep/Mun ni Postal
            toggleBlock(boxDep, selDep, false);
            toggleBlock(boxMun, selMun, false);
            toggleBlock(boxCity, inpCity, true,  true);
            toggleBlock(boxProvincia, inpProvincia, true,  true);
            toggleBlock(boxPostal, inpPostal, false);
        }

        // Mantén tus listeners tal cual
        selPais?.addEventListener('change', function () {
            clearFormForCountryChange();
            aplicarEstadoPorPais(parseInt(this.value || 0, 10));
        });
        selDep?.addEventListener('change', function () {
            resetSelect(selMun);
            if (parseInt(selPais.value || 0, 10) === 1) {
                filtrarMunicipios(parseInt(this.value || 0, 10));
            } else {
                [...selMun.options].forEach((opt, i) => opt.hidden = i !== 0);
            }
        });

        document.addEventListener('DOMContentLoaded', () => {
            if (!selPais?.value) clearFormForCountryChange();
            aplicarEstadoPorPais(parseInt(selPais?.value || 0, 10));
        });

        function clearFormForCountryChange() {
            const form = document.getElementById('address-form');
            if (!form) return;

            form.querySelectorAll('input, select, textarea').forEach(el => {
                if (el.id === 'pais' || el.name === '_token') return;

                if (el.tagName === 'SELECT') {
                    el.selectedIndex = 0;       // vuelve a "Seleccionar"
                } else if (el.type === 'checkbox' || el.type === 'radio') {
                    el.checked = false;
                } else {
                    el.value = '';
                }
            });

            // Quitar estados de error y mensajes
            form.querySelectorAll('.form-group').forEach(fg => {
                fg.classList.remove('has-error');
                fg.querySelector('.error-text')?.remove();
            });
        }

    </script>



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

            // Form-groups
            const fgPais   = selPais?.closest('.form-group');
            const fgDep    = selDep?.closest('.form-group');
            const fgMun    = selMun?.closest('.form-group');
            const fgNombre = inpNombre?.closest('.form-group');
            const fgDir    = inpDireccion?.closest('.form-group');
            const fgTel    = inpTelefono?.closest('.form-group');

            // i18n
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

            // ==== Utilidades de UI (errores/visibilidad) ====
            function isVisibleControl(ctrl){
                if(!ctrl) return false;
                const group = ctrl.closest('.form-group') || ctrl;
                const style = window.getComputedStyle(group);
                return !group.classList.contains('hidden') && style.display !== 'none' && !ctrl.disabled;
            }

            function clearError(fg){
                if(!fg) return;
                fg.classList.remove('has-error');
                const old = fg.querySelector('.error-text');
                if(old) old.remove();
            }

            function setError(fg, msg){
                if(!fg) return;
                clearError(fg);
                fg.classList.add('has-error');
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

            // ==== Validación front ====
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
                // ES (1): municipio requerido
                if(paisId === 1 && selMun && isVisibleControl(selMun)){
                    if(selMun.value === '' || selMun.selectedIndex === 0){
                        setError(fgMun, i18n.municipalityRequired); ok = false;
                    }
                }
                // US (2): departamento requerido
                if(paisId === 2 && selDep && isVisibleControl(selDep)){
                    if(selDep.value === '' || selDep.selectedIndex === 0){
                        setError(fgDep, i18n.departmentRequired); ok = false;
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

            // ==== Axios submit ====
            // URL localizada (Blade):
            const SAVE_URL = "{{ LaravelLocalization::getLocalizedURL(app()->getLocale(), route('user.savenew.direction', [], false)) }}";
            // Redirección por defecto si el backend no envía 'redirect'
            const REDIRECT_FALLBACK = "{{ LaravelLocalization::getLocalizedURL(app()->getLocale(), route('user.index', [], false)) }}";
            // Token CSRF
            const CSRF = (document.querySelector('meta[name="csrf-token"]')?.getAttribute('content'))
                || (form?.querySelector('input[name=_token]')?.value) || '';

            function setSubmitting(state){
                if(!form) return;
                const btn = form.querySelector('[type="submit"], button.submit');
                if(btn){
                    if(state){
                        btn.dataset.prevText = btn.innerHTML;
                        btn.setAttribute('disabled', 'disabled');
                        btn.innerHTML = '<span class="spinner" aria-hidden="true"></span> ' + i18n.saving;
                    } else {
                        btn.removeAttribute('disabled');
                        if(btn.dataset.prevText) btn.innerHTML = btn.dataset.prevText;
                    }
                }
            }

            function getVal(el){
                return (el?.value ?? '').trim();
            }

            async function sendWithAxios(){
                if(typeof window.axios === 'undefined'){
                    alert(i18n.genericError);
                    return;
                }

                // Construye FormData desde el form…
                const fd = new FormData(form);

                // …y asegura/normaliza claves críticas (usa set para evitar duplicados)
                fd.set('pais',               selPais?.value || '');
                fd.set('departamento',       selDep?.value || '');
                fd.set('municipio',          selMun?.value || '');
                fd.set('nombre',             getVal(inpNombre));
                fd.set('direccion',          getVal(inpDireccion));
                fd.set('direccion_opcional', getVal(inpDirOpt));
                fd.set('telefono',           getVal(inpTelefono));
                fd.set('ciudad',             getVal(inpCiudad));    // podrían estar disabled
                fd.set('provincia',          getVal(inpProvincia)); // idem
                fd.set('postal',             getVal(inpPostal));    // idem

                try {
                    setSubmitting(true);

                    const res = await axios.post(
                        SAVE_URL,
                        fd,
                        {
                            headers: {
                                'X-CSRF-TOKEN': CSRF,
                                'Accept': 'application/json'
                                // Nota: NO fijes Content-Type; el navegador agrega boundary de FormData
                            }
                        }
                    );

                    const data = res?.data || {};

                    if (data.success === 1) {
                        // Limpia errores visuales
                        [fgPais, fgDep, fgMun, fgNombre, fgDir, fgTel].forEach(clearError);


                        // Redirige (usa data.redirect si viene, si no fallback)
                        const url = data.redirect;
                        window.location.assign(url);

                    }else{
                        // Fallo general
                        toastr.error(i18n.genericError);
                    }
                } catch (err) {
                    toastr.error(i18n.genericError);
                } finally {
                    setSubmitting(false);
                }
            }

            // Intercepta el submit y envía con Axios
            form?.addEventListener('submit', function (e) {
                e.preventDefault();
                if(!validate()){
                    const firstErr = form.querySelector('.has-error select, .has-error input, .has-error textarea');
                    firstErr?.focus();
                    return;
                }
                sendWithAxios();
            });
        })();
    </script>







    {{-- Superior (Newsletter) block --}}
    @include('frontend.partials.superior')
@endsection
