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
                                    <option value="{{ $a->id }}" {{ (int)$selectedAddressId===(int)$a->id ? 'selected' : '' }}>
                                        {{ $a->nombre }} — {{ $a->direccion }}
                                        {{ $a->ciudad ? ', '.$a->ciudad : '' }}
                                        {{ $a->estado ? ', '.$a->estado : '' }}
                                        {{ $a->zipcode ? ', '.$a->zipcode : '' }}
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
                                        <div class="addr-title">
                                            {{ $addrSelected->nombre }}
                                            @if((int)$addrSelected->predeterminado === 1)
                                                <span class="addr-badge">Predeterminada</span>
                                            @endif
                                        </div>
                                        <div class="addr-line">{{ $addrSelected->direccion }}</div>
                                        <div class="addr-line">
                                            {{ $addrSelected->ciudad ? $addrSelected->ciudad.', ' : '' }}
                                            {{ $addrSelected->estado ? $addrSelected->estado.' ' : '' }}
                                            {{ $addrSelected->zipcode ?? '' }}
                                        </div>
                                        @if($addrSelected->telefono)
                                            <div class="addr-line">Tel: {{ $addrSelected->telefono }}</div>
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
                                <input id="bill_zip" class="control" value="{{ $billing->zipcode ?? '' }}">
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
                    <div class="card-h">Pago</div>
                    <div class="card-b">
                        <p class="muted">Selecciona un método de pago:</p>
                        <div class="row2">
                            <label class="control" style="display:flex;align-items:center;gap:10px">
                                <input type="radio" name="pay_method" value="card" checked> Tarjeta
                            </label>
                            <label class="control" style="display:flex;align-items:center;gap:10px">
                                <input type="radio" name="pay_method" value="cod"> Contra Entrega
                            </label>
                        </div>

                        <div style="display:flex;justify-content:space-between;margin-top:14px">
                            <button class="btn btn-light" id="btnBack2">Volver</button>
                            <button class="btn btn-danger" id="btnPlaceOrder">Proceder a Pagar</button>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        {{-- =================== COLUMNA DERECHA (Resumen) =================== --}}
        <aside>
            <div class="card">
                <div class="card-h">Resumen de la Orden</div>
                <div class="card-b">
                    <ul class="summary">
                        <li><span>Subtotal</span><span>${{ number_format($subtotal,2) }}</span></li>
                        <li><span>Envío</span><span>${{ number_format($shipping,2) }}</span></li>
                        <li><span>Impuestos</span><span>${{ number_format($tax,2) }}</span></li>
                        <li class="total"><span>Total a Pagar</span><span>${{ number_format($total,2) }}</span></li>
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
        // ¿hay direcciones? (variable global)
        const HAS_ADDRESSES = {{ $addresses->isNotEmpty() ? 'true' : 'false' }};

        const ADDRESS_MAP = {!! json_encode(
      $addresses->keyBy('id')->map(function($a){
          return [
              'id'            => (int) $a->id,
              'nombre'        => $a->nombre,
              'direccion'     => $a->direccion,
              'ciudad'        => $a->ciudad,
              'estado'        => $a->estado,
              'zipcode'       => $a->zipcode,
              'telefono'      => $a->telefono,
              'predeterminado'=> (int) $a->predeterminado,
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

            // Si no hay direcciones, bloquea la navegación hacia el paso 2
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
                if(!nombre || !dir){
                    toastr.warning('Completa al menos Nombre de facturación y Dirección.');
                    return;
                }
                const payload = {
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
                if(step < current) go(step);   // solo permitir ir atrás con el header
            });

            function val(id){ return (document.getElementById(id)?.value || '').trim(); }
        })();


        function renderAddrPreview(id){
            const box = document.getElementById('addrPreview');
            const a = ADDRESS_MAP[id];
            if(!box) return;
            if(!a){
                box.innerHTML = `<div class="addr-empty muted">Selecciona una dirección para ver los detalles.</div>`;
                return;
            }
            box.innerHTML = `
                    <div class="addr-card">
                      <div class="addr-title">
                        ${a.nombre ?? ''}
                        ${a.predeterminado === 1 ? '<span class="addr-badge">Predeterminada</span>' : ''}
                      </div>
                      <div class="addr-line">${a.direccion ?? ''}</div>
                      <div class="addr-line">
                        ${(a.ciudad ? a.ciudad+', ' : '')}${(a.estado ? a.estado+' ' : '')}${a.zipcode ?? ''}
                      </div>
                      ${a.telefono ? `<div class="addr-line">Tel: ${a.telefono}</div>` : ''}
                    </div>`;
        }

        if(hasAddresses){
            const sel = document.getElementById('shipping_address');
            const hidden = document.getElementById('envio_id');

            // pintar al cargar
            renderAddrPreview(hidden?.value || sel?.value);

            sel?.addEventListener('change', e=>{
                hidden.value = e.target.value;
                renderAddrPreview(e.target.value);
            });

            document.getElementById('btnToStep2')?.addEventListener('click', ()=>{
                const v = sel.value;
                if(!v){ toastr.error('Selecciona una dirección de envío.'); return; }
                go(2);
            });
        }



    </script>

    {{-- Superior (Newsletter) block --}}
    @include('frontend.partials.superior')
@endsection
