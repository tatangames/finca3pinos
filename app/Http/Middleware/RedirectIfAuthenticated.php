<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {

                // Si está autenticado como admin
                if ($guard === 'admin') {
                    return redirect()->route('admin.panel');
                }

                // Si está autenticado como usuario normal (guard web)
                if ($guard === 'web' || $guard === null) {
                    return redirect()->route('user.dashboard');
                }

                // Si más adelante agregas auth por JWT, puedes controlar otros casos aquí
            }
        }

        return $next($request);
    }
}
