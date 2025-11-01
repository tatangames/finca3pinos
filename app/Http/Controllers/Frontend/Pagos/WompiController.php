<?php

namespace App\Http\Controllers\Frontend\Pagos;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WompiController extends Controller
{
    protected string $api;
    protected string $auth;
    protected array  $jsonHeaders;

    public function __construct()
    {
        $this->api  = rtrim(env('WOMPI_SV_API',  'https://api.wompi.sv'), '/');
        $this->auth = rtrim(env('WOMPI_SV_AUTH', 'https://id.wompi.sv/connect/token'), '/');

        // cabeceras JSON base (sin Authorization; se inyecta en cada request)
        $this->jsonHeaders = [
            'Accept'       => 'application/json',
            'Content-Type' => 'application/json',
        ];
    }

    /** OAuth2 Client Credentials -> access_token (cacheado) */
    protected function token(): string
    {
        $cacheKey = 'wompi_access_token';
        if (cache()->has($cacheKey)) {
            return (string) cache()->get($cacheKey);
        }

        $res = Http::asForm()->post($this->auth, [
            'grant_type'    => 'client_credentials',
            'audience'      => 'wompi_api',
            'client_id'     => env('WOMPI_APP_ID'),
            'client_secret' => env('WOMPI_SECRET'),
        ]);

        if (!$res->ok()) {
            Log::error('WOMPI OAuth error', ['status'=>$res->status(), 'body'=>$res->json()]);
            abort(500, 'No se pudo autenticar con Wompi');
        }

        $access = (string) $res->json('access_token');
        $ttl    = max(60, ((int) $res->json('expires_in', 3600)) - 60);
        cache()->put($cacheKey, $access, $ttl);

        return $access;
    }

    /** 1) TOKENIZACIÓN: recibe PAN/CVC/exp del browser, llama a Wompi y devuelve token */
    public function tokenize(Request $r)
    {
        $v = $r->validate([
            'number' => ['required','string'],
            'cvc'    => ['required','string','max:4'],
            'exp_m'  => ['required','string'],
            'exp_y'  => ['required','string'],
        ]);

        // VALIDACIÓN + NORMALIZACIÓN
        $pan = preg_replace('/\D+/', '', (string) $v['number']); // solo dígitos
        $mm  = (string) $v['exp_m'];
        $yy4 = preg_replace('/\D+/', '', (string) $v['exp_y']); // debe ser AAAA

        if (strlen($pan) < 13 || strlen($pan) > 19) {
            return response()->json(['ok'=>false,'mensaje'=>'Número de tarjeta inválido'], 422);
        }
        if (!preg_match('/^\d{1,2}$/', $mm) || (int)$mm < 1 || (int)$mm > 12) {
            return response()->json(['ok'=>false,'mensaje'=>'Mes de vencimiento inválido'], 422);
        }
        if (!preg_match('/^\d{4}$/', $yy4)) {
            return response()->json(['ok'=>false,'mensaje'=>'El año de vencimiento debe tener 4 dígitos (AAAA)'], 422);
        }
        $mm2 = str_pad($mm, 2, '0', STR_PAD_LEFT);
        $cvv = preg_replace('/\D+/', '', (string) $v['cvc']);

        // PAYLOAD EXACTO QUE ESPERA WOMPI (sin guiones y con AAAA)
        $payload = [
            'numeroTarjeta'   => $pan,
            'cvv'             => $cvv,
            'mesVencimiento'  => (int) $mm2,   // pueden aceptar numérico; si prefieres string usa $mm2
            'anioVencimiento' => (int) $yy4,   // **AÑO EN 4 DÍGITOS**
        ];

        Log::info('WOMPI tokenization INPUT (masked)', [
            'pan_last4' => substr($pan, -4),
            'mm' => $mm2,
            'yy4'=> $yy4,
        ]);

        $res = Http::withHeaders([
            'Accept'        => 'application/json',
            'Content-Type'  => 'application/json',
            'Authorization' => 'Bearer '.$this->token(),
        ])->post("{$this->api}/Tokenizacion", $payload);

        if (!$res->ok()) {
            $err = $res->json() ?? [];
            $msg = $err['mensaje']
                ?? (is_array($err['mensajes'] ?? null) ? implode(' | ', $err['mensajes']) : 'Error tokenizando');
            Log::error('WOMPI tokenization FAIL', ['status'=>$res->status(), 'body'=>$err]);
            return response()->json(['ok'=>false, 'mensaje'=>$msg, 'raw'=>$err], 422);
        }

        $j = $res->json();
        Log::info('WOMPI tokenization OK', $j);

        return response()->json([
            'ok'    => true,
            'token' => $j['token'] ?? ($j['idToken'] ?? null),
            'brand' => $j['marca'] ?? null,
            'last4' => $j['ultimos4'] ?? substr($payload['numeroTarjeta'], -4),
        ]);
    }

