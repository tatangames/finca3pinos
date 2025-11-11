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
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            max-width: 1000px;
            margin: 0 auto;
        }
        .gallery-item {
            position: relative;
            overflow: hidden;
            border-radius: 16px;
            cursor: pointer;
            transition: transform .3s ease;
            width: 200px;
            height: 200px;
            margin: 0 auto;
        }
        .gallery-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 16px;
            transition: transform .4s ease;
        }
        .gallery-item:hover img { transform: scale(1.05); }

        .overlay {
            position: absolute;
            inset: 0;
            background: rgba(0,0,0,.45);
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
        .gallery-item:hover .overlay { opacity: 1; }
        .gallery-item:hover .overlay i { transform: scale(1); }

        .gallery-video-thumb {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #000;
            border-radius: 16px;
        }
        .gallery-video-thumb i {
            font-size: 2.4rem;
            color: #d2aa6d;
        }

        /* ===== MODAL ===== */
        .modal {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,.9);
            justify-content: center;
            align-items: center;
            z-index: 9999;
            backdrop-filter: blur(6px);
            padding: 16px;
        }
        .modal-content {
            position: relative;
            background: #111;
            border-radius: 18px;
            box-shadow: 0 0 40px rgba(0,0,0,.7);
            width: clamp(720px, 88vw, 1280px);
            max-height: 92vh;
            overflow: hidden;
            animation: fadeIn .35s ease;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }
        .modal-content img {
            width: 100%;
            height: auto;
            max-height: calc(92vh - 52px);
            object-fit: contain;
            border-radius: 18px 18px 0 0;
            display: block;
        }
        #modalCaption {
            background: rgba(0,0,0,.85);
            width: 100%;
            color: #f1f1f1;
            font-size: 1rem;
            padding: 12px 0;
            margin: 0;
            text-align: center;
            border-top: 1px solid rgba(255,255,255,.1);
        }
        #modalCaption:empty { display: none; }
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
        .modal-close:hover { color: #d2aa6d; }

        @keyframes fadeIn {
            from { opacity: 0; transform: scale(.96); }
            to   { opacity: 1; transform: scale(1); }
        }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 1024px) {
            .gallery-title { font-size: 1.75rem; }
            .modal-content { width: 96vw; max-height: 90vh; }
            .modal-content img { max-height: calc(90vh - 48px); }
        }
        @media (max-width: 640px) {
            .gallery-title { font-size: 1.5rem; }
            .modal-content { width: 96vw; max-height: 88vh; }
            .modal-content img { max-height: calc(88vh - 44px); }
            #modalCaption { font-size: .9rem; }
        }

        .video-icon-overlay {
            text-shadow: 0 0 8px rgba(0,0,0,.7);
            transition: transform .3s ease, opacity .3s ease;
        }
        .gallery-item:hover .video-icon-overlay {
            transform: translate(-50%, -50%) scale(1.1);
            opacity: 1;
        }



    </style>

    <header class="page-header like-parallax"
            style="background-image: url('{{ asset('images/inner_parallax.jpg') }}');
               background-size: cover; background-position: center; background-repeat: no-repeat;">
        <div class="container">
            <h1>{{ __('meta.gallery') }}</h1>
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
                        <span property="name">{{ __('meta.gallery') }}</span>
                        <meta property="position" content="2">
                    </span>
                </li>
            </ul>
        </div>
    </header>

    <section class="gallery-section">
        <div class="container">
            <h2 class="gallery-title">{{ __('meta.gallery_finca3pinos') }}</h2>

            <div id="galleryGrid" class="gallery-grid">
                @foreach($arrayGaleria as $g)
                    @php
                        $tipo = (int) $g->tipo; // 0 = imagen, 1 = video
                        $isVideo = ($tipo === 1 && $g->urlvideo);
                        $thumb = null;

                        if ($isVideo) {
                            $url = $g->urlvideo;
                            if (str_contains($url, 'youtu.be/')) {
                                $id = explode('youtu.be/', $url)[1] ?? '';
                                $id = strtok($id, '?&');
                                $thumb = "https://img.youtube.com/vi/{$id}/hqdefault.jpg";
                            } elseif (str_contains($url, 'youtube.com/watch?v=')) {
                                $id = explode('watch?v=', $url)[1] ?? '';
                                $id = strtok($id, '&');
                                $thumb = "https://img.youtube.com/vi/{$id}/hqdefault.jpg";
                            } elseif (str_contains($url, 'vimeo.com/')) {
                                // Puedes dejar una miniatura genérica o un color de fondo
                                $thumb = asset('images/video_placeholder.jpg');
                            }
                        }
                    @endphp

                    <div class="gallery-item"
                         data-id="{{ $g->id }}"
                         data-type="{{ $tipo }}"
                         data-url="{{ $g->urlvideo ?? '' }}"
                         data-caption="{{ $g->textoIdioma ?? '' }}"
                         onclick="openModal(this)">

                        @if($isVideo)
                            <div class="gallery-video-thumb" style="position:relative;">
                                @if($thumb)
                                    <img src="{{ $thumb }}" alt="Video thumbnail"
                                         style="width:100%;height:100%;object-fit:cover;border-radius:16px;">
                                @endif
                                <i class="fa fa-play video-icon-overlay"
                                   style="position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);
                          font-size:2.4rem;color:#d2aa6d;opacity:.9;"></i>
                            </div>
                        @else
                            <img
                                src="{{ url('storage/archivos/'.$g->imagen) }}"
                                alt="{{ $g->altseo }}"
                                loading="lazy"
                                decoding="async"
                                itemprop="image">
                        @endif

                        <div class="overlay">
                            <i class="{{ $isVideo ? 'fa fa-play' : 'fa fa-eye' }}"></i>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Sentinel para carga infinita --}}
            <div id="gallerySentinel" style="height: 1px;"></div>

            {{-- Loader --}}
            <div id="galleryLoading" style="display:none; margin:16px 0; color:#bbb;">
                <i class="fa fa-spinner fa-spin"></i> {{ __('meta.loading') }}
            </div>
        </div>
    </section>

    <!-- ===== MODAL ÚNICO (IMAGEN + VIDEO) ===== -->
    <div id="imageModal" class="modal" onclick="closeModalOutside(event)">
        <span class="modal-close" onclick="closeModal()">&times;</span>

        <div class="modal-content" onclick="event.stopPropagation()">
            {{-- Imagen --}}
            <img id="modalImage" src="" alt="" style="display:none;">

            {{-- Video (iframe responsive) --}}
            <div id="modalVideoWrapper"
                 style="display:none; width:100%; position:relative; padding-bottom:56.25%; margin:0;">
                <iframe id="modalVideo"
                        src=""
                        frameborder="0"
                        allow="autoplay; encrypted-media"
                        allowfullscreen
                        style="position:absolute; top:0; left:0; width:100%; height:100%; border-radius:18px 18px 0 0;">
                </iframe>
            </div>

            <p id="modalCaption"></p>
        </div>
    </div>

    <script>
        function getYouTubeEmbed(url) {
            try {
                if (url.includes('youtu.be/')) {
                    const id = url.split('youtu.be/')[1].split(/[?&]/)[0];
                    return 'https://www.youtube.com/embed/' + id;
                }
                if (url.includes('watch?v=')) {
                    const id = url.split('watch?v=')[1].split('&')[0];
                    return 'https://www.youtube.com/embed/' + id;
                }
                return url;
            } catch (e) {
                return url;
            }
        }

        function getVimeoEmbed(url) {
            try {
                const match = url.match(/vimeo\.com\/(\d+)/);
                if (match && match[1]) {
                    return 'https://player.vimeo.com/video/' + match[1];
                }
                return url;
            } catch (e) {
                return url;
            }
        }

        function openModal(el) {
            const modal          = document.getElementById('imageModal');
            const modalImg       = document.getElementById('modalImage');
            const modalVideoWrap = document.getElementById('modalVideoWrapper');
            const modalVideo     = document.getElementById('modalVideo');
            const caption        = document.getElementById('modalCaption');

            const type    = el.dataset.type || '0';
            const url     = el.dataset.url || '';
            const capText = el.dataset.caption || '';

            // Reset visual
            modalImg.style.display       = 'none';
            modalImg.src                 = '';
            modalVideoWrap.style.display = 'none';
            modalVideo.src               = '';
            caption.textContent          = capText;

            if (type === '1' && url) {
                // VIDEO
                let embedUrl = url;

                if (url.includes('youtube.com') || url.includes('youtu.be')) {
                    embedUrl = getYouTubeEmbed(url);
                } else if (url.includes('vimeo.com')) {
                    embedUrl = getVimeoEmbed(url);
                }

                modalVideo.src = embedUrl;
                modalVideoWrap.style.display = 'block';
            } else {
                // IMAGEN
                const img = el.querySelector('img');
                if (img) {
                    modalImg.src = img.src;
                    modalImg.style.display = 'block';
                }
            }

            modal.style.display = 'flex';
            document.body.style.overflow = 'hidden';
        }

        function closeModal() {
            const modal      = document.getElementById('imageModal');
            const modalVideo = document.getElementById('modalVideo');

            modal.style.display = 'none';
            document.body.style.overflow = '';

            // parar video
            if (modalVideo) {
                modalVideo.src = '';
            }
        }

        function closeModalOutside(e) {
            if (e.target.id === 'imageModal') {
                closeModal();
            }
        }

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeModal();
            }
        });
    </script>

    <script>
        (() => {
            const grid = document.getElementById('galleryGrid');
            const sentinel = document.getElementById('gallerySentinel');
            const loader = document.getElementById('galleryLoading');

            // Último del lote inicial (según posicion ASC, id ASC)
            let lastPos = {{ (int)($lastPos ?? 0) }};
            let lastId  = {{ (int)($lastId  ?? 0) }};

            const limit = 24;
            let cargando = false;

            // si el primer lote fue menor que limit → terminado
            let terminado = ({{ count($arrayGaleria) }} < limit);
            if (terminado && sentinel) sentinel.remove();

            async function cargarMas() {
                if (cargando || terminado) return;
                cargando = true;
                loader.style.display = 'block';

                try {
                    const url = new URL(`{{ route('galeria.cargar') }}`);
                    url.searchParams.set('last_pos', String(lastPos));
                    url.searchParams.set('last_id',  String(lastId));
                    url.searchParams.set('limit',    String(limit));

                    const resp = await fetch(url, {
                        headers: { 'X-Requested-With': 'XMLHttpRequest' }
                    });
                    if (!resp.ok) throw new Error('Network error');

                    const data = await resp.json();

                    if (data.count > 0 && data.html) {
                        grid.insertAdjacentHTML('beforeend', data.html);

                        if (Number(data.next_pos) > 0 || Number(data.next_id) > 0) {
                            lastPos = Number(data.next_pos);
                            lastId  = Number(data.next_id);
                        }

                        if (data.count < limit) {
                            terminado = true;
                            observer.disconnect?.();
                            sentinel?.remove();
                        }
                    } else {
                        terminado = true;
                        observer.disconnect?.();
                        sentinel?.remove();
                    }
                } catch (e) {
                    console.error(e);
                } finally {
                    cargando = false;
                    loader.style.display = 'none';
                }
            }

            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) cargarMas();
                });
            }, {
                root: null,
                rootMargin: '0px 0px 600px 0px',
                threshold: 0
            });

            if (!terminado && sentinel) observer.observe(sentinel);

            // Fallback sin IO
            if (!('IntersectionObserver' in window)) {
                window.addEventListener('scroll', () => {
                    if (terminado || cargando) return;
                    const nearBottom =
                        window.innerHeight + window.scrollY >= document.body.offsetHeight - 400;
                    if (nearBottom) cargarMas();
                });
            }
        })();
    </script>

    {{-- Superior (Newsletter) block --}}
@endsection
