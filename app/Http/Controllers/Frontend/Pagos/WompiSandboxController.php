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

    public function pagarNo3ds(Request $request)
    {
        Log::info('WOMPI.no3ds INPUT', $request->all());

        try {
            // === 1) Obtener access_token ===
            $tokenResp = Http::asForm()->post($this->idBase.'/connect/token', [
                'audience'      => 'wompi_api',
                'grant_type'    => 'client_credentials',
                'client_id'     => env('WOMPI_USER_PRINCIPAL_ID'),
                'client_secret' => env('WOMPI_API_KEY'),
            ]);

            if (!$tokenResp->ok()) {
                Log::error('WOMPI.token FAIL', ['resp' => $tokenResp->json()]);
                return response()->json(['ok' => false, 'msg' => 'No se pudo obtener token OAuth2'], 500);
            }

            $accessToken = $tokenResp->json('access_token');
            $authHeaders = array_merge($this->headers, ['Authorization' => "Bearer {$accessToken}"]);

            // === 2) Tokenizar tarjeta ===
            $tokenCard = Http::withHeaders($authHeaders)
                ->post($this->apiBase.'/Tokenizacion', [
                    'numeroTarjeta'   => $request->numeroTarjeta,
                    'cvv'             => $request->cvv,
                    'mesVencimiento'  => (int)$request->mesVencimiento,
                    'anioVencimiento' => (int)$request->anioVencimiento,
                    'nombreTarjeta'   => $request->nombreTarjeta,
                ]);

            if (!$tokenCard->ok() || !$tokenCard->json('token')) {
                Log::error('WOMPI.tokenizacion FAIL', ['resp' => $tokenCard->json()]);
                return response()->json(['ok' => false, 'msg' => 'Error al tokenizar tarjeta', 'resp' => $tokenCard->json()], 500);
            }

            $tokenTarjeta = $tokenCard->json('token');
            Log::info('WOMPI.tokenizacion OK', ['tokenTarjeta' => $tokenTarjeta]);

            // === 3) Crear transacción TokenizadaSin3Ds ===
            $referencia = $request->referencia ?: ('F3P-' . now()->format('Ymd-His') . '-' . Str::upper(Str::random(5)));

            $body = [
                'tokenTarjeta'     => $tokenTarjeta,
                'monto'            => round($request->monto, 2),
                'moneda'           => $request->moneda ?? 'USD',
                'descripcion'      => $request->descripcion ?? 'Compra prueba sin 3DS',
                'referencia'       => $referencia,

                'emailCliente'     => $request->emailCliente,
                'nombreCliente'    => $request->nombreCliente,
                'apellidoCliente'  => $request->apellidoCliente,
                'telefonoCliente'  => $request->telefonoCliente,

                'direccionCliente' => $request->direccionCliente,
                'ciudadCliente'    => $request->ciudadCliente,
                'idPais'           => (int)$request->idPais,
                'idRegion'         => (int)$request->idRegion,
                'codigoPostal'     => $request->codigoPostal,

                'captura'          => true,
            ];

            $transResp = Http::withHeaders($authHeaders)
                ->post($this->apiBase.'/TransaccionCompra/TokenizadaSin3Ds', $body);

            Log::info('WOMPI.transaccion RESP', ['resp' => $transResp->json()]);

            if (!$transResp->ok()) {
                return response()->json(['ok' => false, 'msg' => 'Error al crear transacción', 'resp' => $transResp->json()], 500);
            }

            return response()->json($transResp->json(), 200);

        } catch (\Throwable $e) {
            Log::error('WOMPI.no3ds EXCEPTION', ['msg' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return response()->json(['ok' => false, 'msg' => $e->getMessage()], 500);
        }
    }



}
