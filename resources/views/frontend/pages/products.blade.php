@extends('frontend.layouts.app')

@section('title', __('meta.title'))

@section('content')
    <link rel="stylesheet" href="{{ asset('css/toastr.min.css') }}">

    <style>
        /* ===== Fondo de sección ===== */
        .product-section {
            background:#0e0e0e;
            color:#fff;
            padding: 24px 12px 48px;
            overflow-x: hidden; /* evita scroll lateral en móvil */
        }

        /* ===== Contenedor centrado ===== */
        .product-container {
            max-width: 1100px;
            margin: 0 auto;
            padding: 0 16px;
        }

        /* ===== Tarjeta ===== */
        .product-card {
            background:#fff;
            color:#111;
            border-radius: 18px;
            box-shadow: 0 10px 30px rgba(0,0,0,.25);
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 28px;
            padding: 22px;
            align-items: center;
        }

        @media (max-width: 991.98px){
            .product-card {
                grid-template-columns: 1fr;
                padding: 16px;
                gap: 16px;
                border-radius:16px;
                max-width: 520px;
                margin: 0 auto;
            }
        }

        /* ===== Galería ===== */
        .product-media {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
        }

        .media-box {
            width: 100%;
            max-width: 560px;
            border-radius: 14px;
            background: #fff;
            overflow: hidden;
            border: 1px solid #eee;
            margin: 0 auto;
        }

        .media-box img {
            display: block;
            width: 100%;
            height: auto !important;
            object-fit: contain;
        }

        @media (max-width: 767.98px){
            .media-box {
                max-width: 100%;
                max-height: 70vh;
            }
            .media-box img {
                width: 100%;
                height: auto !important;
            }
        }

        /* ===== Info ===== */
        .product-info {
            display: flex;
            flex-direction: column;
            gap: 14px;
        }

        .product-title {
            font-size: 1.45rem;
            font-weight: 800;
            margin: 0;
            color: #1a1a1a;
            line-height: 1.25;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            min-height: 2.5em;
            text-align: left;
        }

        .product-desc {
            font-size: 1rem;
            color: #666;
            line-height: 1.5;
            margin: 0;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            min-height: 3em;
            text-align: left;
        }

        .product-desc:empty::before { content:""; display:block; }

        /* Tipografía más flexible en móviles chicos */
        @media (max-width: 575.98px){
            .product-title {
                font-size: clamp(1.05rem, 3.5vw, 1.25rem);
                min-height: 2.4em;
            }
            .product-desc {
                font-size: .95rem;
                min-height: 2.8em;
            }
        }

        /* ===== Select Presentaciones ===== */
        .select-label {
            font-size: .9rem;
            color: #777;
            margin: 6px 0 4px;
            display: block;
            text-align: left;
        }

        .select-wrap{
            position: relative;
            width: 60%;
            max-width: 440px;
            border-radius: 12px;                 /* redondo real */
            background: #fff;
            box-shadow: inset 0 0 0 1px #e8e6e2; /* borde suave (no usa border) */
        }
        @media (max-width: 991.98px){
            .select-wrap{ width: 100%; max-width: none; }
        }

        /* Flecha del dropdown dibujada por el contenedor */
        .select-wrap::after{
            content: "";
            position: absolute;
            right: 14px;
            top: 50%;
            width: 18px;
            height: 18px;
            transform: translateY(-50%);
            pointer-events: none;
            background: url("data:image/svg+xml;utf8, \
    <svg xmlns='http://www.w3.org/2000/svg' width='18' height='18' viewBox='0 0 24 24' fill='none' stroke='%23999' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'><polyline points='6 9 12 15 18 9'></polyline></svg>") center no-repeat;
        }

        /* El select en sí: sin bordes ni fondos (transparente) */
        .select-wrap .form-select{
            width: 100%;
            min-height: 52px;
            padding: 12px 46px 12px 16px;        /* deja espacio a la flecha */
            border: none !important;             /* ← sin border */
            background: transparent !important;  /* ← sin bg para que se vea el del wrap */
            box-shadow: none !important;         /* ← quita sombras cuadradas */
            border-radius: 12px;                 /* para clipping interno */
            color: #222;
            font-size: 1.02rem;
            font-weight: 400;
            line-height: 1.3;
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
            outline: none;                       /* sin outline cuadrado */
        }
        .select-wrap .form-select:focus,
        .select-wrap .form-select:focus-visible{
            outline: none;                       /* evitar rectángulo por focus */
        }

        /* Anillo de enfoque en el contenedor (opcional, marca dorada) */
        .select-wrap:focus-within{
            box-shadow:
                inset 0 0 0 1px #e8e6e2,
                0 0 0 3px rgba(210,170,109,.25);
        }

        /* SELECT: sin recorte de texto y flecha centrada */
        .form-select {
            width: 100%;
            min-height: 52px;
            line-height: 1.3;
            padding: 12px 46px 12px 16px;
            border: none; /* ✅ quitamos borde base */
            border-radius: 12px; /* ✅ más redondeado */
            background-color: #ffffff;
            box-shadow: 0 0 0 1px #e6e6e6 inset; /* borde suave interior */
            background-image: url("data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='18' height='18' viewBox='0 0 24 24' fill='none' stroke='%23999' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'><polyline points='6 9 12 15 18 9'></polyline></svg>");
            background-repeat: no-repeat;
            background-position: right 14px center;
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
            color: #222;
            font-weight: 400;
            font-size: 1.02rem;
            transition: box-shadow .2s ease;
        }

        .form-select:focus {
            outline: none;
            box-shadow: 0 0 0 2px rgba(210,170,109,.3); /* brillo dorado en foco */
        }
        .form-select {
            border-radius: 10px;
            box-shadow: 0 0 0 1px #ddd inset;
        }
        .form-select option { font-weight: 400; color: #222; }

        /* ===== Precio ===== */
        .product-price {
            font-size: 1.35rem;
            font-weight: 800;
            color: #C0AA83;
            margin: 6px 0 8px;
            text-align: left;           /* 🔹 Izquierda en escritorio */
        }

        @media (max-width: 575.98px) {
            .product-price {
                font-size: 1.15rem;
                margin: 4px 0 6px;
                text-align: center;       /* 🔹 Centrado solo en móvil */
            }
        }
        .qty-control{
            display:flex;
            align-items:center;
            gap:8px;
        }

        .qty-btn{
            display:inline-flex;
            align-items:center;
            justify-content:center;
            width:40px; height:40px;
            border:1px solid #666;
            border-radius:8px;
            background:#fff;
            font-size:20px;
            line-height:1;
            cursor:pointer;
            transition:filter .15s ease, transform .1s ease;
        }
        .qty-btn:hover{ filter:brightness(.96); }
        .qty-btn:active{ transform:scale(.98); }

        .quantity-input{
            width:96px;                 /* un poco más ancho */
            height:40px;
            text-align:center;
            border:1px solid #666 !important;
            border-radius:8px !important;
            background:#ffffff !important;
            color:#000 !important;
            font-size:16px;
            padding:0 8px;
        }

        /* Oculta spinners nativos para no duplicar */
        .quantity-input::-webkit-outer-spin-button,
        .quantity-input::-webkit-inner-spin-button{ -webkit-appearance: none; margin:0; }
        .quantity-input{ -moz-appearance: textfield; }

        /* En móviles muy chicos apilar */
        @media (max-width:420px){
            .quantity-row{ flex-direction:column; align-items:flex-start; gap:6px; }
            .qty-control{ width:100%; }
            .quantity-input{ flex:1; max-width:140px; }
        }

        /* ===== Cantidad ===== */
        .quantity-row {
            display: flex;
            align-items: center;
            gap: 10px;
            margin: 2px 0 10px;
        }

        .quantity-row label {
            font-size: .9rem;
            color: #777;
            font-weight: 400;
            margin-right: 2px;
        }

        .quantity-input {
            width: 84px;
            text-align: center;
            border: 1px solid #666 !important;
            border-radius: 8px !important;
            background: #ffffff !important;
            color: #000 !important;
            font-size: 16px;
            padding: 6px 8px;
            transition: all .2s ease;
        }

        .quantity-input:focus { border-color: #C0AA83; outline: none; }

        @media (max-width: 420px){
            .quantity-row {
                flex-direction: column;
                align-items: flex-start;
                gap: 6px;
            }
            .quantity-input { width: 100%; max-width: 140px; }
        }

        /* ===== Botón carrito ===== */
        .btn-cart {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            width: 100%;
            min-height: 48px;
            padding: 0 16px;
            border: none;
            border-radius: 10px;
            background: #d2aa6d;
            color: #fff;
            font-weight: 500;
            font-size: 1.05rem;
            letter-spacing: .2px;
            transition: transform .12s ease, filter .15s ease, box-shadow .15s ease;
            margin-top: 8px;
        }

        .btn-cart:hover {
            filter: brightness(.95);
            transform: translateY(-1px);
            box-shadow: 0 10px 22px rgba(210,170,109,.25);
        }

        .btn-cart:active {
            transform: translateY(0);
            box-shadow: none;
        }

        .btn-cart i { color: #fff; }

        /* ===== Breadcrumbs/hero ===== */
        .page-header {
            background-image: url('{{ asset('images/inner_parallax.jpg') }}');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            padding: 36px 0;
        }

        .page-header .container h1 { margin: 0; }
        .breadcrumbs { margin-top: 8px; }

        @media (max-width: 575.98px){
            .page-header { padding: 22px 0; }
            .breadcrumbs { margin-top: 6px; }
        }
    </style>

    <header class="page-header like-parallax">
        <div class="container">
            <h1>{{ __('meta.products') }}</h1>
            <ul class="breadcrumbs" typeof="BreadcrumbList" vocab="https://schema.org/">
                <li class="home">
                <span property="itemListElement" typeof="ListItem">
                    <a property="item" typeof="WebPage"
                       title="{{ __('meta.go_to_finca3pinos') }}"
                       href="{{ LaravelLocalization::getLocalizedURL(app()->getLocale(), '/') }}"
                       class="home">
                        <span property="name">{{ __('meta.finca3pinos') }}</span>
                    </a>
                    <meta property="position" content="1">
                </span>
                </li>
                <li class="post post-page current-item">
                <span property="itemListElement" typeof="ListItem">
                    <a property="item" typeof="WebPage"
                       href="{{ LaravelLocalization::getLocalizedURL(app()->getLocale(), '/productos') }}">
                        <span property="name">{{ __('meta.products') }}</span>
                    </a>
                    <meta property="position" content="2">
                </span>
                </li>
            </ul>
        </div>
    </header>

    @if(!$arrayProductos)
        <section class="product-section">
            <div class="product-container">
                <p style="color:#ccc;text-align:center;margin:24px 0">
                    {{ __('meta.product_not_found') }}
                </p>
            </div>
        </section>
    @else
        <section class="product-section">
            <div class="product-container">

                @forelse($arrayProductos as $prod)
                    @php
                        $isArray = is_array($prod);
                        $id             = $isArray ? ($prod['id'] ?? null) : ($prod->id ?? null);
                        $img            = $isArray ? ($prod['imagen'] ?? '') : ($prod->imagen ?? '');
                        $titulo         = $isArray ? ($prod['titulo'] ?? '') : ($prod->titulo ?? '');
                        $desc           = $isArray ? ($prod['descripcion'] ?? '') : ($prod->descripcion ?? '');
                        $precio         = $isArray ? ($prod['precio'] ?? 0) : ($prod->precio ?? 0);
                        $precioFormat   = $isArray ? ($prod['precioFormat'] ?? null) : ($prod->precioFormat ?? null);
                        $presentaciones = $isArray ? ($prod['presentaciones'] ?? []) : ($prod->presentaciones ?? []);
                        $disponible     = $isArray ? ($prod['disponible'] ?? 1) : ($prod->disponible ?? 1);
                    @endphp

                    <div class="product-card">
                        {{-- Media --}}
                        <div class="product-media">
                            <div class="media-box">
                                <img src="{{ asset('storage/archivos/' . $img) }}"
                                     alt="{{ $titulo ?: 'Producto' }}"
                                     onerror="this.src='{{ asset('images/no-image.png') }}'">
                            </div>
                        </div>

                        {{-- Info --}}
                        <div class="product-info">
                            {{-- Título + descripción --}}
                            <h2 class="product-title">{{ $titulo }}</h2>
                            @if(!empty($desc))
                                <p class="product-desc">{{ $desc }}</p>
                            @endif

                            {{-- Presentaciones --}}
                            @if(!empty($presentaciones))
                                <div>
                                    <label class="select-label">{{ __('meta.product_v3') }}</label>
                                    <div class="select-wrap">
                                        <select class="form-select"
                                                id="presentacion_{{ $id ?? 1 }}"
                                                name="presentacion"
                                                data-product="{{ $id ?? 1 }}">
                                            @foreach ($presentaciones as $pres)
                                                @php
                                                    $pIsArray = is_array($pres);
                                                    $pid      = $pIsArray ? ($pres['id'] ?? '') : ($pres->id ?? '');
                                                    $ptitulo  = $pIsArray ? ($pres['titulo'] ?? '') : ($pres->titulo ?? '');
                                                @endphp
                                                <option value="{{ $pid }}">{{ $ptitulo }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            @endif

                            {{-- Precio --}}
                            <div class="product-price">
                                {{ $precioFormat ?? ('$' . number_format((float)$precio, 2)) }}
                            </div>

                            {{-- Si no disponible --}}
                            @if($disponible == 0)
                                <div class="product-unavailable" style="margin-top:6px; color:#fff;
                                 background:#d9534f; border-radius:6px;
                                 padding:6px 12px; display:inline-block;
                                 font-weight:400; font-size:12px;">
                                    {{ __('meta.out_of_stock') }}
                                </div>
                            @endif

                            {{-- Cantidad --}}
                            @if($disponible != 0)
                                <div class="quantity-row">
                                    <label for="cantidad_{{ $id ?? 1 }}">Cantidad</label>
                                    <div class="qty-control">
                                        <button type="button" class="qty-btn" onclick="stepQty({{ $id ?? 1 }}, -1)">−</button>
                                        <input type="number"
                                               id="cantidad_{{ $id ?? 1 }}"
                                               name="cantidad"
                                               min="1" max="100" value="1"
                                               class="quantity-input"
                                               oninput="validarNumero(this)">
                                        <button type="button" class="qty-btn" onclick="stepQty({{ $id ?? 1 }}, 1)">+</button>
                                    </div>
                                </div>

                                {{-- Botón carrito --}}
                                <button type="button" class="btn-cart" onclick="agregarAlCarrito({{ $id ?? 1 }})">
                                    <i class="fa fa-shopping-cart" aria-hidden="true"></i>
                                    {{ __('meta.product_v2') }}
                                </button>
                            @endif

                        </div>
                    </div>
                @empty
                    <p style="color:#ccc;text-align:center;margin:24px 0">
                        {{ __('meta.product_not_found') }}
                    </p>
                @endforelse

            </div>
        </section>
    @endif


    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="{{ asset('js/toastr.min.js') }}"></script>
    <script src="{{ asset('js/axios.min.js') }}"></script>


    {{-- Superior (Newsletter) block --}}
    @include('frontend.partials.superior')

    <script>
        function stepQty(productId, delta){
            const el = document.getElementById(`cantidad_${productId}`);
            let v = parseInt(el.value || "1", 10);
            if (isNaN(v)) v = 1;
            v += delta;
            if (v < 1) v = 1;
            if (v > 100) v = 100;
            el.value = v;
        }

        function validarNumero(input){
            input.value = input.value.replace(/[^0-9]/g, '');
            let v = parseInt(input.value, 10);
            if (isNaN(v) || v < 1) v = 1;
            if (v > 100) v = 100;
            input.value = v;
        }

        const i18n = {
            addToCartMessage:      "{{ __('meta.added_to_cart') }}",
            noAddToCartMessage:         "{{ __('meta.could_not_add_tocart') }}",

        };

        // Agregar al carrito: ejemplo con lectura de presentacion + cantidad
        async function agregarAlCarrito(productId) {
            // botón que disparó la acción
            const btn = event?.currentTarget || document.querySelector(`button[onclick="agregarAlCarrito(${productId})"]`);
            if (btn) { btn.disabled = true; btn.style.opacity = .7; }

            const select = document.getElementById(`presentacion_${productId}`);
            const presentacionId = select ? select.value : null;
            const cantidad = parseInt(document.getElementById(`cantidad_${productId}`)?.value || 1, 10);

            const body = new URLSearchParams();
            body.append('product_id', productId);
            body.append('quantity', isNaN(cantidad) ? 1 : cantidad);
            if (presentacionId) body.append('presentacionId', presentacionId);

            try {
                const res = await fetch(`{{ route('cart.add') }}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                        'Content-Type': 'application/x-www-form-urlencoded;charset=UTF-8',
                    },
                    body
                });

                if (!res.ok) throw new Error('HTTP ' + res.status);

                const data = await res.json();
                if (data?.ok) {
                    document.querySelectorAll('.header-cart-count.count')
                        .forEach(el => el.textContent = data.count);

                    toastr.success(i18n.addToCartMessage);


                } else {
                    toastr.error(i18n.noAddToCartMessage);
                }
            } catch (e) {
                toastr.error(i18n.noAddToCartMessage);
            } finally {
                if (btn) { btn.disabled = false; btn.style.opacity = ''; }
            }
        }
    </script>
@endsection
