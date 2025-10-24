@php
    $resetUrl = $resetUrl ?? 'https://finca3pinos.com/password-reset-demo';
    $userName = $userName ?? 'Estimado cliente';
    $facebookUrl = 'https://www.facebook.com/finca3pinos';
    $instagramUrl = 'https://www.instagram.com/finca3pinos';
@endphp

    <!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restablecer contraseña</title>
    <style>
        body {
            background-color: #f6f6f6;
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            margin: 0;
            padding: 0;
            color: #333;
        }
        .email-container {
            max-width: 600px;
            margin: 40px auto;
            background-color: #fff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 5px 25px rgba(0,0,0,0.1);
        }
        .header {
            background-color: #d2aa6d;
            color: #fff;
            text-align: center;
            padding: 28px 20px;
        }
        .header h1 {
            margin: 0;
            font-size: 1.6rem;
            font-weight: 700;
            letter-spacing: .03em;
        }
        .body {
            padding: 28px 32px;
            line-height: 1.6;
            font-size: 15px;
        }
        .body p {
            margin-bottom: 16px;
        }
        .btn {
            display: inline-block;
            background-color: #d2aa6d;
            color: #fff !important;
            text-decoration: none;
            padding: 12px 24px;
            border-radius: 8px;
            font-weight: 600;
            margin-top: 12px;
        }
        .footer {
            text-align: center;
            font-size: 13px;
            color: #888;
            border-top: 1px solid #eee;
            padding: 20px;
        }
        .logo {
            max-width: 120px;
            margin-bottom: 16px;
        }
        .social-links {
            margin-top: 16px;
        }
        .social-links a {
            display: inline-block;
            margin: 0 6px;
            text-decoration: none;
        }
        .social-links img {
            width: 28px;
            height: 28px;
            vertical-align: middle;
            border-radius: 6px;
        }
        @media (max-width: 600px) {
            .email-container {
                margin: 20px;
            }
            .body {
                padding: 22px 20px;
            }
        }
    </style>
</head>
<body>
<div class="email-container">
    <div class="header">
        <img src="{{ asset('images/logoindex.png') }}" alt="Finca 3 Pinos" class="logo">
        <h1>Restablecer contraseña</h1>
    </div>

    <div class="body">
        <p>Hola {{ $userName }},</p>

        <p>Hemos recibido una solicitud para restablecer tu contraseña de acceso a <strong>Finca 3 Pinos</strong>.</p>

        <p>Si realizaste esta solicitud, haz clic en el siguiente botón para establecer una nueva contraseña:</p>

        <p style="text-align:center;">
            <a href="{{ $resetUrl }}" class="btn">Restablecer mi contraseña</a>
        </p>

        <p>Si no solicitaste este cambio, puedes ignorar este mensaje. Tu cuenta seguirá segura.</p>

        <p style="margin-top: 20px;">Gracias,<br>El equipo de <strong>Finca 3 Pinos</strong></p>
    </div>

    <div class="footer">
        &copy; {{ date('Y') }} Finca 3 Pinos. Todos los derechos reservados.<br>
        Este correo se generó automáticamente, por favor no respondas a este mensaje.

        <div class="social-links">
            <a href="{{ $facebookUrl }}" target="_blank" title="Facebook">
                <img src="https://cdn-icons-png.flaticon.com/512/1384/1384005.png" alt="Facebook">
            </a>
            <a href="{{ $instagramUrl }}" target="_blank" title="Instagram">
                <img src="https://cdn-icons-png.flaticon.com/512/1384/1384015.png" alt="Instagram">
            </a>
        </div>
    </div>
</div>
</body>
</html>
