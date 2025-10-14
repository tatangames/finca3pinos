{{-- resources/views/backend/login/vistaloginadmin.blade.php --}}
    <!DOCTYPE html>
<html lang="es">
<head>
    <title>Finca 3 Pinos - Panel</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- Bootstrap --}}
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css"
          integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">

    {{-- Favicon --}}
    <link href="{{ asset('images/icono-sistema.png') }}" rel="icon">

    {{-- FontAwesome --}}
    <script defer src="https://use.fontawesome.com/releases/v5.0.6/js/all.js"></script>

    {{-- Tus estilos existentes --}}
    <link rel="stylesheet" href="{{ asset('css/login/styleLogin.css') }}">
    <link rel="stylesheet" href="{{ asset('css/toastr.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/sweetalert2.min.css') }}">

    {{-- Livewire --}}
    @livewireStyles

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap');
        html, body { height: 100%; }
        body{
            font-family:'Roboto',sans-serif; margin:0; padding:0;
            background-image:url({{ asset('images/404.png') }});
            background-size:cover; background-position:center; background-repeat:no-repeat; height:100vh;
        }
        .demo-container{ height:100%; display:flex; justify-content:center; align-items:center; }
        .p-5.bg-white{ border-radius:16px; }
        .logo-container{ text-align:center; margin-bottom:15px; }
        .logo-container img{ width:120px; height:auto; }
        .input-group-text{ background:#D2AA6DFF!important; color:#fff!important; border:none; font-size:16px; }
        .form-control{ border:1px solid #ccc; border-left:5px solid #D2AA6DFF; padding:10px 12px; }
        .form-control:focus{ border-color:#D2AA6DFF; box-shadow:0 0 5px rgba(210,170,109,.6); }
        .btn-lg{ padding:12px 26px; font-size:14px; font-weight:700; letter-spacing:1px; text-transform:uppercase; transition:all .3s ease; }
        .btn-lg:hover{ opacity:.9; }
    </style>
</head>
<body>

{{-- resources/views/backend/login/vistareseteopassword.blade.php --}}
@livewire('auth-admin.reset-password-form', ['token' => $token, 'email' => $email])

{{-- JS base --}}
<script src="{{ asset('js/jquery.min.js') }}"></script>
<script src="{{ asset('js/toastr.min.js') }}"></script>
<script src="{{ asset('js/sweetalert2.all.min.js') }}"></script>

{{-- Livewire --}}
@livewireScripts
<script>
    // Escuchar toasts disparados desde el componente
    document.addEventListener('livewire:initialized', () => {
        Livewire.on('toast', ({type='info', message=''}) => {
            if (window.toastr) toastr[type](message);
        });
    });
</script>
</body>
</html>
