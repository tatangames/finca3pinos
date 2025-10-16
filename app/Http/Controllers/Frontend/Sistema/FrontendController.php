<?php

namespace App\Http\Controllers\Frontend\Sistema;

use App\Http\Controllers\Controller;
use App\Models\Galeria;
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

    public function vistaOurCoffee(){

        return view('frontend.pages.ourcoffee', [
            'aboutHistory' => getRegionContent('about.history'),
        ]);
    }




    public function vistaGallery()
    {
        $arrayGaleria = Galeria::orderBy('posicion', 'ASC')->get();

        return view('frontend.pages.gallery', compact('arrayGaleria'));
    }

    public function cargarGaleria(Request $request)
    {
        abort_unless($request->ajax(), 403);

        $offset = (int) $request->input('offset', 0);
        $limit  = (int) $request->input('limit', 24);

        $galeria = Galeria::orderBy('id', 'desc')->skip($offset)->take($limit)->get();

        $html = view('frontend.partials.galeria_items', ['galeria' => $galeria])->render();

        return response()->json([
            'html'  => $html,
            'count' => $galeria->count(),
        ]);
    }

    public function vistaContact(){



        return view('frontend.pages.contact');
    }


    public function vistaProducts(){

        return view('frontend.pages.products');
    }

}
