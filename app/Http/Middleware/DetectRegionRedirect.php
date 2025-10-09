<?php

namespace App\Http\Middleware;

use Closure;

class DetectRegionRedirect
{
    public function handle($request, Closure $next)
    {
        // 1) EXCEPCIONES: no tocar admin ni assets públicos/estáticos
        $excluded = [
            'admin', 'admin/*',
            'horizon*', 'telescope*',
            'vendor/*', 'storage/*',
            'build/*', 'public/*', 'assets/*', 'css/*', 'js/*', 'images/*', 'img/*',
            'mix-manifest.json',
            'favicon.ico', 'robots.txt', 'sitemap.xml',
        ];
        if ($request->is($excluded) || $request->routeIs('admin.*')) {
            return $next($request);
        }

        // Configuración
        $supported = config('region.supported', []);
        $localeMap = config('region.locale_map', []);
        $mapToLocale = static function (string $region) use ($localeMap): string {
            return $localeMap[$region] ?? $region; // fallback: usa el mismo slug como locale
        };

        // Helper: reconstruir URL con región preservando path y querystring
        $buildUrl = function (string $region) use ($request): string {
            $path = ltrim($request->getPathInfo(), '/');   // ej: "shop"
            $qs   = $request->getQueryString();            // ej: "x=1"
            $url  = '/' . $region . ($path ? '/' . $path : '');
            return $qs ? $url . '?' . $qs : $url;
        };

        // 2) Si ya viene región en el primer segmento, fija locale y sigue
        $first = $request->segment(1);
        if (in_array($first, $supported, true)) {
            app()->setLocale($mapToLocale($first));
            session(['region' => $first]);
            return $next($request);
        }

        // 3) Si hay región guardada en sesión, redirige a esa región
        if ($saved = session('region')) {
            app()->setLocale($mapToLocale($saved));
            return redirect()->to($buildUrl($saved));
        }

        // 4) Detección por IP (opcional) o default
        $best = config('region.default', 'sv');
        try {
            if (function_exists('geoip')) {
                $loc = geoip()->getLocation($request->ip());       // requiere paquete geoip configurado
                $countryMap = config('region.country_to_region', []);
                $best = $countryMap[$loc->iso_code] ?? 'latin-es';  // fallback regional
            }
        } catch (\Throwable $e) {
            $best = config('region.default', 'sv');
        }

        app()->setLocale($mapToLocale($best));
        session(['region' => $best]);
        return redirect()->to($buildUrl($best));
    }
}
