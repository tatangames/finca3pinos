<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Arr;

class SetRegion
{
    public function handle($request, Closure $next)
    {
        $supported = config('region.supported');
        $localeMap = config('region.locale_map');

        // Primer segmento de la URL (ej: sv, us, latin-es)
        $region = $request->route('region') ?? $request->segment(1);

        if (!in_array($region, $supported, true)) {
            $region = session('region', config('region.default'));
        }

        // Permitir override manual por query (?region=us)
        if ($q = $request->query('region')) {
            if (in_array($q, $supported, true)) {
                $region = $q;
                session(['region' => $region]);
            }
        }

        // Guardar y setear locale
        app()->instance('region', $region);
        session(['region' => $region]);

        $locale = Arr::get($localeMap, $region, 'es');
        app()->setLocale($locale);

        return $next($request);
    }
}
