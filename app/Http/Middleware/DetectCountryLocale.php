<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Session;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class DetectCountryLocale
{
    public function handle($request, Closure $next)
    {
        // Si ya viene con un prefijo válido (slug), seguimos normal
        $first = $request->segment(1);
        if (in_array($first, ['us', 'sv', 'latin-es'], true)) {
            return $next($request);
        }

        // Si ya hay locale guardado en sesión, redirigimos al slug correcto
        if ($saved = Session::get('locale')) { // <- debe guardar 'en', 'es' o 'sv'
            // Normalizar: convertir locale a slug
            $slugMap = [
                'en' => 'us',
                'es' => 'latin-es',
                'sv' => 'sv',
            ];
            $slug = $slugMap[$saved] ?? 'latin-es';
            return redirect(LaravelLocalization::getLocalizedURL($saved)); // usa el locale real
        }

        // --- Detección por IP ---
        try {
            $loc = geoip()->getLocation($request->ip());
            $country = strtoupper($loc->iso_code ?? '');
        } catch (\Throwable $e) {
            $country = '';
        }

        // País -> locale real
        $countryToLocale = [
            'SV' => 'sv', // español El Salvador
            'US' => 'en', // inglés
            'CA' => 'en', // Canadá
        ];

        $locale = $countryToLocale[$country] ?? 'es'; // default: español LATAM

        // Guardar locale real (no slug)
        Session::put('locale', $locale);

        // Redirigir a la URL localizada con el locale real
        return redirect(LaravelLocalization::getLocalizedURL($locale));
    }
}
