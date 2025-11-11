@extends('frontend.layouts.app')

@section('title', __('meta.title'))

@section('content')
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

        /* fuerza el texto visible por si hay estilos globales */
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

        /* ===== Tabla ===== */
        .table-wrap {
            padding: 16px 18px 20px;
            overflow: auto
        }

        table.orders {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0 10px
        }

        table.orders thead th {
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: .06em;
            color: var(--muted);
            text-align: left;
            padding: 10px 14px;
            white-space: nowrap
        }

        table.orders tbody tr {
            background: #fff;
            border: 1px solid var(--border);
            box-shadow: 0 2px 10px rgba(0, 0, 0, .02)
        }

        table.orders tbody td {
            padding: 14px 14px;
            white-space: nowrap;
            vertical-align: middle;
            border-top: 1px solid rgba(0, 0, 0, .03)
        }

        .badge {
            display: inline-block;
            padding: 8px 12px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 700;
            background: var(--pill)
        }

        .badge.status--delivered {
            background: #e8f4ff;
            color: #1b4dff
        }

        .badge.status--pending {
            background: #efe9ff;
            color: #6a38c3
        }

        .badge.status--processing {
            background: #e5fbf2;
            color: #0d7a53
        }

        .price {
            font-weight: 800
        }

        /* ===== Botones ===== */
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

        .btn i {
            font-style: normal
        }

        .btn-track {
            background: var(--success);
            color: var(--brand-contrast)
        }

        .btn-order {
            background: var(--primary);
            color: #fff
        }

        .btn-gold {
            background: var(--brand);
            color: #fff
        }

        .btn:focus {
            outline: 2px solid rgba(0, 0, 0, .12);
            outline-offset: 2px
        }

        /* ===== Empty ===== */
        .empty {
            padding: 56px 18px 64px;
            text-align: center;
            color: var(--muted)
        }

        .empty h4 {
            margin: 0 0 10px;
            color: var(--text);
            font-size: 22px
        }

        .empty .btn-order {
            display: block;
            width: max-content;
            margin: 16px auto 0;
            text-align: center
        }

        .empty .btn-order span {
            display: block;
            width: 100%;
            text-align: center
        }

        /* centra el texto del botón */


        .btn.btn-track,
        .btn.btn-gold {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            font-size: 13px;
            padding: 4px 10px;
            border-radius: 20px;   /* 🔹 hace que se vean redondos */
            text-decoration: none;
            transition: background 0.2s;
        }

        .btn.btn-track {
            background-color: #f1f1f1;
            color: #333;
        }
        .btn.btn-gold {
            background-color: #c8b083;
            color: #fff;
        }
        .btn.btn-track:hover {
            background-color: #e2e2e2;
        }
        .btn.btn-gold:hover {
            background-color: #b39c6b;
        }

        /* Íconos un poco más pequeños */
        .btn i {
            font-size: 14px;
            line-height: 1;
        }
        /* ===== Botones pequeños redondeados SOLO en la tabla de órdenes ===== */
        table.orders .btn.btn-track,
        table.orders .btn.btn-gold {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            font-size: 12px !important;
            padding: 3px 9px !important;
            border-radius: 999px !important; /* pill bien redondo */
            text-decoration: none;
            line-height: 1.2;
        }

        /* Colores */
        table.orders .btn.btn-track {
            background-color: #f1f1f1 !important;
            color: #333 !important;
        }

        table.orders .btn.btn-gold {
            background-color: #c8b083 !important;
            color: #fff !important;
        }

        /* Ícono compacto */
        table.orders .btn.btn-track i,
        table.orders .btn.btn-gold i {
            font-size: 16px;
            line-height: 3;
        }


    </style>

    @php
        // pestaña activa: Órdenes por defecto
        $active = request('tab', 'orders');
        $is = fn ($key) => $active === $key ? 'is-active' : '';
    @endphp

    <section class="account-wrap theme-light">
        <div class="account-container">

            {{-- ===== Sidebar ===== --}}
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

                    <a class="{{ $is('profile') }}" href="{{ LaravelLocalization::getLocalizedURL(app()->getLocale(), route('user.view.perfil', [], false)) }}">
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

            {{-- ===== Contenido (Órdenes) ===== --}}
            <div class="account-content" role="region" aria-label="{{ __('meta.orders') }}">
                <div class="head">
                    <h3>{{ __('meta.my_orders')}}</h3>
                </div>

                @if(isset($orders) && $orders->count())
                    <div class="table-wrap">
                        <table class="orders" aria-describedby="orders-table">
                            <thead>
                            <tr>
                                <th>{{ __('meta.order') }}</th>
                                <th>{{ __('meta.date') }}</th>
                                <th>{{ __('meta.total') }}</th>
                                <th>{{ __('meta.status') }}</th>
                                <th>{{ __('meta.options') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($orders as $order)
                                <tr>
                                    <td># {{ $order->id }}</td>
                                    <td>{{ $order->fecha_formateada }}</td>
                                    <td class="price">$ {{ number_format($order->total, 2) }}</td>
                                    <td>
                                        @php
                                            $status = $order->status_id ?? 0;
                                        @endphp

                                        @if ($status == 2)
                                            <span class="badge" style="background-color:#28a745; color:#fff; padding:6px 12px; border-radius:20px; font-weight:500;">
                                            {{ $order->estado_texto }}
                                        </span>
                                        @else
                                            {{ $order->estado_texto }}
                                        @endif
                                    </td>

                                    <td>
                                        {{-- Ajusta estas rutas cuando tengas tracking y detalle --}}
                                        <a class="btn btn-track"
                                           href="{{ LaravelLocalization::getLocalizedURL(
                                            app()->getLocale(),
                                            route('user.orders.tracking', ['order' => $order->id], false)
                                        ) }}">
                                            <i>🚚</i> {{ __('meta.tracking') }}
                                        </a>
                                        <a class="btn btn-gold"
                                           href="{{ LaravelLocalization::getLocalizedURL(
                                            app()->getLocale(),
                                            route('user.orders.detail', ['order' => $order->id], false)
                                        ) }}">
                                            <i>📄</i>{{ __('meta.order') }}
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>

                        @if(method_exists($orders, 'links'))
                            <div style="margin-top:14px">
                                {{ $orders->links() }}
                            </div>
                        @endif
                    </div>
                @else
                    <div class="empty">
                        <h4>{{ __('meta.no_orders_yet') }}</h4>
                        <a class="btn btn-order" href="#"><span>{{ __('meta.gotoshop') }}</span></a>
                    </div>
                @endif
            </div>
        </div>

    </section>

    <script>
        // Logout por POST (placeholder)
        document.getElementById('logoutLink')?.addEventListener('click', function (e) {
            e.preventDefault();
            document.getElementById('logoutForm')?.submit();
        });
    </script>

    {{-- Superior (Newsletter) block --}}
    @include('frontend.partials.superior')
@endsection
