<?php

namespace App\Http\Controllers\Frontend\Sistema;

use App\Models\ProductosPresentacion;
use App\Traits\HandlesCart;
use Illuminate\Http\Request;
use App\Models\Producto;
use Darryldecode\Cart\Facades\CartFacade as Cart;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cookie;
class CartController extends Controller
{

    use HandlesCart;




    // POST /cart/add  (AJAX)
    public function add(Request $r)
    {
        $data = $r->validate([
            'product_id'     => ['required','integer'],
            'quantity'       => ['nullable','integer','min:1','max:100'],
            'presentacionId' => ['nullable','integer'],
        ]);

        $cart   = $this->cart();
        $qtyReq = (int)($data['quantity'] ?? 1);

        // Producto
        $p = Producto::where('id', $data['product_id'])
            ->where('activo', 1)
            ->firstOrFail();

        // Presentación (opcional)
        $presId = $data['presentacionId'] ?? null;
        $tituloPres = null;
        $precio = (float)$p->precio;

        if ($presId) {
            $pres = ProductosPresentacion::where('id', $presId)
                ->where('id_productos', $p->id)
                ->where('activo', 1)
                ->firstOrFail();

            $tituloPres = $pres->titulo ?? null;
            $precio     = (float)$pres->precio;
        }

        $cart->add([
            'id'       => 'p'.$p->id.($presId ? '-pres'.$presId : ''),
            'name'     => $p->content_title,
            'price'    => $precio,
            'quantity' => $qtyReq,
            'attributes' => [
                'product_id'        => $p->id,
                'presentacion_id'   => $presId,      // 🔴 clave EXACTA
                'presentacion_txt'  => $tituloPres,
            ],
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
