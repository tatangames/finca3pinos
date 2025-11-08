<?php

namespace App\Http\Controllers\Frontend\Pagos;

use App\Http\Controllers\Controller;
use App\Models\Direcciones;
use App\Models\Ordenes;
use App\Models\OrdenesItem;
use App\Traits\HandlesCart;
use App\Libraries\Pagadito;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PagaditoController extends Controller
{
    use HandlesCart;

    /**
     * @var Pagadito
     */
    protected $pagadito;

    public function __construct()
    {
        $this->middleware('auth:web');

        if (!class_exists(Pagadito::class)) {
            throw new \RuntimeException(
                'No se encontró App\\Libraries\\Pagadito. Verifica app/Libraries/Pagadito.php y composer dump-autoload.'
            );
        }

        $uid     = trim((string) config('services.pagadito.uid'));
        $wsk     = trim((string) config('services.pagadito.wsk'));
        $sandbox = (bool) config('services.pagadito.sandbox', true);
        $debug   = (bool) config('services.pagadito.debug', false);

        if ($uid === '' || $wsk === '') {
            throw new \RuntimeException('PAGADITO UID/WSK no configurados. Revisa config/services.php o .env.');
        }

        $this->pagadito = new Pagadito($uid, $wsk);

        if ($sandbox && method_exists($this->pagadito, 'mode_sandbox_on')) {
            $this->pagadito->mode_sandbox_on();
        }

        if ($debug && method_exists($this->pagadito, 'mode_debug')) {
            $this->pagadito->mode_debug(true);
        }
    }

    /**
     * Inicia la transacción con Pagadito:
     * - Valida carrito, dirección, billing
     * - Crea la orden (pending) + items en BD
     * - Llama a Pagadito con el ERN
     */
    public function init(Request $request)
    {
        // ===== Billing (JSON desde el front) =====
        $billing = $request->input('billing');
        if (is_string($billing)) {
            $billing = json_decode($billing, true);
        }

        $request->merge(['billing' => $billing]);

        $request->validate([
            'envio_id' => 'required|integer',
            'billing'  => 'required|array',
        ]);

        $userId = Auth::guard('web')->id();

        // ===== Carrito =====
        $cart  = $this->cart();
        $items = $cart->getContent();

        if ($items->isEmpty()) {
            return redirect()
                ->route('checkout.show')
                ->with('error', __('meta.your_cart_empty'));
        }

        $subtotal = (float) $cart->getSubTotal();

        if ($subtotal < 1) {
            return redirect()
                ->route('checkout.show')
                ->with('error', __('meta.minimum_amount_to_pay'));
        }

        $amount = (float) number_format($subtotal, 2, '.', '');

        // ===== Dirección de envío seleccionada =====
        $envioId = (int) $request->envio_id;

        $direccionEnvio = Direcciones::query()
            ->where('direcciones.id_usuario', $userId)
            ->where('direcciones.id', $envioId)
            ->first();

        if (!$direccionEnvio) {
            return redirect()
                ->route('checkout.show')
                ->with('error', __('meta.selected_shipping_notvalid'));
        }

        // ===== Conectar a Pagadito =====
        if (!$this->pagadito->connect()) {
            Log::channel('pagadito')->error('Error conectando a Pagadito [init]', [
                'code'    => $this->pagadito->get_rs_code(),
                'message' => $this->pagadito->get_rs_message(),
            ]);

            return back()->with(
                'error',
                __('meta.error_conecctiong_to_pagadito')
                . ' (' . $this->pagadito->get_rs_code() . '): '
                . $this->pagadito->get_rs_message()
            );
        }

        // ===== Generar ERN único =====
        $ern = 'F3P-' . $userId . '-' . time();

        // ===== Crear orden + items en transacción =====
        try {
            DB::beginTransaction();

            $order = Ordenes::create([
                'id_usuario'         => $userId,
                'ern'                => $ern,
                'fecha'              => now(),

                // Snapshot envío
                'shipping_nombre'    => $direccionEnvio->nombre,
                'shipping_telefono'  => $direccionEnvio->telefono,
                'shipping_pais'      => $direccionEnvio->id_paises, // o nombre si prefieres
                'shipping_estado'    => $direccionEnvio->estado,
                'shipping_ciudad'    => $direccionEnvio->ciudad,
                'shipping_direccion' => $direccionEnvio->direccion,
                'shipping_zipcode'   => $direccionEnvio->zipcode,

                // Snapshot facturación (desde $billing)
                'billing_nombre'     => $billing['nombre']   ?? null,
                'billing_direccion'  => $billing['direccion'] ?? null,
                'billing_ciudad'     => $billing['ciudad']    ?? null,
                'billing_estado'     => $billing['estado']    ?? null,
                'billing_zipcode'    => $billing['zipcode']   ?? null,
                'billing_telefono'   => $billing['telefono']  ?? null,

                'subtotal'           => $amount,
                'shipping_cost'      => 0, // ajusta si tienes cálculo de envío
                'total'              => $amount,
                'status'             => 'pending',
            ]);

            foreach ($items as $item) {
                $qty   = (int) $item->quantity;
                $price = (float) $item->price;

                if ($qty <= 0 || $price <= 0) {
                    throw new \RuntimeException('Producto con cantidad o precio inválido.');
                }

                OrdenesItem::create([
                    'id_orden'   => $order->id,
                    'id_producto'=> $item->id ?? null,
                    'nombre'     => $item->name,
                    'precio'     => $price,
                    'cantidad'   => $qty,
                    'subtotal'   => $qty * $price,
                ]);

                // Detalle para Pagadito
                $this->pagadito->add_detail(
                    $qty,
                    mb_substr($item->name, 0, 125),
                    number_format($price, 2, '.', '')
                );
            }

            DB::commit();

            Log::channel('pagadito')->info('Orden creada pendiente e inicializando Pagadito', [
                'order_id' => $order->id,
                'ern'      => $ern,
                'total'    => $order->total,
            ]);

        } catch (\Throwable $e) {
            DB::rollBack();

            Log::channel('pagadito')->error('Error creando orden antes de Pagadito', [
                'error' => $e->getMessage(),
            ]);

            return back()->with('error', __('meta.transaction_could_not_be'));
        }

        // ===== URLs opcionales OK / Cancel (si la librería las soporta) =====
        // if (method_exists($this->pagadito, 'set_url_ok')) {
        //     $this->pagadito->set_url_ok(route('checkout.pagadito.ok'));
        // }
        // if (method_exists($this->pagadito, 'set_url_error')) {
        //     $this->pagadito->set_url_error(route('checkout.pagadito.cancel'));
        // }

        // ===== Ejecutar transacción (redirige a Pagadito) =====
        if ($this->pagadito->exec_trans($ern)) {
            // La librería normalmente hace header("Location: ...") y exit.
            exit;
        }

        // Si falla exec_trans, marcamos la orden como failed
        $order->update(['status' => 'failed']);

        Log::channel('pagadito')->error('No se pudo iniciar transacción Pagadito [exec_trans]', [
            'ern'     => $ern,
            'code'    => $this->pagadito->get_rs_code(),
            'message' => $this->pagadito->get_rs_message(),
        ]);

        return back()->with(
            'error',
            __('meta.transaction_could_not_be')
            . ' (' . $this->pagadito->get_rs_code() . '): '
            . $this->pagadito->get_rs_message()
        );
    }

    /**
     * Opcional: páginas amigables si usas set_url_ok/set_url_error.
     * No son la fuente de verdad del pago; esa es `retorno` + get_status.
     */
    public function ok(Request $request)
    {
        $ern   = $request->get('order') ?? null;
        $token = $request->get('token') ?? $request->get('parametro1');

        return view('frontend.pages.checkout-success', compact('ern', 'token'));
    }

    public function cancel(Request $request)
    {
        $ern = $request->get('order') ?? null;

        return view('frontend.pages.checkout-cancel', compact('ern'));
    }

    /**
     * URL de Retorno configurada en el panel de Pagadito:
     * https://finca3pinos.com/pagadito/retorno?param1={value}&param2={ern_value}
     *
     * Aquí:
     * - Recibimos token + ern/ref
     * - Consultamos get_status en Pagadito
     * - Actualizamos la orden real
     */
    public function retorno(Request $request)
    {
        $token = $request->input('param1'); // {value}
        $refErn = $request->input('param2'); // {ern_value} (referencia de Pagadito)

        Log::channel('pagadito')->info('Retorno Pagadito recibido', [
            'params' => $request->all(),
            'token'  => $token,
            'ref_ern'=> $refErn,
        ]);

        if (!$token || !$refErn) {
            return redirect()
                ->route('checkout.show')
                ->with('error', __('meta.payment_not_confirmed'));
        }

        // Buscar la orden local
        $order = Ordenes::where('ern', $refErn)->first();

        if (!$order) {
            Log::channel('pagadito')->error('Orden no encontrada para ERN recibido', [
                'ref_ern' => $refErn,
            ]);

            return redirect()
                ->route('checkout.show')
                ->with('error', __('meta.payment_not_confirmed'));
        }

        // Conectar a Pagadito
        if (!$this->pagadito->connect()) {
            Log::channel('pagadito')->error('Error conectando a Pagadito en retorno', [
                'code'    => $this->pagadito->get_rs_code(),
                'message' => $this->pagadito->get_rs_message(),
            ]);

            return redirect()
                ->route('checkout.show')
                ->with('error', __('meta.error_conecctiong_to_pagadito'));
        }

        // Consultar estado del token
        if (!$this->pagadito->get_status($token)) {
            Log::channel('pagadito')->error('get_status Pagadito falló', [
                'code'    => $this->pagadito->get_rs_code(),
                'message' => $this->pagadito->get_rs_message(),
            ]);

            return redirect()
                ->route('checkout.show')
                ->with('error', __('meta.payment_not_confirmed'));
        }

        $statusPagadito = $this->pagadito->get_rs_status();    // ej: "COMPLETED"
        $pagaditoRef    = $this->pagadito->get_rs_reference(); // debería coincidir con $refErn
        $pagaditoAmount = (float) $this->pagadito->get_rs_value();

        Log::channel('pagadito')->info('Respuesta get_status Pagadito', [
            'status'  => $statusPagadito,
            'ref'     => $pagaditoRef,
            'amount'  => $pagaditoAmount,
            'orderId' => $order->id,
        ]);

        // Validación básica referencia
        if ($pagaditoRef !== $order->ern) {
            Log::channel('pagadito')->warning('Referencia Pagadito no coincide con orden local', [
                'pagadito_ref' => $pagaditoRef,
                'order_ern'    => $order->ern,
            ]);
        }

        // Mapear estado Pagadito → estado interno
        if (in_array(strtoupper($statusPagadito), ['COMPLETED', 'APPROVED'])) {
            // (Opcional) validar monto:
            // if (abs($order->total - $pagaditoAmount) > 0.01) { ... }

            $order->update([
                'status'          => 'paid',
                'pagadito_token'  => $token,
                'pagadito_ref'    => $pagaditoRef,
                'pagadito_status' => $statusPagadito,
            ]);

            // Limpiar carrito del usuario
            try {
                $this->cart()->clear();
            } catch (\Throwable $e) {
                Log::warning('No se pudo limpiar carrito después de pago Pagadito', [
                    'error' => $e->getMessage(),
                ]);
            }

            return view('frontend.pages.checkout-success', [
                'order' => $order,
            ]);
        }

        // No aprobado → marcar fallo/cancel si quieres
        $order->update([
            'status'          => 'failed',
            'pagadito_token'  => $token,
            'pagadito_ref'    => $pagaditoRef,
            'pagadito_status' => $statusPagadito,
        ]);

        return redirect()
            ->route('checkout.show')
            ->with('error', __('meta.payment_not_confirmed'));
    }

    /**
     * Helper del trait HandlesCart (por si tu implementación cambia).
     */
    protected function cart()
    {
        // Ajusta a cómo ya lo uses en tu proyecto.
        return $this->getCart(); // si tu trait tiene otro método, ponlo aquí.
    }
}
