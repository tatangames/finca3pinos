@extends('frontend.layouts.app')
@section('title', __('meta.title'))

@section('content')

    <style>
        /* ====== Header Tabs ====== */
        .checkout-steps{display:flex;gap:12px;align-items:center;margin:8px 0 16px}
        .step{display:flex;align-items:center;gap:8px;padding:8px 12px;border:1px solid rgba(0,0,0,.08);
            border-radius:12px;background:#fff;color:#222}
        .step-dot{width:26px;height:26px;border-radius:999px;display:inline-grid;place-items:center;
            font-weight:700;background:#e9efff;color:#2a58ff}
        .step.active{outline:2px solid #2a58ff22}
        .step.done .step-dot{background:#2a58ff;color:#fff}

        /* ====== Tabs ====== */
        .tab-pane{display:none}
        .tab-pane.active{display:block}

        /* ====== Layout ====== */
        .checkout-wrap{display:grid;grid-template-columns:1fr 360px;gap:24px}
        @media(max-width:992px){.checkout-wrap{grid-template-columns:1fr}}

        .card{background:#fff;border:1px solid rgba(0,0,0,.08);border-radius:16px}
        .card-h{padding:14px 16px;border-bottom:1px solid rgba(0,0,0,.06);font-weight:600}
        .card-b{padding:16px}

        .row2{display:grid;grid-template-columns:1fr 1fr;gap:12px}
        .control{display:block;width:100%;padding:10px 12px;border:1px solid rgba(0,0,0,.15);border-radius:10px;background:#fff}
        .btn{display:inline-block;border:0;border-radius:999px;padding:10px 16px;cursor:pointer}
        .btn-primary{background:#2a58ff;color:#fff}
        .btn-light{background:#f3f4f6}
        .btn-danger{background:#e53e3e;color:#fff}
        .muted{color:#6b7280}

        /* ====== Resumen ====== */
        .summary li{display:flex;justify-content:space-between;margin:6px 0}
        .summary .total{font-weight:800;font-size:1.1rem}

        /* ====== Tu bloque de envío ====== */
        .card-ship{background:#fff;border:1px solid rgba(0,0,0,.08);border-radius:14px;margin:10px 0}
        .card-ship .h{padding:12px 14px;border-bottom:1px solid rgba(0,0,0,.06);font-weight:600}
        .card-ship .b{padding:14px}


        /* ====== GUTTERS/LATERALES DEL CHECKOUT ====== */
        .checkout-shell{max-width:1200px;margin:0 auto;padding:0 16px;}
        @media (min-width:992px){ .checkout-shell{padding:0 28px;} }

        /* ====== Encabezado de pasos más compacto ====== */
        .checkout-steps{margin:6px 0 10px; gap:10px;}
        .step{padding:6px 10px;}
        .step-dot{width:24px;height:24px;font-size:.9rem}

        /* ====== Columna + tarjetas más compactas ====== */
        .checkout-wrap{gap:16px;} /* antes 24px */
        .card{border-radius:14px}
        .card-h{padding:12px 14px}
        .card-b{padding:14px}

        /* ====== Tu bloque de envío (más “aire” y compacto) ====== */
        .card-ship{border-radius:14px; margin:12px 0; box-shadow:0 1px 8px rgba(0,0,0,.04);}
        .card-ship .h{padding:12px 16px}
        .card-ship .b{padding:16px 16px}

        /* ====== Inputs un poco más pequeños ====== */
        .control{padding:8px 10px;border-radius:10px}


        /* ===== Contenedor estilo “barra” ===== */
        .steps-bar{
            background:#f1f6ff;              /* similar al azul claro de la imagen */
            border-radius:14px;
            padding:16px 18px;
            border:1px solid rgba(42,88,255,.05);
        }

        /* ===== Tabs estilo cápsula ===== */
        .checkout-steps{
            display:flex; align-items:center; justify-content:flex-start;
            gap:36px;                         /* más aire entre pasos */
            margin:0;                         /* sin márgenes extra */
        }

        /* item sin fondo/blanco; solo texto + círculo */
        .step{
            display:flex; align-items:center; gap:10px;
            background:transparent; border:0; padding:0;
            color:#1f2937;                    /* texto gris oscuro */
            font-weight:500;
            user-select:none;
        }

        /* círculo base negro (pendiente) */
        .step-dot{
            width:32px; height:32px; border-radius:999px;
            display:grid; place-items:center;
            font-weight:700; font-size:.95rem;
            background:#111; color:#fff;
            box-shadow:0 1px 0 rgba(0,0,0,.08) inset;
        }

        /* activos y completados = azules */
        .step.active .step-dot,
        .step.done   .step-dot{
            background:#2a58ff; color:#fff;
        }

        /* color del texto cuando está activo o done */
        .step.active span:last-child,
        .step.done   span:last-child{
            color:#2a58ff;
        }

        /* compactar en móvil */
        @media(max-width:768px){
            .checkout-steps{gap:18px}
            .step-dot{width:28px;height:28px;font-size:.85rem}
            .step span:last-child{font-size:.9rem}
        }

        /* (opcional) cursor/hover */
        .step{cursor:default}
        .step.done, .step.active{cursor:pointer}
        .step:hover .step-dot{filter:brightness(.96)}


        /* ===== Centrar y limitar ancho de los pasos ===== */
        .steps-bar {
            background: #f1f6ff;
            border-radius: 14px;
            padding: 14px 22px;
            border: 1px solid rgba(42,88,255,.05);

            /* centrado */
            display: flex;
            justify-content: center;
        }

        .checkout-steps {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 40px;
            max-width: 700px;       /* <-- limita el ancho total de los tabs */
            width: 100%;
        }

        /* ===== Ajuste visual ===== */
        .step {
            display: flex;
            align-items: center;
            gap: 10px;
            color: #1f2937;
            font-weight: 500;
            background: transparent;
            border: 0;
        }

        .step-dot {
            width: 32px;
            height: 32px;
            border-radius: 999px;
            display: grid;
            place-items: center;
            font-weight: 700;
            background: #111;
            color: #fff;
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

        /* ===== Compacto en móviles ===== */
        @media (max-width:768px) {
            .checkout-steps { gap: 20px; max-width: 100%; }
            .step-dot { width: 28px; height: 28px; font-size: .85rem; }
            .step span:last-child { font-size: .9rem; }
        }


        /* ===== Separación vertical entre los bloques ===== */
        .checkout-wrap {
            display: grid;
            grid-template-columns: 1fr 360px;
            gap: 24px;
            margin-top: 22px; /* <--- separa los bloques del encabezado de tabs */
        }

        @media(max-width:992px){
            .checkout-wrap{
                grid-template-columns: 1fr;
                margin-top: 18px; /* menos margen en móviles */
            }
        }

        .card-ship {
            margin-top: 12px;
        }



        /* === Centrado y márgenes laterales iguales al header === */
        .checkout-shell {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 32px; /* Aumenta el padding lateral */
        }

        /* === Espaciado superior entre tabs y contenido === */
        .checkout-wrap {
            display: grid;
            grid-template-columns: 1fr 360px;
            gap: 24px;
            margin-top: 26px;
            padding: 0 32px; /* <-- da margen lateral igual en el contenido */
        }

        /* Ajuste en móvil */
        @media (max-width: 992px) {
            .checkout-wrap {
                grid-template-columns: 1fr;
                padding: 0 16px;  /* menos padding en pantallas pequeñas */
                margin-top: 18px;
            }
        }


        .addr-card{
            border:1px solid rgba(0,0,0,.1);
            border-radius:10px;
            padding:10px 12px;
            background:#fff;
        }
        .addr-title{font-weight:700; margin-bottom:4px; display:flex; align-items:center; gap:8px;}
        .addr-badge{
            font-size:.75rem; background:#eef2ff; color:#384cff; border:1px solid #dbe3ff;
            padding:2px 8px; border-radius:999px;
        }
        .addr-line{color:#374151; font-size:.95rem;}
        .addr-empty{padding:8px 0;}




    </style>


    <style>

        .pay-tabs{display:flex;gap:8px;margin-bottom:10px}
        .pay-tab{padding:8px 12px;border:1px solid var(--border);border-radius:10px;background:#1c2435;color:#e6efff}
        .pay-tab.active{outline:2px solid #2a58ff33}

        .pay-grid{display:grid;grid-template-columns:420px 1fr;gap:20px}
        @media (max-width: 992px){.pay-grid{grid-template-columns:1fr}}

        .pay-left .pay-info{border:1px solid var(--border);border-radius:14px;padding:12px;margin-bottom:12px}
        .switch-row{display:flex;align-items:center;justify-content:space-between;margin-bottom:6px}
        .switch{position:relative;width:44px;height:24px;display:inline-block}
        .switch input{opacity:0;width:0;height:0}
        .slider{position:absolute;cursor:pointer;top:0;left:0;right:0;bottom:0;background:#394156;border-radius:999px;transition:.2s}
        .slider:before{content:"";position:absolute;height:18px;width:18px;left:3px;top:3px;background:#fff;border-radius:50%;transition:.2s}
        .switch input:checked + .slider{background:#2a58ff}
        .switch input:checked + .slider:before{transform:translateX(20px)}

        .cc-scene{perspective:1000px}
        .cc-card{width:100%;max-width:360px;aspect-ratio: 16/10;border-radius:16px;position:relative;transform-style:preserve-3d;transition:transform .5s;margin:auto;background:linear-gradient(135deg,#2d6bff,#4b9dff)}
        .cc-face{position:absolute;inset:0;border-radius:16px;color:#fff;padding:16px;backface-visibility:hidden}
        .cc-front .cc-row{display:flex;justify-content:space-between;align-items:center}
        .cc-chip{width:36px;height:26px;border-radius:6px;background:linear-gradient(45deg,#f6d365,#fda085);box-shadow:0 2px 8px rgba(0,0,0,.25)}
        .cc-brand{font-weight:800;letter-spacing:.5px}
        .cc-number{margin-top:24px;font:700 22px/1.2 ui-sans-serif,system-ui,Segoe UI;padding:6px 0;letter-spacing:2px}
        .cc-row.info{display:flex;justify-content:space-between;margin-top:10px}
        .cc-row .lbl{font-size:10px;opacity:.8}
        .cc-row .val{font-size:14px;letter-spacing:.5px}

        .cc-back{transform:rotateY(180deg);background:linear-gradient(135deg,#3752e6,#5eb1ff)}
        .cc-strip{height:40px;background:#111;margin-top:22px;border-radius:4px}
        .cc-cvc-box{background:#fff;color:#111;margin:20px 16px 0;border-radius:6px;padding:6px 10px;width:120px}
        .cc-brand-mini{position:absolute;bottom:10px;right:14px;font-weight:800}
        .cc-card.flip{transform:rotateY(180deg)}

        .pay-right .cards-logos{display:flex;gap:16px;align-items:center;margin:10px 0 6px}
        .pay-right .logo{font-size:12px;opacity:.8;border:1px solid var(--border);padding:2px 8px;border-radius:999px}
        .check-terms{display:block;margin:10px 0}
        .actions{display:flex;justify-content:space-between;margin-top:8px}


    </style>

    {{-- ====== Encabezado de pasos ====== --}}

    <div class="checkout-shell">
        <div class="steps-bar">
            <div class="checkout-steps" id="stepsHead">
                <div class="step active" data-step="1"><span class="step-dot">1</span> <span>Envío</span></div>
                <div class="step done"   data-step="2"><span class="step-dot">2</span> <span>Facturación</span></div>
                <div class="step"        data-step="3"><span class="step-dot">3</span> <span>Pago</span></div>
            </div>
        </div>
    </div>

    <div class="checkout-wrap">
        {{-- =================== COLUMNA IZQUIERDA (contenido por paso) =================== --}}
        <div>

            {{-- ========= Paso 1: ENVÍO ========= --}}
            <div class="tab-pane active" id="tab1">
                <div class="card-ship">
                    <div class="h">Dirección de Envío</div>
                    <div class="b">
                        @if($addresses->isEmpty())
                            <p style="margin-bottom:10px">
                                No tienes direcciones registradas. Agrega una para continuar con tu compra.
                            </p>
                            <a class="btn btn-primary"
                               href="{{ route('user.address', ['back' => 'checkout']) }}">
                                + Agregar dirección
                            </a>
                        @else
                            <label for="shipping_address" style="display:block;margin-bottom:6px">Selecciona una dirección</label>
                            <select id="shipping_address" class="control">
                                @foreach($addresses as $a)
                                    @php
                                        $label = trim(($a->pais_nombre ?? '—') . ' — ' . ($a->direccion ?? ''));
                                        $label = \Illuminate\Support\Str::limit($label, 60); // <-- límite
                                    @endphp
                                    <option value="{{ $a->id }}" {{ (int)$selectedAddressId===(int)$a->id ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>

                            @php
                                $addrSelected = $addresses->firstWhere('id', $selectedAddressId);
                            @endphp


                            {{-- Preview de la dirección seleccionada --}}
                            <div id="addrPreview" class="addr-preview" style="margin-top:10px">
                                @if($addrSelected)
                                    <div class="addr-card">
                                        @if($addrSelected->nombre)
                                            <div class="addr-line"><strong>Nombre:</strong> {{ $addrSelected->direccion }}</div>
                                        @endif
                                        @if($addrSelected->direccion)
                                            <div class="addr-line"><strong>Dirección:</strong> {{ $addrSelected->direccion }}</div>
                                        @endif
                                        @if($addrSelected->pais_nombre)
                                            <div class="addr-line"><strong>País:</strong> {{ $addrSelected->pais_nombre }}</div>
                                        @endif
                                        @if($addrSelected->depto_nombre)
                                            <div class="addr-line"><strong>Departamento:</strong> {{ $addrSelected->depto_nombre }}</div>
                                        @endif
                                        @if($addrSelected->muni_nombre)
                                            <div class="addr-line"><strong>Municipio:</strong> {{ $addrSelected->muni_nombre }}</div>
                                        @endif
                                        @if($addrSelected->ciudad)
                                            <div class="addr-line"><strong>Ciudad:</strong> {{ $addrSelected->ciudad }}</div>
                                        @endif
                                        @if($addrSelected->estado)
                                            <div class="addr-line"><strong>Estado/Provincia:</strong> {{ $addrSelected->estado }}</div>
                                        @endif
                                        @if($addrSelected->zipcode)
                                            <div class="addr-line"><strong>Código Postal:</strong> {{ $addrSelected->zipcode }}</div>
                                        @endif
                                        @if($addrSelected->telefono)
                                            <div class="addr-line"><strong>Teléfono:</strong> {{ $addrSelected->telefono }}</div>
                                        @endif
                                    </div>
                                @else
                                    <div class="addr-empty muted">Selecciona una dirección para ver los detalles.</div>
                                @endif
                            </div>




                            <input type="hidden" name="envio_id" id="envio_id" value="{{ $selectedAddressId }}">
                            <div style="display:flex;justify-content:flex-end;margin-top:12px">
                                <button class="btn btn-primary" id="btnToStep2">Siguiente</button>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- ========= Paso 2: FACTURACIÓN ========= --}}
            <div class="tab-pane" id="tab2">
                <div class="card">
                    <div class="card-h">Datos de Facturación</div>
                    <div class="card-b">
                        <div class="row2">
                            <div>
                                <label>Nombre / Razón social</label>
                                <input id="bill_nombre" class="control"
                                       value="{{ $billing->nombre ?? '' }}" placeholder="Nombre de facturación">
                            </div>
                            <div>
                                <label>Teléfono</label>
                                <input id="bill_tel" class="control"
                                       value="{{ $billing->telefono ?? '' }}" placeholder="+503 ...">
                            </div>
                        </div>


                        {{-- === País de Facturación === --}}
                        <label style="margin-top:10px">País</label>
                        <select id="bill_pais" class="control">
                            <option value="">{{ __('meta.select') }}</option>
                            @foreach($paises as $p)
                                <option value="{{ $p->id }}"
                                    {{ (int)($billing_country_id ?? 0) === (int)$p->id ? 'selected' : '' }}>
                                    {{ $p->nombre }}
                                </option>
                            @endforeach
                        </select>



                        <label style="margin-top:10px">Dirección</label>
                        <input id="bill_dir" class="control"
                               value="{{ $billing->direccion ?? '' }}" placeholder="Calle, número, referencias">

                        <div class="row2" style="margin-top:10px">
                            <div>
                                <label>Ciudad</label>
                                <input id="bill_ciudad" class="control" value="{{ $billing->ciudad ?? '' }}">
                            </div>
                            <div>
                                <label>Estado/Provincia</label>
                                <input id="bill_estado" class="control" value="{{ $billing->estado ?? '' }}">
                            </div>
                        </div>

                        <div class="row2" style="margin-top:10px">
                            <div>
                                <label>Código Postal</label>
                                <input id="bill_zip" class="control" value="{{ $billing->codigo_postal ?? '' }}">
                            </div>
                            <div style="display:flex;align-items:end;gap:10px">
                                <button class="btn btn-light" id="btnBack1">Volver</button>
                                <button class="btn btn-primary" id="btnToStep3">Siguiente</button>
                            </div>
                        </div>

                        <input type="hidden" id="bill_payload">
                    </div>
                </div>
            </div>

            {{-- ========= Paso 3: PAGO ========= --}}
            <div class="tab-pane" id="tab3">
                <div class="card">
                    <div class="card-h">Pago con tarjeta</div>
                    <div class="card-b">

                        {{-- Tabs (solo 1 por ahora) --}}
                        <div class="pay-tabs">
                            <button type="button" class="pay-tab active">💳 Pago con tarjeta</button>
                        </div>

                        <div class="pay-grid">
                            {{-- Lado izquierdo: tarjeta animada + opcional cuotas --}}
                            <div class="pay-left">

                                <!-- Tarjeta animada -->
                                <div class="cc-scene">
                                    <div class="cc-card" id="ccCard">
                                        <!-- Frente -->
                                        <div class="cc-face cc-front">
                                            <div class="cc-row">
                                                <span class="cc-chip"></span>
                                                <span class="cc-brand" id="ccBrand">VISA</span>
                                            </div>
                                            <div class="cc-number" id="ccNumberText">#### #### #### ####</div>
                                            <div class="cc-row info">
                                                <div>
                                                    <div class="lbl">NOMBRE</div>
                                                    <div class="val" id="ccNameText">TARJETAHABIENTE</div>
                                                </div>
                                                <div>
                                                    <div class="lbl">EXPIRA</div>
                                                    <div class="val"><span id="ccMM">MM</span>/<span id="ccYY">AA</span></div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Dorso -->
                                        <div class="cc-face cc-back">
                                            <div class="cc-strip"></div>
                                            <div class="cc-cvc-box">
                                                <div class="lbl">CVC</div>
                                                <div class="cvc" id="ccCvcText">•••</div>
                                            </div>
                                            <div class="cc-brand-mini" id="ccBrandBack">VISA</div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Lado derecho: formulario --}}
                            <div class="pay-right">
                                <form id="cardForm" autocomplete="off" novalidate>
                                    <div class="form-group">
                                        <label>Número de Tarjeta</label>
                                        <input class="control" id="cardNumber" inputmode="numeric" placeholder="4111 1111 1111 1111" maxlength="19">
                                    </div>

                                    <div class="form-group">
                                        <label>Nombre de la tarjeta</label>
                                        <input class="control" id="cardName" placeholder="Como aparece en la tarjeta" maxlength="40">
                                    </div>

                                    <div class="row2">
                                        <div>
                                            <label>Mes</label>
                                            <select class="control" id="cardMM">
                                                <option value="">MM</option>
                                                @for($m=1; $m<=12; $m++)
                                                    <option value="{{ str_pad($m,2,'0',STR_PAD_LEFT) }}">{{ str_pad($m,2,'0',STR_PAD_LEFT) }}</option>
                                                @endfor
                                            </select>
                                        </div>
                                        <div>
                                            <label>Año</label>
                                            <select class="control" id="cardYY">
                                                <option value="">AA</option>
                                                @php $y = (int) date('y'); @endphp
                                                @for($i=0; $i<=12; $i++)
                                                    <option value="{{ sprintf('%02d',$y+$i) }}">{{ sprintf('%02d',$y+$i) }}</option>
                                                @endfor
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label>CVC</label>
                                        <input class="control" id="cardCVC" inputmode="numeric" placeholder="•••" maxlength="4">
                                    </div>

                                    <div class="cards-logos">
                                        <span class="logo">VISA</span>
                                        <span class="logo">Mastercard</span>
                                        <span class="logo">AmEx</span>
                                        <span class="logo">Ambiente Seguro</span>
                                    </div>



                                    <div class="actions">
                                        <button class="btn btn-light" type="button" id="btnBack2">Volver</button>
                                        <button class="btn btn-danger" type="submit" id="btnPay">Proceder a Pagar</button>
                                    </div>
                                </form>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

        </div>

        {{-- =================== COLUMNA DERECHA (Resumen) =================== --}}
        <aside style="margin-top: 11px">
            <div class="card">
                <div class="card-h">Resumen de la Orden</div>
                <div class="card-b">
                    <ul class="summary">
                        <li><span>Subtotal</span><span id="sum-subtotal">${{ number_format($subtotal,2) }}</span></li>
                        <li><span>Envío</span><span id="sum-shipping">${{ number_format($shipping,2) }}</span></li>
                        <li class="total"><span>Total a Pagar</span><span id="sum-total">${{ number_format($total,2) }}</span></li>
                    </ul>
                </div>
            </div>
        </aside>
    </div>

    {{-- ======= Librerías que ya usas ======= --}}
    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="{{ asset('js/toastr.min.js') }}"></script>
    <script src="{{ asset('js/axios.min.js') }}"></script>
    <script src="{{ asset('js/sweetalert2.all.min.js') }}"></script>



    <script>
        const PAISES_MAP = {!! json_encode(
        $paises->keyBy('id')->map(fn($p)=>$p->nombre)->toArray(),
        JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES
    ) !!};
    </script>


    <script>
        const HAS_ADDRESSES = {{ $addresses->isNotEmpty() ? 'true' : 'false' }};
        const ADDRESS_MAP = {!! json_encode(
  $addresses->keyBy('id')->map(function($a){
      return [
          'id'            => (int) $a->id,
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
          'precio_envio'  => (float) ($a->precio_envio ?? 0), // <-- clave correcta
      ];
  })->toArray()
, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES) !!};
    </script>

    <script>
        (function(){
            const stepsHead = document.getElementById('stepsHead');
            const panes = {1:tab('tab1'), 2:tab('tab2'), 3:tab('tab3')};
            function tab(id){return document.getElementById(id);}
            function go(step){
                [...stepsHead.querySelectorAll('.step')].forEach((s,i)=>{
                    const idx=i+1;
                    s.classList.toggle('active', idx===step);
                    s.classList.toggle('done',   idx<step);
                });
                Object.values(panes).forEach(p=>p.classList.remove('active'));
                panes[step].classList.add('active');
                window.scrollTo({top: stepsHead.offsetTop-8, behavior:'smooth'});
            }

            const hasAddresses = HAS_ADDRESSES;
            if(hasAddresses){
                document.getElementById('shipping_address')?.addEventListener('change', e=>{
                    document.getElementById('envio_id').value = e.target.value;
                });
                document.getElementById('btnToStep2')?.addEventListener('click', ()=>{
                    const v = document.getElementById('shipping_address').value;
                    if(!v){ toastr.error('Selecciona una dirección de envío.'); return; }
                    go(2);
                });
            }

            document.getElementById('btnBack1')?.addEventListener('click', ()=> go(1));

            document.getElementById('btnToStep3')?.addEventListener('click', ()=>{
                const nombre = val('bill_nombre'), dir = val('bill_dir');
                const idPais = (document.getElementById('bill_pais')?.value || '').trim();

                if(!idPais){
                    toastr.warning('Selecciona el país de facturación.');
                    return;
                }
                if(!nombre || !dir){
                    toastr.warning('Completa al menos Nombre de facturación y Dirección.');
                    return;
                }
                const payload = {
                    id_paises: parseInt(idPais, 10),
                    pais_nombre: PAISES_MAP[idPais] || null,
                    nombre,
                    telefono: val('bill_tel'),
                    direccion: dir,
                    ciudad:   val('bill_ciudad'),
                    estado:   val('bill_estado'),
                    zipcode:  val('bill_zip')
                };
                document.getElementById('bill_payload').value = JSON.stringify(payload);
                go(3);
            });


            document.getElementById('btnBack2')?.addEventListener('click', ()=> go(2));

            document.getElementById('btnPlaceOrder')?.addEventListener('click', async ()=>{
                const envio_id = document.getElementById('envio_id')?.value || null;
                const billing  = document.getElementById('bill_payload')?.value || null;
                const pay      = document.querySelector('input[name="pay_method"]:checked')?.value || 'card';

                try{
                    const {data} = await axios.post('{{ route('checkout.place') }}', {
                        envio_id, billing: billing ? JSON.parse(billing) : null, pay_method: pay
                    });
                }catch(e){
                    toastr.error('No se pudo procesar el pedido. Revisa los datos e inténtalo de nuevo.');
                    console.error(e);
                }
            });

            stepsHead.addEventListener('click', (ev)=>{
                const node = ev.target.closest('.step'); if(!node) return;
                const step = +node.dataset.step;
                const current = [...stepsHead.querySelectorAll('.step')]
                    .findIndex(s=>s.classList.contains('active')) + 1;
                if(step < current) go(step);
            });

            function val(id){ return (document.getElementById(id)?.value || '').trim(); }
        })();

        /* ===================================================
           FUNCIONES ADICIONALES: PREVIEW + RESUMEN ACTUALIZABLE
        =================================================== */

        function money(n){ return '$' + Number(n||0).toFixed(2); }

        function updateSummaryByAddress(id){
            const a = ADDRESS_MAP[id];
            if(!a) return;
            const subtotal = Number((document.getElementById('sum-subtotal').innerText || '0').replace(/[^0-9.]/g,''));
            const shipping = Number(a.precio_envio || 0); // <-- usa precio_envio
            document.getElementById('sum-shipping').innerText = money(shipping);
            document.getElementById('sum-total').innerText    = money(subtotal + shipping);
        }

        function renderAddrPreview(id){
            const box = document.getElementById('addrPreview');
            const a = ADDRESS_MAP[id];
            if(!box) return;
            if(!a){
                box.innerHTML = `<div class="addr-empty muted">Selecciona una dirección para ver los detalles.</div>`;
                return;
            }

            const lines = [];
            if(a.nombre)       lines.push(`<div class="addr-line"><strong>Nombre:</strong> ${a.nombre}</div>`);
            if(a.direccion)    lines.push(`<div class="addr-line"><strong>Dirección:</strong> ${a.direccion}</div>`);
            if(a.pais)         lines.push(`<div class="addr-line"><strong>País:</strong> ${a.pais}</div>`);
            if(a.departamento) lines.push(`<div class="addr-line"><strong>Departamento:</strong> ${a.departamento}</div>`);
            if(a.municipio)    lines.push(`<div class="addr-line"><strong>Municipio:</strong> ${a.municipio}</div>`);
            if(a.ciudad)       lines.push(`<div class="addr-line"><strong>Ciudad:</strong> ${a.ciudad}</div>`);
            if(a.estado)       lines.push(`<div class="addr-line"><strong>Estado/Provincia:</strong> ${a.estado}</div>`);
            if(a.zipcode)      lines.push(`<div class="addr-line"><strong>Código Postal:</strong> ${a.zipcode}</div>`);
            if(a.telefono)     lines.push(`<div class="addr-line"><strong>Teléfono:</strong> ${a.telefono}</div>`);

            box.innerHTML = `
      <div class="addr-card">
          ${lines.length ? lines.join('') : '<div class="addr-empty muted">Sin datos adicionales.</div>'}
      </div>`;
        }

        /* ===================================================
           EVENTO: CAMBIO DE DIRECCIÓN
        =================================================== */
        document.addEventListener('DOMContentLoaded', ()=>{
            if(!HAS_ADDRESSES) return;
            const sel    = document.getElementById('shipping_address');
            const hidden = document.getElementById('envio_id');
            const current = (hidden?.value || sel?.value);

            if(current){
                renderAddrPreview(current);
                updateSummaryByAddress(current);
            }

            sel?.addEventListener('change', e=>{
                hidden.value = e.target.value;
                renderAddrPreview(e.target.value);
                updateSummaryByAddress(e.target.value);
            });
        });
    </script>



    <script>
        (function(){
            // === Helpers ===
            const $ = (s)=>document.querySelector(s);
            const brandByPan = (pan) => {
                if(/^3[47]/.test(pan)) return 'AMEX';
                if(/^4/.test(pan))     return 'VISA';
                if(/^5[1-5]/.test(pan) || /^2(2[2-9]|[3-6]\d|7[01])/.test(pan)) return 'Mastercard';
                if(/^6(?:011|5)/.test(pan)) return 'Discover';
                return 'CARD';
            };
            const luhn = (num)=>{
                let sum=0, dbl=false;
                for(let i=num.length-1;i>=0;i--){
                    let d = +num[i];
                    if(dbl){ d*=2; if(d>9) d-=9; }
                    sum+=d; dbl=!dbl;
                }
                return sum%10===0;
            };
            const formatPan = (s, isAmex)=> {
                const d = s.replace(/\D/g,'').slice(0, isAmex?15:16);
                let out='';
                for(let i=0;i<d.length;i++){
                    out += d[i];
                    if(isAmex && (i===3||i===9)) out+=' ';
                    else if(!isAmex && i%4===3 && i!==d.length-1) out+=' ';
                }
                return out;
            };

            // === DOM refs ===
            const card = $('#ccCard');
            const nInp = $('#cardNumber');
            const nameInp = $('#cardName');
            const mmSel = $('#cardMM');
            const yySel = $('#cardYY');
            const cvcInp = $('#cardCVC');

            const nTxt = $('#ccNumberText');
            const nameTxt = $('#ccNameText');
            const mmTxt = $('#ccMM');
            const yyTxt = $('#ccYY');
            const cvcTxt = $('#ccCvcText');
            const brandFront = $('#ccBrand');
            const brandBack  = $('#ccBrandBack');

            // === Number typing ===
            function updateBrandAndFormat(){
                const raw = nInp.value.replace(/\D/g,'');
                const isAmex = /^3[47]/.test(raw);
                nInp.value   = formatPan(nInp.value, isAmex);
                const b = brandByPan(raw);
                brandFront.textContent = b; brandBack.textContent = b;
                nTxt.textContent = nInp.value || '#### #### #### ####';
            }
            nInp.addEventListener('input', updateBrandAndFormat);
            nInp.addEventListener('blur', ()=>{ // opcional marca inválido
                const raw = nInp.value.replace(/\D/g,'');
                if(raw.length>=13 && !luhn(raw)){
                    toastr.warning('Verifica el número de tarjeta (Luhn inválido).');
                }
            });

            // === Name ===
            nameInp.addEventListener('input', ()=>{
                const v = nameInp.value.replace(/[^A-Za-zÁÉÍÓÚÑáéíóúñ\s\.]/g,'').toUpperCase().slice(0,26);
                nameInp.value = v;
                nameTxt.textContent = v || 'TARJETAHABIENTE';
            });

            // === Fecha ===
            mmSel.addEventListener('change', ()=> mmTxt.textContent = mmSel.value || 'MM');
            yySel.addEventListener('change', ()=> yyTxt.textContent = yySel.value || 'AA');

            // === CVC & flip ===
            cvcInp.addEventListener('focus', ()=> card.classList.add('flip'));
            cvcInp.addEventListener('blur',  ()=> card.classList.remove('flip'));
            cvcInp.addEventListener('input', ()=>{
                cvcInp.value = cvcInp.value.replace(/\D/g,'').slice(0,4);
                cvcTxt.textContent = cvcInp.value.padEnd(3,'•');
            });

            // === Back button keeps tu navegación ===
            $('#btnBack2')?.addEventListener('click', ()=> {
                // ya tienes go(2) arriba; lo reutilizamos:
                if(typeof go==='function') go(2);
            });

            // === Envío del pago (NO enviar PAN/CVC al backend) ===
            // En producción, tokeniza con tu gateway (Stripe, PayPal, Wompi, etc.)
            $('#cardForm').addEventListener('submit', async (e)=>{
                e.preventDefault();


                const panRaw = nInp.value.replace(/\D/g,'');
                const mm = mmSel.value, yy = yySel.value, cvc = cvcInp.value;

                if(panRaw.length<13 || !luhn(panRaw)) { toastr.error('Número de tarjeta inválido.'); return; }
                if(!(+mm>=1 && +mm<=12)) { toastr.error('Mes inválido.'); return; }
                if(!yy) { toastr.error('Año inválido.'); return; }
                if(cvc.length<3) { toastr.error('CVC inválido.'); return; }

                // Solo mandamos metadata NO sensible
                const payment = {
                    method: 'card',
                    brand: brandByPan(panRaw),
                    last4: panRaw.slice(-4),
                    holder: nameInp.value.trim(),
                    exp: `${mm}/${yy}`,
                    cuotas: $('#cuotasToggle').checked ? true : false
                };

                try{
                    const envio_id = document.getElementById('envio_id')?.value || null;
                    const billing  = document.getElementById('bill_payload')?.value || null;

                    const {data} = await axios.post('{{ route('checkout.place') }}', {
                        envio_id,
                        billing: billing ? JSON.parse(billing) : null,
                        pay_method: 'card',
                        payment_meta: payment
                    });

                    // Éxito UX
                    Swal.fire({icon:'success', title:'Pago procesado', text:'Tu pedido ha sido creado.'});
                    // Redirige si tu backend devuelve URL
                    if(data?.redirect){ window.location.href = data.redirect; }

                }catch(err){
                    console.error(err);
                    Swal.fire({icon:'error', title:'No se pudo procesar el pago', text:'Intenta nuevamente.'});
                }
            });
        })();
    </script>



    {{-- Superior (Newsletter) block --}}
    @include('frontend.partials.superior')
@endsection
