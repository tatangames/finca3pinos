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

        /* ===== Address cards ===== */
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

        /* Add new card */
        .add-card {
            display: flex;
            align-items: center;
            justify-content: center;
            border: 1px dashed var(--border);
            background: #fff;
            border-radius: 16px;
            min-height: 120px
        }

        .add-btn {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 10px;
            padding: 20px 10px;
            border: 0;
            background: transparent;
            cursor: pointer
        }

        .add-btn .plus {
            width: 48px;
            height: 48px;
            border-radius: 999px;
            border: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px
        }

        .add-btn span {
            font-weight: 700
        }

        /* Empty */
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
    </style>

    @php
        $active = 'addresses';
        $is = fn ($key) => $active === $key ? 'is-active' : '';
        /** @var \Illuminate\Support\Collection|\Illuminate\Pagination\AbstractPaginator $addresses */
        // Estructura esperada de cada $address:
        // id, title/name, address, reference, department, municipality, is_default (bool), count (int opcional)
    @endphp

    <section class="account-wrap theme-light">
        <div class="account-container">

            {{-- ===== Sidebar ===== --}}
            <aside class="account-sidebar" aria-label="Sidebar">
                <div class="box-head"><h4>{{ __('meta.my_account') }}</h4></div>
                <nav class="account-menu" role="navigation">
                    <a class="{{ $is('orders') }}" href="#"><span
                            class="label"><span>🧾</span><span>{{ __('meta.orders') }}</span></span><span
                            class="hint">→</span></a>
                    <a class="{{ $is('addresses') }}" href="#"><span
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
                <div class="head">
                    <h3>{{ __('meta.addresses') }}</h3>
                </div>

                <div class="address-wrap">
                    @if(isset($addresses) && $addresses->count())
                        <div class="address-grid">

                            @foreach($addresses as $address)
                                @php
                                    $isDefault = (bool)($address->is_default ?? false);
                                    $cardClass = $isDefault ? 'is-default' : '';
                                    $count = $address->count ?? null; // por ejemplo, número de entregas a esa dirección
                                @endphp

                                <div class="address-card {{ $cardClass }}">
                                    <div class="radio">
                                        <form method="POST" action="#" class="form-default">
                                            @csrf
                                            <input type="hidden" name="address_id" value="{{ $address->id }}">
                                            <input class="address-radio" type="radio" name="default_address"
                                                   {{ $isDefault ? 'checked' : '' }} aria-label="Set default">
                                        </form>
                                    </div>

                                    <div class="address-tools" aria-label="Actions">
                                        <a href="#" title="{{ __('meta.edit') }}">✏️</a>
                                        <form method="POST" action="#"
                                              onsubmit="return confirm('{{ __('meta.areyousure') }}')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" title="{{ __('meta.delete') }}">🗑️</button>
                                        </form>
                                    </div>

                                    <h4 class="address-title">
                                        {{ $address->title ?? __('meta.default') }}
                                        @if($count !== null)
                                            <span class="chip">{{ $count }}</span>
                                        @endif
                                    </h4>

                                    <div class="address-body">
                                        @if(!empty($address->address))
                                            <div>{{ $address->address }}</div>
                                        @endif
                                        @if(!empty($address->reference))
                                            <div class="muted">{{ __('meta.reference_point') }}
                                                : {{ $address->reference }}</div>
                                        @endif
                                        <div class="kv"><b>{{ __('meta.department') }}
                                                :</b> {{ $address->department ?? '—' }}</div>
                                        <div class="kv"><b>{{ __('meta.municipality') }}
                                                :</b> {{ $address->municipality ?? '—' }}</div>
                                    </div>
                                </div>
                            @endforeach

                            {{-- Add new --}}
                            <div class="add-card">
                                <a class="add-btn" href="#" aria-label="{{ __('meta.add_new_address') }}">
                                    <div class="plus">＋</div>
                                    <span>{{ __('meta.add_new_address') }}</span>
                                </a>
                            </div>
                        </div>

                        @if(method_exists($addresses, 'links'))
                            <div style="margin-top:16px">
                                {{ $addresses->links() }}
                            </div>
                        @endif
                    @else
                        <div class="empty">
                            <h4>{{ __('meta.no_addresses') }}</h4>
                            <a class="btn btn-primary" href="#">{{ __('meta.add_new_address') }}</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>

    <script>
        // Logout por POST (placeholder)
        document.getElementById('logoutLink')?.addEventListener('click', function (e) {
            e.preventDefault();
            document.getElementById('logoutForm')?.submit();
        });

        // Enviar el formulario al seleccionar una dirección predeterminada
        document.querySelectorAll('.form-default .address-radio').forEach(function (el) {
            el.addEventListener('change', function () {
                this.closest('form').submit();
            });
        });
    </script>

    {{-- Superior (Newsletter) block --}}
    @include('frontend.partials.superior')
@endsection
