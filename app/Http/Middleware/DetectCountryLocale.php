<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class DetectCountryLocale
{
    private const COUNTRY_TO_LOCALE = [
        'SV' => 'sv',
        'US' => 'en',
        'CA' => 'en',
        // resto => 'sv'
    ];

    public function handle($request, \Closure $next)
    {
        $first = strtolower((string) $request->segment(1));

        $supportedLocales = array_keys(config('laravellocalization.supportedLocales', []));
        $localesMapping   = array_keys(config('laravellocalization.localesMapping', []));
        $acceptSegments   = array_unique(array_merge($supportedLocales, $localesMapping));

        // 0) Si ya viene con /en, /sv, /ko, etc -> seguir normal
        if (in_array($first, $acceptSegments, true)) {
            // opcional: guardar lo que viene en la URL para futuras peticiones
            Session::put('locale', $first);
            return $next($request);
        }

        // 1) Respeta elección manual
        if (Session::get('locale_forced') && ($saved = Session::get('locale'))) {
            return $this->redirectIfNeeded($request, $saved, $next);
        }

        // 2) Forzar en local (dev)
        if (app()->environment('local')) {
            if ($force = env('LOCALE_FORCE')) {
                Session::put('locale', $force);
                Session::put('locale_forced', true);
                return $this->redirectIfNeeded($request, $force, $next);
            }
            if ($forceCountry = env('REGION_FORCE')) {
                $guess = self::COUNTRY_TO_LOCALE[strtoupper($forceCountry)] ?? 'sv';
                Session::put('locale', $guess);
                Session::put('locale_forced', true);
                return $this->redirectIfNeeded($request, $guess, $next);
            }
        }

        // 3) Cloudflare
        $iso = strtoupper($request->header('CF-IPCountry', ''));
        if ($iso && $iso !== 'XX' && $iso !== 'T1') {
            $locale = self::COUNTRY_TO_LOCALE[$iso] ?? 'sv';
            Session::put('locale', $locale);
            return $this->redirectIfNeeded($request, $locale, $next);
        }

        // 4) IP real + GeoIP
        $ip = $this->clientIp($request);
        $country = '';
        $city = 'N/A';

        try {
            $loc     = geoip()->getLocation($ip);
            $country = strtoupper($loc->iso_code ?? '');
            $city    = $loc->city ?? 'N/A';
        } catch (\Throwable $e) {}

        $locale = self::COUNTRY_TO_LOCALE[$country] ?? 'sv';

        Log::info('🌎 GEOIP detect', [
            'ip'      => $ip,
            'country' => $country ?: 'N/A',
            'city'    => $city,
            'cf_iso'  => $iso ?: 'N/A',
            'picked'  => $locale,
        ]);

        Session::put('locale', $locale);
        return $this->redirectIfNeeded($request, $locale, $next);
    }

    private function redirectIfNeeded($request, string $locale, \Closure $next)
    {
        $hideDefault = (bool) config('laravellocalization.hideDefaultLocaleInURL', true);
        $default     = config('laravellocalization.defaultLocale', config('app.locale', 'sv'));

        $supportedLocales = array_keys(config('laravellocalization.supportedLocales', []));
        $localesMapping   = array_keys(config('laravellocalization.localesMapping', []));
        $acceptSegments   = array_unique(array_merge($supportedLocales, $localesMapping));

        $first = strtolower((string) $request->segment(1));

        // Caso: locale = default oculto (sv), ya estamos en URL sin prefijo -> NO redirigir
        if ($hideDefault && $locale === $default && !in_array($first, $acceptSegments, true)) {
            app()->setLocale($locale);
            return $next($request);
        }

        // Construir URL localizada
        $target = LaravelLocalization::getLocalizedURL($locale, null, [], true);

        // Si ya estamos en la misma URL -> evitar loop
        if (rtrim($target, '/') === rtrim($request->fullUrl(), '/')) {
            app()->setLocale($locale);
            return $next($request);
        }

        return redirect()->to($target);
    }

    private function clientIp($request): string
    {
        if ($cip = $request->header('CF-Connecting-IP')) return $cip;

        if ($xff = $request->header('X-Forwarded-For')) {
            $parts = array_map('trim', explode(',', $xff));
            if (!empty($parts[0])) return $parts[0];
        }

        if ($rip = $request->header('X-Real-IP')) return $rip;

        return $request->ip();
    }
}
