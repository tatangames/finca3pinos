<?php

namespace App\Http\Controllers\Frontend\Pagos;

use App\Http\Controllers\Controller;
use App\Models\Departamento;
use App\Models\Direcciones;
use App\Models\DireccionFacturacion;
use App\Models\Municipio;
use App\Models\Ordenes;
use App\Models\OrdenesItem;
use App\Models\Pais;
use App\Models\Producto;
use App\Models\ProductosPresentacion;
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
        // ===== Validar solo dirección de envío =====
        $request->validate([
            'envio_id' => 'required|integer',
        ]);

        $userId = Auth::guard('web')->id();

        if (!$userId) {
            return redirect()
                ->route('login')
                ->with('error', __('meta.session_expired'));
        }

        // ===== Carrito =====
        $cart  = $this->cart();
        $items = $cart->getContent();

        if ($items->isEmpty()) {
            return redirect()
                ->route('checkout.show')
                ->with('error', __('meta.your_cart_empty'));
        }

        $subtotal = (float) $cart->getSubTotal();

        // ===== Dirección de envío seleccionada =====
        $envioId = (int) $request->envio_id;

        $direccionEnvio = Direcciones::query()
            ->where('id_usuario', $userId)
            ->where('id', $envioId)
            ->first();

        if (!$direccionEnvio) {
            return redirect()
                ->route('checkout.show')
                ->with('error', __('meta.selected_shipping_notvalid'));
        }

        // ===== Helper: validar activo / disponible =====
        $isInactive = function ($model) {
            if (!$model) return true;
            if (isset($model->disponible) && (int)$model->disponible === 0) return true;
            if (isset($model->activo) && (int)$model->activo === 0) return true;
            return false;
        };

        // ===== Verificar país =====
        $pais = Pais::find($direccionEnvio->id_paises);
        if ($isInactive($pais)) {
            return redirect()
                ->route('checkout.show')
                ->with('error', __('meta.shipping_not_available_for_address'));
        }

        // ===== Verificar departamento (si aplica) =====
        if (!empty($direccionEnvio->id_departamento)) {
            $depto = Departamento::find($direccionEnvio->id_departamento);
            if ($isInactive($depto)) {
                return redirect()
                    ->route('checkout.show')
                    ->with('error', __('meta.shipping_not_available_for_address'));
            }
        }

        // ===== Verificar municipio (si aplica) =====
        if (!empty($direccionEnvio->id_municipio)) {
            $muni = Municipio::find($direccionEnvio->id_municipio);
            if ($isInactive($muni)) {
                return redirect()
                    ->route('checkout.show')
                    ->with('error', __('meta.shipping_not_available_for_address'));
            }
        }

        // ===== Costo de envío =====
        $shippingCost = 0.0;

        if ((int)$direccionEnvio->id_paises === 1) {
            if (!empty($direccionEnvio->id_municipio)) {
                $municipio = Municipio::find($direccionEnvio->id_municipio);
                if ($municipio && $municipio->precio_envio !== null) {
                    $shippingCost = (float)$municipio->precio_envio;
                }
            }
        } else {
            if ($pais && $pais->precio_envio !== null) {
                $shippingCost = (float)$pais->precio_envio;
            }
        }

        // ===== Totales =====
        $amountProducts = (float) number_format($subtotal, 2, '.', '');
        $amount         = (float) number_format($amountProducts + $shippingCost, 2, '.', '');

        if ($amount < 1) {
            return redirect()
                ->route('checkout.show')
                ->with('error', __('meta.minimum_amount_to_pay'));
        }

        // ===== Facturación (última del usuario, opcional) =====
        $billing = DireccionFacturacion::query()
            ->where('id_usuario', $userId)
            ->orderByDesc('id')
            ->first();

        // ===== Conectar Pagadito =====
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

        // ===== ERN único =====
        $ern = 'F3P-' . $userId . '-' . time();

        $order = null;

        try {
            DB::beginTransaction();

            // ===== Crear orden pendiente =====
            $order = Ordenes::create([
                'id_usuario'         => $userId,
                'ern'                => $ern,
                'fecha'              => now(),

                'id_paises'          => $direccionEnvio->id_paises,
                'id_departamentos'   => $direccionEnvio->id_departamento ?? null,
                'id_municipios'      => $direccionEnvio->id_municipio ?? null,
                'shipping_nombre'    => $direccionEnvio->nombre,
                'shipping_telefono'  => $direccionEnvio->telefono,
                'shipping_pais'      => $direccionEnvio->id_paises,
                'shipping_estado'    => $direccionEnvio->estado,
                'shipping_ciudad'    => $direccionEnvio->ciudad,
                'shipping_direccion' => $direccionEnvio->direccion,
                'shipping_direccion_opc' => $direccionEnvio->direccion_opcional,
                'shipping_zipcode'   => $direccionEnvio->zipcode,

                'billing_idpaises'   => optional($billing)->id_paises,
                'billing_nombre'     => optional($billing)->nombre,
                'billing_direccion'  => optional($billing)->direccion,
                'billing_ciudad'     => optional($billing)->ciudad,
                'billing_estado'     => optional($billing)->estado,
                'billing_zipcode'    => optional($billing)->zipcode,
                'billing_telefono'   => optional($billing)->telefono,

                'subtotal'           => $amountProducts,
                'shipping_cost'      => $shippingCost,
                'total'              => $amount,
                'status_id'          => 1,

                'estado_pedido_1'    => 0,
                'fecha_pedido_1'     => null,
                'estado_pedido_2'    => 0,
                'fecha_pedido_2'     => null,
                'seguimiento'        => null,
                'visible_cliente' => 1 // visible alñ client
            ]);

            // ===== Items de la orden =====
            foreach ($items as $item) {
                $qty   = (int) $item->quantity;
                $price = (float) $item->price;

                if ($qty <= 0 || $price <= 0) {
                    throw new \RuntimeException('Product Invalid.');
                }

                $attrs = $item->attributes ?? [];

                // Leer desde attributes (soporta array / AttributeCollection)
                $productoId = isset($attrs['product_id'])
                    ? (int)$attrs['product_id']
                    : (is_object($attrs) && method_exists($attrs, 'get') ? (int)$attrs->get('product_id') : null);

                $presentacionId = isset($attrs['presentacion_id'])
                    ? (int)$attrs['presentacion_id']
                    : (is_object($attrs) && method_exists($attrs, 'get') ? (int)$attrs->get('presentacion_id') : null);

                // Producto requerido
                if (!$productoId) {
                    throw new \RuntimeException('Falta el producto en uno de los ítems del carrito.');
                }

                $producto = Producto::find($productoId);
                if (
                    !$producto ||
                    (int)$producto->activo === 0 ||
                    (int)$producto->disponible === 0
                ) {
                    throw new \RuntimeException('Este producto no está disponible actualmente.');
                }

                // Presentación requerida (según tu regla)
                if (!$presentacionId) {
                    throw new \RuntimeException('Falta la presentación de un producto en el carrito.');
                }

                $presentacion = ProductosPresentacion::where('id', $presentacionId)
                    ->where('id_productos', $productoId)
                    ->where('activo', 1)
                    ->first();

                if (!$presentacion) {
                    throw new \RuntimeException('Esta presentación de producto no está disponible actualmente.');
                }

                $nombreItem = $item->name;

                OrdenesItem::create([
                    'id_orden'        => $order->id,
                    'id_producto'     => $productoId,
                    'id_presentacion' => $presentacionId,
                    'precio'          => $price,
                    'cantidad'        => $qty,
                    'subtotal'        => $qty * $price,
                ]);

                $this->pagadito->add_detail(
                    $qty,
                    mb_substr($nombreItem, 0, 125),
                    number_format($price, 2, '.', '')
                );
            }

            // ===== Envío como ítem =====
            if ($shippingCost > 0) {
                $shippingLabel = __('meta.shipping');

                $this->pagadito->add_detail(
                    1,
                    mb_substr($shippingLabel, 0, 125),
                    number_format($shippingCost, 2, '.', '')
                );
            }

            DB::commit();

            Log::channel('pagadito')->info('Orden creada pendiente e inicializando Pagadito', [
                'order_id' => $order->id,
                'ern'      => $ern,
                'subtotal' => $order->subtotal,
                'shipping' => $order->shipping_cost,
                'total'    => $order->total,
            ]);

        } catch (\Throwable $e) {
            DB::rollBack();

            Log::channel('pagadito')->error('Error creando orden antes de Pagadito', [
                'error' => $e->getMessage(),
            ]);

            $msg = $e instanceof \RuntimeException
                ? $e->getMessage()
                : __('meta.transaction_could_not_be');

            return back()->with('error', $msg);
        }

        // ===== Ejecutar transacción Pagadito =====
        if ($this->pagadito->exec_trans($ern)) {
            exit;
        }

        if ($order) {
            $order->update([
                'status_id' => 3,
            ]);
        }

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
        $token  = $request->input('param1') ?? $request->input('token');
        $refErn = $request->input('param2') ?? $request->input('order') ?? $request->input('pedido');

        Log::channel('pagadito')->info('Retorno Pagadito recibido', [
            'query'  => $request->query(),
            'token'  => $token,
            'refErn' => $refErn,
        ]);

        if (!$token) {
            return redirect()
                ->route('checkout.show')
                ->with('error', __('meta.payment_not_confirmed'));
        }

        // Buscar orden por ERN recibido
        $order = null;
        if ($refErn) {
            $order = Ordenes::where('ern', $refErn)->first();
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

        $statusPagadito = $this->pagadito->get_rs_status();    // estado texto
        $pagaditoRef    = $this->pagadito->get_rs_reference(); // ERN / ref
        $rawValue       = $this->pagadito->get_rs_value();

        Log::channel('pagadito')->info('Respuesta get_status Pagadito', [
            'status'   => $statusPagadito,
            'ref'      => $pagaditoRef,
            'rawValue' => $rawValue,
            'type'     => is_object($rawValue) ? 'object' : gettype($rawValue),
        ]);

        // Intentar con referencia de Pagadito si no se encontró antes
        if (!$order && $pagaditoRef) {
            $order = Ordenes::where('ern', $pagaditoRef)->first();
        }

        if (!$order) {
            Log::channel('pagadito')->error('Orden no encontrada para ERN/ref recibido', [
                'refErn'      => $refErn,
                'pagaditoRef' => $pagaditoRef,
            ]);

            return redirect()
                ->route('checkout.show')
                ->with('error', __('meta.payment_not_confirmed'));
        }

        $normalized = strtoupper(trim($statusPagadito));

        // Estados de éxito (según docs Pagadito / integración)
        $successStates = ['COMPLETED', 'APPROVED'];

        // Estados que NO deben borrar carrito (pendientes)
        $pendingStates = ['REGISTERED', 'VERIFIED', 'IN_PROCESS', 'PENDING'];

        // Estados de fallo / cancelación
        $failedStates   = ['REJECTED', 'FAILED', 'ERROR', 'EXPIRED', 'REVOKED'];
        $canceledStates = ['CANCELLED', 'CANCELED'];

        // ===== ÉXITO: pago confirmado =====
        if (in_array($normalized, $successStates, true)) {

            // Evitar downgrade si ya estaba marcada como pagada
            if ((int)$order->status_id !== 2) {
                $order->update([
                    'status_id'       => 2, // Pagado
                    'pagadito_token'  => $token,
                    'pagadito_ref'    => $pagaditoRef ?: $order->ern,
                    'pagadito_status' => $statusPagadito,
                ]);
            }

            // AHORA SÍ limpiamos carrito
            try {
                $this->cart()->clear();
            } catch (\Throwable $e) {
                Log::channel('pagadito')->warning('No se pudo limpiar carrito después del pago', [
                    'error' => $e->getMessage(),
                ]);
            }

            return view('frontend.pages.checkout-success', [
                'order' => $order,
            ]);
        }

        // ===== PENDIENTE: no confirmamos ni borramos nada =====
        if (in_array($normalized, $pendingStates, true)) {

            // Mantener como pendiente si aún no está pagada
            if ((int)$order->status_id === 1) {
                $order->update([
                    'pagadito_token'  => $token,
                    'pagadito_ref'    => $pagaditoRef ?: $order->ern,
                    'pagadito_status' => $statusPagadito,
                ]);
            }

            return redirect()
                ->route('user.orders')
                ->with('info', __('meta.payment_in_process') ?? 'Tu pago está en proceso de verificación.');
        }

        // ===== FALLÓ o CANCELADO: NO borrar carrito, solo marcar orden =====
        if (in_array($normalized, $failedStates, true) || in_array($normalized, $canceledStates, true)) {

            // Solo marcar fallo si no está pagada
            if ((int)$order->status_id !== 2) {
                $order->update([
                    'status_id'       => 3, // fallado / cancelado (ajusta según tu catálogo)
                    'pagadito_token'  => $token,
                    'pagadito_ref'    => $pagaditoRef ?: $order->ern,
                    'pagadito_status' => $statusPagadito,
                ]);
            }

            return redirect()
                ->route('checkout.show')
                ->with('error', __('meta.payment_not_confirmed'));
        }

        // ===== Estado desconocido: tratamos como no confirmado =====
        Log::channel('pagadito')->warning('Estado Pagadito desconocido en retorno', [
            'status' => $statusPagadito,
            'ern'    => $order->ern,
        ]);

        if ((int)$order->status_id !== 2) {
            $order->update([
                'status_id'       => 3,
                'pagadito_token'  => $token,
                'pagadito_ref'    => $pagaditoRef ?: $order->ern,
                'pagadito_status' => $statusPagadito,
            ]);
        }

        return redirect()
            ->route('checkout.show')
            ->with('error', __('meta.payment_not_confirmed'));
    }




}
