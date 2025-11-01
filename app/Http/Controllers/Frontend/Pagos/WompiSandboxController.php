<?php

namespace App\Http\Controllers\Frontend\Pagos;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderPayment;
use App\Services\WompiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class WompiSandboxController extends Controller
{

    private string $apiBase;
    private string $idBase;
    private array $headers;

    public function __construct()
    {
        $this->apiBase = rtrim(env('WOMPI_API_BASE', 'https://api.wompi.sv'), '/');
        $this->idBase  = rtrim(env('WOMPI_ID_BASE', 'https://id.wompi.sv'), '/');

        $this->headers = [
            'Accept'            => 'application/json',
            'Content-Type'      => 'application/json',
            'user-principal-id' => env('WOMPI_USER_PRINCIPAL_ID'),
            'x-api-key'         => env('WOMPI_API_KEY'),
        ];
    }










    protected function getToken(): string
    {
        // Cachea el token 55 min
        return Cache::remember('wompi_oauth_token', 55*60, function () {
            $resp = Http::asForm()->post('https://id.wompi.sv/connect/token', [
                'grant_type'    => 'client_credentials',
                'client_id'     => env('WOMPI_APP_ID'),
                'client_secret' => env('WOMPI_API_SECRET'),
                'audience'      => 'wompi_api',
            ]);

            if (!$resp->ok()) {
                Log::error('Wompi OAuth error', ['body' => $resp->body()]);
                abort(500, 'No se pudo autenticar con Wompi.');
            }

            return $resp->json('access_token');
        });
    }

    public function pagar3ds(Request $request)
    {
        // Para la demo: toma datos mínimos del request o arma dummy
        // Ideal: venir de tu "place order" con montos/dirección ya calculados
        $orderCode = $request->input('order_code', 'DEMO-ORDER');
        $monto     = (float) ($request->input('monto') ?? 10.00);

        // Datos del cliente (tómalos del form Paso 3)
        $cli = [
            'nombre'       => $request->input('nombre' , 'Juan'),
            'apellido'     => $request->input('apellido','Pérez'),
            'email'        => $request->input('email'   ,'[email protected]'),
            'ciudad'       => $request->input('ciudad'  ,'Santa Ana'),
            'direccion'    => $request->input('direccion','Calle 123'),
            'idPais'       => (string)($request->input('idPais','SV')),     // ISO 3166-1 alpha2
            'idRegion'     => (string)($request->input('idRegion','SV-SA')),// ISO 3166-2
            'codigoPostal' => (string)($request->input('zip','2201')),
            'telefono'     => (string)($request->input('tel','50370000000')),
        ];

        // PAN/CVV solo para sandbox (en productivo debes usar tokenización / campos hospedados)
        $card = [
            'numeroTarjeta'   => $request->input('pan'       ,'4242424242424242'),
            'cvv'             => $request->input('cvc'       ,'123'),
            'mesVencimiento'  => (int)($request->input('mm'  , 12)),
            'anioVencimiento' => (int)($request->input('yyyy', 2030)),
        ];

        $payload = [
            'tarjetaCreditoDebido' => $card,
            'monto'       => $monto,
            'urlRedirect' => route('wompi.redirect'), // ← donde volverá el navegador
            'nombre'      => $cli['nombre'],
            'apellido'    => $cli['apellido'],
            'email'       => $cli['email'],
            'ciudad'      => $cli['ciudad'],
            'direccion'   => $cli['direccion'],
            'idPais'      => $cli['idPais'],
            'idRegion'    => $cli['idRegion'],
            'codigoPostal'=> $cli['codigoPostal'],
            'telefono'    => $cli['telefono'],
            // Config opcional (puedes pasar tu webhook aquí también)
            'configuracion' => [
                'urlWebhook' => route('wompi.webhook'),
            ],
            // datosAdicionales opcional (identifica la orden, etc.)
            'datosAdicionales' => [
                'order_code' => $orderCode,
            ],
        ];

        $token = $this->getToken();

        $resp = Http::withToken($token)
            ->acceptJson()->asJson()
            ->post('https://api.wompi.sv/TransaccionCompra/3DS', $payload);

        Log::info('wompi.3ds.create RESP', ['status'=>$resp->status(),'json'=>$resp->json()]);

        if (!$resp->ok()) {
            return response()->json([
                'ok' => false,
                'mensaje' => $resp->json('mensaje') ?? 'Error creando transacción 3DS',
                'raw' => $resp->json(),
            ], 400);
        }

        // Wompi devuelve: idTransaccion, esReal, urlCompletarPago3Ds, monto
        $url3ds = $resp->json('urlCompletarPago3Ds');
        $txId   = $resp->json('idTransaccion');

        return response()->json([
            'requiere3ds'    => true,
            'url3ds'         => $url3ds,
            'idTransaccion'  => $txId,
        ]);
    }

    public function txStatus(Request $request)
    {
        $id = $request->query('id');
        if (!$id) return response()->json(['mensaje'=>'Falta id'], 422);

        $token = $this->getToken();
        $resp = Http::withToken($token)->get("https://api.wompi.sv/TransaccionCompra/{$id}");

        Log::info('wompi.tx.status RESP', ['status'=>$resp->status(),'json'=>$resp->json()]);

        if (!$resp->ok()) {
            return response()->json(['estado'=>'ERROR','mensaje'=>'No se pudo consultar el estado'], 400);
        }

        // Normaliza al contrato que tu JS espera
        $data = $resp->json();
        return response()->json([
            'idTransaccion'     => $data['idTransaccion'] ?? $id,
            'estado'            => !empty($data['esAprobada']) ? 'APROBADA' : 'FALLIDA',
            'esReal'            => (bool)($data['esReal'] ?? false),
            'codigoAutorizacion'=> $data['codigoAutorizacion'] ?? null,
            'monto'             => (float)($data['monto'] ?? 0),
            'raw'               => $data,
        ]);
    }

    public function redirect3ds(Request $request)
    {
        // Wompi te envía parámetros en la URL para saber si aprobó (esAprobada, esReal, etc.)
        // Recomendado: validar hash o contrastar con GET /TransaccionCompra/{id}
        $txId   = $request->query('idTransaccion');
        $ok     = filter_var($request->query('esAprobada'), FILTER_VALIDATE_BOOLEAN);
        $monto  = $request->query('monto');
        $codAut = $request->query('codigoAutorizacion');

        Log::info('wompi.redirect', $request->query());

        // Muestra una vista de resultado o redirige a tu "Gracias"
        if ($ok) {
            return view('checkout.gracias', compact('txId','monto','codAut'));
        }
        return view('checkout.fallo', compact('txId'))->with('msg', $request->query('mensaje'));
    }

    public function webhook(Request $request)
    {
        Log::info('wompi.webhook', ['json'=>$request->all()]);
        // Aquí puedes marcar la orden como pagada. Para la prueba solo registramos log.
        return response()->json(['ok'=>true]);
    }









}
