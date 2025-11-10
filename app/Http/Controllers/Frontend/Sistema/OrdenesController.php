<?php

namespace App\Http\Controllers\Frontend\Sistema;

use App\Http\Controllers\Controller;
use App\Mail\PasswordResetMail;
use App\Models\Departamento;
use App\Models\Direcciones;
use App\Models\DireccionFacturacion;
use App\Models\Municipio;
use App\Models\Ordenes;
use App\Models\Pais;
use App\Models\Producto;
use App\Models\ProductosPresentacion;
use App\Models\Usuario;
use App\Traits\HandlesCart;
use Carbon\Carbon;
use Darryldecode\Cart\Facades\CartFacade as Cart;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class OrdenesController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:web');
    }



    public function vistaMisOrdenesSeguimiento($idorden)
    {
        // Usuario autenticado
        $userId = Auth::id();

        // Buscar la orden del usuario
        $order = Ordenes::where('id', $idorden)
            ->where('id_usuario', $userId)
            ->first();

        if (!$order) {
            // Si no se encuentra, redirige o muestra error
            return redirect()
                ->route('user.orders')
                ->with('error', __('meta.order_not_found'));
        }

        // 🔹 Determinar formato según país
        $formatoFecha = $order->id_paises == 1 ? 'd-m-M' : 'm-d-Y';

        // Armar estados de seguimiento
        $estados = [
            [
                'label'  => __('meta.preparing_order'),
                'active' => (bool)$order->estado_pedido_1,
                'date'   => $order->fecha_pedido_1
                    ? Carbon::parse($order->fecha_pedido_1)->format($formatoFecha)
                    : null,
            ],
            [
                'label'  => __('meta.order_sent'),
                'active' => (bool)$order->estado_pedido_2,
                'date'   => $order->fecha_pedido_2
                    ? Carbon::parse($order->fecha_pedido_2)->format($formatoFecha)
                    : null,
            ],
        ];

        // Pasar los datos a la vista
        return view('frontend.dashboard.seguimiento.vistaordenseguimiento', [
            'order'   => $order,
            'estados' => $estados,
        ]);
    }


    public function vistaMisOrdenesDetalle($idorden)
    {
        $userId = Auth::id();

        $order = Ordenes::with(['detalles.producto', 'detalles.presentacion'])
            ->where('id', $idorden)
            ->where('id_usuario', $userId)
            ->first();

        if (!$order) {
            return redirect()
                ->route('user.orders')
                ->with('error', __('meta.order_not_found'));
        }

        $formatoFecha = $order->id_paises == 1 ? 'd-m-M' : 'm-d-Y';

        $fechaOrden = $order->fecha
            ? Carbon::parse($order->fecha)->format($formatoFecha)
            : null;

        $subtotal = $order->detalles->reduce(function ($carry, $item) {
            $precio   = $item->precio ?? $item->precio_unitario ?? 0;
            $cantidad = $item->cantidad ?? 1;
            return $carry + ($precio * $cantidad);
        }, 0);

        $envio = $order->costo_envio ?? $order->shipping_cost ?? 0;
        $total = $order->total ?? ($subtotal + $envio);

        $infoPais          = Pais::find($order->id_paises);
        $nombreDepartamento= Departamento::find($order->id_departamentos)->nombre ?? '';
        $nombreMunicipio   = Municipio::find($order->id_municipios)->nombre ?? '';

        return view('frontend.dashboard.seguimiento.vistadetalleorden', [
            'order'              => $order,
            'fechaOrden'         => $fechaOrden,
            'subtotal'           => $subtotal,
            'envio'              => $envio,
            'total'              => $total,
            'nombrePais'         => $infoPais->nombre ?? '',
            'nombreDepartamento' => $nombreDepartamento,
            'nombreMunicipio'    => $nombreMunicipio,
        ]);
    }











}
