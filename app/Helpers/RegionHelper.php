<?php

use Illuminate\Support\Facades\Cache;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use App\Models\Region;
use App\Models\RegionContent;

if (!function_exists('getRegionContent')) {
    /**
     * Obtiene contenido dinámico por región y clave.
     * Cachea 30 minutos por región + key.
     */
    function getRegionContent(string $key): string
    {
        // Detecta slug real (de la URL o del locale)
        $slug = request()->segment(1) ?? LaravelLocalization::getCurrentLocale();

        // Fallback: si entra sin prefijo o slug inválido
        if (!in_array($slug, ['sv','en','es'], true)) {
            $slug = 'sv';
        }

        // Clave única de caché por región y clave
        $cacheKey = "rc:$slug:$key";

        return Cache::remember($cacheKey, now()->addMinutes(30), function () use ($slug, $key) {
            $region = Region::where('slug', $slug)->first();
            if (!$region) return '';

            $body = RegionContent::where('region_id', $region->id)
                ->where('key', $key)
                ->where('status', 'published')
                ->value('body');

            if ($body) return $body;

            // Fallback a contenido en español
            $fallbackId = Region::where('slug', 'es')->value('id');
            return RegionContent::where('region_id', $fallbackId)
                ->where('key', $key)
                ->where('status', 'published')
                ->value('body') ?? '';
        });
    }
}
