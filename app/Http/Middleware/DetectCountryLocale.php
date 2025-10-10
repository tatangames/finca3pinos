<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Session;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class DetectCountryLocale
{
    // locale real -> slug deseado
    private const LOCALE_TO_SLUG = [
        'en' => 'us',
        'es' => 'latin-es',
        'sv' => 'sv',
    ];

    // país -> locale real
    private const COUNTRY_TO_LOCALE = [
        'SV' => 'sv',
        'US' => 'en',
        'CA' => 'en',
    ];

    public function handle($request, Closure $next)
    {
        // Si ya trae slug válido, seguir normal
        $first = $request->segment(1);
        if (in_array($first, ['us', 'sv', 'latin-es'], true)) {
            return $next($request);
        }

        // Si la sesión tiene algo, normalízalo a locale real y redirige con slug
        if ($saved = Session::get('locale')) {
            $locale = $this->normalizeToLocale($saved); // en/es/sv
            return redirect()->to($this->sluggedUrl($locale, $request));
        }

        // GeoIP → locale real
        try {
            $loc = geoip()->getLocation($request->ip());
            $country = strtoupper($loc->iso_code ?? '');
        } catch (\Throwable $e) {
            $country = '';
        }
        $locale = self::COUNTRY_TO_LOCALE[$country] ?? 'es';

        Session::put('locale', $locale);

        return redirect()->to($this->sluggedUrl($locale, $request));
    }

    private function normalizeToLocale(string $value): string
    {
        // Acepta por si quedó guardado un slug
        $slugToLocale = ['us' => 'en', 'latin-es' => 'es', 'sv' => 'sv'];
        return $slugToLocale[$value] ?? $value; // en/es/sv
    }

    private function sluggedUrl(string $locale, $request): string
    {
        // Deja que el paquete construya la URL localizada…
        $url = LaravelLocalization::getLocalizedURL($locale, $request->fullUrl());

        // …y reemplaza el primer segmento /en|/es|/sv por tu slug personalizado
        $from = $locale;                                   // en | es | sv
        $to   = self::LOCALE_TO_SLUG[$locale] ?? $locale;  // us | latin-es | sv
        if ($from !== $to) {
            $url = preg_replace('#/'.$from.'(?=/|$)#', '/'.$to, $url, 1);
        }
        return $url;
    }
}
