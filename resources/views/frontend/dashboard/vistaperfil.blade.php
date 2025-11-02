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


        .address-form.address-form--compact .form-group:has(#nombre){
            margin-top: 20px !important;
        }

        .address-form.address-form--compact .form-group:has(#direccion){
            margin-top: 20px !important;
        }

        .address-form.address-form--compact .form-group:has(#ciudad){
            margin-top: 20px !important;
        }

        .address-form.address-form--compact .form-group:has(#telefono){
            margin-top: 20px !important;
        }

        .address-form.address-form--compact .form-group:has(#codigo_postal){
            margin-top: 20px !important;
        }

        .address-form.address-form--compact .form-group:has(#estado){
            margin-top: 20px !important;
        }
        .address-form.address-form--compact .form-group:has(#textoBilling){
            margin-top: 25px !important;
        }

    </style>

    @php
        $active = 'profile';
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
                    <a class="{{ $is('profile') }}" href="{{ LaravelLocalization::getLocalizedURL(app()->getLocale(), route('user.view.perfil', [], false)) }}"><span
                            class="label"><span>⚙️</span><span>{{ __('meta.profile') }}</span></span><span class="hint">→</span></a>

                    <a href="#" id="logoutLinkPerfil">
                        <span class="label"><span>🚪</span><span>{{ __('meta.logout') }}</span></span><span class="hint">→</span>
                    </a>
                    <form id="logoutForm" method="POST" action="{{ route('user.logout') }}" style="display:none;">
                        @csrf
                    </form>
                </nav>
            </aside>

            {{-- ===== Content ===== --}}
            <div class="account-content" role="region">
                <div class="address-wrap">
                    <form id="facturacion-form" class="address-form address-form--compact">
                        @csrf
                        <h4 style="text-align: left!important; font-weight: 600">{{ __('meta.profile') }}</h4>

                        {{-- ===== DATOS DEL USUARIO ===== --}}
                        <div class="form-group">
                            <label>{{ __('meta.email_address') }}</label>
                            <input type="email" value="{{ $infouser->email ?? '' }}" disabled
                                   style="background-color:#f0f0f0; color:#6c757d; cursor:not-allowed;">
                        </div>

                        <br>
                        <h4 style="text-align: left!important; font-weight: 600" id="textoBilling">{{ __('meta.billing_information') }}</h4>

                        {{-- ===== DATOS DE FACTURACIÓN ===== --}}
                        <div class="form-group">
                            <label>{{ __('meta.country') }}</label>
                            <div class="select-wrap">
                                <select id="pais" name="pais">
                                    <option value="">{{ __('meta.select') }}</option>
                                    @foreach($paises as $p)
                                        <option value="{{ $p->id }}" {{ (int)($arrayDireccionFactura->id_paises ?? 0) === (int)$p->id ? 'selected' : '' }}>
                                            {{ $p->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>{{ __('meta.name_and_lastname') }}</label>
                            <input id="nombre" name="nombre" type="text" maxlength="50"
                                   value="{{ $arrayDireccionFactura->nombre ?? '' }}"
                                   placeholder="{{ __('meta.name_and_lastname') }}">
                        </div>

                        <div class="form-group">
                            <label>{{ __('meta.direction') }}</label>
                            <input id="direccion" name="direccion" type="text" maxlength="100"
                                   value="{{ $arrayDireccionFactura->direccion ?? '' }}"
                                   placeholder="{{ __('meta.input_direction') }}">
                        </div>

                        <div class="form-group" id="bloque-ciudad">
                            <label>{{ __('meta.city') }}</label>
                            <input id="ciudad" name="ciudad" type="text" maxlength="50"
                                   value="{{ $arrayDireccionFactura->ciudad ?? '' }}"
                                   placeholder="{{ __('meta.city') }}">
                        </div>

                        <div class="form-group" id="bloque-estado">
                            <label>{{ __('meta.state_province') }}</label>
                            <input id="estado" name="estado" type="text" maxlength="50"
                                   value="{{ $arrayDireccionFactura->estado ?? '' }}"
                                   placeholder="{{ __('meta.state_province') }}">
                        </div>

                        <div class="form-group">
                            <label>{{ __('meta.postal_code') }}</label>
                            <input id="codigo_postal" name="codigo_postal" type="text" maxlength="20"
                                   value="{{ $arrayDireccionFactura->codigo_postal ?? '' }}"
                                   placeholder="{{ __('meta.postal_code') }}">
                        </div>

                        <div class="form-group">
                            <label>{{ __('meta.phone_number') }}</label>
                            <input id="telefono" name="telefono" type="text" maxlength="20"
                                   value="{{ $arrayDireccionFactura->telefono ?? '' }}"
                                   placeholder="{{ __('meta.phone_number') }}">
                        </div>

                        {{-- Botones --}}
                        <div class="form-actions">
                            <button type="submit" class="btn-save">{{ __('meta.save_changes') }}</button>
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
        document.addEventListener('DOMContentLoaded', function () {
            const logoutLink = document.getElementById('logoutLinkPerfil');
            const logoutForm = document.getElementById('logoutForm');
            if (logoutLink && logoutForm) {
                logoutLink.addEventListener('click', function (e) {
                    e.preventDefault();
                    logoutForm.submit();
                });
            }
        });
    </script>

    <script>
        const SAVE_URL = "{{ LaravelLocalization::getLocalizedURL(app()->getLocale(), route('user.update.perfil', [], false)) }}";
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Oculta por si el HTML no trae la clase (seguro extra)
            document.getElementById('bloque-provincia')?.classList.add('hidden');
            document.getElementById('bloque-postal')?.classList.add('hidden');
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
        // ====== Selectores ======
        const selPais   = document.getElementById('pais');
        const inpCity   = document.getElementById('ciudad');
        const inpEstado = document.getElementById('estado');
        const inpPostal = document.getElementById('codigo_postal');

        // ====== Inicialización ======
        document.addEventListener('DOMContentLoaded', () => {
            // No ocultamos nada ni aplicamos lógica por país
            // Solo aseguramos que los inputs estén activos
            [inpCity, inpEstado, inpPostal].forEach(inp => {
                if (!inp) return;
                inp.disabled = false;
                inp.required = false;
            });
        });
    </script>

    <script>
        (() => {
            const form = document.getElementById('facturacion-form');
            if (!form) return;


            // CSRF desde meta o input hidden
            const CSRF = (document.querySelector('meta[name="csrf-token"]')?.getAttribute('content'))
                || (form.querySelector('input[name="_token"]')?.value) || '';

            // Helpers UI
            function setSubmitting(state){
                const btn = form.querySelector('.btn-save');
                if (!btn) return;
                if (state) {
                    btn.dataset.prevText = btn.innerHTML;
                    btn.disabled = true;
                    btn.innerHTML = '{{ __("meta.saving")}}';
                } else {
                    btn.disabled = false;
                    if (btn.dataset.prevText) btn.innerHTML = btn.dataset.prevText;
                }
            }

            function clearErrors(){
                form.querySelectorAll('.error-text').forEach(n => n.remove());
                form.querySelectorAll('.has-error').forEach(n => n.classList.remove('has-error'));
            }

            function setFieldError(fieldName, msg){
                const el = form.querySelector(`[name="${fieldName}"]`) || document.getElementById(fieldName);
                const group = el?.closest('.form-group');
                if (!group) return;
                group.classList.add('has-error');
                const span = document.createElement('span');
                span.className = 'error-text';
                span.textContent = msg;
                group.appendChild(span);
            }

            // Validación mínima (solo que no vayan vacíos)
            function validate(){
                clearErrors();
                let ok = true;
               /* const requiredIds = ['pais','nombre','direccion','telefono']; // agrega/quita si quieres obligatorios

                requiredIds.forEach(id => {
                    const el = form.querySelector(`#${id}[name="${id}"]`) || form.querySelector(`[name="${id}"]`);
                    if (!el) return;
                    if (!String(el.value || '').trim()){
                        ok = false;
                        setFieldError(id, '{{ __("validation.required") ?? "Campo requerido" }}');
                    }
                });*/

                return ok;
            }

            async function submitForm(){
                if (typeof window.axios === 'undefined'){
                    return;
                }

                // Construir payload
                const fd = new FormData();
                fd.set('pais',          form.pais?.value || '');
                fd.set('nombre',        form.nombre?.value?.trim() || '');
                fd.set('direccion',     form.direccion?.value?.trim() || '');
                fd.set('ciudad',        form.ciudad?.value?.trim() || '');
                fd.set('estado',        form.estado?.value?.trim() || '');
                fd.set('codigo_postal', form.codigo_postal?.value?.trim() || '');
                fd.set('telefono',      form.telefono?.value?.trim() || '');

                try{
                    setSubmitting(true);

                    const res = await axios.post(SAVE_URL, fd, {
                        headers: {
                            'X-CSRF-TOKEN': CSRF,
                            'Accept': 'application/json'
                            // No pongas Content-Type manual: el navegador agrega boundary de FormData
                        }
                    });

                    const data = res?.data || {};
                    if (data.success === 1) {
                        if (window.toastr) toastr.success('{{ __("meta.saved_successfully") }}');
                    } else {
                        // Mensaje genérico o específico
                        const msg = '{{ __("meta.error_v1") }}';
                        if (window.toastr) toastr.error(msg);
                    }

                } catch (err){
                    // Errores de validación Laravel (422)
                    const msg = '{{ __("meta.error_v1") }}';
                    if (window.toastr) toastr.error(msg);

                } finally {
                    setSubmitting(false);
                }
            }

            form.addEventListener('submit', (e) => {
                e.preventDefault();
                if (!validate()){
                    const first = form.querySelector('.has-error input, .has-error select, .has-error textarea');
                    first?.focus();
                    return;
                }
                submitForm();
            });
        })();
    </script>


    {{-- Superior (Newsletter) block --}}
    @include('frontend.partials.superior')
@endsection
