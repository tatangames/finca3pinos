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
use App\Models\Region;
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

        // ===== Fecha según país =====
        $formatoFecha = $order->id_paises == 1 ? 'd-m-M' : 'm-d-Y';

        $fechaOrden = $order->fecha
            ? Carbon::parse($order->fecha)->format($formatoFecha)
            : null;

        // ===== Subtotal =====
        $subtotal = $order->detalles->reduce(function ($carry, $item) {
            $precio   = $item->precio ?? $item->precio_unitario ?? 0;
            $cantidad = $item->cantidad ?? 1;
            return $carry + ($precio * $cantidad);
        }, 0);

        $envio = $order->costo_envio ?? $order->shipping_cost ?? 0;
        $total = $order->total ?? ($subtotal + $envio);

        // ===== Ubicación =====
        $infoPais           = Pais::find($order->id_paises);
        $nombreDepartamento = Departamento::find($order->id_departamentos)->nombre ?? '';
        $nombreMunicipio    = Municipio::find($order->id_municipios)->nombre ?? '';

        $locale = app()->getLocale(); // ej: 'es', 'en', 'ko'

        /**
         * Obtener title desde region_* usando el content_key.
         *
         * Prioridad:
         *  1) locale actual
         *  2) 'es'
         *  3) 'en'
         */
        $getTranslation = function (?string $contentKey, string $locale) {
            if (!$contentKey) {
                return null;
            }

            return DB::table('region_contents as c')
                ->join('region_content_translation as t', 't.content_id', '=', 'c.id')
                ->where('c.key', $contentKey)
                ->whereIn('t.locale', [$locale, 'es', 'en'])
                ->orderByRaw(
                    "FIELD(t.locale, ?, 'es', 'en')",
                    [$locale]
                )
                ->value('t.title');
        };

        // ===== Items =====
        $items = $order->detalles->map(function ($detalle) use ($getTranslation, $locale) {

            // -------- PRODUCTO --------
            $producto = $detalle->producto;

            $productoContentKey = $producto->content_key
                ?? $detalle->producto_content_key
                ?? null;

            $productoNombre = $detalle->nombre // por si guardaste nombre estático en la orden
                ?? ($producto->nombre ?? null) // si algún día agregas 'nombre' al producto
                ?? $getTranslation($productoContentKey, $locale)
                ?? 'Producto';

            // -------- PRESENTACIÓN --------
            $presentacion = $detalle->presentacion;

            $presentacionContentKey = $presentacion->content_key
                ?? $detalle->presentacion_content_key
                ?? null;

            $presentacionNombre = $getTranslation($presentacionContentKey, $locale);

            // Nombre final combinado
            $nombreFinal = $presentacionNombre
                ? "{$productoNombre} — {$presentacionNombre}"
                : $productoNombre;

            $precio   = $detalle->precio ?? $detalle->precio_unitario ?? 0;
            $cantidad = $detalle->cantidad ?? 1;

            return [
                'nombre'   => $nombreFinal,
                'cantidad' => $cantidad,
                'precio'   => $precio,
                'subtotal' => number_format($precio * $cantidad, 2),
            ];
        });

        return view('frontend.dashboard.seguimiento.vistadetalleorden', [
            'order'              => $order,
            'fechaOrden'         => $fechaOrden,
            'subtotal'           => $subtotal,
            'envio'              => $envio,
            'total'              => $total,
            'nombrePais'         => $infoPais->nombre ?? '',
            'nombreDepartamento' => $nombreDepartamento,
            'nombreMunicipio'    => $nombreMunicipio,
            'items'              => $items,
        ]);
    }













}
