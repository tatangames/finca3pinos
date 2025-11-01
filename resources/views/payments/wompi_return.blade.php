{{-- resources/views/payments/wompi_return.blade.php --}}
    <!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Procesando...</title>
    <script>
        window.addEventListener('DOMContentLoaded', function() {
            const payload = {
                type: "WOMPI_3DS_DONE",
                idTransaccion: "{{ $id ?? '' }}",
                estado: "{{ $est ?? '' }}"
            };
            try { if (window.opener) window.opener.postMessage(payload, "*"); } catch(e){}
            try { if (window.parent) window.parent.postMessage(payload, "*"); } catch(e){}
        });
    </script>
</head>
<body style="font-family:system-ui; text-align:center; padding:2rem">
<h2>Procesando resultado del pago...</h2>
<p>Puedes cerrar esta ventana.</p>
</body>
</html>
