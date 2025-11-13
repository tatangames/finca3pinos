@extends('frontend.layouts.app')
@section('title', __('meta.title'))

@section('content')

    <style>
        html, body {
            overflow-x: hidden;
        }

        *, *::before, *::after {
            box-sizing: border-box;
        }

        /* Contenedor pasos */
        .checkout-shell {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 28px;
        }

        @media (max-width: 992px) {
            .checkout-shell {
                padding: 0 16px;
            }
        }

        .steps-bar {
            background: #f1f6ff;
            border-radius: 14px;
            padding: 14px 22px;
            border: 1px solid rgba(42, 88, 255, .05);
            display: flex;
            justify-content: center;
        }

        .checkout-steps {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 40px;
            max-width: 700px;
            width: 100%;
            margin: 0;
        }

        .step {
            display: flex;
            align-items: center;
            gap: 10px;
            color: #1f2937;
            font-weight: 500;
            background: transparent;
            border: 0;
            padding: 0;
            user-select: none;
            cursor: default;
        }

        .step-dot {
            width: 32px;
            height: 32px;
            border-radius: 999px;
            display: grid;
            place-items: center;
            font-weight: 700;
            font-size: .95rem;
            background: #111;
            color: #fff;
            box-shadow: 0 1px 0 rgba(0, 0, 0, .08) inset;
        }

        .step.active .step-dot,
        .step.done .step-dot {
            background: #2a58ff;
            color: #fff;
        }

        .step.active span:last-child,
        .step.done span:last-child {
            color: #2a58ff;
        }

        .step.done, .step.active {
            cursor: pointer;
        }

        .step:hover .step-dot {
            filter: brightness(.96);
        }

        @media (max-width: 768px) {
            .checkout-steps {
                gap: 20px;
                max-width: 100%;
            }

            .step-dot {
                width: 28px;
                height: 28px;
                font-size: .85rem;
            }

            .step span:last-child {
                font-size: .9rem;
            }
        }

        /* Tabs */
        .tab-pane {
            display: none;
        }

        .tab-pane.active {
            display: block;
        }

        /* Layout general: una columna, compacto */
        .checkout-wrap {
            max-width: 780px;
            margin: 24px auto 40px;
            padding: 0 16px;
        }

        @media (max-width: 992px) {
            .checkout-wrap {
                padding: 0 16px;
                margin-top: 18px;
                margin-bottom: 40px;
            }
        }

        .card,
        .card-ship,
        .addr-card,
        .steps-bar {
            max-width: 100%;
        }

        .card {
            background: #fff;
            border: 1px solid rgba(0, 0, 0, .07);
            border-radius: 12px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, .04);
            overflow: hidden;
            margin-bottom: 14px;
        }

        .card-h {
            padding: 10px 14px;
            border-bottom: 1px solid rgba(0, 0, 0, .05);
            font-weight: 600;
            font-size: 1rem;
        }

        .card-b {
            padding: 20px;
        }

        .card-ship {
            background: #fff;
            border: 1px solid rgba(0, 0, 0, .08);
            border-radius: 14px;
            margin: 12px 0;
            box-shadow: 0 1px 8px rgba(0, 0, 0, .04);
        }

        .card-ship .h {
            padding: 12px 16px;
            border-bottom: 1px solid rgba(0, 0, 0, .06);
            font-weight: 600;
        }

        .card-ship .b {
            padding: 16px 16px;
        }

        .row2 {
            display: grid;
            grid-template-columns:1fr 1fr;
            gap: 12px;
        }

        @media (max-width: 576px) {
            .row2 {
                grid-template-columns:1fr;
            }
        }

        .control {
            display: block;
            width: 100%;
            padding: 8px 10px;
            border: 1px solid rgba(0, 0, 0, .15);
            border-radius: 10px;
            background: #fff;
            font-size: .95rem;
        }

        .btn {
            display: inline-block;
            border: 0;
            border-radius: 999px;
            padding: 10px 16px;
            cursor: pointer;
            font-size: .95rem;
        }

        .btn-primary {
            background: #2a58ff;
            color: #fff;
        }

        .btn-light {
            background: #f3f4f6;
            color: #111;
        }

        .btn[disabled] {
            opacity: .6;
            cursor: not-allowed;
        }

        .muted {
            color: #6b7280;
            font-size: 13px;
        }

        /* Address preview */
        .addr-card {
            border: 1px solid rgba(0, 0, 0, .1);
            border-radius: 10px;
            padding: 10px 12px;
            background: #fff;
        }

        .addr-line {
            color: #374151;
            font-size: .95rem;
        }

        .addr-empty {
            padding: 8px 0;
        }

        /* Resumen */
        .sum-line {
            display: flex;
            justify-content: space-between;
            margin: 2px 0;
            font-size: 15px;
        }

        .sum-total {
            font-weight: 800;
            font-size: 1rem;
            margin-top: 6px;
        }

        .card-b h4 {
            font-size: 1.1rem;
            margin-bottom: 10px;
        }

        .actions {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-top: 14px;
        }

        img, iframe {
            max-width: 100%;
            height: auto;
            display: block;
        }


        .control[readonly],
        .control[disabled] {
            background-color: #f3f3f3 !important;
            color: #555 !important;
            cursor: not-allowed;
        }

        #btnToStep2 {
            border-radius: 12px !important;
        }


        #btnBack1 {
            border-radius: 12px !important;
        }

        #btnToStep3 {
            border-radius: 12px !important;
        }

        #btnBack2 {
            border-radius: 12px !important;
        }

        #btnPayPagadito {
            border-radius: 12px !important;
        }


    </style>

    {{-- PASOS --}}
    <div class="checkout-shell">
        <div class="steps-bar">
            <div class="checkout-steps" id="stepsHead">
                <div class="step active" data-step="1">
                    <span class="step-dot">1</span>
                    <span>{{ __('meta.shipping') }}</span>
                </div>
                <div class="step" data-step="2">
                    <span class="step-dot">2</span>
                    <span>{{ __('meta.billing') }}</span>
                </div>
                <div class="step" data-step="3">
                    <span class="step-dot">3</span>
                    <span>{{ __('meta.payment') }}</span>
                </div>
            </div>
        </div>
    </div>

    @if(session('error'))
        <div class="alert alert-danger" style="margin-bottom:15px">
            {{ session('error') }}
        </div>
    @endif

    <div class="checkout-wrap">

        {{-- ========= Paso 1: ENVÍO ========= --}}
        <div class="tab-pane active" id="tab1">
            <div class="card-ship">
                <div class="h">{{ __('meta.shipping_address') }}</div>
                <div class="b">
                    @if($addresses->isEmpty())
                        <p style="margin-bottom:10px">
                            {{ __('meta.dont_have_address') }}
                        </p>
                        <a class="btn btn-primary"
                           href="{{ route('user.address', ['back' => 'checkout']) }}">
                            {{ __('meta.add_address') }}
                        </a>
                    @else
                        <label for="shipping_address" style="display:block;margin-bottom:6px">
                            {{ __('meta.select_an_address') }}
                        </label>
                        <select id="shipping_address" class="control">
                            @foreach($addresses as $a)
                                @php
                                    $label = trim(($a->pais_nombre ?? '—') . ' — ' . ($a->direccion ?? ''));
                                    $label = \Illuminate\Support\Str::limit($label, 60);
                                @endphp
                                <option value="{{ $a->id }}"
                                    {{ (int)$selectedAddressId === (int)$a->id ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>

                        @php
                            $addrSelected = $addresses->firstWhere('id', $selectedAddressId);
                        @endphp

                        <div id="addrPreview" style="margin-top:10px">
                            @if($addrSelected)
                                <div class="addr-card">
                                    @if($addrSelected->nombre)
                                        <div class="addr-line">
                                            <strong>{{ __('meta.contact_v5') }}:</strong> {{ $addrSelected->nombre }}
                                        </div>
                                    @endif
                                    @if($addrSelected->direccion)
                                        <div class="addr-line">
                                            <strong>{{ __('meta.direction') }}:</strong> {{ $addrSelected->direccion }}
                                        </div>
                                    @endif
                                    @if($addrSelected->pais_nombre)
                                        <div class="addr-line">
                                            <strong>{{ __('meta.country') }}:</strong> {{ $addrSelected->pais_nombre }}
                                        </div>
                                    @endif
                                    @if($addrSelected->depto_nombre)
                                        <div class="addr-line">
                                            <strong>{{ __('meta.department') }}
                                                :</strong> {{ $addrSelected->depto_nombre }}
                                        </div>
                                    @endif
                                    @if($addrSelected->muni_nombre)
                                        <div class="addr-line">
                                            <strong>{{ __('meta.municipality') }}
                                                :</strong> {{ $addrSelected->muni_nombre }}
                                        </div>
                                    @endif
                                    @if($addrSelected->ciudad)
                                        <div class="addr-line">
                                            <strong>{{ __('meta.city') }}:</strong> {{ $addrSelected->ciudad }}
                                        </div>
                                    @endif
                                    @if($addrSelected->estado)
                                        <div class="addr-line">
                                            <strong>{{ __('meta.state_province') }}
                                                :</strong> {{ $addrSelected->estado }}
                                        </div>
                                    @endif
                                    @if($addrSelected->zipcode)
                                        <div class="addr-line">
                                            <strong>{{ __('meta.postal_code') }}:</strong> {{ $addrSelected->zipcode }}
                                        </div>
                                    @endif
                                    @if($addrSelected->telefono)
                                        <div class="addr-line">
                                            <strong>{{ __('meta.phone') }}:</strong> {{ $addrSelected->telefono }}
                                        </div>
                                    @endif
                                </div>
                            @else
                                <div class="addr-empty muted">{{ __('meta.select_an_address_to') }}</div>
                            @endif
                        </div>

                        <input type="hidden" name="envio_id" id="envio_id" value="{{ $selectedAddressId }}">

                        <div style="display:flex;justify-content:flex-end;margin-top:12px">
                            <button class="btn btn-primary" id="btnToStep2">
                                {{ __('meta.next') }}
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- ========= Paso 2: FACTURACIÓN ========= --}}
        <div class="tab-pane" id="tab2">
            <div class="card">
                <div class="card-h" style="display:flex;justify-content:space-between;align-items:center;gap:10px;">
                    <span>{{ __('meta.billing_information') }}</span>

                    {{-- Botón para editar en el perfil --}}
                    <a href="{{ route('user.view.perfil') }}" class="btn btn-sm btn-outline-primary">
                        {{ __('meta.edit_billing_in_profile') }}
                    </a>
                </div>

                <div class="card-b">


                    <div class="row2">
                        <div>
                            <label>{{ __('meta.contact_v5') }}</label>
                            <input id="bill_nombre" class="control"
                                   value="{{ $billing->nombre ?? '' }}"
                                   placeholder=""
                                   disabled
                                   readonly>
                        </div>
                        <div>
                            <label>{{ __('meta.phone') }}</label>
                            <input id="bill_tel" class="control"
                                   value="{{ $billing->telefono ?? '' }}"
                                   placeholder=""
                                   disabled
                                   readonly>
                        </div>
                    </div>

                    <label style="margin-top:10px">{{ __('meta.country') }}</label>
                    <select id="bill_pais" class="control" disabled>
                        <option value="">{{ __('meta.select') }}</option>
                        @foreach($paises as $p)
                            <option value="{{ $p->id }}"
                                {{ (int)($billing_country_id ?? 0) === (int)$p->id ? 'selected' : '' }}>
                                {{ $p->nombre }}
                            </option>
                        @endforeach
                    </select>

                    <label style="margin-top:10px">{{ __('meta.addresses') }}</label>
                    <input id="bill_dir" class="control"
                           value="{{ $billing->direccion ?? '' }}"
                           placeholder=""
                           readonly>

                    <div class="row2" style="margin-top:10px">
                        <div>
                            <label>{{ __('meta.city') }}</label>
                            <input id="bill_ciudad" class="control"
                                   value="{{ $billing->ciudad ?? '' }}"
                                   readonly>
                        </div>
                        <div>
                            <label>{{ __('meta.state_province') }}</label>
                            <input id="bill_estado" class="control"
                                   value="{{ $billing->estado ?? '' }}"
                                   readonly>
                        </div>
                    </div>

                    <div class="row2" style="margin-top:10px">
                        <div>
                            <label>{{ __('meta.postal_code') }}</label>
                            <input id="bill_zip" class="control"
                                   value="{{ $billing->codigo_postal ?? '' }}"
                                   readonly>
                        </div>

                        <div style="
                    display:flex;
                    justify-content:center;
                    align-items:center;
                    gap:20px;
                    margin-top:30px;
                    margin-bottom:25px;
                ">
                            <button class="btn btn-light" id="btnBack1">
                                {{ __('meta.back') }}
                            </button>
                            <button class="btn btn-primary" id="btnToStep3">
                                {{ __('meta.next') }}
                            </button>
                        </div>
                    </div>

                    {{-- Payload oculto que usas para enviar al backend / Pagadito --}}
                    <input type="hidden" id="bill_payload">
                </div>
            </div>
        </div>


        {{-- ========= Paso 3: PAGO ========= --}}
        <div class="tab-pane" id="tab3">
            <div class="card">
                <div class="card-h">{{ __('meta.secure_payment_with_pagadito') }}</div>
                <div class="card-b">

                    <h4>{{ __('meta.your_order_summary') }}</h4>
                    <div class="sum-line">
                        <span>{{ __('meta.subtotal') }}:</span>
                        <span id="sum-subtotal">
                            {{ $sumSubtotalFormat ?? '$' . number_format($subtotal, 2) }}
                        </span>
                    </div>
                    <div class="sum-line">
                        <span>{{ __('meta.shipping') }}:</span>
                        <span id="sum-shipping">
                            {{ $sumShippingFormat ?? '$' . number_format($shipping, 2) }}
                        </span>
                    </div>
                    <div class="sum-line sum-total">
                        <span>{{ __('meta.total_to_pay') }}:</span>
                        <span id="sum-total">
                            {{ $sumTotalFormat ?? '$' . number_format($total, 2) }}
                        </span>
                    </div>

                    <p class="muted" style="margin-top:8px;">
                        {{ __('meta.redirect_notice') }}
                    </p>

                    <div class="actions">
                        <button class="btn btn-light" type="button" id="btnBack2">
                            {{ __('meta.back') }}
                        </button>
                        <button class="btn btn-primary" type="button" id="btnPayPagadito">
                            {{ __('meta.pay_with_pagadito') }}
                        </button>
                    </div>

                    <div style="
    display:flex;
    justify-content:center;
    align-items:center;
    margin-top:20px;
