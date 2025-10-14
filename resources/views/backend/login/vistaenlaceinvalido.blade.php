<!DOCTYPE html>
<html lang="es">
<head>
    <title>Finca 3 Pinos - Enlace no válido</title>
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

    {{-- Estilos base --}}
    <link rel="stylesheet" href="{{ asset('css/toastr.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/sweetalert2.min.css') }}">

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap');

        html, body {
            height: 100%;
            font-family: 'Roboto', sans-serif;
            margin: 0;
            padding: 0;
            background: #000 url({{ asset('images/404.png') }}) center/cover no-repeat;
        }

        .demo-container {
            height: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .card {
            background: rgba(255, 255, 255, 0.96);
            border-radius: 16px;
            padding: 50px 40px;
            text-align: center;
            max-width: 420px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.25);
        }

        .card img {
            width: 110px;
            height: auto;
            margin-bottom: 20px;
        }

        .logo-container {
            text-align: center;
            margin-bottom: 20px;
        }

        .logo-container img {
            width: 110px;
            height: auto;
        }

        h4 {
            font-weight: 700;
            color: #333;
        }

        p {
            color: #444;
            margin-top: 10px;
            margin-bottom: 30px;
        }

        .btn-main {
            background: #D2AA6D;
            color: #fff !important;
            border: none;
            border-radius: 8px;
            padding: 12px 24px;
            font-weight: 600;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }

        .btn-main:hover,
        .btn-main:focus,
        .btn-main:active {
            background: #c39a5f;
            color: #fff !important;
            text-decoration: none;
            outline: none;
            transform: scale(1.02);
        }

        .footer-text {
            font-size: 13px;
            color: #777;
            margin-top: 25px;
        }
    </style>
</head>
<body>

<div class="demo-container">
    <div class="card shadow-lg">
        <div class="logo-container">
            <img src="{{ asset('images/logoindex.png') }}" alt="Logo">
        </div>
        <h4>Enlace no válido</h4>
        <p>El enlace para restablecer la contraseña ha expirado o ya fue utilizado.</p>
        <a href="{{ route('admin.password.reset') }}" class="btn btn-main w-100">Solicitar nuevo enlace</a>
        <p class="footer-text">© {{ date('Y') }} Finca 3 Pinos — Panel de Administración</p>
    </div>
</div>

</body>
</html>
