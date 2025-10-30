<?php

namespace App\Http\Middleware;

use App\Traits\HandlesCart;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureCartNotEmpty {
    use HandlesCart;
    public function handle($req, Closure $next) {
        if ($this->cart()->isEmpty()) {
            return redirect()->route('user.cart')->with('warning', __('meta.your_cart_empty'));
        }
        return $next($req);
    }
}
