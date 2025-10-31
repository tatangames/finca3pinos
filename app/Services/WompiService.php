<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WompiService
{
    private function getToken(): string
    {
        $auth = config('wompi.sv.auth');
        $id   = config('wompi.app_id');
        $sec  = config('wompi.secret');

        $res = Http::asForm()->post($auth, [
            'grant_type'    => 'client_credentials',
            'audience'      => 'wompi_api',
            'client_id'     => $id,
            'client_secret' => $sec,
        ]);

        if (!$res->successful()) {
            Log::error('Wompi OAuth fail', ['status'=>$res->status(),'body'=>$res->body()]);
            throw new \RuntimeException('No se pudo autenticar con Wompi');
        }

        return $res->json('access_token');
    }

    /**
     * Crea un enlace de pago y devuelve urlEnlace para redirigir.
     * $data: order_id, amount (decimal o int), currency, description, customer, return_url, notify_url
     */
    public function createCheckout(array $data): array
    {
        $base   = rtrim(config('wompi.sv.api'), '/');
        $token  = $this->getToken();

        // En SV el “monto” va como decimal (>= 0.01) según la doc.
        $amountDecimal = is_int($data['amount']) ? $data['amount'] / 100 : (float) $data['amount'];

        // Forzamos mínimo (regla de Wompi SV: positivo)
        $amountDecimal = max(0.01, round($amountDecimal, 2));

        $reference     = $data['order_id']; // tu order_code
        $description   = $data['description'] ?? $reference;

        // Payload mínimo + configuración útil (redirect + webhook)
        $payload = [
            'identificadorEnlaceComercio' => $reference,
            'monto'        => round($amountDecimal, 2),
            'nombreProducto'=> $description,
            'configuracion' => [
                'urlRedirect' => $data['return_url'] ?? config('wompi.return'),
                'urlWebhook'  => $data['notify_url'] ?? config('wompi.notify'),
                // Si no usas webhook, puedes mandar 'emailsNotificacion' (al menos uno de los dos debe ir)
            ],
            // Opcional:
            // 'infoProducto' => ['descripcionProducto' => $description],
            // 'formaPago'    => ['permitirTarjetaCreditoDebido' => true, 'permitirPagoConPuntoAgricola' => false, 'permitirPagoEnCuotasAgricola' => false],
        ];

        Log::info('Wompi SV monto', [
            'order'  => $reference,
            'amount' => $amountDecimal,
            'raw'    => $data['amount'],
        ]);



        $url = $base . '/EnlacePago';

        $res = Http::withToken($token)
            ->acceptJson()
            ->post($url, $payload);

        Log::info('Wompi createCheckout RESP', ['status'=>$res->status(),'url'=>$url,'resp'=>$res->json()]);

        if (!$res->successful()) {
            return ['ok'=>false, 'redirect_url'=>null, 'session_id'=>null, 'raw'=>$res->json()];
        }

        $json       = $res->json();
        $redirect   = $json['urlEnlace']   ?? null;  // <- a esta URL rediriges
        $sessionId  = (string) ($json['idEnlace'] ?? ''); // Id interno del enlace

        return [
            'ok'           => (bool) $redirect,
            'redirect_url' => $redirect,
            'session_id'   => $sessionId,
            'raw'          => $json,
        ];
    }
}
