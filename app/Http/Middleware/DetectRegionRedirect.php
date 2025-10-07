<?php

namespace App\Http\Middleware;

use Closure;

class DetectRegionRedirect
{
    public function handle($request, Closure $next)
    {
        $supported = config('region.supported');
        $first = $request->segment(1); // ej: 'sv', 'us', 'latin-es'

        // Si ya viene con región al inicio, seguimos normal
        if (in_array($first, $supported, true)) {
            return $next($request);
        }

        // Si el usuario ya eligió región antes, respétala
        if ($saved = session('region')) {
            return redirect()->to("/{$saved}{$request->getRequestUri()}");
        }

        // Best effort por IP (opcional)
        $best = config('region.default');
        try {
            if (function_exists('geoip')) {
                $loc = geoip()->getLocation($request->ip());
                $map = config('region.country_to_region');
                $best = $map[$loc->iso_code] ?? 'latin-es';
            }
        } catch (\Throwable $e) {
            $best = config('region.default');
        }

        session(['region' => $best]);
        // Redirige preservando el path completo (sin región)
        $uri = ltrim($request->getRequestUri(), '/'); // p.ej. "shop?x=1"
        return redirect()->to("/{$best}/{$uri}");
    }
}
