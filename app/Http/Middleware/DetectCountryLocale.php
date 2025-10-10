<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class DetectCountryLocale
{
    // País -> locale real
    private const COUNTRY_TO_LOCALE = [
        'SV' => 'sv',
        'US' => 'en',
        'CA' => 'en',
    ];

    public function handle($request, Closure $next)
    {
        // Prefijo ya válido (locale o slug) => seguir normal
        $first = $request->segment(1);
        $supported   = config('laravellocalization.supportedLocales', []);
        $validLocales = array_keys($supported);
        $validSlugs   = array_map(fn($k, $v) => $v['url'] ?? $k, array_keys($supported), $supported);

        if (in_array($first, $validLocales, true) || in_array($first, $validSlugs, true)) {
            return $next($request);
        }

        // Respeta sesión si existe (en/es/sv)
        if ($saved = Session::get('locale')) {
            return redirect()->to(LaravelLocalization::getLocalizedURL($saved));
        }

        // 1) Forzar en local por .env (útil en Laragon)
        if (App::environment('local')) {
            if ($force = env('LOCALE_FORCE')) {           // en | es | sv
                Session::put('locale', $force);
                return redirect()->to(LaravelLocalization::getLocalizedURL($force));
            }
            if ($forceCountry = env('REGION_FORCE')) {    // SV | US | CA ...
                $guess = self::COUNTRY_TO_LOCALE[strtoupper($forceCountry)] ?? 'es';
                Session::put('locale', $guess);
                return redirect()->to(LaravelLocalization::getLocalizedURL($guess));
            }
        }

        // 2) Si viene de Cloudflare, usa CF-IPCountry directo
        $iso = strtoupper($request->header('CF-IPCountry', ''));
        if ($iso) {
            $locale = self::COUNTRY_TO_LOCALE[$iso] ?? 'es';
            Session::put('locale', $locale);
            return redirect()->to(LaravelLocalization::getLocalizedURL($locale));
        }

        // 3) Resolver IP real detrás de proxy/CDN
        $ip = $this->clientIp($request);

        // 4) GeoIP
        try {
            $loc = geoip()->getLocation($ip);
            $country = strtoupper($loc->iso_code ?? '');
        } catch (\Throwable $e) {
            $country = '';
        }

        Log::info('🌎 GEOIP detect', [
            'ip'      => $this->clientIp($request),
            'country' => $country,
            'city'    => $loc->city ?? 'N/A',
            'iso'     => $loc->iso_code ?? 'N/A',
        ]);

        $locale = self::COUNTRY_TO_LOCALE[$country] ?? 'es';
        Session::put('locale', $locale);

        return redirect()->to(LaravelLocalization::getLocalizedURL($locale));
    }

    private function clientIp($request): string
    {
        // X-Forwarded-For puede traer "ip1, ip2, ip3" => toma la primera
        $xff = $request->header('X-Forwarded-For');
        if ($xff) {
            $parts = array_map('trim', explode(',', $xff));
            if (!empty($parts[0])) return $parts[0];
        }
        if ($cip = $request->header('CF-Connecting-IP')) return $cip;
        if ($rip = $request->header('X-Real-IP')) return $rip;
        return $request->ip(); // 127.0.0.1 / ::1 en local
    }
}