    /** 2) COMPRA 3DS: usa token + cvv, crea transacción y devuelve url para iframe/popup */
    public function pay3ds(Request $r)
    {
        $v = $r->validate([
            'envio_id' => ['required'],
            'billing'  => ['nullable','array'],

            // crudo (prioritario para tu tenant)
            'numero'   => ['nullable','string'],
            'exp_m'    => ['nullable','string'],
            'exp_y'    => ['nullable','string'], // AAAA
            'cvv'      => ['required','string','max:4'],

            // token (opcional)
            'token'    => ['nullable','string'],
        ]);

        // ===== total demo =====
        $monto = (float) (session('cart_total') ?? 0.01);
        $monto = number_format($monto, 2, '.', ''); // "10.00"
        $order = 'F3P-'.now()->format('Ymd-His');

        // ===== normalizaciones cliente =====
        $tel = preg_replace('/\D+/', '', (string) data_get($v,'billing.telefono','50370000000'));
        if (strlen($tel) < 8) $tel = '50370000000';

        // ===== TARJETA: prioridad CRUDO =====
        $tarjeta = [];
        $usaCrudo = false;

        if (!empty($v['numero']) && !empty($v['exp_m']) && !empty($v['exp_y'])) {
            $pan = preg_replace('/\D+/', '', (string) $v['numero']);
            $mm  = (int) $v['exp_m'];
            $yy4 = preg_replace('/\D+/', '', (string) $v['exp_y']);

            if (strlen($pan) < 13 || strlen($pan) > 19) {
                return response()->json(['ok'=>false,'mensaje'=>'Número de tarjeta inválido'], 422);
            }
            if ($mm < 1 || $mm > 12) {
                return response()->json(['ok'=>false,'mensaje'=>'Mes de vencimiento inválido'], 422);
            }
            if (!preg_match('/^\d{4}$/', $yy4)) {
                return response()->json(['ok'=>false,'mensaje'=>'El año de vencimiento debe tener 4 dígitos (AAAA)'], 422);
            }

            $tarjeta = [
                'numeroTarjeta'   => $pan,
                'mesVencimiento'  => $mm,
                'anioVencimiento' => (int) $yy4,
                'cvv'             => preg_replace('/\D+/', '', $v['cvv']),
            ];
            $usaCrudo = true;

            Log::info('WOMPI 3DS usando CRUDO (masked)', [
                'last4' => substr($pan, -4), 'mm'=>$mm, 'yy4'=>$yy4
            ]);
        } elseif (!empty($v['token'])) {
            $tarjeta = [
                'token' => $v['token'],
                'cvv'   => preg_replace('/\D+/', '', $v['cvv']),
            ];
            Log::info('WOMPI 3DS usando TOKEN');
        } else {
            return response()->json(['ok'=>false,'mensaje'=>'Faltan datos de tarjeta'], 422);
        }

        // ===== País/Región FIJOS (solo El Salvador) =====
        $idPaisCode   = 'SV';     // El Salvador (ISO-3166-1 alpha-2)
        $idRegionCode = 'SV-SA';  // Santa Ana por defecto (ISO-3166-2)
        // Si quieres fijar San Salvador sería 'SV-SS', La Libertad 'SV-LI', etc.

        $body = [
            'tarjetaCreditoDebido' => $tarjeta,
            'monto'        => (float) $monto,
            'moneda'       => env('WOMPI_CURRENCY', 'USD'),
            'descripcion'  => "Orden {$order}",
            'urlRedirect'  => env('WOMPI_RETURN_URL'),

            'nombre'       => auth()->user()->name ?? 'Cliente',
            'apellido'     => 'Web',
            'email'        => auth()->user()->email ?? 'cliente@example.com',
            'telefono'     => $tel,
            'direccion'    => (string) data_get($v,'billing.direccion','—'),
            'ciudad'       => (string) data_get($v,'billing.ciudad','Santa Ana'),

            'idPais'       => $idPaisCode,    // <-- fijo
            'idRegion'     => $idRegionCode,  // <-- fijo (ajústalo si quieres otro depto)
            'codigoPostal' => (string) data_get($v,'billing.codigo_postal','2201'),
        ];

        Log::info('WOMPI 3DS INPUT', [
            'order'    => $order,
            'usaCrudo' => $usaCrudo,
            'idPais'   => $idPaisCode,
            'idRegion' => $idRegionCode,
            'body'     => array_merge($body, [
                'tarjetaCreditoDebido' => $usaCrudo
                    ? ['last4'=>substr($tarjeta['numeroTarjeta'],-4),'mm'=>$tarjeta['mesVencimiento'],'yy4'=>$tarjeta['anioVencimiento']]
                    : ['token'=>data_get($tarjeta,'token')]
            ]),
        ]);

        $res = Http::withHeaders([
            'Accept'        => 'application/json',
            'Content-Type'  => 'application/json',
            'Authorization' => 'Bearer '.$this->token(),
        ])->post("{$this->api}/TransaccionCompra/3DS", $body);

        $json = $res->json();
        Log::info('WOMPI 3DS RESP', ['status'=>$res->status(), 'json'=>$json]);

        if (!$res->ok()) {
            $msg = $json['mensaje'] ?? (is_array($json['mensajes'] ?? null) ? implode(' | ', $json['mensajes']) : 'No se pudo iniciar 3DS');
            return response()->json(['ok'=>false, 'mensaje'=>$msg], 422);
        }

        return response()->json([
            'ok'            => true,
            'requiere3ds'   => true,
            'url3ds'        => $json['urlCompletarPago3Ds'] ?? null,
            'idTransaccion' => $json['idTransaccion']      ?? null,
        ]);
    }




