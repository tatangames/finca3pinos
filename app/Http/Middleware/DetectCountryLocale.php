<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Session;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class DetectCountryLocale
{
    public function handle($request, Closure $next)
    {
        // Si ya viene con un prefijo válido, seguir normal
        $first = $request->segment(1);
        if (in_array($first, ['us', 'sv', 'latin-es'], true)) {
            return $next($request);
        }

        // Si ya tenés guardado el locale en sesión, redirigí a ese locale
        if ($saved = Session::get('locale')) { // <-- guardar 'en'/'sv'/'es'
            return redirect(LaravelLocalization::getLocalizedURL($saved));
        }

        // --- Detección por IP ---
        try {
            // SIMULAR: cambia esta IP para probar (US: 8.8.8.8, CA: 99.236.68.45, SV: 181.209.88.90)
             $loc = geoip()->getLocation($request->ip());
           // $loc = geoip()->getLocation('8.8.8.8'); // ← QUITA esto cuando termines de probar

            $country = strtoupper($loc->iso_code ?? '');
        } catch (\Throwable $e) {
            $country = '';
        }

        // País -> LOCALE (no slug)
        $countryToLocale = [
            'SV' => 'sv', // español El Salvador
            'US' => 'en', // inglés
            'CA' => 'en', // por ahora inglés (si quieres fr-CA luego lo cambiamos)
        ];

        $locale = $countryToLocale[$country] ?? 'es'; // default: español LATAM

        // Guardar LOCALE en sesión
        Session::put('locale', $locale);

        // Redirigir a la URL localizada con LOCALE
        return redirect(LaravelLocalization::getLocalizedURL($locale));
    }
}
