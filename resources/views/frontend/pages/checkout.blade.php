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
    </style>

    {{-- ====== Encabezado de pasos ====== --}}
    <div class="checkout-steps" id="stepsHead">
        <div class="step active" data-step="1"><span class="step-dot">1</span> Envío</div>
        <div class="step"        data-step="2"><span class="step-dot">2</span> Facturación</div>
        <div class="step"        data-step="3"><span class="step-dot">3</span> Pago</div>
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
            const hasAddresses = {{ $addresses->isNotEmpty() ? 'true' : 'false' }};
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
    </script>

    {{-- Superior (Newsletter) block --}}
    @include('frontend.partials.superior')
@endsection
