{{-- resources/views/payments/wompi_return.blade.php --}}
    <!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Procesando pago...</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body {
            font-family: system-ui, -apple-system, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            background: #fafafa;
            color: #333;
            margin: 0;
            padding: 2rem;
            text-align: center;
        }
        .wrap {
            max-width: 480px;
            margin: 80px auto;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,.08);
            padding: 32px 20px;
        }
        h2 {
            margin-top: 0;
            font-weight: 600;
            color: #2a2a2a;
        }
        p {
            margin: 12px 0 0;
            color: #666;
        }
        .loader {
            margin: 24px auto;
            border: 5px solid #eee;
            border-top: 5px solid #d2aa6d;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
        }
        @keyframes spin { 0% { transform: rotate(0deg);} 100% { transform: rotate(360deg);} }
    </style>
    <script>
        // Al cargar esta página (dentro del iframe/popup de 3DS),
        // notifica al parent o al opener que el flujo 3DS terminó.
        window.addEventListener('DOMContentLoaded', function() {
            const payload = {
                type: "WOMPI_3DS_DONE",
                idTransaccion: "{{ $id ?? '' }}",
                estado: "{{ $est ?? '' }}"
            };
            try {
                if (window.opener) window.opener.postMessage(payload, "*");
                if (window.parent) window.parent.postMessage(payload, "*");
            } catch (e) {
                console.error('postMessage error', e);
            }
        });
    </script>
</head>
<body>
<div class="wrap">
    <div class="loader"></div>
    <h2>Procesando resultado del pago...</h2>
    <p>Puedes cerrar esta ventana una vez completado.</p>
</div>
</body>
</html>
