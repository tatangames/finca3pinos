@extends('frontend.layouts.app')

@section('title', __('meta.order_detail') ?? 'Detalles de la orden')

@section('content')
    <style>
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
            padding: 32px 12px;
        }

        .account-container {
            max-width: 1200px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: 280px 1fr;
            gap: 24px;
        }

        @media (max-width: 992px) {
            .account-container {
                grid-template-columns: 1fr;
            }
        }

        /* Sidebar */
        .account-sidebar {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 18px;
            overflow: hidden;
        }
        .account-sidebar .box-head {
            padding: 18px 18px 8px;
            border-bottom: 1px solid var(--border);
            background: #fff;
        }
        .account-sidebar .box-head h4 {
            margin: 0;
            font-size: 15px;
            letter-spacing: .06em;
            color: var(--brand);
            text-transform: uppercase;
        }
        .account-menu {
            display: flex;
            flex-direction: column;
            padding: 8px;
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
            background: transparent;
        }
        .account-menu a .label {
            display: flex;
            align-items: center;
            gap: 10px;
            flex: 1;
            min-width: 0;
        }
        .account-menu a .label span:last-child {
            color: var(--text);
            font-weight: 600;
        }
        .account-menu a:hover {
            background: #fafafa;
            border-color: var(--border);
        }
        .account-menu a.is-active {
            background: var(--brand);
            color: #fff;
            border-color: transparent;
        }
        .account-menu a.is-active .label span:last-child {
            color: #fff;
        }
        .account-menu .hint {
            font-size: 12px;
            color: var(--muted);
        }

        /* Content */
        .account-content {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 18px;
            overflow: hidden;
        }
        .account-content .head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 18px 20px;
            border-bottom: 1px solid var(--border);
            background: #fff;
        }
        .account-content .head h3 {
            margin: 0;
            font-size: 20px;
            font-weight: 700;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 9px 16px;
            border-radius: 10px;
            border: none;
            cursor: pointer;
            text-decoration: none;
            font-weight: 700;
            font-size: 13px;
        }
        .btn-back {
            background: #111827;
            color: #fff;
        }
        .btn-back:hover {
            background: #000;
        }

        /* Header orden */
        .order-header {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 18px 20px;
            background: #eef2ff;
            border-bottom: 1px solid var(--border);
        }
        .order-header-icon {
            width: 40px;
            height: 40px;
            border-radius: 999px;
            background: #3b82f6;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 20px;
        }
        .order-header-text h4 {
            margin: 0;
            font-size: 16px;
            font-weight: 700;
        }
        .order-header-text span {
            display: block;
            font-size: 12px;
            color: var(--muted);
        }

        /* Info envío + resumen */
        .order-info-wrap {
            display: grid;
            grid-template-columns: 1.5fr 1fr;
            gap: 24px;
            padding: 20px;
        }
        @media (max-width: 768px) {
            .order-info-wrap {
                grid-template-columns: 1fr;
            }
        }
        .order-block-title {
            font-size: 13px;
            font-weight: 700;
            margin-bottom: 6px;
        }
        .order-block p {
            margin: 0;
            font-size: 13px;
            color: var(--text);
        }
        .order-summary p {
            margin: 0 0 4px;
            font-size: 13px;
        }
        .order-summary span.label {
            color: var(--muted);
        }
        .order-summary span.value {
            font-weight: 600;
        }

        /* Items */
        .items-wrap {
            padding: 0 20px 24px;
        }
        .item-card {
            margin-top: 14px;
            padding: 14px 16px;
            background: #fff;
            border-radius: 18px;
            border: 1px solid var(--border);
            display: grid;
            grid-template-columns: 90px 1fr;
            gap: 14px;
            align-items: flex-start;
        }
        @media (max-width: 640px) {
            .item-card {
                grid-template-columns: 70px 1fr;
            }
        }
        .item-img {
            width: 90px;
            height: 90px;
            border-radius: 14px;
            background: #f3f4f6;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }
        .item-img img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
        }
        .item-title {
            font-size: 14px;
            font-weight: 700;
            margin-bottom: 6px;
        }
        .item-line {
            font-size: 13px;
            margin: 0 0 2px;
        }
        .item-line span.label {
            color: var(--muted);
        }
        .item-line span.value {
            font-weight: 600;
        }
    </style>

    @php
        $active = 'orders';
        $is = fn ($key) => $active === $key ? 'is-active' : '';
    @endphp

    <section class="account-wrap theme-light">
        <div class="account-container">

            {{-- Sidebar --}}
            <aside class="account-sidebar" aria-label="Sidebar">
                <div class="box-head">
                    <h4>{{ __('meta.my_account') }}</h4>
                </div>

                <nav class="account-menu" role="navigation">
                    <a class="{{ $is('orders') }}"
                       href="{{ LaravelLocalization::getLocalizedURL(app()->getLocale(), route('user.orders', [], false)) }}">
                        <span class="label">
                            <span>🧾</span>
                            <span>{{ __('meta.orders') }}</span>
                        </span>
                        <span class="hint">→</span>
                    </a>

                    <a class="{{ $is('addresses') }}"
                       href="{{ LaravelLocalization::getLocalizedURL(app()->getLocale(), route('user.address', [], false)) }}">
                        <span class="label">
                            <span>📍</span>
                            <span>{{ __('meta.addresses') }}</span>
                        </span>
                        <span class="hint">→</span>
                    </a>

                    <a class="{{ $is('profile') }}"
                       href="{{ LaravelLocalization::getLocalizedURL(app()->getLocale(), route('user.view.perfil', [], false)) }}">
                        <span class="label">
                            <span>⚙️</span>
                            <span>{{ __('meta.profile') }}</span>
                        </span>
                        <span class="hint">→</span>
                    </a>

                    <a href="#" id="logoutLink">
                        <span class="label">
                            <span>🚪</span>
                            <span>{{ __('meta.logout') }}</span>
                        </span>
                        <span class="hint">→</span>
                    </a>
                    <form id="logoutForm" method="POST" action="#" style="display:none">
                        @csrf
                    </form>
                </nav>
            </aside>

            {{-- Contenido Detalle --}}
            <div class="account-content">

                <div class="head">
                    <h3>{{ __('meta.order_detail') ?? 'Detalles de la orden' }}</h3>

                    <a href="{{ LaravelLocalization::getLocalizedURL(app()->getLocale(), route('user.orders', [], false)) }}"
                       class="btn btn-back">
                        ← {{ __('meta.back_to_orders') ?? 'Volver' }}
                    </a>
                </div>

                {{-- Cabecera orden --}}
                <div class="order-header">
                    <div class="order-header-icon">📦</div>
                    <div class="order-header-text">
                        <h4>ORDEN #{{ $order->id }}</h4>
                        @if($fechaOrden)
                            <span style="font-size: 14px">{{ $fechaOrden }}</span>
                        @endif
                        @if(!empty($order->seguimiento))
                            <span style="font-size: 14px">
                                {{ __('meta.tracking') }}:
                                &nbsp;{!! $order->seguimiento !!}
                            </span>
                        @endif
                    </div>
                </div>

                {{-- Info envío + resumen --}}
                <div class="order-info-wrap">
                    <div class="order-block">
                        <div class="order-block-title">{{ __('meta.shipping_address') }}</div>

                        @if(!empty($order->shipping_nombre))
                            <p>{{ $order->shipping_nombre }}</p>
                        @endif
                        @if(!empty($order->shipping_direccion))
                            <p>{{ $order->shipping_direccion }}</p>
                        @endif
                        @if(!empty($nombrePais))
                            <p>{{ $nombrePais }}</p>
                        @endif
                        @if(!empty($nombreDepartamento))
                            <p>{{ $nombreDepartamento }}</p>
                        @endif
                        @if(!empty($nombreMunicipio))
                            <p>{{ $nombreMunicipio }}</p>
                        @endif
                    </div>

                    <div class="order-summary">
                        <div class="order-block-title">{{ __('meta.order_summary')}}</div>
                        <p>
                            <span class="label" style="font-size: 14px">{{ __('meta.subtotal') }}: </span>
                            <span class="value" style="font-size: 14px">${{ number_format($subtotal, 2) }}</span>
                        </p>
                        <p>
                            <span class="label" style="font-size: 14px">{{ __('meta.shipping') }}: </span>
                            <span class="value" style="font-size: 14px">${{ number_format($envio, 2) }}</span>
                        </p>
                        <p>
                            <span class="label" style="font-size: 14px">{{ __('meta.total') }}: </span>
                            <span class="value" style="font-size: 14px">${{ number_format($total, 2) }}</span>
                        </p>
                    </div>
                </div>

                {{-- Items --}}
                <div class="items-wrap">
                    @foreach($items as $index => $item)
                        @php
                            // Detalle bruto (para imagen, etc.)
                            $detalle  = $order->detalles[$index] ?? null;
                            $producto = $detalle->producto ?? null;

                            // Nombre ya viene completo desde el backend: "Producto X — Presentación"
                            $nombreCompleto = $item['nombre'] ?? '';

                            // Valores numéricos seguros
                            $precio    = isset($item['precio']) ? (float)$item['precio'] : 0;
                            $cantidad  = isset($item['cantidad']) ? (int)$item['cantidad'] : 1;
                            $lineTotal = isset($item['subtotal'])
                                ? (float)$item['subtotal']
                                : $precio * $cantidad;
                        @endphp

                        <div class="item-card">
                            <div class="item-img">
                                @if($producto && !empty($producto->imagen))
                                    <img src="{{ asset('storage/archivos/' . $producto->imagen) }}"
                                         alt="{{ $nombreCompleto }}"
                                         class="img-fluid rounded">
                                @else
                                    📷
                                @endif
                            </div>
                            <div>
                                <div class="item-title">
                                    {{ $nombreCompleto }}
                                </div>
                                <p class="item-line">
                                    <span class="label" style="font-size: 14px">{{ __('meta.price') ?? 'Precio' }}: </span>
                                    <span class="value" style="font-size: 14px">
                        ${{ number_format($precio, 2) }}
                    </span>
                                </p>
                                <p class="item-line">
                                    <span class="label" style="font-size: 14px">{{ __('meta.quantity') ?? 'Cantidad' }}: </span>
                                    <span class="value" style="font-size: 14px">
                        {{ $cantidad }}
                    </span>
                                </p>
                                <p class="item-line">
                                    <span class="label" style="font-size: 14px">{{ __('meta.subtotal') ?? 'Total' }}: </span>
                                    <span class="value" style="font-size: 14px">
                        ${{ number_format($lineTotal, 2) }}
                    </span>
                                </p>
                            </div>
                        </div>
                    @endforeach
                </div>

            </div>
        </div>
    </section>

    @include('frontend.partials.superior')

    <script>
        document.getElementById('logoutLink')?.addEventListener('click', function (e) {
            e.preventDefault();
            document.getElementById('logoutForm')?.submit();
        });
    </script>
@endsection
