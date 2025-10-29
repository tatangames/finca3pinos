<?php

namespace App\Http\Controllers\Frontend\Sistema;

use Illuminate\Http\Request;
use App\Models\Producto;
use Darryldecode\Cart\Facades\CartFacade as Cart;
use App\Http\Controllers\Controller;

class CartController extends Controller
{
    public function index()
    {
        $items = Cart::getContent();          // colección del paquete
        $total = Cart::getTotal();
        return view('frontend.carrito.index', compact('items','total'));
    }

    // POST /cart/add  (AJAX)
    public function add(Request $r)
    {
        $data = $r->validate([
            'product_id'     => ['required','integer'],
            'quantity'       => ['nullable','integer','min:1','max:100'],
            'presentacionId' => ['nullable','integer'],
        ]);

        $p = Producto::findOrFail($data['product_id']);

        // atributos opcionales (presentación, etc.)
        $attributes = [];
        if (!empty($data['presentacionId'])) {
            $attributes['presentacion_id'] = (int)$data['presentacionId'];
        }

        Cart::add([
            'id'        => $p->id,
            'name'      => $p->titulo ?? $p->nombre ?? "Producto {$p->id}",
            'price'     => (float)($p->precio ?? 0),
            'quantity'  => (int)($data['quantity'] ?? 1),
            'attributes'=> $attributes,
        ]);

        return response()->json([
            'ok'     => true,
            'count'  => Cart::getTotalQuantity(),
            'total'  => Cart::getTotal(),
            'msg'    => __('Producto agregado al carrito'),
        ]);
    }

    // GET /cart/count (AJAX para badge)
    public function count()
    {
        return response()->json([
            'count' => Cart::getTotalQuantity(),
        ]);
    }

    // DELETE /cart/clear
    public function clear()
    {
        Cart::clear();
        return back()->with('success','Carrito vaciado.');
    }
}
