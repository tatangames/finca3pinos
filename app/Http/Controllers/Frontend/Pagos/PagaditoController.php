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
        // =========================
        // 1) Billing desde el front
        // =========================
        $billing = $request->input('billing');

        // Si viene como JSON string, lo convertimos a array
        if (is_string($billing)) {
            $billing = json_decode($billing, true);
        }

        $request->merge(['billing' => $billing]);

        $request->validate([
            'envio_id' => 'required|integer',
            'billing'  => 'required|array',
        ]);

        $userId = Auth::guard('web')->id();

        // =========================
        // 2) Carrito
        // =========================
        $cart  = $this->cart();
        $items = $cart->getContent();

        if ($items->isEmpty()) {
            return redirect()
                ->route('checkout.show')
                ->with('error', 'Tu carrito está vacío.');
        }

        $subtotal = (float) $cart->getSubTotal();

        // Monto mínimo para Pagadito (ajusta si tu cuenta indica otro)
        if ($subtotal < 1) {
            return redirect()
                ->route('checkout.show')
                ->with('error', 'El monto mínimo para pagar con Pagadito es $1.00 USD.');
        }

        // Normalizamos monto (sin símbolos, sin comas)
        $amount = number_format($subtotal, 2, '.', ''); // "10.00"

        // =========================
        // 3) Dirección de envío
        // =========================
        $envioId = (int) $request->envio_id;

        $direccionEnvio = Direcciones::query()
            ->where('direcciones.id_usuario', $userId)
            ->where('direcciones.id', $envioId)
            ->first();

        if (!$direccionEnvio) {
            return redirect()
                ->route('checkout.show')
                ->with('error', 'La dirección de envío seleccionada no es válida.');
        }

        // (Si quieres guardar la dirección/billing en sesión o DB para validarlo al regresar de Pagadito, hazlo aquí)

        // =========================
        // 4) Cargar SDK Pagadito
        // =========================
        $pagaditoPath = app_path('Libraries/Pagadito.php');

        if (!file_exists($pagaditoPath)) {
            \Log::error("Pagadito SDK no encontrado en: {$pagaditoPath}");
            return back()->with('error', 'Error interno al iniciar el pago. (SDK Pagadito no encontrado)');
        }

        require_once $pagaditoPath;

        // =========================
        // 5) Instanciar Pagadito
        // =========================
        $Pagadito = new \Pagadito(
            env('PAGADITO_UID'),
            env('PAGADITO_WSK')
        );

        // Sandbox ON/OFF
        if (filter_var(env('PAGADITO_SANDBOX', true), FILTER_VALIDATE_BOOL)) {
            $Pagadito->mode_sandbox_on();
        }

        // =========================
        // 6) Conectar con Pagadito
        // =========================
        if (!$Pagadito->connect()) {
            Log::error('Error al conectar con Pagadito', [
                'code'    => $Pagadito->get_rs_code(),
                'message' => $Pagadito->get_rs_message(),
            ]);

            return back()->with(
                'error',
                'Error al conectar con Pagadito (' .
                $Pagadito->get_rs_code() . '): ' . $Pagadito->get_rs_message()
            );
        }

        // =========================
        // 7) Agregar items del carrito
        // =========================
        foreach ($items as $item) {
            $qty   = (int) $item->quantity;
            $price = (float) $item->price;

            if ($qty <= 0 || $price <= 0) {
                Log::warning('Producto con cantidad o precio inválido al crear transacción Pagadito', [
                    'product_id' => $item->id,
                    'qty'        => $qty,
                    'price'      => $price,
                ]);

                return redirect()
                    ->route('checkout.show')
                    ->with('error', 'Hay un producto con cantidad o precio inválido en tu carrito.');
            }

            // Descripción corta y limpia
            $description = mb_substr((string) $item->name, 0, 125);

            // Precio con 2 decimales, punto como separador
            $unitPrice = number_format($price, 2, '.', '');

            $Pagadito->add_detail($qty, $description, $unitPrice);
        }

        // (Opcional) Si tienes costo de envío aparte, podrías agregarlo como otro detail:
        //
        // if ($costoEnvio > 0) {
        //     $Pagadito->add_detail(1, 'Envío', number_format($costoEnvio, 2, '.', ''));
        // }

        // =========================
        // 8) Crear ERN único
        // =========================
        $ern = 'F3P-' . $userId . '-' . time();

        // Aquí podrías guardar en BD la orden pendiente vinculada a $ern, $amount, usuario, dirección, etc.

        // =========================
        // 9) Ejecutar transacción
        // =========================
        // La mayoría de implementaciones del SDK usa sólo el ERN; los montos salen de los detail.
        if ($Pagadito->exec_trans($ern)) {
            // Si es exitoso, el propio SDK normalmente hace el redirect a la pasarela de Pagadito.
            // Por seguridad, terminamos la ejecución.
            exit;
        }

        // =========================
        // 10) Manejo de error en creación de transacción
        // =========================
        Log::error('No se pudo crear la transacción en Pagadito', [
            'ern'     => $ern,
            'amount'  => $amount,
            'code'    => $Pagadito->get_rs_code(),
            'message' => $Pagadito->get_rs_message(),
        ]);

        return back()->with(
            'error',
            'No se pudo crear la transacción en Pagadito (' .
            $Pagadito->get_rs_code() . '): ' . $Pagadito->get_rs_message()
        );
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
}
