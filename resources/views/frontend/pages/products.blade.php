@extends('frontend.layouts.app')

@section('title', __('meta.title'))

@section('content')

    <style>
        /* ===== Sección y contenedor (pegado al top) ===== */
        .vc_section.bg-color-black {
            background: #0e0e0e;
            color: #fff;
            padding: 6px 0 20px !important;
        }

        #products-full .products-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 6px 16px 20px;
        }

        /* ===== Tabs de categorías (compactos) ===== */
        ul.cats.tabs-cats.slider-filter,
        .tabs-cats {
            display: flex;
            justify-content: center;
            gap: 22px;
            margin: 2px auto 10px;
            padding: 0;
            list-style: none;
            text-align: center;
        }

        .tabs-cats li {
            list-style: none;
        }

        .tabs-cats .cat {
            position: relative;
            cursor: pointer;
            color: #fff;
            font-family: 'Kanit', sans-serif;
            font-weight: 300;
            font-size: 18px;
            line-height: 28px;
            letter-spacing: .2px;
            text-transform: none;
            padding-bottom: 2px;
            transition: color .2s;
        }

        .tabs-cats .cat::after {
            content: "";
            position: absolute;
            left: 0;
            bottom: 0;
            width: 0;
            height: 2px;
            background: #C0AA83;
            transition: width .25s;
        }

        .tabs-cats .cat.cat-active::after, .tabs-cats .cat:hover::after {
            width: 100%;
        }

        /* ===== Grid ===== */
        .products-grid {
            display: grid;
            grid-template-columns:repeat(3, 1fr);
            gap: 20px;
        }

       /* .products-grid {
            display: grid;
            place-items: center;          /* 🔹 centra horizontal y verticalmente si hay espacio extra */
            grid-template-columns: repeat(auto-fit, 350px); /* 🔹 mismo ancho del card */
            justify-content: center;      /* 🔹 centra las columnas */
            gap: 20px;
        }*/





        @media (max-width: 1199.98px) {
            .products-grid {
                grid-template-columns:repeat(3, 1fr);
            }
        }

        @media (max-width: 991.98px) {
            .products-grid {
                grid-template-columns:repeat(2, 1fr);
            }
        }

        @media (max-width: 575.98px) {
            .products-grid {
                grid-template-columns:1fr;
            }
        }

        /* ===== Card compacta ===== */
        article.product {
            background: #fff;
            padding: 20px 16px;   /* antes 12px 10px */
            border-radius: 18px;  /* esquinas más suaves */
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        /* Imagen más compacta */
        .product .photo {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 0;
        }

        .product .img-box {
            width: 320px;
            height: 320px;
            max-width: 100%;
            border-radius: 10px;
            background: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 6px;
            overflow: hidden;
        }

        @media (min-width: 1400px) {
            .product .img-box {
                width: 280px;
                height: 280px;
            }
        }

        .product .img-box img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            display: block;
        }

        .product .description {
            padding-top: 6px;
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        /* Título: 2 líneas exactas */
        .product h5{
            font-size:1rem; font-weight:700; color:#1a1a1a; text-align:center;
            margin:0; line-height:1.25;
            display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical; overflow:hidden;
            min-height:2.5em;        /* 1.25 * 2 líneas */
        }

        /* Descripción: 2 líneas exactas (aunque esté vacía mantiene el alto) */
        .product .post_content{
            font-size:0.92rem; color:#666; text-align:center; line-height:1.4; margin:0;
            display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical; overflow:hidden;
            min-height:2.8em;        /* 1.4 * 2 líneas */
        }
        /* si el div viene vacío, mantiene el espacio igualmente */
        .product .post_content:empty::before{ content:""; display:block; }

        /* Select presentaciones compacto */
        .select-label {
            font-size: .85rem;
            color: #777;
            margin: 2px 0 4px;
            display: block;
            text-align: left;
        }

        .form-select {
            width: 100%;
            height: 44px;
            padding: 0 42px 0 12px;
            border-radius: 10px;
            border: 1px solid #dcdcdc;
            background: #fff
            url("data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='18' height='18' viewBox='0 0 24 24' fill='none' stroke='%23999' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'><polyline points='6 9 12 15 18 9'></polyline></svg>")
            no-repeat right 12px center;
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
            color: #222;
            font-weight: 400;  /* 🔹 normal en lugar de bold */
            font-size: 0.95rem; /* 🔹 tamaño cómodo y legible */
        }

        .form-select:focus {
            outline: none;
            border-color: #d2aa6d;
            box-shadow: 0 0 0 2px rgba(210, 170, 109, .15);
        }

        .form-select option {
            font-weight: 400;
            color: #222;
        }

        /* Precio (más chico y pegado) */
        .price {
            font-size: 1.1rem;
            font-weight: 800;
            color: #C0AA83;;
            text-align: center;
            line-height: 1;
            margin: 8px 0 8px;
        }

        /* Botón carrito compacto */
        .btn-cart {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            width: 100%;
            height: 46px;
            padding: 0 16px;
            border: none;
            border-radius: 6px;
            background: #d2aa6d;
            color: #fff;
            font-weight: 500;     /* 🔹 antes era 800 — ahora más suave */
            font-size: 1rem;
            text-transform: none;
            letter-spacing: .2px;
            transition: transform .1s ease, filter .15s ease;
            margin-top: 12px;
        }

        .btn-cart i {
            color: #fff;
        }

        .btn-cart:hover {
            filter: brightness(.95);
            transform: translateY(-1px);
        }

        .btn-cart:active {
            transform: translateY(0);
        }

        /* Bloque categoría */
        .category-block {
            margin-bottom: 26px;
        }

        .category-title {
            display: none;
        }




        /* Bloque que contiene Título + Descripción */
        .product .meta-block{
            display:flex;
            flex-direction:column;
            gap:6px;                 /* separación interna */
            min-height: 100px;       /* asegura mismo alto en todas (ajusta 92–108 si quieres) */
        }







    </style>


    <section id="products-full" data-vc-full-width="true" data-vc-full-width-init="true"
             class="vc_section bg-color-black">
        <div class="vc_row wpb_row vc_row-fluid">
            <div class="wpb_column vc_column_container vc_col-sm-12">
                <div class="vc_column-inner">
                    <div class="wpb_wrapper">

                        <div class="products-container">

                            {{-- ======= Tabs de categorías ======= --}}
                            @if(isset($arrayCategorias) && $arrayCategorias->count())
                                <ul class="tabs-cats" role="tablist">
                                    @foreach($arrayCategorias as $i => $cat)
                                        <li>
                                            <a href="javascript:void(0)"
                                               class="cat {{ $i === 0 ? 'cat-active' : '' }}"
                                               data-cat="{{ $cat->id }}"
                                               role="tab" aria-selected="{{ $i === 0 ? 'true' : 'false' }}">
                                                {{ $cat->titulo }}
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            @endif

                            {{-- ======= Bloques por categoría ======= --}}
                            @if(isset($arrayCategorias) && $arrayCategorias->count())
                                @foreach ($arrayCategorias as $i => $categoria)
                                    <div class="category-block js-cat-block"
                                         data-cat="{{ $categoria->id }}"
                                         style="{{ $i === 0 ? '' : 'display:none' }}">
                                        {{-- Título oculto (dejado por accesibilidad) --}}
                                        <h2 class="category-title">{{ $categoria->titulo }}</h2>

                                        <div class="products-grid">
                                            @foreach ($categoria->productos as $prod)
                                                <article class="product">
                                                    <a class="photo"
                                                       href="{{ LaravelLocalization::getLocalizedURL(app()->getLocale(), '/producto/' . $prod->slug) }}">
                                                        <div class="img-box">
                                                            <img
                                                                src="{{ asset('storage/archivos/' . $prod->imagen) }}"
                                                                alt="{{ $prod->titulo }}"
                                                                onerror="this.src='{{ asset('images/no-image.png') }}'">
                                                        </div>
                                                    </a>

                                                    <div class="description">
                                                        <div class="meta-block">
                                                            <a class="header"
                                                               href="{{ LaravelLocalization::getLocalizedURL(app()->getLocale(), '/producto/' . $prod->slug) }}">
                                                                <h5>{{ $prod->titulo }}</h5>
                                                            </a>

                                                            {{-- deja SIEMPRE el div; si no hay texto, quedará vacío pero con altura fija --}}
                                                            <div class="post_content entry-content">
                                                                {{ $prod->descripcion ?? '' }}
                                                            </div>
                                                        </div>

                                                        {{-- Presentaciones --}}
                                                        <div>
                                                            <label class="select-label">
                                                                {{ __('meta.product_v3') }}
                                                            </label>
                                                            <select class="form-select"
                                                                    name="presentacion"
                                                                    data-product="{{ $prod->id }}">
                                                                @foreach ($prod->presentaciones as $pres)
                                                                    <option value="{{ $pres->id }}">
                                                                        {{ $pres->titulo }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>

                                                        {{-- Precio --}}
                                                        <div class="price">
                                                            {{ $prod->precioFormat }}
                                                        </div>

                                                        {{-- Botón carrito --}}
                                                        <button type="button"
                                                                class="btn-cart"
                                                                onclick="agregarAlCarrito({{ $prod->id }})">
                                                            <i class="fa fa-shopping-cart" aria-hidden="true"></i>
                                                            {{ __('meta.product_v2') }}
                                                        </button>
                                                    </div>
                                                </article>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="category-block">
                                    <p style="text-align:center; color:#ccc; margin: 24px 0 8px;">
                                        {{ __('meta.no_products') ?? 'No hay productos disponibles.' }}
                                    </p>
                                </div>
                            @endif

                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="vc_row-full-width vc_clearfix"></div>

    {{-- Superior (Newsletter) block --}}
    @include('frontend.partials.superior')

    {{-- ===== JS: Tabs + Add to cart placeholder ===== --}}
    <script>
        // Tabs: mostrar/ocultar bloques por categoría
        document.querySelectorAll('.tabs-cats .cat').forEach(tab => {
            tab.addEventListener('click', () => {
                const id = tab.getAttribute('data-cat');

                // activar pill
                document.querySelectorAll('.tabs-cats .cat').forEach(c => c.classList.remove('cat-active'));
                tab.classList.add('cat-active');

                // mostrar solo el bloque correspondiente
                document.querySelectorAll('.js-cat-block').forEach(b => {
                    b.style.display = (b.getAttribute('data-cat') === id) ? '' : 'none';
                });

                // accesibilidad
                document.querySelectorAll('.tabs-cats .cat').forEach(c => c.setAttribute('aria-selected', 'false'));
                tab.setAttribute('aria-selected', 'true');
            });
        });

        // Agregar al carrito (ejemplo: lee la presentación elegida)
        function agregarAlCarrito(productId) {
            const select = document.querySelector(`select.form-select[data-product='${productId}']`);
            const presentacionId = select ? select.value : null;


            console.log('Agregar al carrito:', {productId, presentacionId});
        }
    </script>
@endsection
