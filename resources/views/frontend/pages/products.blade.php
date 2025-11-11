@extends('frontend.layouts.app')

@section('title', __('meta.title'))

@section('content')
    <link rel="stylesheet" href="{{ asset('css/toastr.min.css') }}">

    <style>
        /* ========= TUS ESTILOS (sin cambios) ========= */
        .product-section{background:#0e0e0e;color:#fff;padding:24px 12px 48px;overflow-x:hidden}
        .product-container{max-width:1100px;margin:0 auto;padding:0 16px}
        .product-card{background:#fff;color:#111;border-radius:18px;box-shadow:0 10px 30px rgba(0,0,0,.25);display:grid;grid-template-columns:1fr 1fr;gap:28px;padding:22px;align-items:center}
        @media (max-width:991.98px){.product-card{grid-template-columns:1fr;padding:16px;gap:16px;border-radius:16px;max-width:520px;margin:0 auto}}
        .product-media{display:flex;align-items:center;justify-content:center;width:100%}
        .media-box{width:100%;max-width:560px;border-radius:14px;background:#fff;overflow:hidden;border:1px solid #eee;margin:0 auto}
        .media-box img{display:block;width:100%;height:auto !important;object-fit:contain}
        @media (max-width:767.98px){.media-box{max-width:100%;max-height:70vh}.media-box img{width:100%;height:auto !important}}
        .product-info{display:flex;flex-direction:column;gap:14px}
        .product-title{font-size:1.45rem;font-weight:800;margin:0;color:#1a1a1a;line-height:1.25;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;min-height:2.5em;text-align:left}
        .product-desc{font-size:1rem;color:#666;line-height:1.5;margin:0;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;min-height:3em;text-align:left}
        .product-desc:empty::before{content:"";display:block}
        @media (max-width:575.98px){.product-title{font-size:clamp(1.05rem,3.5vw,1.25rem);min-height:2.4em}.product-desc{font-size:.95rem;min-height:2.8em}}
        .select-label{font-size:.9rem;color:#777;margin:6px 0 4px;display:block;text-align:left}
        .select-wrap{position:relative;width:60%;max-width:440px;border-radius:12px;background:#fff;box-shadow:inset 0 0 0 1px #e8e6e2}
        @media (max-width:991.98px){.select-wrap{width:100%;max-width:none}}
        .select-wrap::after{content:"";position:absolute;right:14px;top:50%;width:18px;height:18px;transform:translateY(-50%);pointer-events:none;background:url("data:image/svg+xml;utf8, <svg xmlns='http://www.w3.org/2000/svg' width='18' height='18' viewBox='0 0 24 24' fill='none' stroke='%23999' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'><polyline points='6 9 12 15 18 9'></polyline></svg>") center no-repeat}
        .select-wrap .form-select{width:100%;min-height:52px;padding:12px 46px 12px 16px;border:none !important;background:transparent !important;box-shadow:none !important;border-radius:12px;color:#222;font-size:1.02rem;font-weight:400;line-height:1.3;-webkit-appearance:none;-moz-appearance:none;appearance:none;outline:none}
        .select-wrap .form-select:focus,.select-wrap .form-select:focus-visible{outline:none}
        .select-wrap:focus-within{box-shadow:inset 0 0 0 1px #e8e6e2,0 0 0 3px rgba(210,170,109,.25)}
        .form-select{width:100%;min-height:52px;line-height:1.3;padding:12px 46px 12px 16px;border:none;border-radius:12px;background-color:#fff;box-shadow:0 0 0 1px #e6e6e6 inset;background-image:url("data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='18' height='18' viewBox='0 0 24 24' fill='none' stroke='%23999' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'><polyline points='6 9 12 15 18 9'></polyline></svg>");background-repeat:no-repeat;background-position:right 14px center;-webkit-appearance:none;-moz-appearance:none;appearance:none;color:#222;font-weight:400;font-size:1.02rem;transition:box-shadow .2s ease}
        .form-select:focus{outline:none;box-shadow:0 0 0 2px rgba(210,170,109,.3)}
        .form-select{border-radius:10px;box-shadow:0 0 0 1px #ddd inset}
        .form-select option{font-weight:400;color:#222}
        .product-price{font-size:1.35rem;font-weight:800;color:#C0AA83;margin:6px 0 8px;text-align:left}
        @media (max-width:575.98px){.product-price{font-size:1.15rem;margin:4px 0 6px;text-align:center}}
        .qty-control{display:flex;align-items:center;gap:8px}
        .qty-btn{display:inline-flex;align-items:center;justify-content:center;width:40px;height:40px;border:1px solid #666;border-radius:8px;background:#fff;font-size:20px;line-height:1;cursor:pointer;transition:filter .15s ease,transform .1s ease}
        .qty-btn:hover{filter:brightness(.96)}
        .qty-btn:active{transform:scale(.98)}
        .quantity-input{width:96px;height:40px;text-align:center;border:1px solid #666 !important;border-radius:8px !important;background:#fff !important;color:#000 !important;font-size:16px;padding:0 8px}
        .quantity-input::-webkit-outer-spin-button,.quantity-input::-webkit-inner-spin-button{-webkit-appearance:none;margin:0}
        .quantity-input{-moz-appearance:textfield}
        @media (max-width:420px){.quantity-row{flex-direction:column;align-items:flex-start;gap:6px}.qty-control{width:100%}.quantity-input{flex:1;max-width:140px}}
        .quantity-row{display:flex;align-items:center;gap:10px;margin:2px 0 10px}
        .quantity-row label{font-size:.9rem;color:#777;font-weight:400;margin-right:2px}
        .quantity-input{width:84px;text-align:center;border:1px solid #666 !important;border-radius:8px !important;background:#fff !important;color:#000 !important;font-size:16px;padding:6px 8px;transition:all .2s ease}
        .quantity-input:focus{border-color:#C0AA83;outline:none}
        @media (max-width:420px){.quantity-row{flex-direction:column;align-items:flex-start;gap:6px}.quantity-input{width:100%;max-width:140px}}
        .btn-cart{display:inline-flex;align-items:center;justify-content:center;gap:10px;width:100%;min-height:48px;padding:0 16px;border:none;border-radius:10px;background:#d2aa6d;color:#fff;font-weight:500;font-size:1.05rem;letter-spacing:.2px;transition:transform .12s ease,filter .15s ease,box-shadow .15s ease;margin-top:8px}
        .btn-cart:hover{filter:brightness(.95);transform:translateY(-1px);box-shadow:0 10px 22px rgba(210,170,109,.25)}
        .btn-cart:active{transform:translateY(0);box-shadow:none}
        .btn-cart i{color:#fff}

        /* ========= HEADER COMPACTO LOCAL ========= */
        /* NO usamos 'like-parallax' para evitar estilos globales del tema */
        .page-header--compact{
            height:25px !important;             /* ← ajusta a tu gusto */
            padding:0 !important;
            display:flex !important;
            align-items:center !important;
            background-size:cover;
            background-position:center;
            background-repeat:no-repeat;
            position:relative;
        }

        /* Si el tema crea altura con ::before/::after, lo anulamos */
        .page-header--compact::before,
        .page-header--compact::after{content:none !important;display:none !important}

        /* Contenido centrado verticalmente */
        .page-header--compact > .container{
            height:25%;
            display:flex;
            flex-direction:column;
            justify-content:center;
            padding:0 !important;
            margin:0;
        }

        .page-header--compact h1{
            margin:0 0 6px !important;
            font-size:2rem;                      /* tamaño del título */
            line-height:1.2;
        }
        .page-header--compact .breadcrumbs{margin:0 !important}

        /* Responsive: header un poco menor en móviles */
        @media (max-width:575.98px){
            .page-header--compact{height:120px !important}
            .page-header--compact h1{font-size:1.6rem}
        }

        /* ===== Compactar tarjeta/producto en desktop (opcional) ===== */
        @media (min-width:992px){
            .product-container{max-width:920px}
            .product-card{max-width:880px;margin:0 auto;gap:22px;padding:18px}
            .media-box{max-width:480px}
        }


        /* ——— Header compacto con máxima prioridad ——— */
        #heroProducts.page-header--compact{
            height: 140px !important;
            min-height: 0 !important;
            max-height: 140px !important;
            padding: 0 !important;
            margin: 0 !important;
            display: flex !important;
            align-items: center !important;
            background-size: cover !important;
            background-position: center !important;
            background-repeat: no-repeat !important;
            position: relative !important;
            overflow: clip; /* evita colapsos y márgenes raros */
        }

        /* Muchos temas inflan con ::before/::after */
        #heroProducts.page-header--compact::before,
        #heroProducts.page-header--compact::after{
            content: none !important;
            display: none !important;
        }

        /* Evita padding interno heredado */
        #heroProducts.page-header--compact > .container{
            height: 100% !important;
            display: flex !important;
            flex-direction: column !important;
            justify-content: center !important;
            padding: 0 !important;
            margin: 0 !important;
        }

        /* Evita que el margen del H1 “empuje” la altura */
        /* ===== Título del hero más pequeño, liviano y con margen ===== */
        #heroProducts.page-header--compact h1{
            margin: 0 0 8px 24px !important;   /* ← margen izquierdo agregado */
            color: #fff;
            font-weight: 200;                  /* menos grueso */
            line-height: 1.1;
            font-size: clamp(24px, 4vw, 38px); /* más pequeño */
            letter-spacing: 0;
            text-shadow: 0 1px 2px rgba(0,0,0,.25);
        }

        /* breadcrumbs también alineadas con el margen del título */



        /* Miga de pan sin margen extra */
        #heroProducts.page-header--compact .breadcrumbs{
            margin-left: 24px !important;      /* alinea con el título */
        }

        /* (opcional) en móvil aún más bajo */
        @media (max-width: 575.98px){
            #heroProducts.page-header--compact{ height: 120px !important; max-height:120px !important; }
        }

        /* ===== HERO COMPACTO CON ESTILO ===== */
        #heroProducts.page-header--compact{
            /* altura controlada */
            height: 180px !important;         /* ← ajusta 160–200 según quieras */
            min-height: 0 !important;
            max-height: 180px !important;

            /* reset y layout */
            padding: 0 !important;
            margin: 0 !important;
            display: flex !important;
            align-items: center !important;
            position: relative !important;
            overflow: clip;

            /* fondo */
            background-size: cover !important;
            background-position: center !important;
            background-repeat: no-repeat !important;
        }

        /* Overlay para recuperar contraste del texto sobre la foto */
        #heroProducts.page-header--compact::after{
            content:"";
            position:absolute; inset:0;
            background: linear-gradient(
                to bottom,
                rgba(0,0,0,.45),
                rgba(0,0,0,.45)
            );
            pointer-events:none;
        }

        /* Contenido centrado verticalmente */
        #heroProducts.page-header--compact > .container{
            position: relative;              /* por encima del overlay */
            z-index: 1;
            height: 100%;
            display: flex; flex-direction: column;
            justify-content: center;
            padding: 0 16px !important;
        }

        /* Título grande y pesado como en el diseño */
        #heroProducts.page-header--compact h1{
            margin: 0 0 10px !important;
            color: #fff;
            font-weight: 900;
            line-height: 1.05;
            font-size: clamp(32px, 6vw, 56px); /* grande en desktop, adaptable en móvil */
            letter-spacing: .2px;
            text-shadow: 0 1px 2px rgba(0,0,0,.25);
        }

        /* ===== Breadcrumbs estilo imagen ===== */
        #heroProducts.page-header--compact .breadcrumbs{
            margin: 0 !important;
            padding: 0;
            list-style: none;
            display: flex; flex-wrap: wrap; align-items: center;
            gap: 12px;
            font-size: clamp(16px, 2.2vw, 28px); /* grande como en la captura */
            font-weight: 800;
        }

        /* items */
        #heroProducts.page-header--compact .breadcrumbs li{
            display: inline-flex; align-items: center;
            color: #fff;
        }

        /* enlace “Finca 3 Pinos” en dorado de marca */
        #heroProducts.page-header--compact .breadcrumbs a{
            color: #d2aa6d; text-decoration: none;
        }

        /* separador › */
        #heroProducts.page-header--compact .breadcrumbs li + li::before{
            content: "›";
            margin: 0 10px;
            color: rgba(255,255,255,.8);
            font-weight: 700;
        }

        /* último item en blanco */
        #heroProducts.page-header--compact .breadcrumbs .current-item span[property="name"]{
            color: #fff;
        }

        /* Responsive: un poco más bajo en móvil si quieres */
        @media (max-width: 575.98px){
            #heroProducts.page-header--compact{
                height: 150px !important;
                max-height: 150px !important;
            }
            #heroProducts.page-header--compact .breadcrumbs{
                gap: 8px;
            }
        }



        /* ===== HERO COMPACTO — DEFINITIVO (pegar al FINAL) ===== */
        #heroProducts{
            height: 160px !important;             /* ← ajusta 140–200 según gusto */
            padding: 0 !important;
            margin: 0 !important;
            display: flex !important;
            align-items: center !important;
            position: relative !important;
            background-position: center !important;
            background-size: cover !important;
            background-repeat: no-repeat !important;
            overflow: clip;
        }

        /* anula pseudoelementos/parallax del tema */
        #heroProducts::before,
        #heroProducts::after{ content: none !important; display: none !important; }

        /* overlay suave para contraste */
        #heroProducts::after{
            content:"";
            position:absolute; inset:0;
            background: rgba(0,0,0,.35);
            display:block;
        }

        /* contenido centrado y con sangría a la izquierda */
        #heroProducts .container{
            position: relative;
            z-index: 1;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 0 !important;
            margin-left: 125px;   /* ← aquí controlas el margen a la izquierda */
        }

        /* Título: más pequeño y menos bold */
        #heroProducts h1{
            margin: 0 0 10px !important;
            color: #fff;
            font-weight: 700;
            line-height: 1.1;
            font-size: clamp(28px, 5vw, 42px);   /* tamaño final del título */
        }

        /* Breadcrumbs como en tu imagen */
        #heroProducts .breadcrumbs{
            margin: 0 !important;
            padding: 0;
            list-style: none;
            display: flex; align-items: center; gap: 12px;
            font-weight: 700;
            font-size: clamp(14px, 2vw, 22px);
            color: #fff;
        }
        #heroProducts .breadcrumbs a{ color:#d2aa6d; text-decoration:none; }
        #heroProducts .breadcrumbs li + li::before{
            content:"›"; margin: 0 10px; color: rgba(255,255,255,.9); font-weight:700;
        }

        @media (max-width:575.98px){
            #heroProducts{ height: 140px !important; }
        }


        .product-price {
            display: inline-block;
            background: #fff7e8;
            color: #b6894c;
            font-weight: 700;
            font-size: 1.2rem;
            padding: 5px 6px;           /* ← antes 5px 10px: menos ancho */
            border-radius: 8px;
            border: 1px solid #f0e2c8;
            box-shadow: 0 1px 4px rgba(0,0,0,.08);
            letter-spacing: .2px;
            line-height: 1;
            transition: transform .2s ease, box-shadow .2s ease;
        }
        .product-price:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 10px rgba(0,0,0,.12);
        }

        @media (max-width:575.98px){
            .product-price {
                font-size: 1rem;
                padding: 4px 5px;       /* ← también más estrecho en móvil */
                border-radius: 6px;
            }
        }

        /* === Versión compacta de los selectores de cantidad === */
        .qty-control {
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .qty-btn {
            width: 35px;         /* antes 40px */
            height: 35px;        /* antes 40px */
            font-size: 16px;     /* más pequeño */
            border-radius: 6px;  /* esquinas más compactas */
            border: 1px solid #666;
            background: #fff;
            cursor: pointer;
            transition: all 0.15s ease;
        }

        .qty-btn:hover { filter: brightness(.96); }
        .qty-btn:active { transform: scale(.96); }

        .quantity-input {
            width: 65px;         /* antes 84px */
            height: 35px;        /* antes 40px */
            font-size: 15px;
            border-radius: 6px !important;
            border: 1px solid #666 !important;
            text-align: center;
            background: #fff !important;
            color: #000 !important;
            padding: 0 6px;
            transition: all 0.2s ease;
        }

        .quantity-input:focus {
            border-color: #C0AA83;
            outline: none;
        }

        .quantity-row {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .quantity-row label {
            font-size: .9rem;
            color: #777;
        }

        /* === Precio compacto y sin estirar === */
        .product-info .product-price{
            display: inline-flex !important;   /* evita block/100% */
            align-items: center;
            flex: 0 0 auto !important;         /* no crecer en flex */
            align-self: flex-start;            /* no estirar a lo ancho */
            width: auto !important;
            max-width: max-content;
            white-space: nowrap;               /* no saltos de línea */

            background: #fff7e8;
            color: #b6894c;
            font-weight: 700;
            font-size: 1.15rem !important;     /* más pequeño */
            line-height: 1;
            padding: 4px 6px !important;       /* menos ancho */
            border-radius: 6px !important;
            border: 1px solid #f0e2c8;
            box-shadow: 0 1px 4px rgba(0,0,0,.08);
            margin: 6px 0 8px !important;
        }

        .product-info .product-price:hover{
            transform: translateY(-1px);
            box-shadow: 0 4px 10px rgba(0,0,0,.12);
        }


    </style>

    <!-- ===== HEADER (sin like-parallax) ===== -->
    <header id="heroProducts"
            style="background-image:url('{{ asset('images/inner_parallax.jpg') }}');">
        <div class="container">
            <h1>{{ __('meta.products')}}</h1>
            <ul class="breadcrumbs" typeof="BreadcrumbList" vocab="https://schema.org/">
                <li class="home">
        <span property="itemListElement" typeof="ListItem">
          <a property="item" typeof="WebPage"
             href="{{ LaravelLocalization::getLocalizedURL(app()->getLocale(), '/') }}">
            <span property="name">{{ __('meta.finca3pinos')}}</span>
          </a>
          <meta property="position" content="1">
        </span>
                </li>
                <li class="post post-page current-item">
        <span property="itemListElement" typeof="ListItem">
          <span property="name">{{ __('meta.products') }}</span>
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
                        $isArray        = is_array($prod);
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
                            @if (!empty($presentaciones))
                                <div>
                                    <label class="select-label">{{ __('meta.product_v3') }}</label>
                                    <div class="select-wrap">
                                        <select class="form-select"
                                                id="presentacion_{{ $id }}"
                                                name="presentacion"
                                                data-product="{{ $id }}">
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

                            {{-- No disponible --}}
                            @if($disponible == 0)
                                <div class="product-unavailable" style="margin-top:6px; color:#fff;
                                     background:#d9534f; border-radius:6px;
                                     padding:6px 12px; display:inline-block;
                                     font-weight:400; font-size:12px;">
                                    {{ __('meta.out_of_stock') }}
                                </div>
                            @endif

                            {{-- Cantidad + botón (solo si disponible) --}}
                            @if($disponible != 0 && $id)
                                <div class="quantity-row">
                                    <label for="cantidad_{{ $id }}">Cantidad</label>
                                    <div class="qty-control">
                                        <button type="button" class="qty-btn" onclick="stepQty({{ $id }}, -1)">−</button>
                                        <input type="number"
                                               id="cantidad_{{ $id }}"
                                               name="cantidad"
                                               min="1" max="100" value="1"
                                               class="quantity-input"
                                               oninput="validarNumero(this)">
                                        <button type="button" class="qty-btn" onclick="stepQty({{ $id }}, 1)">+</button>
                                    </div>
                                </div>

                                <button type="button"
                                        class="btn-cart"
                                        onclick="agregarAlCarrito({{ $id }}, this)">
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

    <script>
        function stepQty(productId, delta) {
            const el = document.getElementById(`cantidad_${productId}`);
            let v = parseInt(el?.value || "1", 10);
            if (isNaN(v)) v = 1;
            v += delta;
            if (v < 1) v = 1;
            if (v > 100) v = 100;
            el.value = v;
        }

        function validarNumero(input) {
            input.value = input.value.replace(/[^0-9]/g, '');
            let v = parseInt(input.value, 10);
            if (isNaN(v) || v < 1) v = 1;
            if (v > 100) v = 100;
            input.value = v;
        }

        const i18n = {
            addToCartMessage: "{{ __('meta.added_to_cart') }}",
            noAddToCartMessage: "{{ __('meta.could_not_add_tocart') }}",
        };

        async function agregarAlCarrito(productId, btnEl) {
            const btn = btnEl || null;

            if (btn) {
                btn.disabled = true;
                btn.style.opacity = 0.7;
            }

            // Presentación
            const select = document.getElementById(`presentacion_${productId}`);
            let presentacionId = null;

            if (select) {
                const val = select.value;
                if (val !== '' && val !== null && typeof val !== 'undefined') {
                    presentacionId = val;
                }
            }

            // Cantidad
            const inputQty = document.getElementById(`cantidad_${productId}`);
            const cantidad = parseInt(inputQty ? inputQty.value : 1, 10);
            const qty = isNaN(cantidad) || cantidad <= 0 ? 1 : cantidad;

            const body = new URLSearchParams();
            body.append('product_id', productId);
            body.append('quantity', qty);
            if (presentacionId) {
                body.append('presentacionId', presentacionId);
            }

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

                const data = await res.json().catch(() => ({}));

                if (!res.ok || !data?.ok) {
                    toastr.error(data?.message || i18n.noAddToCartMessage);
                    return;
                }

                document.querySelectorAll('.header-cart-count.count')
                    .forEach(el => el.textContent = data.count);

                toastr.success(i18n.addToCartMessage);

            } catch (e) {
                toastr.error(i18n.noAddToCartMessage);
            } finally {
                if (btn) {
                    btn.disabled = false;
                    btn.style.opacity = '';
                }
            }
        }
    </script>


@endsection
