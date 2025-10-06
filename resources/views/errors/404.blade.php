@include('frontend.menu.superior')
@include('frontend.menu.body.bodycontacto')
@include("frontend.menu.navbar")


<!-- START SECTION 404 -->
<section class="notfound pt-0">
    <div class="container">
        <div class="top-headings text-center">
            <img src="{{ asset('images/error-404.jpg') }}" alt="Page 404">
            <h3 class="text-center">Página No encontrada!</h3>
            <p class="text-center"></p>
        </div>
        <div class="port-info">
            <a href="{{ route('user.index') }}" class="btn btn-primary btn-lg">Volver a Inicio</a>
        </div>
    </div>
</section>
<!-- END SECTION 404 -->



@include("frontend.menu.footer-js")
@include("frontend.menu.final")
