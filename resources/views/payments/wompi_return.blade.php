{{-- resources/views/payments/wompi_return.blade.php --}}
    <!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Procesando...</title>
    <script>
        window.addEventListener('message', async (ev)=>{
            if (!ev?.data || ev.data.type !== 'WOMPI_3DS_DONE') return;

            // cierra modal/iframe o popup
            if (window.__close3DS) window.__close3DS();

            try {
                const r = await axios.get("{{ route('wompi.tx.status') }}", { params:{ id: ev.data.idTransaccion }});
                const st = (r?.data?.estado || '').toUpperCase(); // APROBADA|DECLINADA|FALLIDA|PENDIENTE|...
                if (st === 'APROBADA') {
                    Swal.fire({icon:'success', title:'Pago aprobado', text:`Tx ${ev.data.idTransaccion}`});
                    // TODO: aquí marcas orden como pagada, limpias carrito, rediriges al "gracias", etc.
                } else if (st === 'PENDIENTE') {
                    Swal.fire({icon:'info', title:'Pago pendiente', text:'En breve confirmaremos con tu banco.'});
                } else {
                    Swal.fire({icon:'error', title:'Pago no aprobado', text:`Estado: ${st || 'Desconocido'}`});
                }
            } catch (e) {
                toastr.error('No se pudo verificar el resultado.');
            }
        });

    </script>
</head>
<body style="font-family:system-ui; text-align:center; padding:2rem">
<h2>Procesando resultado del pago...</h2>
<p>Puedes cerrar esta ventana.</p>
</body>
</html>



