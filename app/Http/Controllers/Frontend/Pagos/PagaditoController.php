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
        $cart   = $this->cart();
        $items  = $cart->getContent();

        if ($items->isEmpty()) {
            return redirect()->route('checkout.show')
                ->with('error', 'Tu carrito está vacío.');
        }

        $subtotal = (float) $cart->getSubTotal();
        if ($subtotal < 1) {
            return redirect()->route('checkout.show')
                ->with('error', 'El monto mínimo para pagar con Pagadito es $1.00 USD.');
        }

        $amount = number_format($subtotal, 2, '.', '');
        $ern    = 'F3P-' . time();

        require_once base_path('pagadito/Pagadito.php');

        $Pagadito = new \Pagadito(env('PAGADITO_UID'), env('PAGADITO_WSK'));

        if (env('PAGADITO_SANDBOX', true)) {
            $Pagadito->mode_sandbox_on();
        }

        if (!$Pagadito->connect()) {
            return back()->with('error',
                'Error al conectar con Pagadito ('.
                $Pagadito->get_rs_code().'): '.$Pagadito->get_rs_message()
            );
        }

        foreach ($items as $item) {
            $qty   = (int) $item->quantity;
            $price = (float) $item->price;

            if ($qty <= 0 || $price <= 0) {
                return back()->with('error', 'Producto con cantidad o precio inválido.');
            }

            $Pagadito->add_detail(
                $qty,
                substr($item->name, 0, 125),
                number_format($price, 2, '.', '')
            );
        }

        // Ejecutar transacción
        if ($Pagadito->exec_trans($ern, $amount, 'USD')) {
            // Pagadito se encarga de redirigir al checkout seguro
            exit;
        }

        return back()->with('error',
            'No se pudo crear la transacción en Pagadito ('.
            $Pagadito->get_rs_code().'): '.$Pagadito->get_rs_message()
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
