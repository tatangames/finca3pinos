@extends('frontend.layouts.app')

@section('title', __('meta.tracking'))

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

        /* ===== Sidebar ===== */
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

        /* ===== Content ===== */
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
            letter-spacing: .02em;
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
            font-weight: 700;
            font-size: 13px;
        }

        .btn-order {
            background: #1f2937;
            color: #fff;
        }

        .btn-order:hover {
            background: #111827;
        }

        /* ===== Tracking Timeline (2 estados) ===== */
        .tracking-wrap {
            padding: 40px 26px 36px;
            text-align: center;
        }

        .tracking-title {
            font-size: 22px;
            font-weight: 700;
            margin-bottom: 10px;
        }

        .tracking-sub {
            font-size: 13px;
            color: var(--muted);
            margin-bottom: 24px;
        }

        .tracking-steps {
            position: relative;
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-top: 24px;
        }

        /* Línea base */
        .tracking-steps::before {
            content: "";
            position: absolute;
            top: 34px;
            left: 14%;
            right: 14%;
            height: 4px;
            background: #e0e7ff;
            z-index: 1;
        }

        .step {
            position: relative;
            z-index: 2;
            flex: 1;
            text-align: center;
        }

        .step-circle {
            width: 64px;
            height: 64px;
            border-radius: 999px;
            background: #e0e7ff;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 10px;
            font-size: 26px;
            color: #ffffff;
            transition: all .25s ease;
        }

        .step-label {
            font-size: 14px;
            font-weight: 700;
            margin-bottom: 4px;
        }

        .step-date {
            font-size: 13px;
            color: var(--muted);
        }

        /* Estado activo */
        .step.active .step-circle {
            background: var(--primary);
            box-shadow: 0 6px 18px rgba(37, 99, 235, .35);
        }

        .step.active .step-label {
            color: #000;
        }

        /* Mobile */
        @media (max-width: 640px) {
            .tracking-wrap {
                padding: 24px 16px 24px;
            }

            .tracking-steps::before {
                left: 18%;
                right: 18%;
            }

            .step-circle {
                width: 54px;
                height: 54px;
                font-size: 22px;
            }

            .tracking-title {
                font-size: 18px;
            }
        }




    </style>

    @php
        $active = 'orders';
        $is = fn ($key) => $active === $key ? 'is-active' : '';

        // Usamos los 2 estados enviados desde el controlador
        $prep = $estados[0] ?? null;
        $sent = $estados[1] ?? null;
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

            {{-- ===== Contenido Tracking ===== --}}
            <div class="account-content">
                <div class="head d-flex justify-content-between align-items-center">
                    <div class="tracking-text" style="text-align:left;">
                        {{ __('meta.tracking') }}:&nbsp;{!! $order->seguimiento !!}
                    </div>

                    <a href="{{ LaravelLocalization::getLocalizedURL(app()->getLocale(), route('user.orders', [], false)) }}"
                       class="btn btn-order">
                        {{ __('meta.back_to_orders') ?? '← '.__('meta.orders') }}
                    </a>
                </div>




                <div class="tracking-wrap">

                    {{-- 🔹 Botón Detalle de la Orden --}}
                    <a href="{{ LaravelLocalization::getLocalizedURL(
                                            app()->getLocale(),
                                            route('user.orders.detail', ['order' => $order->id], false)
                                        ) }}"
                       class="btn btn-order"
                       style="margin: 8px auto 20px; display: inline-block;">
                        {{ __('meta.order_detail') }}
                    </a>

                    <div class="tracking-sub">
                        {{ __('meta.order_status') }}
                    </div>

                    <div class="tracking-steps">
                        {{-- Paso 1: Preparando pedido --}}
                        <div class="step {{ !empty($prep['active']) ? 'active' : '' }}">
                            <div class="step-circle">
                                🕒
                            </div>
                            <div class="step-label">
                                {{ __('meta.order_status_preparing') }}
                            </div>
                            @if(!empty($prep['date']))
                                <div class="step-date">
                                    {{ $prep['date'] }}
                                </div>
                            @endif
                        </div>

                        {{-- Paso 2: Orden enviada --}}
                        <div class="step {{ !empty($sent['active']) ? 'active' : '' }}">
                            <div class="step-circle">
                                🚚
                            </div>
                            <div class="step-label">
                                {{__('meta.order_status_shipped') }}
                            </div>
                            @if(!empty($sent['date']))
                                <div class="step-date">
                                    {{ $sent['date'] }}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>

    {{-- Superior (Newsletter) block --}}

    <script>
        document.getElementById('logoutLink')?.addEventListener('click', function (e) {
            e.preventDefault();
            document.getElementById('logoutForm')?.submit();
        });
    </script>
@endsection
