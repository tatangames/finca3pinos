@component('mail::message')
    {{-- Encabezado --}}
    # Restablecer contraseña

    Hola **{{ $nombre }}**,

    Recibimos una solicitud para restablecer tu contraseña del panel de **{{ $marca }}** ({{ $email }}).

    @component('mail::button', ['url' => $url])
        Restablecer ahora
    @endcomponent

    > Si tú no solicitaste este cambio, ignora este mensaje.

    Gracias,<br>
    Equipo {{ $marca }}

    {{-- Footer opcional con estilo --}}
    @slot('subcopy')
        <div style="text-align:center;margin-top:12px;font-size:12px;">
    <span style="display:inline-block;padding:4px 10px;background:#D2AA6D;color:#fff;border-radius:6px;">
        {{ $marca }}
    </span>
        </div>
    @endslot
@endcomponent
