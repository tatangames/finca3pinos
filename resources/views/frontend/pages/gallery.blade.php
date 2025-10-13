@extends('frontend.layouts.app')

@section('title', __('meta.title'))

@section('content')

    <style>

        /* ===== GALERÍA ===== */
        .gallery-section {
            background: #0e0e0e;
            color: #fff;
            padding: 40px 10px;
            text-align: center;
        }

        .gallery-title {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 40px;
            color: #d2aa6d;
            letter-spacing: .05em;
            text-transform: uppercase;
        }

        /* === GRID === */
        .gallery-grid {
            display: grid;
            grid-template-columns:repeat(auto-fit, minmax(280px, 1fr));
            gap: 20px;
            max-width: 1200px;
            margin: 0 auto;
        }

        /* === ITEM === */
        .gallery-item {
            position: relative;
            overflow: hidden;
            border-radius: 16px;
            cursor: pointer;
            transition: transform .3s ease;
        }

        .gallery-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform .4s ease;
        }

        .gallery-item:hover img {
            transform: scale(1.05);
        }

        .overlay {
            position: absolute;
            inset: 0;
            background: rgba(0, 0, 0, .45);
            opacity: 0;
            transition: opacity .4s ease;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .overlay i {
            color: #d2aa6d;
            font-size: 1.8rem;
            transform: scale(.8);
            transition: transform .3s ease;
        }

        .gallery-item:hover .overlay {
            opacity: 1;
        }

        .gallery-item:hover .overlay i {
            transform: scale(1);
        }

        /* ===== MODAL BACKDROP ===== */
        .modal {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, .9);
            justify-content: center;
            align-items: center;
            z-index: 9999;
            backdrop-filter: blur(6px);
            padding: 16px;
        }

        /* ===== CARD EXPANDIDO =====
           Grande pero controlado: ocupa 88vw (máx 1280px) y hasta 92vh de alto */
        .modal-content {
            position: relative;
            background: #111;
            border-radius: 18px;
            box-shadow: 0 0 40px rgba(0, 0, 0, .7);
            width: clamp(720px, 88vw, 1280px);
            max-height: 92vh;
            overflow: hidden;
            animation: fadeIn .35s ease;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        /* Imagen: llena el ancho del card, respeta proporción y no invade el caption */
        .modal-content img {
            width: 100%;
            height: auto;
            max-height: calc(92vh - 52px);
            object-fit: contain;
            border-radius: 18px 18px 0 0;
            display: block;
        }

        /* Caption compacto (si no hay texto, no ocupa espacio) */
        #modalCaption {
            background: rgba(0, 0, 0, .85);
            width: 100%;
            color: #f1f1f1;
            font-size: 1rem;
            padding: 12px 0;
            margin: 0;
            text-align: center;
            border-top: 1px solid rgba(255, 255, 255, .1);
        }

        #modalCaption:empty {
            display: none;
        }

        /* Botón cerrar */
        .modal-close {
            position: absolute;
            top: 14px;
            right: 18px;
            color: #fff;
            font-size: 2rem;
            cursor: pointer;
            transition: color .3s ease;
            z-index: 10;
        }

        .modal-close:hover {
            color: #d2aa6d;
        }

        /* Animación */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: scale(.96)
            }
            to {
                opacity: 1;
                transform: scale(1)
            }
        }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 1024px) {
            .gallery-title {
                font-size: 1.75rem;
            }

            .modal-content {
                width: 96vw;
                max-height: 90vh;
            }

            .modal-content img {
                max-height: calc(90vh - 48px);
            }
        }

        @media (max-width: 640px) {
            .gallery-title {
                font-size: 1.5rem;
            }

            .modal-content {
                width: 96vw;
                max-height: 88vh;
            }

            .modal-content img {
                max-height: calc(88vh - 44px);
            }

            #modalCaption {
                font-size: .9rem;
            }
        }


    </style>

    <header class="page-header like-parallax"
            style="background-image: url('{{ asset('images/inner_parallax.jpg') }}');
               background-size: cover;
               background-position: center;
               background-repeat: no-repeat;">
        <div class="container" bis_skin_checked="1"><h1>{{ __('meta.gallery') }}</h1>
            <ul class="breadcrumbs" typeof="BreadcrumbList" vocab="https://schema.org/">
                <li class="home"><span property="itemListElement" typeof="ListItem"><a property="item" typeof="WebPage"
                                                                                       title="Go to Finca3pinos.com"
                                                                                       href="{{ LaravelLocalization::getLocalizedURL(app()->getLocale(), '/') }}"
                                                                                       class="home"
                                                                                       bis_skin_checked="1">
                            <span property="name">{{ __('meta.finca3pinos') }}</span></a><meta property="position"
                                                                                               content="1"></span></li>
                <li class="post post-page current-item"><span property="itemListElement" typeof="ListItem"><span
                            property="name">{{ __('meta.gallery') }}</span><meta property="position" content="2"></span>
                </li>
            </ul>
        </div>
    </header>



    <section class="gallery-section">
        <div class="container">
            <h2 class="gallery-title">Galería Finca 3 Pinos</h2>

            <div class="gallery-grid">
                <div class="gallery-item" onclick="openModal(this)">
                    <img src="{{ asset('images/finca.jpg') }}" alt="Finca 3 Pinos - Café Geisha">
                    <div class="overlay"><i class="fa fa-eye"></i></div>
                </div>
                <div class="gallery-item" onclick="openModal(this)">
                    <img src="{{ asset('images/finca.jpg') }}" alt="Plantaciones de café">
                    <div class="overlay"><i class="fa fa-search"></i></div>
                </div>
                <div class="gallery-item" onclick="openModal(this)">
                    <img src="{{ asset('images/finca.jpg') }}" alt="Proceso de secado del café">
                    <div class="overlay"><i class="fa fa-search"></i></div>
                </div>
                <div class="gallery-item" onclick="openModal(this)">
                    <img src="{{ asset('images/finca.jpg') }}" alt="Granos Geisha">
                    <div class="overlay"><i class="fa fa-search"></i></div>
                </div>
                <div class="gallery-item" onclick="openModal(this)">
                    <img src="{{ asset('images/finca.jpg') }}" alt="Taza de café artesanal">
                    <div class="overlay"><i class="fa fa-search"></i></div>
                </div>
                <div class="gallery-item" onclick="openModal(this)">
                    <img src="{{ asset('images/finca3.jpg') }}" alt="Montañas de Metapán">
                    <div class="overlay"><i class="fa fa-search"></i></div>
                </div>
            </div>
        </div>
    </section>

    <!-- ===== MODAL ===== -->
    <div id="imageModal" class="modal" onclick="closeModalOutside(event)">
        <span class="modal-close" onclick="closeModal()">&times;</span>
        <div class="modal-content">
            <img id="modalImage" src="" alt="">
            <p id="modalCaption"></p>
        </div>
    </div>






    <script>


        function openModal(el) {
            const modal = document.getElementById('imageModal');
            const modalImg = document.getElementById('modalImage');
            const caption = document.getElementById('modalCaption');
            const img = el.querySelector('img');

            modalImg.src = img.dataset.full || img.src;
            caption.textContent = img.alt || '';
            modal.style.display = 'flex';
            document.body.style.overflow = 'hidden'; // bloquea scroll fondo
        }

        function closeModal() {
            document.getElementById('imageModal').style.display = 'none';
            document.body.style.overflow = ''; // restaura scroll
        }

        function closeModalOutside(e) {
            if (e.target.id === 'imageModal') {
                closeModal();
            }
        }

        // Cerrar con ESC
        document.addEventListener('keydown', e => {
            if (e.key === 'Escape') closeModal();
        });


    </script>





    {{-- Superior (Newsletter) block --}}
    @include('frontend.partials.superior')
@endsection
