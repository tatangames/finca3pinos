<?php

namespace App\Http\Middleware;

use App\Traits\HandlesCart;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use Darryldecode\Cart\Facades\CartFacade as Cart;

class SetCartInstance
{
    use HandlesCart;

    public function handle($request, Closure $next)
    {
        // Garantiza que existe cookie del carrito y usa la misma clave siempre
        $this->cartKey();

        return $next($request);
    }
}
