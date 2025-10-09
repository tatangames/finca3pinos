<?php

namespace App\Http\Controllers\Frontend\Sistema;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Region;
use App\Models\RegionContent;
use Illuminate\Support\Facades\Cache;

class FrontendController extends Controller
{
    public function vistaLogin()
    {

        $regionSlug = session('region', config('region.default', 'sv'));

        // Carga con caché (30 min). Podés repetir esta función para otras keys.
        $aboutHistory = Cache::remember("rc:$regionSlug:about.history", now()->addMinutes(30), function () use ($regionSlug) {
            $region = Region::where('slug', $regionSlug)->first();
            if (!$region) return '<p>(Contenido no disponible)</p>';

            $content = RegionContent::where('region_id', $region->id)
                ->where('key', 'about.history')
                ->where('status', 'published')
                ->first();

            return $content?->body ?? '<p>(Pronto añadiremos nuestra historia.)</p>';
        });

        return view('frontend.index', [
            'aboutHistory' => $aboutHistory,
            // si querés más bloques:
            // 'homeDescription' => $homeDescription,
            // 'footerBlurb'     => $footerBlurb,
        ]);

    }
}
