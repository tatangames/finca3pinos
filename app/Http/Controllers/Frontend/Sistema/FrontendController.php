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


        return view('frontend.index');
    }

    public function vistaAbout(){



        return view('frontend.pages.about', [
            'aboutHistory' => getRegionContent('about.history'),
        ]);
    }

}
