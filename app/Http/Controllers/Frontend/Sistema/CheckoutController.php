<?php

namespace App\Http\Controllers\Frontend\Sistema;

use App\Http\Controllers\Controller;
use App\Models\Direcciones;
use App\Models\DireccionFacturacion;
use App\Traits\HandlesCart;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{

    use HandlesCart;

    public function show()
    {
        $cart     = $this->cart();
        $items    = $cart->getContent();
        $subtotal = $cart->getSubTotal();
        $shipping = 0;
        $tax      = 0;
        $total    = $subtotal + $shipping + $tax;

        $billing   = DireccionFacturacion::where('id_usuario', auth()->id())->first();

        $addresses = Direcciones::where('id_usuario', auth()->id())
            ->orderByDesc('predeterminado')
            ->latest('id')
            ->get();

        $selectedAddressId = optional(
            $addresses->firstWhere('predeterminado', 1) ?? $addresses->first()
        )->id;

        return view('frontend.pages.checkout', compact(
            'items','subtotal','shipping','tax','total','billing','addresses','selectedAddressId'
        ));
    }




}
