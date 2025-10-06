<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        if ($request->expectsJson()) return null;

        // Si tus rutas admin comienzan con /admin
        if ($request->is('admin/*')) {
            return route('admin.login');
        }

        if ($request->is('panel')) {
            return route('admin.login');
        }

        // redireccionar a ruta login
        return route('user.login');
    }
}
