<?php

use App\Models\RegionContentTranslation;
use Illuminate\Support\Facades\Cache;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use App\Models\Region;
use App\Models\RegionContent;

if (!function_exists('getRegionContent')) {
    function getRegionContent(string $key): array
    {
        // 1) Idioma actual: /sv /en /es
        $locale = LaravelLocalization::getCurrentLocale(); // 'sv' | 'en' | 'es'

        // 2) Región elegida: primero sesión (si la seteas en middleware), si no, mapeo por locale
        $regionSlug = session('region_slug'); // opcional
        if (!$regionSlug) {
            // Ajusta a tu preferencia:
            $regionByLocale = [
                'sv' => 'sv',        // ES (El Salvador)
                'en' => 'us',        // EN (USA)
                'ko' => 'kr',
            ];
            $regionSlug = $regionByLocale[$locale] ?? 'sv';
        }


        // 3) Sin caché: consultar directamente
        $region = Region::where('slug', $regionSlug)->first();
        if (!$region) return [];

        $content = RegionContent::where('region_id', $region->id)
            ->where('key', $key)
            ->first();

        if (!$content) return [];

        // Traducción en idioma actual
        $tr = RegionContentTranslation::where('content_id', $content->id)
            ->where('locale', $locale)
            ->first();

        if ($tr) {
            return [
                'title'  => $tr->title ?? '',
                'body'   => $tr->body ?? '',
                'slug'   => $tr->slug ?? '',
                'altseo' => $tr->altseo ?? '',
            ];
        }

        // Fallback
        $fallback = $region->locale ?: config('app.fallback_locale', 'es');

        $trFallback = RegionContentTranslation::where('content_id', $content->id)
            ->where('locale', $fallback)
            ->first();

        return [
            'title'  => $trFallback->title ?? '',
            'body'   => $trFallback->body ?? '',
            'slug'   => $trFallback->slug ?? '',
            'altseo' => $trFallback->altseo ?? '',
        ];




        // 3) Cache por región+key+idioma
        $cacheKey = "rc:$regionSlug:$key:$locale";

        return Cache::remember($cacheKey, 30 * 60, function () use ($regionSlug, $key, $locale) {
            $region = Region::where('slug', $regionSlug)->first();
            if (!$region) return '';

            $content = RegionContent::where('region_id', $region->id)
                ->where('key', $key)
                ->first();

            if (!$content) return '';

            // Traducción en el idioma actual
            $tr = RegionContentTranslation::where('content_id', $content->id)
                ->where('locale', $locale)
                ->first();

            if ($tr?->body) return $tr->body;

            // Fallback: idioma por defecto de la región o fallback global
            $fallback = $region->locale ?: config('app.fallback_locale', 'es');

            $trFallback = RegionContentTranslation::where('content_id', $content->id)
                ->where('locale', $fallback)
                ->first();

            return [
                'title'  => $trFallback->title ?? '',
                'body'   => $trFallback->body ?? '',
                'slug'   => $trFallback->slug ?? '',
                'altseo' => $trFallback->altseo ?? '',
            ];
        });
    }
}
