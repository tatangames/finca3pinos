<?php

namespace App\Http\Controllers\Frontend\Sistema;

use App\Models\ProductosPresentacion;
use App\Traits\HandlesCart;
use Illuminate\Http\Request;
use App\Models\Producto;
use Darryldecode\Cart\Facades\CartFacade as Cart;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cookie;
class CartController extends Controller
{

    use HandlesCart;





    public function add(Request $r)
    {
        $data = $r->validate([
            'product_id'     => ['required', 'integer'],
            'quantity'       => ['integer', 'min:1', 'max:100'],
            'presentacionId' => ['nullable', 'integer'],
        ]);

        $cart = $this->cart();

        // ===== Cantidad =====
        $qtyReq = (int)($data['quantity'] ?? 1);
        if ($qtyReq < 1) {
            $qtyReq = 1;
        }

        // ===== Producto =====
        $p = Producto::where('id', $data['product_id'])
            ->where('activo', 1)
            ->where('disponible', 1)
            ->firstOrFail();

        // ===== Precio =====
        $precioFinal = $p->precio !== null ? (float)$p->precio : 0;

        if ($precioFinal <= 0) {
            return response()->json([
                'ok'      => false,
                'message' => 'El producto no tiene un precio válido configurado.',
            ], 422);
        }

        // ===== Presentación (solo id) =====
        $presId = $data['presentacionId'] ?? null;

        if ($presId) {
            $pres = ProductosPresentacion::where('id', $presId)
                ->where('id_productos', $p->id)
                ->where('activo', 1)
                ->first();

            if (!$pres) {
                return response()->json([
                    'ok'      => false,
                    'message' => 'La presentación seleccionada no es válida.',
                ], 422);
            }
        }

        // ===== Agregar al carrito =====
        $cart->add([
            'id'       => 'p' . $p->id . ($presId ? '-pres' . $presId : ''),
            'name'     => $p->titulo ?? $p->nombre ?? 'Producto ' . $p->id,
            'price'    => $precioFinal,
            'quantity' => $qtyReq,
            'attributes' => [
                'product_id'      => $p->id,
                'presentacion_id' => $presId,
            ],
        ]);

        // ===== Log para verificar =====
        Log::info('CARRITO_ADD', [
            'product_id'      => $p->id,
            'precio'          => $precioFinal,
            'cantidad'        => $qtyReq,
            'presentacion_id' => $presId,
        ]);

        return response()->json([
            'ok'    => true,
            'count' => $cart->getTotalQuantity(),
        ]);
    }







    // GET /cart/count (AJAX para badge)
    public function count()
    {
        $cart = $this->cart();

        return response()->json([
            'count' => (int)$cart->getTotalQuantity(),
            'subtotal' => (float)$cart->getSubTotal(), // opcional, útil para tu header o mini-cart
        ]);
    }

    // DELETE /cart/clear
    public function clear()
    {
        $cart = $this->cart(); // usa la misma sesión del carrito
        $cart->clear();

        return back()->with('success','Carrito vaciado.');
    }









    public function updateItem(Request $request)
    {
        $data = $request->validate([
            'row_id' => ['required','string'],
            'qty'    => ['required','integer','min:0'],
        ]);

        $cart = $this->cart();

        if (!$cart->get($data['row_id'])) {
            return response()->json(['ok'=>false,'msg'=>'Ítem no encontrado'], 404);
        }

        if ((int)$data['qty'] === 0) {
            $cart->remove($data['row_id']);
            return response()->json([
                'ok'       => true,
                'rowTotal' => 0,
                'subtotal' => (float)$cart->getSubTotal(),
                'count'    => (int)$cart->getTotalQuantity(),
            ]);
        }

        // Actualiza cantidad de forma absoluta (no relativa)
        $cart->update($data['row_id'], [
            'quantity' => [
                'relative' => false,
                'value'    => (int)$data['qty'],
            ],
        ]);

        $row = $cart->get($data['row_id']);
        $rowTotal = (float)$row->getPriceSum(); // price * qty

        return response()->json([
            'ok'       => true,
            'rowTotal' => $rowTotal,
            'subtotal' => (float)$cart->getSubTotal(),
            'count'    => (int)$cart->getTotalQuantity(),
        ]);
    }

    public function removeItem(Request $request)
    {
        $data = $request->validate([
            'row_id' => ['required','string'],
        ]);

        $cart = $this->cart();

        // Si el row_id existe, elimínalo directamente
        if ($cart->get($data['row_id'])) {
            $cart->remove($data['row_id']);
        } else {
            // Si en algún caso mandas "id" (product_id), podrías buscarlo:
            // foreach ($cart->getContent() as $row) { if ((int)$row->attributes->get('product_id') === (int)$request->id) { $cart->remove($row->id); break; } }
        }

        return response()->json([
            'ok'       => true,
            'subtotal' => (float)$cart->getSubTotal(),
            'count'    => (int)$cart->getTotalQuantity(),
        ]);
    }







}
