<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class DetectCountryLocale
{
    /**
     * Mapa PAÍS (ISO2) -> locale de Laravel
     * Nota: el slug /latin-es lo genera LaravelLocalization desde el locale 'es'
     */
    private const COUNTRY_TO_LOCALE = [
        'SV' => 'sv', // El Salvador -> /sv
        'US' => 'en', // USA/Canadá -> /en
        'CA' => 'en',
        // resto: 'es' (-> /latin-es por config)
    ];

    public function handle($request, Closure $next)
    {
        // 0) Si ya viene con slug/locale en URL, seguimos
        $first       = $request->segment(1);
        $supported   = config('laravellocalization.supportedLocales', []);
        $validLocales = array_keys($supported); // ['en','es','sv']
        $validSlugs   = array_map(fn ($k, $v) => $v['url'] ?? $k, array_keys($supported), $supported); // ['en','latin-es','sv']

        if (in_array($first, $validLocales, true) || in_array($first, $validSlugs, true)) {
            return $next($request);
        }

        // 1) Si el usuario eligió manualmente (flag), respeta su sesión
        if (Session::get('locale_forced') && ($saved = Session::get('locale'))) {
            return redirect()->to(LaravelLocalization::getLocalizedURL($saved));
        }

        // 2) Fuerzas locales solo en entorno local (dev)
        if (app()->environment('local')) {
            if ($force = env('LOCALE_FORCE')) {
                Session::put('locale', $force);
                Session::put('locale_forced', true);
                return redirect()->to(LaravelLocalization::getLocalizedURL($force));
            }
            if ($forceCountry = env('REGION_FORCE')) {
                $guess = self::COUNTRY_TO_LOCALE[strtoupper($forceCountry)] ?? 'es';
                Session::put('locale', $guess);
                Session::put('locale_forced', true);
                return redirect()->to(LaravelLocalization::getLocalizedURL($guess));
            }
        }

        // 3) Cloudflare: CF-IPCountry (siempre primero)
        $iso = strtoupper($request->header('CF-IPCountry', ''));
        if ($iso && $iso !== 'XX' && $iso !== 'T1') {
            $locale = self::COUNTRY_TO_LOCALE[$iso] ?? 'es';
            Session::put('locale', $locale);
            // no marcamos forced: es autodetección
            return redirect()->to(LaravelLocalization::getLocalizedURL($locale));
        }

        // 4) IP real (con TrustProxies ya funciona $request->ip())
        $ip = $this->clientIp($request);

        // 5) GeoIP como fallback
        $country = '';
        $city = 'N/A';
        try {
            $loc     = geoip()->getLocation($ip);
            $country = strtoupper($loc->iso_code ?? '');
            $city    = $loc->city ?? 'N/A';
        } catch (\Throwable $e) {}

        $locale = self::COUNTRY_TO_LOCALE[$country] ?? 'es';

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
        // Con TrustProxies activo, $request->ip() ya es confiable
        if ($cip = $request->header('CF-Connecting-IP')) return $cip;

        if ($xff = $request->header('X-Forwarded-For')) {
            $parts = array_map('trim', explode(',', $xff));
            if (!empty($parts[0])) return $parts[0];
        }
        if ($rip = $request->header('X-Real-IP')) return $rip;

        return $request->ip();
    }
}
