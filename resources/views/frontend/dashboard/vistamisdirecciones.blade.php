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
            --pill: #f2f2f4;
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

        /* ===== Address cards ===== */
        .address-wrap {
            padding: 18px 18px 22px;
        }

        .address-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 18px;
        }

        @media (max-width: 900px) {
            .address-grid {
                grid-template-columns: 1fr;
            }
        }

        .address-card {
            position: relative;
            background: #fff;
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: 16px 16px 16px 48px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, .02);
        }

        /* Reset checkbox */
        .address-card .radio {
            position: absolute;
            left: 14px;
            top: 14px;
            width: 18px !important;
            height: 18px !important;
            padding: 0 !important;
            margin: 0 !important;
            background: transparent !important;
            border: 0 !important;
            box-shadow: none !important;
        }

        .address-card .radio::before,
        .address-card .radio::after {
            content: none !important;
        }

        .address-card .radio form {
            display: flex;
            width: 100%;
            height: 100%;
            margin: 0 !important;
            padding: 0 !important;
        }

        .address-title {
            display: flex;
            align-items: center;
            gap: 10px;
            margin: 0 0 8px;
            font-size: 16px;
            font-weight: 800;
        }

        .chip {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 12px;
            font-weight: 800;
            border-radius: 999px;
            background: var(--pill);
            padding: 6px 10px;
        }

        /* ===== Tools (editar / borrar) ===== */
        .address-tools {
            position: absolute;
            right: 10px;
            top: 10px;
            display: flex;
            gap: 8px;
            align-items: center;
        }

        .address-tools form {
            display: inline;
            margin: 0 !important;
            padding: 0 !important;
        }

        .address-tools a,
        .address-tools button {
            padding: 0 !important;
            margin: 0 !important;
            width: 26px !important;
            height: 26px !important;
            border-radius: 8px !important;
            border: 1px solid var(--border) !important;
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            background: #fff !important;
            color: #666 !important;
            line-height: 1 !important;
            box-shadow: none !important;
            text-decoration: none !important;
            cursor: pointer !important;
            min-width: 0 !important;
            appearance: none !important;
            -webkit-appearance: none !important;
            font-size: 14px;
            transition: all .15s ease;
        }

        .address-tools a::before,
        .address-tools button::before {
            content: none !important;
        }

        .address-tools a:hover,
        .address-tools button:hover {
            background: var(--pill) !important;
            color: var(--brand-900) !important;
            transform: scale(1.05);
        }

        .address-body {
            font-size: 14px;
            line-height: 1.5;
            color: #333;
        }

        .muted {
            color: var(--muted);
        }

        .kv {
            margin-top: 6px;
        }

        .kv b {
            font-weight: 800;
        }

        .is-default .address-title::after {
            content: "";
            display: inline-block;
            margin-left: 6px;
            width: 8px;
            height: 8px;
            border-radius: 999px;
            background: var(--success);
        }

        .is-default {
            border-color: rgba(56, 193, 114, .35);
        }

        /* ===== Add new card ===== */
        .add-card {
            display: flex;
            align-items: center;
            justify-content: center;
            border: 1px dashed var(--border);
            background: #fff;
            border-radius: 16px;
            min-height: 120px;
        }

        .add-btn {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 10px;
            padding: 20px 10px;
            border: 0;
            background: transparent;
            cursor: pointer;
            text-decoration: none;
            color: var(--text);
        }

        .add-btn .plus {
            width: 48px;
            height: 48px;
            border-radius: 999px;
            border: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
        }

        .add-btn span {
            font-weight: 700;
        }

        /* ===== Empty state ===== */
        .empty {
            padding: 56px 18px 64px;
            text-align: center;
            color: var(--muted);
        }

        .empty h4 {
            margin: 0 0 10px;
            color: var(--text);
            font-size: 22px;
        }

        /* ===== Buttons ===== */
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
            transition: all .15s ease;
        }

        .btn-primary {
            background: var(--primary);
            color: #fff;
        }

        .btn-primary:hover {
            opacity: 0.9;
        }





        .address-card .radio {
            position: absolute;
            left: 14px;
            top: 14px;
            width: 18px !important;
            height: 18px !important;
            padding: 0 !important;
            margin: 0 !important;
            background: transparent !important;
            border: none !important;
            box-shadow: none !important;
            outline: none !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
        }

        .address-card .radio::before,
        .address-card .radio::after {
            content: none !important;
        }

        .address-card .radio form {
            display: flex;
            width: 100%;
            height: 100%;
            margin: 0 !important;
            padding: 0 !important;
            background: transparent !important;
        }

        /* Input radio limpio */
        .address-card input[type="radio"] {
            width: 18px !important;
            height: 18px !important;
            margin: 0 !important;
            padding: 0 !important;
            background: transparent !important;
            border: none !important;
            outline: none !important;
            accent-color: var(--brand) !important;
            appearance: auto !important;
            -webkit-appearance: auto !important;
            cursor: pointer !important;
            flex-shrink: 0;
        }

        .address-card input[type="radio"]:focus {
            outline: none !important;
            box-shadow: none !important;
        }

        .address-card input[type="radio"]:hover {
            accent-color: var(--brand-900) !important;
        }


