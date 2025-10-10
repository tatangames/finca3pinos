<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Session;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class DetectCountryLocale
{
    private const COUNTRY_TO_LOCALE = [
        'SV' => 'sv',
        'US' => 'en',
        'CA' => 'en',
    ];

    public function handle($request, Closure $next)
    {
        // --- 1) Prefijos válidos (locales + slugs 'url') ---
        $supported = config('laravellocalization.supportedLocales', []);

        // locales reales, p.ej.: ['en','es','sv']
        $validLocales = array_keys($supported);

        // slugs configurados en 'url' o el propio locale si no existe 'url'
        $validSlugs = [];
        foreach ($supported as $localeKey => $props) {
            $validSlugs[] = $props['url'] ?? $localeKey;
        }

        $first = $request->segment(1);

        // Si ya viene con un prefijo válido (slug o locale), NO redirijas.
        if (in_array($first, $validLocales, true) || in_array($first, $validSlugs, true)) {
            return $next($request);
        }

        // --- 2) Si hay locale en sesión, respétalo sin loops ---
        if ($saved = Session::get('locale')) {           // debe ser en/es/sv
            return redirect()->to(LaravelLocalization::getLocalizedURL($saved));
        }

        // --- 3) GeoIP -> locale real ---
        try {
            $loc = geoip()->getLocation($request->ip());
            $country = strtoupper($loc->iso_code ?? '');
        } catch (\Throwable $e) {
            $country = '';
        }

        $locale = self::COUNTRY_TO_LOCALE[$country] ?? 'es';
        Session::put('locale', $locale);

        // Redirige 1 sola vez; el paquete pondrá el slug correcto según 'url'
        return redirect()->to(LaravelLocalization::getLocalizedURL($locale));
    }
}
