<?php

namespace App\Http\Controllers\Frontend\Sistema;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use App\Models\Region;
use App\Models\RegionContent;
use Illuminate\Support\Facades\Cache;

class FrontendController extends Controller
{
    public function vistaIndex()
    {
        // idioma actual: 'sv' o 'us' o 'latin-es'
        $regionSlug = LaravelLocalization::getCurrentLocale();

        $aboutHistory = Cache::remember("rc:$regionSlug:about.history", now()->addMinutes(30), function () use ($regionSlug) {
            $region = Region::where('slug', $regionSlug)->first();
            if (!$region) {
                return '<p>(Contenido no disponible)</p>';
            }

            $content = RegionContent::where('region_id', $region->id)
                ->where('key', 'about.history')
                ->where('status', 'published')
                ->first();

            return $content?->body ?? '<p>(Pronto añadiremos nuestra historia.)</p>';
        });

        return view('frontend.index', [
            'aboutHistory' => $aboutHistory,
        ]);

    }

    public function vistaAbout(){

        return "vista about";
    }

}
