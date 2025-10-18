@extends('frontend.layouts.app')

@section('title', __('meta.title'))

@section('content')

    <header class="page-header like-parallax"
            style="background-image: url('{{ asset('images/inner_parallax.jpg') }}');
               background-size: cover;
               background-position: center;
               background-repeat: no-repeat;">
        <div class="container" bis_skin_checked="1"><h1>{{ __('meta.our_coffee') }}</h1>
            <ul class="breadcrumbs" typeof="BreadcrumbList" vocab="https://schema.org/">
                <li class="home"><span property="itemListElement" typeof="ListItem"><a property="item" typeof="WebPage"
                                                                                       title="{{ __('meta.go_to_finca3pinos') }}"
                                                                                       href="{{ LaravelLocalization::getLocalizedURL(app()->getLocale(), '/') }}"
                                                                                       class="home"
                                                                                       bis_skin_checked="1">
                            <span property="name">{{ __('meta.finca3pinos') }}</span></a><meta property="position" content="1"></span></li>
                <li class="post post-page current-item"><span property="itemListElement" typeof="ListItem"><span
                            property="name">{{ __('meta.our_coffee') }}</span><meta property="position" content="2"></span></li>
            </ul>
        </div>
    </header>


    <section class="geisha-section" aria-labelledby="geisha-title">
        <style>
            .geisha-section{
                --bg:#0e0e0e;
                --text:#f7f5f2;
                --muted:#c7c2b8;
                --brand:#b98a3b;
                --brand-2:#e3c27a;
                --card:#161616;
                --line:rgba(255,255,255,.12);
                background:linear-gradient(180deg,#0b0b0b,#121212 55%,#0b0b0b);
                color:var(--text);
                position:relative;
                overflow:hidden;
            }

            .geisha-section .geisha-wrap{
                max-width:1200px;
                margin-inline:auto;
                padding:56px 20px;
            }

            /* Typography */
            .geisha-section h1,
            .geisha-section h2,
            .geisha-section h3{
                line-height:1.1;
                margin:0 0 .6em;
            }
            .geisha-section .eyebrow{
                letter-spacing:.15em;
                text-transform:uppercase;
                font-weight:700;
                color:var(--brand);
                font-size:.82rem;
            }
            .geisha-section .title-xl{
                font-size:clamp(1.8rem,3vw+1rem,3rem);
                text-wrap:balance;
            }
            .geisha-section .lead{
                font-size:1.1rem;
                color:var(--muted);
                max-width:62ch;
            }
            .geisha-section .muted{color:var(--muted)}

            /* Layout */
            .geisha-section .grid{display:grid;gap:28px}
            .geisha-section .grid-2{
                grid-template-columns:1.1fr .9fr;
                align-items:stretch;
            }
            @media (max-width:960px){
                .geisha-section .grid-2{grid-template-columns:1fr}
            }
            .geisha-section .cols-3{
                display:grid;
                grid-template-columns:repeat(3,1fr);
                gap:20px;
            }
            @media (max-width:1024px){
                .geisha-section .cols-3{grid-template-columns:1fr}
            }

            /* Media (hero) */
            .geisha-section .media{
                position:relative;
                border-radius:18px;
                overflow:hidden;
                border:none;
                background:#0b0b0b;
                margin:0;
                aspect-ratio:1/1;
                box-shadow:0 10px 30px rgba(0,0,0,.45);
            }
            @media (min-width:961px){
                .geisha-section .media{
                    aspect-ratio:auto;
                    height:100%;
                    min-height:420px;
                }
            }
            .geisha-section .media picture,
            .geisha-section .media img{
                position:absolute;
                inset:-1px;
                width:calc(100% + 2px);
                height:calc(100% + 2px);
                display:block;
            }
            .geisha-section .media img{
                object-fit:cover;
                object-position:50% 60%;
            }

            /* Divider */
            .geisha-section .divider{
                height:1px;
                background:var(--line);
                margin:36px 0;
            }

            /* Prose */
            .geisha-section .prose{color:var(--text)}
            .geisha-section .prose p{margin:.75rem 0}
            .geisha-section .prose ul{list-style:none;padding:0;margin:.75rem 0}
            .geisha-section .prose li{
                padding-left:1.6rem;
                position:relative;
                margin:.4rem 0;
            }
            .geisha-section .prose li:before{
                content:"";
                position:absolute;
                left:0;
                top:.35rem;
                width:10px;
                height:10px;
                border-radius:50%;
                background:linear-gradient(135deg,var(--brand),var(--brand-2));
            }

            /* Cards */
            .geisha-section .card{
                background:var(--card);
                border:1px solid var(--line);
                border-radius:16px;
                padding:20px;
                transition:transform .25s ease,border-color .25s ease;
            }
            .geisha-section .card:hover{
                transform:translateY(-2px);
                border-color:var(--brand-2);
            }

            /* Stats */
            .geisha-section .stats{
                display:grid;
                grid-template-columns:repeat(4,1fr);
                gap:14px;
            }
            @media (max-width:960px){
                .geisha-section .stats{grid-template-columns:repeat(2,1fr)}
            }
            .geisha-section .stat h3{
                font-size:2rem;
                color:var(--brand-2);
                margin:0;
            }
            .geisha-section .stat p{
                margin:.25rem 0 0;
                color:var(--muted);
            }

            /* Feature list */
            .geisha-section .features{
                display:grid;
                grid-template-columns:repeat(2,1fr);
                gap:16px;
            }
            @media (max-width:740px){
                .geisha-section .features{grid-template-columns:1fr}
            }
            .geisha-section .feat{
                display:grid;
                grid-template-columns:auto 1fr;
                gap:12px;
                align-items:flex-start;
            }
            .geisha-section .feat svg{
                width:28px;
                height:28px;
                color:var(--brand-2);
                filter:drop-shadow(0 0 10px rgba(233,202,120,.25));
            }

            /* Buttons */
            .geisha-section .cta{
                display:flex;
                gap:12px;
                flex-wrap:wrap;
                margin-top:16px;
            }
            .geisha-section .btn{
                appearance:none;
                border:1px solid var(--brand);
                background:transparent;
                color:var(--text);
                padding:10px 16px;
                border-radius:999px;
                font-weight:600;
                cursor:pointer;
                transition:.25s transform,.25s background,.25s border-color;
                text-decoration:none;
                display:inline-flex;
                align-items:center;
                justify-content:center;
            }
            .geisha-section .btn:hover{
                transform:translateY(-2px);
                border-color:var(--brand-2);
                background:rgba(185,138,59,.1);
            }
            .geisha-section .btn.primary{
                background:linear-gradient(135deg,var(--brand),var(--brand-2));
                color:#121212;
                border:none;
            }

            /* Badges / chips */
            .geisha-section .badge{
                display:inline-flex;
                gap:8px;
                align-items:center;
                border:1px solid var(--line);
                padding:8px 12px;
                border-radius:12px;
                color:var(--muted);
                font-size:.9rem;
            }
            .geisha-section .pill{
                display:inline-block;
                background:rgba(233,202,120,.1);
                border:1px solid var(--brand-2);
                padding:6px 10px;
                border-radius:999px;
                color:var(--brand-2);
                font-weight:700;
                font-size:.75rem;
                letter-spacing:.08em;
            }
            .geisha-section .chip{
                display:inline-flex;
                align-items:center;
                gap:10px;
                background:#151515;
                border:1px solid var(--line);
                border-radius:999px;
                padding:8px 12px;
                color:var(--muted);
            }

            /* Map/thumb frames */
            .geisha-section .map{
                aspect-ratio:2.2/1;
                border-radius:16px;
                overflow:hidden;
                border:1px solid var(--line);
            }
            .geisha-section .map img{
                width:100%;
                height:100%;
                object-fit:cover;
            }

            /* MAPA + CARD LADO A LADO */
            .geisha-section .grid-2 { align-items: stretch; }
            .geisha-section .map.is-stretch { height:100%; aspect-ratio:auto; }
            .geisha-section .card.is-stretch {
                height:100%;
                display:flex;
                flex-direction:column;
                justify-content:center;
            }
            @media (max-width:960px){
                .geisha-section .map.is-stretch { height:auto; aspect-ratio:2.2/1; }
                .geisha-section .card.is-stretch { height:auto; }
            }

            /* Motion safe */
            @media (prefers-reduced-motion:reduce){
                .geisha-section .card:hover{transform:none}
            }

            /* Fine-tune hero focus on tablets */
            @media (min-width:641px) and (max-width:1279px){
                .geisha-section .media img{object-position:50% 58%}
            }

            /* Mantener orden invertido y alinear arriba */
            .geisha-section .grid-2.reverse{
                direction: rtl;
                align-items: start;
            }
            .geisha-section .grid-2.reverse > *{ direction: ltr; }

            /* 🔧 Fix: los <figure> traen margin-top/bottom por defecto */
            .geisha-section .map{
                margin: 0;          /* elimina el 1em por defecto */
                display: block;     /* asegura layout consistente */
                align-self: start;  /* fuerza tope en la celda */
            }

            /* Asegura que el card también quede al tope */
            .geisha-section .card.is-stretch{ align-self: start; }

        </style>




        <div class="geisha-wrap">

            <!-- HERO / INTRO -->
            <header class="grid grid-2">
                <div>
                    <span class="eyebrow">{{ __('meta.coffee_v1') }}</span>
                    <h1 id="geisha-title" class="title-xl">{{ __('meta.coffee_v2') }}</h1>
                    <p class="lead">
                        {{ __('meta.coffee_v3') }}
                    </p>
                    <div class="cta">
                        <a href="" class="btn primary">{{ __('meta.coffee_v19') }}</a>
                    </div>
                    <div class="badge" style="margin-top:14px">
                        <span class="pill">{{ __('meta.coffee_v4') }}</span>
                        <span>{{ __('meta.coffee_v20') }}</span>
                    </div>
                </div>

                <figure class="media" aria-label="{{ __('meta.coffee_v21') }}">
                    <picture>
                        <!-- Móvil pequeño -->
                        <source
                            media="(max-width: 640px)"
                            srcset="{{ asset('images/finca3pinos/montana-640.webp') }}"
                            type="image/webp" />

                        <!-- Tablet / móvil grande -->
                        <source
                            media="(max-width: 1024px)"
                            srcset="{{ asset('images/finca3pinos/montana-1024.webp') }}"
                            type="image/webp" />

                        <!-- Laptop / escritorio estándar -->
                        <source
                            media="(max-width: 1366px)"
                            srcset="{{ asset('images/finca3pinos/montana-1366.webp') }}"
                            type="image/webp" />

                        <!-- Desktop grande -->
                        <source
                            media="(max-width: 1920px)"
                            srcset="{{ asset('images/finca3pinos/montana-1920.webp') }}"
                            type="image/webp" />

                        <!-- Ultra wide / 4K -->
                        <source
                            media="(min-width: 1921px)"
                            srcset="{{ asset('images/finca3pinos/montana-2560.webp') }}"
                            type="image/webp" />

                        <!-- Fallback (por compatibilidad) -->
                        <img
                            src="{{ asset('images/finca3pinos/montana-1920.webp') }}"
                            alt="{{ __('meta.coffee_v22') }}"
                            fetchpriority="high"
                            loading="eager"
                            decoding="async"
                            sizes="100vw"
                            style="object-fit: cover; object-position: 50% 60%;" />
                    </picture>
                </figure>

            </header>

            <div class="divider" role="separator" aria-hidden="true"></div>

            <!-- ORIGEN Y PRESTIGIO + PERFIL SENSORIAL -->
            <section class="grid grid-2">
                <article class="card prose">
                    <span class="eyebrow">{{ __('meta.coffee_v23') }}</span>
                    <h2>{{ __('meta.coffee_v24') }}</h2>
                    <ul>
                        <li>{{ __('meta.coffee_v25') }}</li>
                        <li>{{ __('meta.coffee_v26') }} <em>{{ __('meta.coffee_v27') }}</em>.</li>
                        <li>{{ __('meta.coffee_v28') }}</li>
                    </ul>
                </article>
                <article class="card prose">
                    <span class="eyebrow">{{ __('meta.coffee_v15') }}</span>
                    <h2>{{ __('meta.coffee_v29') }}</h2>
                    <ul>
                        <li>{{ __('meta.coffee_v30') }}</li>
                        <li>{{ __('meta.coffee_v31') }}</li>
                        <li>{{ __('meta.coffee_v32') }}</li>
                        <li>{{ __('meta.coffee_v33') }}</li>
                    </ul>
                </article>
            </section>

            <!-- EXPERIENCIA Y TECNOLOGÍA -->
            <section class="card" style="margin-top:20px">
                <span class="eyebrow">{{ __('meta.coffee_v34') }}</span>
                <h2>{{ __('meta.coffee_v35') }}</h2>
                <div class="features" style="margin-top:10px">
                    <div class="feat">
                        <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                            <path d="M12 2a9 9 0 100 18 9 9 0 000-18zm0 2a7 7 0 110 14A7 7 0 0112 4zm1 3h-2v5l4 2 .8-1.8-2.8-1.2V7z"/></svg>
                        <div>
                            <h3>{{ __('meta.coffee_v36') }}</h3>
                            <p class="muted">{{ __('meta.coffee_v37') }}</p>
                        </div>
                    </div>
                    <div class="feat">
                        <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                            <path d="M4 4h16v4H4V4zm0 6h16v10H4V10zm2 2v6h12v-6H6z"/></svg>
                        <div>
                            <h3>{{ __('meta.coffee_v38') }}</h3>
                            <p class="muted">{{ __('meta.coffee_v39') }}</p>
                        </div>
                    </div>
                    <div class="feat">
                        <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                            <path d="M3 7h18v2H3V7zm0 4h12v2H3v-2zm0 4h18v2H3v-2z"/></svg>
                        <div>
                            <h3>{{ __('meta.coffee_v40') }}</h3>
                            <p class="muted">{{ __('meta.coffee_v41') }}</p>
                        </div>
                    </div>
                    <div class="feat">
                        <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                            <path d="M12 3l9 4v6c0 5-3.8 9.7-9 10-5.2-.3-9-5-9-10V7l9-4zm0 2.2L5 7.5v5.4c0 4.1 3 7.9 7 8.1 4-.2 7-4 7-8.1V7.5l-7-2.3zM7 12h10v2H7v-2z"/></svg>
                        <div>
                            <h3>{{ __('meta.coffee_v42') }}</h3>
                            <p class="muted">{{ __('meta.coffee_v43') }}</p>
                        </div>
                    </div>
                    <div class="feat">
                        <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 2l4 8h8l-6 5 2 9-8-5-8 5 2-9-6-5h8z"/></svg>
                        <div>
                            <h3>{{ __('meta.coffee_v44') }}</h3>
                            <p class="muted">{{ __('meta.coffee_v45') }}</p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- ESTADÍSTICAS / PRUEBAS SOCIALES -->
            <section class="stats" style="margin-top:20px">
                <div class="stat card"><h3>12</h3><p>{{ __('meta.coffee_v46') }}</p></div>
                <div class="stat card"><h3>{{ __('meta.coffee_v47') }}</h3><p>{{ __('meta.coffee_v48') }}</p></div>
                <div class="stat card"><h3>1,400–1,700</h3><p>{{ __('meta.coffee_v49') }}</p></div>
                <div class="stat card"><h3>90+</h3><p>{{ __('meta.coffee_v50') }}</p></div>
            </section>

            <!-- MAPA / UBICACIÓN + COMPROMISO (lado a lado) -->
            <section class="grid grid-2 reverse" style="margin-top:20px">
                <figure class="map is-stretch">
                    <picture>
                        <source media="(max-width: 640px)"  srcset="{{ asset('images/finca3pinos/montana2-640.webp') }}"  type="image/webp" />
                        <source media="(max-width: 1024px)" srcset="{{ asset('images/finca3pinos/montana2-1024.webp') }}" type="image/webp" />
                        <source media="(max-width: 1366px)" srcset="{{ asset('images/finca3pinos/montana2-1366.webp') }}" type="image/webp" />
                        <source media="(max-width: 1920px)" srcset="{{ asset('images/finca3pinos/montana2-1920.webp') }}" type="image/webp" />
                        <source media="(min-width: 1921px)" srcset="{{ asset('images/finca3pinos/montana2-2560.webp') }}" type="image/webp" />
                        <img
                            src="{{ asset('images/finca3pinos/montana2-1920.webp') }}"
                            alt="{{ __('meta.coffee_v51') }}"
                            loading="lazy" decoding="async"
                            style="width:100%;height:100%;object-fit:cover;border-radius:16px;">
                    </picture>
                </figure>

                <article class="card is-stretch">
                    <div class="grid" style="gap:10px">
                        <div class="chip"><span>🌿</span><span>{{ __('meta.coffee_v52') }}</span></div>
                        <div class="chip"><span>⚙️</span><span>{{ __('meta.coffee_v53') }}</span></div>
                        <div class="chip"><span>☕</span><span>{{ __('meta.coffee_v54') }}</span></div>
                    </div>
                    <p class="lead" style="margin-top:10px">
                        {{ __('meta.coffee_v55') }}
                    </p>
                </article>
            </section>



        </div>



    </section>









    {{-- Superior (Newsletter) block --}}
    @include('frontend.partials.superior')
@endsection
