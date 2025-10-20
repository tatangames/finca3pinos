<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class DetectCountryLocale
{
    /**
     * Mapa PAÍS (ISO2) -> locale de Laravel
     * Fallback global: 'sv' (idioma por defecto sin prefijo)
     */
    private const COUNTRY_TO_LOCALE = [
        'SV' => 'sv', // El Salvador -> sv (sin prefijo)
        'US' => 'en', // USA/Canadá -> en (/en o /us si usas mapping)
        'CA' => 'en',
        // resto: 'sv'
    ];

    public function handle($request, Closure $next)
    {
        // 0) Si ya viene con locale/alias en la URL, continuar
        $first = (string) $request->segment(1);

        $supportedLocales = array_keys(config('laravellocalization.supportedLocales', [])); // p.ej. ['en','sv','ko']
        $localesMapping   = array_keys(config('laravellocalization.localesMapping', []));   // p.ej. ['us','sv','kr']

        $acceptSegments = array_unique(array_merge($supportedLocales, $localesMapping));

        if (in_array($first, $acceptSegments, true)) {
            return $next($request);
        }

        // 1) Respeta elección manual almacenada en sesión
        if (Session::get('locale_forced') && ($saved = Session::get('locale'))) {
            return redirect()->to(LaravelLocalization::getLocalizedURL($saved));
        }

        // 2) Fuerzas locales en entorno local (dev)
        if (app()->environment('local')) {
            if ($force = env('LOCALE_FORCE')) {
                Session::put('locale', $force);
                Session::put('locale_forced', true);
                return redirect()->to(LaravelLocalization::getLocalizedURL($force));
            }
            if ($forceCountry = env('REGION_FORCE')) {
                $guess = self::COUNTRY_TO_LOCALE[strtoupper($forceCountry)] ?? 'sv';
                Session::put('locale', $guess);
                Session::put('locale_forced', true);
                return redirect()->to(LaravelLocalization::getLocalizedURL($guess));
            }
        }

        // 3) Cloudflare: CF-IPCountry primero
        $iso = strtoupper($request->header('CF-IPCountry', ''));
        if ($iso && $iso !== 'XX' && $iso !== 'T1') {
            $locale = self::COUNTRY_TO_LOCALE[$iso] ?? 'sv';
            Session::put('locale', $locale);
            return redirect()->to(LaravelLocalization::getLocalizedURL($locale));
        }

        // 4) IP real (TrustProxies ya ajusta $request->ip())
        $ip = $this->clientIp($request);

        // 5) GeoIP como fallback
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
            'headers' => [
                'X-Forwarded-For'   => $request->header('X-Forwarded-For'),
                'CF-Connecting-IP'  => $request->header('CF-Connecting-IP'),
                'X-Real-IP'         => $request->header('X-Real-IP'),
            ],
        ]);

        Session::put('locale', $locale);
        return redirect()->to(LaravelLocalization::getLocalizedURL($locale));
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
