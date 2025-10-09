<?php

use App\Models\Region;
use App\Models\RegionContent;
use Illuminate\Support\Facades\Cache;

if (!function_exists('region_content')) {
    function region_content(string $key, ?string $regionSlug = null, ?string $fallback = null): ?string
    {
        $regionSlug = $regionSlug ?? session('region') ?? config('region.default', 'sv');

        $cacheKey = "region_content:{$regionSlug}:{$key}";

        return Cache::remember($cacheKey, now()->addMinutes(30), function () use ($key, $regionSlug, $fallback) {
            $region = Region::where('slug', $regionSlug)->first();

            if ($region) {
                $content = RegionContent::where('region_id', $region->id)
                    ->where('key', $key)
                    ->where('status', 'published')
                    ->first();
                if ($content) {
                    return $content->body;
                }
            }

            // fallback
            return $fallback ?? '<p>(xx)</p>';
        });
    }
}
