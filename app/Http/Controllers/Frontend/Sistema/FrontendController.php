<?php

namespace App\Http\Controllers\Frontend\Sistema;

use App\Http\Controllers\Controller;
use App\Models\Galeria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Validator;
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
        $arrayGaleria = Galeria::orderBy('posicion', 'ASC')
            ->get()
            ->map(function ($item) {
                // Usa tu helper global (automáticamente detecta locale y región)
                $item->texto_idioma = getRegionContent($item->content_key);
                return $item;
            });


        return view('frontend.pages.gallery', compact('arrayGaleria'));
    }


    public function cargarGaleria(Request $request)
    {
        abort_unless($request->ajax(), 403);

        $offset = (int) $request->input('offset', 0);
        $limit  = (int) $request->input('limit', 24);

        $galeria = Galeria::orderBy('id', 'desc')
            ->skip($offset)
            ->take($limit)
            ->get()
            ->map(function ($item) {
                // Asigna la traducción según su key y el idioma/región actual
                $item->texto_idioma = getRegionContent($item->content_key);
                return $item;
            });

        $html = view('frontend.partials.galeria_items', ['galeria' => $galeria])->render();

        return response()->json([
            'html'  => $html,
            'count' => $galeria->count(),
        ]);
    }

    public function vistaContact(){
        return view('frontend.pages.contact');
    }


    public function send(Request $request)
    {
        $rules = [
            'name'    => ['required', 'string', 'max:100'],
            'email'   => ['required', 'email', 'max:100'],
            'message' => ['required', 'string', 'max:2000'],
        ];

        $attributes = [
            'name'    => __('meta.contact_v5'),
            'email'   => __('meta.contact_v6'),
            'message' => __('meta.contact_v7'),
        ];

        $validator = Validator::make($request->all(), $rules, [], $attributes);

        if ($validator->fails()) {
            return response()->json([
                'ok'     => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        // Aquí podrías enviar correo, guardar en BD, etc.

        return response()->json([
            'ok'      => true,
            'message' => __('meta.contact_ok'),
        ]);
    }




    public function vistaProducts(){

        return view('frontend.pages.products');
    }

}
