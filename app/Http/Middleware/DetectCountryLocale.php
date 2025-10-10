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
        'SV' => 'sv',
        'US' => 'en',
        'CA' => 'en',
        'GB' => 'en',
        'AU' => 'en',
        'NZ' => 'en',
        // resto: 'es' (-> /latin-es por config)
    ];

    public function handle($request, Closure $next)
    {
        // 0) Si ya viene con URL localizada, no hacer nada
        $first       = $request->segment(1);
        $supported   = config('laravellocalization.supportedLocales', []);
        $validLocales = array_keys($supported);                     // ['en','es','sv']
        $validSlugs   = array_map(
            fn ($k, $v) => $v['url'] ?? $k,
            array_keys($supported),
            $supported
        ); // e.g. ['en','latin-es','sv']

        if (in_array($first, $validLocales, true) || in_array($first, $validSlugs, true)) {
            return $next($request);
        }

        // 1) Respetar preferencia guardada en sesión (locale: en/es/sv)
        if ($saved = Session::get('locale')) {
            return redirect()->to(LaravelLocalization::getLocalizedURL($saved));
        }

        // 2) Forzar en local (útil en Laragon)
        if (App::environment('local')) {
            if ($force = env('LOCALE_FORCE')) {           // valores: en | es | sv
                Session::put('locale', $force);
                return redirect()->to(LaravelLocalization::getLocalizedURL($force));
            }
            if ($forceCountry = env('REGION_FORCE')) {    // valores: SV | US | CA | ...
                $guess = self::COUNTRY_TO_LOCALE[strtoupper($forceCountry)] ?? 'es';
                Session::put('locale', $guess);
                return redirect()->to(LaravelLocalization::getLocalizedURL($guess));
            }
        }

        // 3) Si viene de Cloudflare, usar CF-IPCountry
        if ($iso = strtoupper($request->header('CF-IPCountry', ''))) {
            $locale = self::COUNTRY_TO_LOCALE[$iso] ?? 'es';
            Session::put('locale', $locale);
            return redirect()->to(LaravelLocalization::getLocalizedURL($locale));
        }

        // 4) Resolver IP real detrás de proxy/CDN
        $ip = $this->clientIp($request);

        // 5) GeoIP (con try/catch)
        $loc = null;
        $country = '';
        try {
            $loc     = geoip()->getLocation($ip);
            $country = strtoupper($loc->iso_code ?? '');
        } catch (\Throwable $e) {
            // silencioso
        }

        Log::info('🌎 GEOIP detect', [
            'ip'      => $ip,
            'country' => $country ?: 'N/A',
            'city'    => $loc->city ?? 'N/A',
            'iso'     => $loc->iso_code ?? 'N/A',
        ]);

        $locale = self::COUNTRY_TO_LOCALE[$country] ?? 'es'; // default -> ES => /latin-es
        Session::put('locale', $locale);

        return redirect()->to(LaravelLocalization::getLocalizedURL($locale));
    }

    private function clientIp($request): string
    {
        if ($xff = $request->header('X-Forwarded-For')) {
            $parts = array_map('trim', explode(',', $xff));
            if (!empty($parts[0])) return $parts[0];
        }
        if ($cip = $request->header('CF-Connecting-IP')) return $cip;
        if ($rip = $request->header('X-Real-IP'))        return $rip;
        return $request->ip();
    }
}
