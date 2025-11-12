<?php

namespace App\Http\Controllers\Frontend\Sistema;

use App\Http\Controllers\Controller;
use App\Models\Direcciones;
use App\Models\DireccionFacturacion;
use App\Models\Order;
use App\Models\Producto;
use App\Models\Usuario;
use App\Traits\HandlesCart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
class CheckoutController extends Controller
{

    use HandlesCart;

    public function __construct()
    {
        $this->middleware('auth:web');
    }


    public function vistaCheckout()
    {
        $cart     = $this->cart();
        $items    = $cart->getContent();
        $subtotal = $cart->getSubTotal();

        // 🚨 Validar si el carrito está vacío
        if ($items->isEmpty()) {
            return redirect()->route('user.cart');
        }

        $userId = Auth::guard('web')->id();

        $addresses = Direcciones::query()
            ->where('direcciones.id_usuario', $userId)
            ->leftJoin('paises',        'paises.id',        '=', 'direcciones.id_paises')
            ->leftJoin('departamentos', 'departamentos.id', '=', 'direcciones.id_departamento')
            ->leftJoin('municipios',    'municipios.id',    '=', 'direcciones.id_municipio')
            ->orderByDesc('direcciones.predeterminado')
            ->latest('direcciones.id')
            ->get([
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


        $selectedAddress = optional(
            $addresses->firstWhere('predeterminado', 1) ?? $addresses->first()
        );

        $shipping = (float) ($selectedAddress->precio_envio ?? 0.0);
        $total    = $subtotal + $shipping;

        // Facturación
        $billing = DireccionFacturacion::where('id_usuario', auth()->id())->first();
        $selectedAddressId   = $selectedAddress->id ?? null;

        // === NUEVO: países para el select de facturación
        $paises = DB::table('paises')
            ->where('activo', 1)
            ->orderBy('nombre')
            ->get(['id','nombre']);

        $billing_country_id = $billing->id_paises ?? null;

        return view('frontend.pages.checkout', compact(
            'items', 'subtotal', 'shipping', 'total',
            'billing', 'addresses', 'selectedAddressId',
            'paises', 'billing_country_id'
        ));
    }



    public function vistaCotizar()
    {
        return view('frontend.pages.cotizar');
    }


}
