<?php

namespace App\Http\Controllers\Frontend\Sistema;

use App\Http\Controllers\Controller;
use App\Mail\ContactMail;
use App\Models\Categoria;
use App\Models\Galeria;
use App\Models\Producto;
use App\Models\ProductosPresentacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
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
        $limitFirst = 24;

        $arrayGaleria = Galeria::where('activo', 1)
            ->orderBy('posicion', 'ASC')
            //->orderBy('id', 'ASC')
            ->take($limitFirst)
            ->get()
            ->map(function ($item) {

                $arrayRegion = getRegionContent($item->content_key);
                $item->textoIdioma = $arrayRegion['body'];
                $item->altseo = $arrayRegion['altseo'];
                return $item;
            });

        // Cursor inicial basado en el ÚLTIMO del grid
        $last = optional($arrayGaleria->last());
        $lastPos = $last?->posicion ?? 0;
        $lastId  = $last?->id ?? 0;


        return view('frontend.pages.gallery', compact('arrayGaleria', 'limitFirst', 'lastPos', 'lastId'));
    }


    public function cargarGaleria(Request $request)
    {
        abort_unless($request->ajax(), 403);

        $lastPos = (int) $request->input('last_pos', 0);
        $lastId  = (int) $request->input('last_id', 0);
        $limit   = (int) $request->input('limit', 24);

        $q = Galeria::where('activo', 1)
            ->where(function ($qq) use ($lastPos, $lastId) {
                // (posicion, id) > (lastPos, lastId)
                $qq->where('posicion', '>', $lastPos)
                    ->orWhere(function ($q2) use ($lastPos, $lastId) {
                        $q2->where('posicion', $lastPos)
                            ->where('id', '>', $lastId);
                    });
            })
            ->orderBy('posicion', 'ASC')
            ->orderBy('id', 'ASC')
            ->take($limit);

        $galeria = $q->get()->map(function ($g) {
            // Si getRegionContent devuelve un array ['body' => ..., 'altseo' => ...]
            // mapea a los campos que usas en Blade:
            $rc = getRegionContent($g->content_key);
            $g->textoIdioma = $rc['body']  ?? '';
            $g->altseo      = $rc['altseo'] ?? '';
            return $g;
        });

        $last = optional($galeria->last());
        $nextPos = $last?->posicion ?? 0;
        $nextId  = $last?->id ?? 0;

        $html = view('frontend.partials.galeria_items', ['galeria' => $galeria])->render();

        return response()->json([
            'html'       => $html,
            'count'      => $galeria->count(),
            'next_pos'   => $nextPos,
            'next_id'    => $nextId,
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

        // ✅ Datos del formulario
        $data = [
            'name'    => $request->name,
            'email'   => $request->email,
            'message' => $request->message,
        ];

        try {
            // ✅ Envía el correo a tu Gmail (por ejemplo)
           // Mail::to('tatangamess@gmail.com')->send(new ContactMail($data));

            return response()->json([
                'ok'      => true,
                'message' => __('meta.contact_ok'),
            ]);
        } catch (\Exception $e) {
            // ✅ En caso de error, puedes registrar el fallo
            Log::error('Error al enviar correo: ' . $e->getMessage());

            return response()->json([
                'ok'      => false,
                'message' => 'No se pudo enviar el correo. Intente más tarde.',
            ], 500);
        }
    }

    function limitarTexto($texto, $limite = 80) {
        return strlen($texto) > $limite ? substr($texto, 0, $limite) . '...' : $texto;
    }

    public function vistaProducts(){

        /*$arrayCategorias = Categoria::where('activo', 1)
            ->orderBy('posicion', 'ASC')
            ->get()
            ->map(function ($itemCategoria) {

                $arrayRegionCategoria = getRegionContent($itemCategoria->content_key);
                $itemCategoria->titulo = $arrayRegionCategoria['title'];

                // --- Productos activos dentro de la categoría ---
                $arrayProductos = Producto::where('activo', 1)
                    ->where('id_categorias', $itemCategoria->id)
                    ->orderBy('posicion', 'ASC')
                    ->get()
                    ->map(function ($itemProducto) {

                        $arrayRegionProducto = getRegionContent($itemProducto->content_key);
                        $itemProducto->titulo = $arrayRegionProducto['title'];
                        $itemProducto->descripcion = $this->limitarTexto($arrayRegionProducto['body']);

                        $itemProducto->slug = $arrayRegionProducto['slug'];
                        $itemProducto->precioFormat = '$' . number_format((float)$itemProducto->precio, 2, '.', '');

                        // --- Presentaciones activas ---
                        $arrayPresentaciones = ProductosPresentacion::where('activo', 1)
                            ->where('id_productos', $itemProducto->id)
                            ->orderBy('posicion', 'ASC')
                            ->get()
                            ->map(function ($itemPresentacion) {

                                $arrayRegionPresentacion = getRegionContent($itemPresentacion->content_key);
                                $itemPresentacion->titulo = $arrayRegionPresentacion['title'];
                                return $itemPresentacion;
                            });

                        // ✅ Solo incluir productos que tengan presentaciones
                        if ($arrayPresentaciones->isEmpty()) {
                            return null; // Se filtra más abajo
                        }

                        $itemProducto->presentaciones = $arrayPresentaciones;
                        return $itemProducto;
                    })
                    // ✅ Eliminar productos nulos (sin presentaciones)
                    ->filter();

                // ✅ Solo incluir categorías que tengan productos válidos
                if ($arrayProductos->isEmpty()) {
                    return null; // Se filtra más abajo
                }

                $itemCategoria->productos = $arrayProductos;
                return $itemCategoria;
            })
            // ✅ Eliminar categorías nulas (sin productos)
            ->filter()
            ->values(); // reindexa el array final (0,1,2,...)*/



        // Solo producto 1 de esa categoría
        $arrayProductos = Producto::where('id', 1)
            ->where('activo', 1)
            ->get()
            ->map(function ($itemProducto) {
                $rcProd = getRegionContent($itemProducto->content_key);
                $itemProducto->titulo      = $rcProd['title'] ?? '';
                $itemProducto->descripcion = $rcProd['body']  ?? '';
                $itemProducto->slug        = $rcProd['slug']  ?? '';
                $itemProducto->precioFormat = '$' . number_format((float)$itemProducto->precio, 2, '.', '');

                // Presentaciones activas
                $arrayPresentaciones = \App\Models\ProductosPresentacion::where('activo', 1)
                    ->where('id_productos', $itemProducto->id)
                    ->orderBy('posicion', 'ASC')
                    ->get()
                    ->map(function ($itemPresentacion) {
                        $rcPres = getRegionContent($itemPresentacion->content_key);
                        $itemPresentacion->titulo = $rcPres['title'] ?? '';
                        return $itemPresentacion;
                    });

                // Solo incluir si tiene presentaciones
                if ($arrayPresentaciones->isEmpty()) {
                    return null;
                }

                $itemProducto->presentaciones = $arrayPresentaciones;
                return $itemProducto;
            })
            ->filter(); // quita nulls (sin presentaciones)


        return view('frontend.pages.products', compact('arrayProductos'));
    }

}