</style>


    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="{{ asset('js/toastr.min.js') }}"></script>
    <script src="{{ asset('js/axios.min.js') }}"></script>
    <script src="{{ asset('js/sweetalert2.all.min.js') }}"></script>
    <script src="{{ asset('js/alertaPersonalizada.js') }}"></script>

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
                    <a class="{{ $is('orders') }}" href="{{ LaravelLocalization::getLocalizedURL(app()->getLocale(), route('user.orders', [], false)) }}">
                        <span class="label"><span>🧾</span><span>{{ __('meta.orders') }}</span></span>
                        <span class="hint">→</span>
                    </a>
                    <a class="{{ $is('addresses') }}" href="{{ LaravelLocalization::getLocalizedURL(app()->getLocale(), route('user.address', [], false)) }}">
                        <span class="label"><span>📍</span><span>{{ __('meta.addresses') }}</span></span>
                        <span class="hint">→</span>
                    </a>
                    <a class="{{ $is('profile') }}" href="{{ LaravelLocalization::getLocalizedURL(app()->getLocale(), route('user.view.perfil', [], false)) }}">
                        <span class="label"><span>⚙️</span><span>{{ __('meta.profile') }}</span></span>
                        <span class="hint">→</span>
                    </a>

                    <a href="#" id="logoutLinkPerfil">
                        <span class="label"><span>🚪</span><span>{{ __('meta.logout') }}</span></span><span class="hint">→</span>
                    </a>
                    <form id="logoutForm" method="POST" action="{{ route('user.logout') }}" style="display:none;">
                        @csrf
                    </form>
                </nav>
            </aside>

            {{-- ===== Content (Addresses) ===== --}}
            <div class="account-content" role="region" aria-label="{{ __('meta.addresses') }}">
                <div class="head">
                    <h3>{{ __('meta.addresses') }}</h3>
                </div>

                <div class="address-wrap">
                    @if(isset($arrayDirecciones) && $arrayDirecciones->count())
                        <div class="address-grid">

                            @foreach($arrayDirecciones as $address)
                                @php
                                    $isDefault = (bool)($address->predeterminado ?? false);
                                    $cardClass = $isDefault ? 'is-default' : '';
                                    $count = $address->count ?? null;
                                    $title = $address->nombre ?? ($address->title ?? __('meta.default'));
                                @endphp

                                <div class="address-card {{ $cardClass }}">
                                    <div class="radio">
                                        <form method="POST" action="#" class="form-default">
                                            @csrf
                                            <input type="hidden" name="address_id" value="{{ $address->id }}">
                                            <input
                                                class="address-radio"
                                                type="radio"
                                                name="default_address"
                                                value="{{ $address->id }}"
                                                data-address-id="{{ $address->id }}"
                                                data-is-default="{{ $isDefault ? '1' : '0' }}"
                                                {{ $isDefault ? 'checked' : '' }}
                                                aria-label="Set default">
                                        </form>
                                    </div>

                                    <div class="address-tools" aria-label="Actions">
                                        <a href="{{ LaravelLocalization::getLocalizedURL(app()->getLocale(), route('user.edit.direction', ['iddirection' => $address->id], false)) }}"
                                           title="{{ __('meta.edit') }}"
                                          >✏️</a>

                                        <a href="javascript:void(0)"
                                           title="{{ __('meta.delete') }}"
                                           onclick="eliminarDireccion({{ $address->id }})">🗑️</a>
                                    </div>

                                    <h4 class="address-title">
                                        {{ $title }}
                                        @if($count !== null)
                                            <span class="chip">{{ $count }}</span>
                                        @endif
                                    </h4>

                                    <div class="address-body">
                                        {{-- Texto armado en el controlador según id_paises --}}
                                        {!! $address->textoDireccion !!}
                                    </div>
                                </div>
                            @endforeach

                            {{-- Add new --}}
                            <div class="add-card">
                                <a class="add-btn" href="{{ LaravelLocalization::getLocalizedURL(app()->getLocale(), route('user.new.direction', [], false)) }}" aria-label="{{ __('meta.add_new_address') }}">
                                    <div class="plus">＋</div>
                                    <span>{{ __('meta.add_new_address') }}</span>
                                </a>
                            </div>
                        </div>
                    @else
                        <div class="empty">
                            <h4>{{ __('meta.no_addresses') }}</h4>
                            <a class="btn btn-primary" href="{{ LaravelLocalization::getLocalizedURL(app()->getLocale(), route('user.new.direction', [], false)) }}">{{ __('meta.add_new_address') }}</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>

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

        const DELETE_URL = "{{ LaravelLocalization::getLocalizedURL(app()->getLocale(), route('user.delete.direction', [], false)) }}";

        const MAKE_DEFAULT_URL = "{{ LaravelLocalization::getLocalizedURL(app()->getLocale(), route('user.direction.default', [], false)) }}";
        const CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]')?.content || "{{ csrf_token() }}";

        // Logout por POST (placeholder)
        document.getElementById('logoutLink')?.addEventListener('click', function (e) {
            e.preventDefault();
            document.getElementById('logoutForm')?.submit();
        });

        const i18n = {
            deleteQuestionMessage: "{{ __('meta.delete_question') }}",
            willDeleteMessage: "{{ __('meta.action_will_delete') }}",
            cancelMessage: "{{ __('meta.cancel') }}",
            yesDeleteMessage: "{{ __('meta.yes_delete') }}",
            deleteV2Message: "{{ __('meta.delete_v2') }}", // eliminado
            addressWasDeleteMessage: "{{ __('meta.address_was_detele') }}",
            genericError:         "{{ __('meta.error_v1') }}",
            guardadoMessage:      "{{ __('meta.saved_successfully') }}",
        };

        // Delegación para todos los botones de borrar
        document.querySelectorAll('.address-tools button[title="{{ __('meta.delete_v2') }}"]').forEach(btn => {
            btn.addEventListener('click', function (e) {
                e.preventDefault();

                // obtener id del address (puede venir del atributo data-id o un hidden input)
                const card = this.closest('.address-card');
                const addressId = card?.querySelector('input[name="address_id"]')?.value;

                if (!addressId) return;

                Swal.fire({
                    title: i18n.deleteQuestionMessage,
                    text: i18n.willDeleteMessage,
                    icon: 'info',
                    showCancelButton: true,
                    confirmButtonColor: '#28a745',
                    cancelButtonColor: '#d33',
                    cancelButtonText: i18n.cancelMessage,
                    confirmButtonText: i18n.yesDeleteMessage,
                }).then((result) => {
                    if (result.isConfirmed) {
                        axios.post(DELETE_URL, {
                            id: addressId
                        })
                            .then(res => {
                                if (res.data.success === 1) {
                                    Swal.fire({
                                        title: i18n.deleteV2Message,
                                        text: i18n.addressWasDeleteMessage,
                                        icon: 'success',
                                        timer: 1500,
                                        showConfirmButton: false
                                    });
                                    setTimeout(() => window.location.reload(), 1600);
                                } else {

                                }
                            })
                            .catch(err => {


                            });
                    }
                });
            });
        });

        function eliminarDireccion(iddireccion) {
            Swal.fire({
                title: i18n.deleteQuestionMessage,
                text: i18n.willDeleteMessage,
                icon: 'info',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#d33',
                cancelButtonText: i18n.cancelMessage,
                confirmButtonText: i18n.yesDeleteMessage,
            }).then((result) => {
                if (result.isConfirmed) {
                    borrar(iddireccion)
                }
            });
        }

        function borrar(iddireccion) {

            openLoading()

            axios.post(DELETE_URL, {
                id: iddireccion})
                .then(res => {
                    closeLoading()

                if (res.data.success === 1) {
                    Swal.fire({
                        title: i18n.deleteV2Message,
                        text: i18n.addressWasDeleteMessage,
                        icon: 'success',
                        timer: 1500,
                        showConfirmButton: false
                    });
                    setTimeout(() => window.location.reload(), 1600);
                } else {
                    toastr.error(i18n.genericError);
                }
            }).catch(err => {
                toastr.error(i18n.genericError);
                closeLoading()
            });
        }

    </script>


    <script>

        // El que arranca como predeterminado
        let currentDefaultRadio = document.querySelector('.address-radio[data-is-default="1"]') || null;

        // Helper: marca/desmarca visualmente una tarjeta como default
        function setCardDefault(radio, isDefault) {
            if (!radio) return;
            radio.dataset.isDefault = isDefault ? '1' : '0';
            const card = radio.closest('.address-card');
            card?.classList.toggle('is-default', !!isDefault);
        }

        // Helper: habilitar/deshabilitar todos los radios mientras hay request
        function setRadiosDisabled(disabled) {
            document.querySelectorAll('.address-radio').forEach(r => r.disabled = !!disabled);
        }

        // Listener único (delegado) para cualquier radio
        document.addEventListener('change', function (e) {
            const el = e.target;
            if (!el.matches('.address-radio')) return;

            const wasDefault = el.dataset.isDefault === '1';
            const addressId  = el.dataset.addressId || el.value;

            // Si el usuario re-clickea el actual predeterminado, no hacemos nada
            if (wasDefault) return;

            // Guardamos referencias para poder revertir
            const prev = currentDefaultRadio;
            const next = el;

            // Optimistic UI: actualiza UI mientras probamos el backend
            setCardDefault(prev, false);
            setCardDefault(next, true);

            // A nivel de radios, deja seleccionado el nuevo
            if (prev) prev.checked = false;
            next.checked = true;

            setRadiosDisabled(true);

            axios.post(MAKE_DEFAULT_URL, { id: addressId, _token: CSRF_TOKEN })
                .then(res => {
                    setRadiosDisabled(false);
                    if (res.data?.success === 1) {
                        // Confirmamos cambio
                        currentDefaultRadio = next;
                        toastr?.success(i18n?.guardadoMessage);
                    } else {
                        // REVERTIR: backend dijo que no
                        revertSelection(prev, next);
                        toastr?.error(i18n?.genericError);
                    }
                })
                .catch(() => {
                    setRadiosDisabled(false);
                    // REVERTIR: request falló
                    revertSelection(prev, next);
                    toastr?.error(i18n?.genericError);
                });
        });

        function revertSelection(prev, next) {
            // Vuelve a poner el que era default
            if (prev) {
                prev.checked = true;
                setCardDefault(prev, true);
            }
            // Desmarca el que intentaron seleccionar
            if (next) {
                next.checked = false;
                setCardDefault(next, false);
            }
        }
    </script>





    {{-- Superior (Newsletter) block --}}
    @include('frontend.partials.superior')
@endsection
