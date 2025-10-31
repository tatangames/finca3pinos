<?php

namespace App\Http\Controllers\Frontend\Sistema;

use App\Http\Controllers\Controller;
use App\Models\Direcciones;
use App\Models\DireccionFacturacion;
use App\Models\Order;
use App\Models\Producto;
use App\Traits\HandlesCart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
class CheckoutController extends Controller
{

    use HandlesCart;

    public function show()
    {
        $cart     = $this->cart();
        $items    = $cart->getContent();
        $subtotal = $cart->getSubTotal();

        $addresses = Direcciones::query()
            ->where('direcciones.id_usuario', auth()->id())
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


    public function place(Request $request)
    {
        $data = $request->validate([
            'envio_id'   => ['required','integer','exists:direcciones,id'],
            'billing'    => ['nullable','array'],
            'pay_method' => ['required','string'],
        ]);

        $user = auth()->user();
        $cart = app('cart');
        $items = $cart->getContent();
        $subTotal = (float) $cart->getSubTotal();

        // === Dirección de envío seleccionada ===
        $addr = \App\Models\Direcciones::query()
            ->where('direcciones.id', $data['envio_id'])
            ->leftJoin('paises','paises.id','=','direcciones.id_paises')
            ->leftJoin('departamentos','departamentos.id','=','direcciones.id_departamento')
            ->leftJoin('municipios','municipios.id','=','direcciones.id_municipio')
            ->first([
                'direcciones.*',
                DB::raw('paises.nombre        as pais_nombre'),
                DB::raw('departamentos.nombre as depto_nombre'),
                DB::raw('municipios.nombre    as muni_nombre'),
            ]);

        if (!$addr) {
            return response()->json(['ok'=>false,'msg'=>'Dirección de envío no encontrada.'],422);
        }

        $shippingCost = (float) ($addr->precio_envio ?? 0);
        $grandTotal   = round($subTotal + $shippingCost, 2);

        // Si por algún motivo quedó 0, aborta antes de crear el enlace
        if ($grandTotal < 0.01) {
            return response()->json([
                'ok'  => false,
                'msg' => 'El total a pagar es cero. Verifica cantidades y productos.',
            ], 422);
        }

        DB::beginTransaction();
        try {
            // === 1) Crear la orden ===
            $order = Order::create([
                'code'           => 'F3P-'.now()->format('Ymd-His').'-'.Str::upper(Str::random(5)),
                'user_id'        => $user?->id,
                'currency'       => config('wompi.currency','USD'),
                'sub_total'      => $subTotal,
                'shipping_total' => $shippingCost,
                'grand_total'    => $grandTotal,
                'status'         => 'payment_pending',
            ]);

            // === 2) Items ===
            foreach ($items as $row) {
                // Si tu row->id viene "product:presentacion" ya lo separaste antes; aquí solo qty/precio
                $qty        = max(1, (int) $row->qty);
                $unitPrice  = (float) $row->price;
                $discount   = (float) ($row->attributes['discount'] ?? 0);
                $tax        = (float) ($row->attributes['tax'] ?? 0);

                // (unit - disc + tax) * qty   (ajusta si no usas descuento/impuesto por línea)
                $lineBase   = max(0, $unitPrice - $discount + $tax);
                $lineTotal  = round($lineBase * $qty, 2);

                $order->items()->create([
                    'purchasable_id'   => $productId,                 // según tu mapeo previo
                    'purchasable_type' => \App\Models\Producto::class, // o Product::class
                    'sku'          => $row->attributes['sku'] ?? null,
                    'name'         => $row->name,
                    'presentation' => $row->attributes['presentation'] ?? null,
                    'qty'          => $qty,
                    'unit_price'   => $unitPrice,
                    'discount'     => $discount,
                    'tax'          => $tax,
                    'total'        => $lineTotal,
                    'meta'         => $row->attributes ?? [],
                ]);

                $subAcum = ($subAcum ?? 0) + $lineTotal; // <- si quieres recalcular subTotal desde aquí
            }


            // === 3) Dirección de envío ===
            $order->addresses()->create([
                'type'          => 'shipping',
                'country'       => $addr->pais_nombre,
                'state'         => $addr->estado ?: $addr->depto_nombre,
                'city'          => $addr->ciudad,
                'zipcode'       => $addr->zipcode,
                'address_line'  => $addr->direccion,
                'name'          => $addr->nombre,
                'phone'         => $addr->telefono,
                'meta'          => [
                    'id_paises'       => $addr->id_paises,
                    'id_departamento' => $addr->id_departamento,
                    'id_municipio'    => $addr->id_municipio,
                    'precio_envio'    => $addr->precio_envio,
                ],
            ]);

            // === 4) Dirección de facturación (si hay) ===
            if (!empty($data['billing'])) {
                $b = $data['billing'];

                $order->addresses()->create([
                    'type'          => 'billing',
                    'country'       => $b['pais_nombre'] ?? null,
                    'state'         => $b['estado'] ?? null,
                    'city'          => $b['ciudad'] ?? null,
                    'zipcode'       => $b['zipcode'] ?? null,
                    'address_line'  => $b['direccion'] ?? null,
                    'name'          => $b['nombre'] ?? null,
                    'phone'         => $b['telefono'] ?? null,
                    'meta'          => [
                        'id_paises' => $b['id_paises'] ?? null,
                    ],
                ]);
            }

            DB::commit();

            // === 5) Preparar datos para Wompi ===
            $customer = [
                'name'  => optional($order->billingAddress)->name
                    ?? optional($order->shippingAddress)->name
                        ?? optional($order->user)->name,
                'email' => optional($order->user)->email,
                'phone' => optional($order->shippingAddress)->phone,
            ];

            // (Opcional) Vaciar carrito
            // $cart->clear();

            return response()->json([
                'ok'         => true,
                'order_code' => $order->code,
                'total'      => $order->grand_total,
                'customer'   => $customer,
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Checkout place error', ['msg'=>$e->getMessage()]);
            return response()->json([
                'ok'=>false,
                'msg'=>'No se pudo crear la orden. Intenta nuevamente.',
            ],422);
        }
    }


}