">
                        <img src="{{ asset('images/pasarelapagadito.png') }}"
                             alt="Pasarela Pagadito"
                             style="max-width:220px; opacity:0.9;">
                    </div>

                    <div id="pay-msg"
                         class="muted"
                         style="margin-top:8px;display:none">
                        {{ __('meta.processing_payment') }}
                    </div>
                </div>
            </div>

            {{-- FORMULARIO OCULTO PARA ENVIAR A PAGADITO (POST NORMAL) --}}
            <form id="formPagadito"
                  method="POST"
                  action="{{ route('checkout.pagadito.init') }}"
                  style="display:none;">
                @csrf
                <input type="hidden" name="envio_id" id="pg_envio_id">
                <input type="hidden" name="billing" id="pg_billing">
            </form>
        </div>


    </div>

    {{-- Librerías --}}
    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="{{ asset('js/toastr.min.js') }}"></script>
    <script src="{{ asset('js/sweetalert2.all.min.js') }}"></script>
    {{-- axios puede quedarse si lo usas en otras partes, pero ya no se usa para Pagadito --}}
    <script src="{{ asset('js/axios.min.js') }}"></script>

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <script>
        // ===== MAPAS Y BASES =====
        const PAISES_MAP = {!! json_encode(
        $paises->keyBy('id')->map(fn($p)=>$p->nombre)->toArray(),
        JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES
    ) !!};

        const HAS_ADDRESSES = {{ $addresses->isNotEmpty() ? 'true' : 'false' }};

        const ADDRESS_MAP = {!! json_encode(
        $addresses->keyBy('id')->map(function($a){
            return [
                'id'            => (int) $a->id,
                'id_paises'     => (int) $a->id_paises,   // <-- IMPORTANTE
                'nombre'        => $a->nombre,
                'direccion'     => $a->direccion,
                'pais'          => $a->pais_nombre ?? null,
                'departamento'  => $a->depto_nombre ?? null,
                'municipio'     => $a->muni_nombre ?? null,
                'ciudad'        => $a->ciudad,
                'estado'        => $a->estado,
                'zipcode'       => $a->zipcode,
                'telefono'      => $a->telefono,
                'predeterminado'=> (int) $a->predeterminado,
                'precio_envio'  => (float) ($a->precio_envio ?? 0),
            ];
        })->toArray(),
        JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES
    ) !!};

        const BASE_SUBTOTAL = {{ (float) $subtotal }};

        // ===== I18N BÁSICO =====
        const i18n = {
            errorDesconocidoMessage: "{{ __('meta.unknown_error') }}",
            seNecesitaDireccionMessage: "{{ __('meta.select_shipping_before_continue') }}",
            noHayFormularioPagoMessage: "{{ __('meta.payment_form_not_found') }}",
            quoteNeededTitle: "{{ __('meta.checkout_limited_notice') }}",
            quoteNeededHtml: "{{ __('meta.need_to_quote_shipping') }}",
            quoteNow: "{{ __('meta.quote_now') }}",
            cancel: "{{ __('meta.cancel') }}",
        };

        // ===== CONFIGURACIÓN DE CONTROL DE PAÍS =====
        const ALLOWED_COUNTRIES = [1, 2, 3]; // 1: El Salvador, 2: Estados Unidos, 3: Korea
        const QUOTE_URL = "{{ route('user.quote') }}"; // <-- AJUSTA ESTA RUTA

        // ¿La dirección seleccionada pertenece a país permitido?
        function isAllowedAddress(addrId) {
            const a = ADDRESS_MAP[addrId];
            if (!a) return false;
            return ALLOWED_COUNTRIES.includes(Number(a.id_paises));
        }

        // Muestra SweetAlert para cotización y, si confirman, redirige
        function showQuoteSwal(addrId) {
            return Swal.fire({
                icon: 'info',
                title: i18n.quoteNeededHtml,
                html: i18n.quoteNeededTitle,
                confirmButtonText: i18n.quoteNow,
                cancelButtonText: i18n.cancel,
                showCancelButton: true,
                reverseButtons: true,
            }).then(res => {
                if (res.isConfirmed) {
                    const sep = QUOTE_URL.includes('?') ? '&' : '?';
                    window.location.href = QUOTE_URL + sep + 'address_id=' + encodeURIComponent(addrId);
                }
                return res;
            });
        }

        (function () {
            const stepsHead = document.getElementById('stepsHead');
            const panes = {
                1: document.getElementById('tab1'),
                2: document.getElementById('tab2'),
                3: document.getElementById('tab3')
            };

            function go(step) {
                [...stepsHead.querySelectorAll('.step')].forEach((s, i) => {
                    const idx = i + 1;
                    s.classList.toggle('active', idx === step);
                    s.classList.toggle('done', idx < step);
                });
                Object.values(panes).forEach(p => p.classList.remove('active'));
                panes[step].classList.add('active');
                window.scrollTo({top: stepsHead.offsetTop - 8, behavior: 'smooth'});
            }
            window.go = go;

            function money(n) {
                return '$' + Number(n || 0).toFixed(2);
            }

            // ===== Actualizar resumen según dirección =====
            function updateSummaryByAddress(id) {
                const a = ADDRESS_MAP[id];
                if (!a) return;

                const shipping = Number(a.precio_envio || 0);
                const subtotal = BASE_SUBTOTAL;
                const total = subtotal + shipping;

                const elSub = document.getElementById('sum-subtotal');
                const elShip = document.getElementById('sum-shipping');
                const elTot = document.getElementById('sum-total');

                if (elSub) elSub.innerText = money(subtotal);
                if (elShip) elShip.innerText = money(shipping);
                if (elTot) elTot.innerText = money(total);
            }

            // ===== Vista previa dirección =====
            function renderAddrPreview(id) {
                const box = document.getElementById('addrPreview');
                const a = ADDRESS_MAP[id];
                if (!box) return;

                if (!a) {
                    box.innerHTML = `<div class="addr-empty muted">
                    {{ __('meta.select_an_address_to') }}
                    </div>`;
                    return;
                }

                const lines = [];
                if (a.nombre) lines.push(`<div class="addr-line"><strong>{{ __('meta.contact_v5') }}:</strong> ${a.nombre}</div>`);
                if (a.direccion) lines.push(`<div class="addr-line"><strong>{{ __('meta.direction') }}:</strong> ${a.direccion}</div>`);
                if (a.pais) lines.push(`<div class="addr-line"><strong>{{ __('meta.country') }}:</strong> ${a.pais}</div>`);
                if (a.departamento) lines.push(`<div class="addr-line"><strong>{{ __('meta.department') }}:</strong> ${a.departamento}</div>`);
                if (a.municipio) lines.push(`<div class="addr-line"><strong>{{ __('meta.municipality') }}:</strong> ${a.municipio}</div>`);
                if (a.ciudad) lines.push(`<div class="addr-line"><strong>{{ __('meta.city') }}:</strong> ${a.ciudad}</div>`);
                if (a.estado) lines.push(`<div class="addr-line"><strong>{{ __('meta.state_province') }}:</strong> ${a.estado}</div>`);
                if (a.zipcode) lines.push(`<div class="addr-line"><strong>{{ __('meta.postal_code') }}:</strong> ${a.zipcode}</div>`);
                if (a.telefono) lines.push(`<div class="addr-line"><strong>{{ __('meta.phone') }}:</strong> ${a.telefono}</div>`);

                box.innerHTML = `<div class="addr-card">${
                    lines.length ? lines.join('') : '<div class="addr-empty muted">{{ __('meta.no_additional_data') }}</div>'
                }</div>`;
            }

            // ===== Inicialización / Cambio de dirección en Paso 1 =====
            if (HAS_ADDRESSES) {
                const sel = document.getElementById('shipping_address');
                const hidden = document.getElementById('envio_id');

                document.addEventListener('DOMContentLoaded', () => {
                    const current = (hidden?.value || sel?.value);
                    if (current) {
                        renderAddrPreview(current);
                        updateSummaryByAddress(current);
                    }
                });

                sel?.addEventListener('change', e => {
                    const id = e.target.value;
                    if (hidden) hidden.value = id;
                    renderAddrPreview(id);
                    updateSummaryByAddress(id);
                });

                // ===== Paso 1 -> Paso 2 (VALIDA PAÍS) =====
                document.getElementById('btnToStep2')?.addEventListener('click', async () => {
                    const addrId = sel?.value;
                    if (!addrId) {
                        toastr.error(i18n.seNecesitaDireccionMessage);
                        return;
                    }
                    if (!isAllowedAddress(addrId)) {
                        await showQuoteSwal(addrId);
                        return; // NO avanzar
                    }
                    if (hidden) hidden.value = addrId;
                    go(2);
                });
            }

            // Back a paso 1
            document.getElementById('btnBack1')?.addEventListener('click', () => go(1));

            // ===== Paso 2 -> Paso 3 (re-chequeo por seguridad) =====
            document.getElementById('btnToStep3')?.addEventListener('click', async () => {
                const addrId = (document.getElementById('envio_id')?.value || '').trim();
                if (!addrId) {
                    toastr.error(i18n.seNecesitaDireccionMessage);
                    go(1);
                    return;
                }
                if (!isAllowedAddress(addrId)) {
                    await showQuoteSwal(addrId);
                    return; // NO avanzar
                }
                go(3);
            });

            // Click en steps (solo retroceder)
            stepsHead.addEventListener('click', ev => {
                const node = ev.target.closest('.step');
                if (!node) return;
                const step = +node.dataset.step;
                const current = [...stepsHead.querySelectorAll('.step')]
                    .findIndex(s => s.classList.contains('active')) + 1;
                if (step < current) go(step);
            });

            // Back paso 3 -> 2
            document.getElementById('btnBack2')?.addEventListener('click', () => go(2));

            // ===== Pagar con Pagadito (re-chequeo por seguridad) =====
            (function () {
                const btnPay = document.getElementById('btnPayPagadito');
                const payMsg = document.getElementById('pay-msg');
                const form   = document.getElementById('formPagadito');

                btnPay?.addEventListener('click', async () => {
                    const addrId = (document.getElementById('envio_id')?.value || '').trim();

                    if (!addrId) {
                        toastr.error(i18n.seNecesitaDireccionMessage);
                        go(1);
                        return;
                    }
                    if (!isAllowedAddress(addrId)) {
                        await showQuoteSwal(addrId);
                        return; // NO pagar
                    }
                    if (!form) {
                        toastr.error(i18n.noHayFormularioPagoMessage);
                        return;
                    }

                    // Enviar solo la dirección seleccionada
                    document.getElementById('pg_envio_id').value = addrId;

                    // Si tienes un campo pg_billing en el form, lo puedes limpiar:
                    const billingInput = document.getElementById('pg_billing');
                    if (billingInput) billingInput.value = '';

                    btnPay.disabled = true;
                    if (payMsg) payMsg.style.display = 'block';

                    form.submit();
                });
            })();
        })();
    </script>



@endsection
