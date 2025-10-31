<?php

namespace App\Http\Controllers\Frontend\Pagos;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderPayment;
use App\Services\WompiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;
class WompiController extends Controller
{

    public function __construct(private WompiService $wompi){}

    public function create(Request $request)
    {
        // ===== 1) Log de entrada + validación clara =====
        Log::info('wompi.create INPUT', [
            'payload' => $request->all(),
            'ip'      => $request->ip(),
        ]);

        $v = Validator::make($request->all(), [
            'order_code'  => ['required', 'string', 'max:60', 'exists:orders,code'],
            'description' => ['nullable', 'string', 'max:255'],
            'customer'    => ['nullable', 'array'], // si lo mandas, que sea objeto/array real
        ], [
            'order_code.required' => 'Falta el código de la orden.',
            'order_code.exists'   => 'La orden no existe.',
        ]);

        if ($v->fails()) {
            return response()->json([
                'ok'     => false,
                'why'    => 'validation',
                'errors' => $v->errors(),
            ], 422);
        }

        $data  = $v->validated();
        $order = Order::where('code', $data['order_code'])->with(['shippingAddress', 'billingAddress', 'user'])->firstOrFail();

        // ===== 2) Monto desde la BD (seguro). Ajusta centavos según tu Wompi =====
        $useCents     = true; // <-- pon false si tu integración NO usa centavos
        $amountCents  = (int) round($order->grand_total * 100);
        $amountForApi = $useCents ? $amountCents : (float) $order->grand_total;

        // ===== 3) Datos de contexto =====
        $description = $data['description'] ?? ('Orden '.$order->code);
        $customer    = $data['customer'] ?? [
            'name'  => optional($order->billingAddress)->name
                ?? optional($order->shippingAddress)->name
                    ?? optional($order->user)->name,
            'email' => optional($order->user)->email,
            'phone' => optional($order->shippingAddress)->phone,
        ];

        // ===== 4) Llamada a Wompi (usa tu servicio) =====
        try {
            $res = $this->wompi->createCheckout([
                'order_id'    => $order->code,           // referencia interna
                'amount'      => $amountForApi,          // int (centavos) o decimal según $useCents
                'currency'    => $order->currency,       // 'USD' / 'COP' / etc.
                'description' => $description,
                'customer'    => $customer,
                'return_url'  => config('wompi.return'),
                'notify_url'  => config('wompi.notify'),
            ]);
        } catch (\Throwable $e) {
            Log::error('Wompi create exception', ['order' => $order->code, 'ex' => $e->getMessage()]);
            return response()->json([
                'ok'  => false,
                'why' => 'exception',
                'msg' => 'No se pudo contactar a Wompi.',
            ], 502);
        }

        if (empty($res) || empty($res['ok'])) {
            Log::error('Wompi create error (sin ok)', ['order' => $order->code, 'res' => $res]);
            return response()->json([
                'ok'  => false,
                'why' => 'wompi',
                'msg' => 'No se pudo iniciar el pago con Wompi.',
            ], 502);
        }

        if (empty($res['redirect_url']) || empty($res['session_id'])) {
            Log::error('Wompi create error (faltan campos)', ['order' => $order->code, 'res' => $res]);
            return response()->json([
                'ok'  => false,
                'why' => 'wompi_payload',
                'msg' => 'Respuesta inválida de Wompi.',
            ], 502);
        }

        // ===== 5) Registrar intento de pago =====
        OrderPayment::create([
            'order_id'         => $order->id,
            'gateway'          => 'wompi',
            'gateway_env'      => config('wompi.env'),
            'method'           => 'card',
            'status'           => 'initiated',
            'amount'           => $order->grand_total,            // en tu DB lo guardas legible (no centavos)
            'currency'         => $order->currency,
            'token'            => $res['session_id'],
            'request_payload'  => $res['raw'] ?? null,            // si tu servicio guarda raw
            'response_payload' => $res['raw'] ?? null,
        ]);

        // ===== 6) Marcar orden como pendiente de pago =====
        $order->update([
            'pay_gateway' => 'wompi',
            'pay_token'   => $res['session_id'],
            'status'      => 'payment_pending',
        ]);

        // ===== 7) Respuesta al front =====
        return response()->json([
            'ok'       => true,
            'redirect' => $res['redirect_url'],
        ]);
    }


    public function return(Request $request)
    {
        // El estado final lo confirmas por webhook.
        return view('pagos.retorno', [
            'token' => $request->get('id') ?? $request->get('session') ?? null,
        ]);
    }

    public function notify(Request $request)
    {
        $raw = $request->getContent();
        $sig = $request->header('Wompi-Signature') ?? $request->header('X-Signature') ?? '';

        if (!$this->wompi->verifyWebhook($sig, $raw)) {
            Log::warning('Wompi firma inválida');
            return response('invalid', 400);
        }

        $payload = $request->json()->all();
        Log::info('Wompi notify', $payload);

        // Mapea campos reales según evento: approved/declined/pending, order_id, transaction_id...
        $orderCode = $payload['order_id'] ?? ($payload['data']['order_id'] ?? null);
        $status    = strtolower($payload['status'] ?? ($payload['data']['status'] ?? ''));
        $txnId     = $payload['transaction_id'] ?? ($payload['data']['id'] ?? null);
        $amount    = isset($payload['amount']) ? (float) $payload['amount'] : null;

        if(!$orderCode) return response('no order', 400);

        $order = Order::where('code', $orderCode)->first();
        if(!$order) return response('not found', 404);

        $payment = OrderPayment::where('order_id', $order->id)
            ->where('gateway','wompi')
            ->latest()->first();

        if($payment){
            $payment->transaction_id  = $txnId;
            $payment->response_payload = $payload;
            $payment->status = match($status){
                'approved','paid','successful' => 'approved',
                'failed','declined'            => 'failed',
                default                        => 'pending',
            };
            if($amount && !$payment->amount) $payment->amount = $amount/100; // si vino en centavos
            $payment->save();
        }

        // Validación simple de monto (opcional)
        // if($amount && (int)round($order->grand_total*100) !== (int)$amount) { ... }

        switch ($status) {
            case 'approved':
            case 'paid':
            case 'successful':
                $order->status = 'paid';
                $order->paid_at = now();
                $order->save();
                break;
            case 'failed':
            case 'declined':
                $order->status = 'failed';
                $order->save();
                break;
            default:
                // pending / review
                $order->status = 'payment_pending';
                $order->save();
        }

        return response('ok', 200);
    }
}
