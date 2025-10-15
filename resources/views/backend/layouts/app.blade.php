<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>@yield('title','Comascosv | Panel')</title>

    <link rel="icon" href="{{ asset('images/logopestana.jpg') }}">

    <!-- Fuentes y estilos base -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="{{ asset('fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/adminlte.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/toastr.min.css') }}">
    <link id="page-style" rel="stylesheet" href="@yield('page-style-href')">
    {{-- ✅ Aquí se insertarán los estilos específicos de cada vista --}}
    @stack('styles')

    {{-- HTMX configuración --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://unpkg.com/htmx.org@1.9.12"></script>
    <script>
        document.addEventListener('htmx:configRequest', e => {
            e.detail.headers['X-CSRF-TOKEN'] =
                document.querySelector('meta[name="csrf-token"]').content;
        });
    </script>

</head>

<body class="hold-transition sidebar-mini @yield('body-class')"
      hx-boost="true"
      hx-select="#content, script#page-style-oob"
      hx-target="#content"
      hx-swap="innerHTML"
      hx-push-url="true">

<div class="wrapper">
    {{-- NAVBAR --}}
    @include('backend.menus.navbar', ['user' => auth('admin')->user()])

    {{-- SIDEBAR --}}
    @include('backend.menus.sidebar')

    {{-- CONTENIDO PRINCIPAL --}}
    <div class="content-wrapper">
        <section id="content" class="content pt-3">
            @yield('content')
        </section>
    </div>

    {{-- FOOTER --}}
    @include('backend.menus.footer')
</div>

<!-- ============================================================
     JS BASE DEL PANEL
     ============================================================ -->
<script src="{{ asset('js/jquery.min.js') }}"></script>
<script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('js/adminlte.min.js') }}"></script>

<!-- Mantener menú activo tras navegación HTMX -->
<script>
    function syncActive() {
        const current = window.location.pathname + window.location.search;
        document.querySelectorAll('.nav-sidebar a.nav-link').forEach(a => {
            a.classList.toggle('active', a.getAttribute('href') === current);
            const li = a.closest('.nav-item.has-treeview, .nav-item.menu-open');
            if (li && a.classList.contains('active')) li.classList.add('menu-open');
        });
    }
    document.addEventListener('DOMContentLoaded', syncActive);
    document.addEventListener('htmx:afterSettle', syncActive);
</script>

<!-- ============================================================
     FIX GLOBAL DE SCROLL PARA TODO EL PANEL
     ============================================================ -->
<script>
    try { history.scrollRestoration = 'manual'; } catch (e) {}

    // Al cambiar de vista con HTMX, sube al tope
    document.addEventListener('htmx:afterSettle', function () {
        requestAnimationFrame(() => window.scrollTo(0, 0));
    });

    // También al volver desde cache del navegador
    window.addEventListener('pageshow', e => {
        if (e.persisted) window.scrollTo(0, 0);
    });
</script>

{{-- ✅ Scripts específicos de cada vista --}}
@stack('scripts')

</body>
</html>
