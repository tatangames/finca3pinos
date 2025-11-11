<!DOCTYPE html>
<html lang="{{ $locale ?? app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <title>{{ __('meta.order_preparing_title') }}</title>
    <style>
        body { font-family: 'Arial', sans-serif; background: #f9f9f9; margin: 0; padding: 0; color: #333; }
        .container { background: #fff; margin: 20px auto; padding: 20px; max-width: 600px; border-radius: 8px; }
        h2 { color: #b48a4e; }
        .footer { margin-top: 20px; font-size: 12px; color: #777; }
        .btn {
            background: #b48a4e;
            color: #ffffff;
            padding: 10px 18px;
            text-decoration: none;
            border-radius: 5px;
            border: 1px solid #b48a4e;
            font-weight: bold;
            display: inline-block;
        }
        .btn:hover {
            background: #b48a4e;
            color: #ffffff;
        }
    </style>

</head>
<body>
<div class="container">
    <h2>{{ __('meta.order_preparing_title') }} 🧾</h2>
    <p>{{ __('meta.product_v15') }} {{ $orden->usuario->nombre}},</p>
    <p>{{ __('meta.we_are_happy_to_inform_you') }} <strong>#{{ $orden->id }}</strong> {{ __('meta.is_being_prepared') }}</p>

    @if($fechaPreparacion)
        <p><strong>{{ __('meta.preparation_date') }}:</strong> {{ $fechaPreparacion }}</p>
    @endif

    <p>{{ __('meta.we_will_notify_when_shipped') }}</p>

    <p style="margin-top: 30px; text-align: center;">
        <a href="{{ url('/') }}"
           style="background:#b48a4e;
              color:#ffffff !important;
              padding:12px 24px;
              text-decoration:none !important;
              border-radius:6px;
              border:1px solid #b48a4e;
              font-weight:bold;
              font-family:Arial, sans-serif;
              display:inline-block;
              text-align:center;">
            {{ __('meta.go_to_store') }}
        </a>
    </p>

    <div class="footer">
        <p>{{ __('meta.thank_you_finca') }}</p>
    </div>
</div>
</body>
</html>
