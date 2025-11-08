<?php

namespace App\Http\Controllers\Frontend\Pagos;

use App\Http\Controllers\Controller;
use App\Models\Direcciones;
use App\Traits\HandlesCart;
use App\Libraries\Pagadito;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class PagaditoController extends Controller
{
    use HandlesCart;

    protected $pagadito;

    public function __construct()
    {
        $this->middleware('auth:web');

        if (!class_exists(Pagadito::class)) {
            throw new \RuntimeException(
                'No se encontró la clase App\\Libraries\\Pagadito. Verifica app/Libraries/Pagadito.php y composer dump-autoload.'
            );
        }

        $uid     = trim((string) config('services.pagadito.uid'));
        $wsk     = trim((string) config('services.pagadito.wsk'));
        $sandbox = (bool) config('services.pagadito.sandbox', true);
        $debug   = (bool) config('services.pagadito.debug', false);

        // Debug duro: si vienen vacíos, paramos aquí
        if ($uid === '' || $wsk === '') {
            dd('UID o WSK vacíos', $uid, $wsk);
        }

        $this->pagadito = new Pagadito($uid, $wsk);

        if ($sandbox && method_exists($this->pagadito, 'mode_sandbox_on')) {
            $this->pagadito->mode_sandbox_on();
        }

        if ($debug && method_exists($this->pagadito, 'mode_debug')) {
            $this->pagadito->mode_debug(true);
        }
    }

    public function init(Request $request)
    {
        // ========== Billing ==========
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

        // ========== Carrito ==========
        $cart     = $this->cart();
        $items    = $cart->getContent();
        $subtotal = (float) $cart->getSubTotal();

        if ($items->isEmpty()) {
            return redirect()
                ->route('checkout.show')
                ->with('error', 'Tu carrito está vacío.');
        }

        // ========== Dirección envío ==========
        $envioId = (int) $request->envio_id;

        $direccionEnvio = Direcciones::query()
            ->where('direcciones.id_usuario', $userId)
            ->where('direcciones.id', $envioId)
            ->leftJoin('paises',        'paises.id',        '=', 'direcciones.id_paises')
            ->leftJoin('departamentos', 'departamentos.id', '=', 'direcciones.id_departamento')
            ->leftJoin('municipios',    'municipios.id',    '=', 'direcciones.id_municipio')
            ->first([
                'direcciones.*',
                DB::raw('paises.nombre        as pais_nombre'),
                DB::raw('departamentos.nombre as depto_nombre'),
                DB::raw('municipios.nombre    as muni_nombre'),
                DB::raw("
                    CASE
                        WHEN direcciones.id_paises = 1
                            THEN COALESCE(municipios.precio_envio, 0)
                        ELSE COALESCE(paises.precio_envio, 0)
                    END AS precio_envio
                "),
            ]);

        if (!$direccionEnvio) {
            return redirect()
                ->route('checkout.show')
                ->with('error', 'La dirección de envío seleccionada no es válida.');
        }

        $shipping = (float) ($direccionEnvio->precio_envio ?? 0.0);
        $total    = $subtotal + $shipping;

        if ($total <= 0) {
            return redirect()
                ->route('checkout.show')
                ->with('error', 'El total debe ser mayor a 0.');
        }

        // ========== Código de orden ==========
        $orderCode = 'F3P-' . Str::upper(Str::random(10));

        $pagadito = $this->pagadito;

        // ========== Conectar con Pagadito ==========
        if (!$pagadito->connect()) {
            $code    = $pagadito->get_rs_code();
            $message = $pagadito->get_rs_message();

            Log::error('Pagadito connect() failed', [
                'code'    => $code,
                'message' => $message,
            ]);

            return redirect()
                ->route('checkout.show')
                ->with('error', "Error al conectar con Pagadito ($code): $message");
        }

        // ========== Detalles del carrito ==========
        foreach ($items as $it) {
            $pagadito->add_detail(
                (int) $it->quantity,
                mb_substr($it->name, 0, 80),
                round((float) $it->price, 2)
            );
        }

        if ($shipping > 0) {
            $pagadito->add_detail(1, 'Envío', round($shipping, 2));
        }

        // (Opcional) Puedes mandar parámetros personalizados
        $pagadito->set_custom_param('order', $orderCode);

        // OJO: tu versión de la clase Pagadito que pegaste NO tiene set_url_ok/set_url_cancel.
        // Esa lógica va incluida dentro de Pagadito o se maneja con custom_params + config en Pagadito.
        // Si tu archivo SÍ las trae más abajo, puedes descomentar:
        //
        // $pagadito->set_url_ok(route('checkout.pagadito.ok', ['order' => $orderCode]));
        // $pagadito->set_url_cancel(route('checkout.pagadito.cancel', ['order' => $orderCode]));

        // ========== Ejecutar transacción ==========
        // IMPORTANTE:
        // En tu SDK, exec_trans() hace header("Location ...") y exit() cuando es PG1002.
        // Si devuelve false, algo falló -> mostramos mensaje.
        if (!$pagadito->exec_trans($orderCode)) {
            $code    = $pagadito->get_rs_code();
            $message = $pagadito->get_rs_message();

            Log::error('Pagadito exec_trans() failed', [
                'code'    => $code,
                'message' => $message,
                'order'   => $orderCode,
            ]);

            return redirect()
                ->route('checkout.show')
                ->with('error', "No se pudo crear la transacción en Pagadito ($code): $message");
        }

        // Si fue exitoso, la librería ya redirigió con header() y exit().
        return;
    }

    public function ok(Request $request)
    {
        $token     = $request->get('token') ?? $request->get('parametro1');
        $orderCode = $request->get('order');

        return view('frontend.pages.checkout-success', compact('orderCode', 'token'));
    }

    public function cancel(Request $request)
    {
        $orderCode = $request->get('order');

        return view('frontend.pages.checkout-cancel', compact('orderCode'));
    }



    public function retorno(Request $request)
    {
        // === Registrar todo lo que Pagadito envía ===
        Log::channel('pagadito')->info('✅ Retorno recibido desde Pagadito', $request->all());

        // === Simular guardado de orden ===
        $fakeOrder = [
            'id'          => rand(1000, 9999),
            'transaction' => $request->input('param2'), // {ern_value}
            'token'       => $request->input('param1'), // {value}
            'amount'      => 1.00,
            'currency'    => 'USD',
            'status'      => 'PAGADO',
            'date'        => now()->toDateTimeString(),
        ];

        Log::channel('pagadito')->info('💾 Orden simulada guardada', $fakeOrder);

        // === Mostrar respuesta simple en navegador ===
        return response()->json([
            'message' => 'Retorno de Pagadito recibido correctamente.',
            'order'   => $fakeOrder,
        ]);
    }

}
