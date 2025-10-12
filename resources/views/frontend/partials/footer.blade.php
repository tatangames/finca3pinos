<style>
    /* ===== Footer con mapa visible (sin viñeta radial) ===== */
    section#block-footer {
        /* Ajustes base */
        --bg-top: #141414;
        --bg-bottom: #0d0d0d;
        --map-size: 1500px auto;
        --map-opacity: 0.20;

        position: relative;
        z-index: 0;
        background: linear-gradient(180deg, var(--bg-top), var(--bg-bottom));
        overflow: hidden;
    }

    /* Capa del mapa */
    section#block-footer::before {
        content: "";
        position: absolute;
        inset: 0;
        z-index: -1;
        background: url("{{ asset('images/mapa.png') }}") center 45% / var(--map-size) no-repeat;
        opacity: var(--map-opacity);
        filter: invert(1) brightness(1.15) contrast(1.2);
        mix-blend-mode: screen; /* mapa claro fundido sobre fondo oscuro */
    }

    /* Ajustes del contenido del footer */
    section#block-footer .container {
        position: relative;
        z-index: 1;
        padding: 40px 20px; /* altura reducida */
    }

    /* ==== Card del logo (blanco) ==== */
    .footer-logo-card .logo-card {
        display: inline-block;
        background: #ffffff;               /* fondo blanco */
        border-radius: 10px;
        padding: 12px 16px;
        box-shadow: 0 3px 10px rgba(0,0,0,0.25);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .footer-logo-card .logo-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 6px 16px rgba(0,0,0,0.35);
    }

    .footer-logo-card .footer-logo img {
        display: block;
        margin: 0 auto;
        width: 100px;
        height: auto;
    }

    /* Texto debajo del logo */
    .footer-logo-card p {
        color: #dcdcdc;
        font-size: 13px;
        line-height: 1.5;
        margin-top: 10px;
    }

    /* ==== Estilos generales del footer ==== */
    .footer-dark .header-widget {
        color: #e3c27a;
        font-weight: 700;
        margin-bottom: 12px;
        letter-spacing: 0.5px;
    }

    .footer-dark ul.menu,
    .footer-dark ul.social-icons-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .footer-dark ul.menu li,
    .footer-dark ul.social-icons-list li {
        margin-bottom: 6px;
    }

    .footer-dark ul.menu a,
    .footer-dark ul.social-icons-list a {
        color: #f0f0f0;
        text-decoration: none;
        transition: color 0.3s ease;
    }

    .footer-dark ul.menu a:hover,
    .footer-dark ul.social-icons-list a:hover {
        color: #e3c27a;
    }

    /* ==== Footer inferior ==== */
    .footer-dark-bottom {
        background: #0a0a0a;
        border-top: 1px solid rgba(255,255,255,0.08);
        padding: 12px 0;
        text-align: center;
        color: #b5b5b5;
        font-size: 13px;
    }

    .footer-dark-bottom a.go-top {
        display: inline-block;
        margin-left: 8px;
        color: #cbb27c;
        font-weight: 600;
        text-decoration: none;
    }

    .footer-dark-bottom a.go-top:hover {
        color: #fff;
    }

    /* ==== Responsive ==== */
    @media (max-width:1024px) {
        section#block-footer {
            --map-size: 1100px auto;
            --map-opacity: 0.18;
        }
        section#block-footer .container {
            padding: 36px 16px;
        }
    }

    @media (max-width:640px) {
        section#block-footer {
            --map-size: 800px auto;
        }
        section#block-footer::before {
            background-position: center 35%;
        }
        section#block-footer .container {
            padding: 30px 14px;
        }
    }

</style>







<section id="block-footer" class="footer-dark">
    <div class="container">
        <div class="row">

            <!-- Columna 1: logo dentro de un card -->
            <div class="col-md-4 col-sm-6 col-ms-12">
                <div class="footer-widget-area">
                    <div class="widget widget_text footer-logo-card">
                        <div class="logo-card">
                            <a href="{{ route('user.index') }}" class="footer-logo">
                                <img src="{{ asset('images/logoindex.png') }}" style="width: 120px" height="120px"
                                     alt="Finca 3 Pinos - Café Geisha"
                                     loading="lazy" decoding="async">
                            </a>
                        </div>
                        <p>Café Geisha de la Cordillera Alotepec–Metapán.<br>
                            Tradición, innovación y respeto por la tierra en cada taza.</p>
                    </div>
                </div>
            </div>

            <!-- Columna 2: menú -->
            <div class="col-md-4 col-sm-6 col-ms-12 hidden-xs hidden-ms">
                <div class="footer-widget-area">
                    <div class="widget widget_nav_menu">
                        <h4 class="header-widget">Explorar</h4>
                        <ul class="menu">
                            <li><a href="#">Inicio</a></li>
                            <li><a href="#">Sobre Nosotros</a></li>
                            <li><a href="#">Productos</a></li>
                            <li><a href="#">Contacto</a></li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Columna 3: redes sociales -->
            <div class="col-md-4 col-sm-6 col-ms-12">
                <div class="footer-widget-area">
                    <div class="widget widget_coffeeking_icons">
                        <h4 class="header-widget">Síguenos</h4>
                        <ul class="social-icons-list">
                            <li>
                                <a href="https://facebook.com/finca3pinos" target="_blank">
                                    <span class="fa fa-facebook"></span> Facebook
                                </a>
                            </li>
                            <li>
                                <a href="https://instagram.com/finca3pinos" target="_blank">
                                    <span class="fa fa-instagram"></span> Instagram
                                </a>
                            </li>
                            <li>
                                <a href="https://tiktok.com/@finca3pinos" target="_blank">
                                    <span class="fa fa-music"></span> TikTok
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

<footer class="footer-block footer-dark-bottom">
    <div class="container">
        <p>© {{ date('Y') }} Finca 3 Pinos — Todos los derechos reservados.</p>
        <a href="#" class="go-top hidden-xs hidden-ms" style="color: white !important;"><span class="fa fa-arrow-up"></span> TOP</a>
    </div>
</footer>