    /** 3) Consultar estado por idTransaccion (para polling o postMessage) */
    public function txStatus(Request $r)
    {
        $id = $r->query('id');
        if (!$id) return response()->json(['ok'=>false,'mensaje'=>'Falta idTransaccion'], 422);

        $url = "{$this->api}/TransaccionCompra/{$id}";

        try {
            $res = Http::withHeaders($this->jsonHeaders + [
                    'Authorization' => 'Bearer '.$this->token(),
                ])->get($url);

            Log::info('WOMPI STATUS RESP', [
                'id'     => $id,
                'status' => $res->status(),
                'body'   => $res->json(),
            ]);

            if (!$res->ok()) {
                $j   = $res->json() ?? [];
                $msg = $j['mensaje']
                    ?? (is_array($j['mensajes'] ?? null) ? implode(' | ', $j['mensajes']) : 'No se pudo consultar el estado');
                return response()->json(['ok'=>false,'mensaje'=>$msg,'raw'=>$j], 422);
            }

            $j  = $res->json() ?? [];
            $aprobada  = (bool) ($j['esAprobada'] ?? false);
            $resultado = (string) ($j['resultadoTransaccion'] ?? '');
            $mensaje   = (string) ($j['mensaje'] ?? '');
            $authCode  = $j['codigoAutorizacion'] ?? null;

            // Mapeo de estado legible para el front
            $estado = $aprobada ? 'APROBADA'
                : (stripos($resultado, 'Declinada') !== false ? 'DECLINADA'
                    : (stripos($resultado, 'Pendiente') !== false ? 'PENDIENTE'
                        : 'FALLIDA'));

            // Texto explicativo corto
            $detalle = $mensaje ?: (
            $estado === 'DECLINADA' ? 'Transacción declinada por el emisor.' :
                ($estado === 'PENDIENTE' ? 'Transacción pendiente de confirmación bancaria.' :
                    ($estado === 'APROBADA' ? 'Pago aprobado.' : 'No fue posible completar el pago.'))
            );

            return response()->json([
                'ok'             => true,
                'idTransaccion'  => $j['idTransaccion'] ?? $id,
                'estado'         => $estado,
                'detalle'        => $detalle,                 // <-- para mostrar en el modal
                'resultado'      => $resultado,               // info cruda por si la quieres
                'codigoMensaje'  => $mensaje ?: null,
                'codigoAutorizacion' => $authCode,
                'monto'          => $j['monto']  ?? null,
                'moneda'         => $j['moneda'] ?? null,
                'raw'            => $j,                       // deja mientras pruebas
            ]);
        } catch (\Throwable $e) {
            Log::error('WOMPI STATUS ERROR', ['id'=>$id, 'message'=>$e->getMessage()]);
            return response()->json([
                'ok'=>false, 'mensaje'=>'Error al consultar el estado', 'error'=>$e->getMessage()
            ], 500);
        }
    }




    /** 4) URL de retorno usada dentro del iframe/popup para cerrar tu modal */
    public function return(Request $r)
    {
        $id  = $r->query('idTransaccion');
        $est = $r->query('estado'); // APROBADA|DECLINADA|FALLIDA|PENDIENTE
        Log::info('WOMPI RETURN HIT', ['id'=>$id, 'estado'=>$est]);

        // Vista minimal que hace postMessage al abrirse
        return response()->view('payments.wompi_return', compact('id','est'));
    }


}
