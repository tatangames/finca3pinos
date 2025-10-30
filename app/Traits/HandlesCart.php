<?php

namespace App\Traits;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cookie;
use Darryldecode\Cart\Facades\CartFacade as Cart;

trait HandlesCart
{
    protected function cartKey(): string
    {
        $token = Cookie::get('cart_token');

        if (!$token) {
            $token = Str::uuid()->toString();
            Cookie::queue(Cookie::make('cart_token', $token, 60 * 24 * 30, null, null, false, true));
        }

        return 'guest_'.$token;
    }

    protected function cart()
    {
        return Cart::session($this->cartKey());
    }
}
