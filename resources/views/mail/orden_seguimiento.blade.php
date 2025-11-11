<!DOCTYPE html>
<html lang="{{ $locale ?? app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <title>{{ __('meta.your_order_has_been_shipped') }}</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background: #f9f9f9;
            margin: 0;
            padding: 0;
            color: #333;
        }
        .container {
            background: #fff;
            margin: 20px auto;
            padding: 20px;
            max-width: 600px;
            border-radius: 8px;
        }
        h2 {
            color: #2a58ff;
        }
        .footer {
            margin-top: 20px;
            font-size: 12px;
            color: #777;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>{{ __('meta.your_order_has_been_shipped') }} 🚚</h2>

    <p>{{ __('meta.product_v15') }} {{ $orden->usuario->nombre }},</p>
    <p>
        {{ __('meta.your_order') }}
        <strong>#{{ $orden->id }}</strong>
        {{ __('meta.has_been_shipped_and_on_the_way') }}
    </p>

    @if($fechaEnvio)
        <p><strong>{{ __('meta.shipping_date') }}:</strong> {{ $fechaEnvio }}</p>
    @endif

    <p>{{ __('meta.you_can_check_order_status') }}</p>

    <!-- Botón principal -->
    <p style="margin-top: 30px; text-align: center;">
        <a href="{{ url('/') }}"
           style="background:#2a58ff;
                  color:#ffffff !important;
                  padding:12px 24px;
                  text-decoration:none !important;
                  border-radius:6px;
                  border:1px solid #2a58ff;
                  font-weight:bold;
                  font-family:Arial, sans-serif;
                  display:inline-block;
                  text-align:center;">
            {{ __('meta.view_my_order') }}
        </a>
    </p>

    <div class="footer">
        <p>{{ __('meta.thank_you_finca') }}</p>
    </div>
</div>
</body>
</html>
