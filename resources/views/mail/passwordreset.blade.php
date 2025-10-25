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
    <title>{{ __('meta.reset_password') }}</title>
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
        <img src="{{ secure_asset('images/logoindex.png') }}" alt="{{ __('meta.finca3pinos') }}" class="logo">
        <h1>{{ __('meta.reset_password') }}</h1>
    </div>

    <div class="body">
        <p>{{ __('meta.product_v15') }},</p>

        <p>{{ __('meta.product_v16') }} <strong>{{ __('meta.finca3pinos') }}</strong>.</p>

        <p>{{ __('meta.product_v17') }}</p>

        <p style="text-align:center;">
            <a href="{{ $resetUrl }}" class="btn">{{ __('meta.product_v8') }}</a>
        </p>

        <p>{{ __('meta.product_v18') }}</p>

        <p style="margin-top: 20px;">{{ __('meta.product_v19') }}<br>{{ __('meta.product_v20') }} <strong>{{ __('meta.finca3pinos') }}</strong></p>
    </div>

    <div class="footer">
        &copy; {{ date('Y') }} {{ __('meta.derechos') }}<br>
        {{ __('meta.product_v21') }}

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
