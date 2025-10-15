<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Finca3Pinos | Panel</title>

    <link href="{{ asset('images/icono-sistema.png') }}" rel="icon">
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome Icons -->
    <link href="{{ asset('fontawesome-free/css/all.min.css') }}" type="text/css" rel="stylesheet" />
    <!-- Theme style -->
    <link href="{{ asset('css/adminlte.min.css') }}" type="text/css" rel="stylesheet" />
    <!-- Mensajes Toast -->
    <link href="{{ asset('css/toastr.min.css') }}" type="text/css" rel="stylesheet" />
    @yield('content-admin-css')
</head>


<!-- para iniciar con el menu cerrado colocar
 <body class="sidebar-mini sidebar-closed sidebar-collapse" style="height: auto;">
 -->

<body class="hold-transition sidebar-mini">
<div class="wrapper">
    @include("backend.menus.navbar")
    @include("backend.menus.sidebar")

    <div class="content-wrapper" style=" background-color: #fff;">
        <!-- redireccionamiento de vista -->

        <iframe style="vertical-align: bottom; width: 100%; resize: initial; overflow: hidden; min-height: 96vh" frameborder="0"  scrolling="" id="frameprincipal" src="{{ route($ruta) }}" name="frameprincipal">
        </iframe>

    </div>

    @include("backend.menus.footer")

</div>


<script src="{{ asset('js/jquery.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('js/bootstrap.bundle.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('js/adminlte.min.js') }}" type="text/javascript"></script>
<script>
    // Cerrar al hacer clic fuera en el documento principal
    $(document).on('click', function (e) {
        const $drop = $('.nav-item.dropdown');
        if (!$drop.is(e.target) && $drop.has(e.target).length === 0 && $('.show').has(e.target).length === 0) {
            $drop.find('.dropdown-menu').removeClass('show');
            $drop.find('.nav-link').attr('aria-expanded', 'false');
        }
    });

    // También cerrar si se hace clic dentro del iframe
    function wireIframeClose() {
        var iframe = document.getElementById('frameprincipal');
        if (!iframe) return;

        try {
            // mismo origen: podemos acceder al doc interno
            var idoc = iframe.contentWindow.document;

            // Cerrar con click o focus dentro del iframe
            $(idoc).on('mousedown keydown', function () {
                $('.nav-item.dropdown .dropdown-menu').removeClass('show');
                $('.nav-item.dropdown .nav-link').attr('aria-expanded', 'false');
            });
        } catch (err) {
            // distinto origen: no se puede acceder (por si acaso)
            console.warn('No se pudo acceder al documento del iframe:', err);
        }
    }

    // Enlazar cuando el iframe termine de cargar (y en cambios de src)
    $('#frameprincipal').on('load', wireIframeClose);

    // Por si ya estaba cargado cuando se ejecuta el script
    if (document.getElementById('frameprincipal')?.complete) {
        wireIframeClose();
    }

    // Opcional: cerrar con ESC
    $(document).on('keydown', function(e){
        if (e.key === 'Escape') {
            $('.nav-item.dropdown .dropdown-menu').removeClass('show');
            $('.nav-item.dropdown .nav-link').attr('aria-expanded', 'false');
        }
    });
</script>


@yield('content-admin-js')




</body>
</html>
