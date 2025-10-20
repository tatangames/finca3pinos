<?php

namespace App\Http\Controllers\Backend\Sistema;

use App\Http\Controllers\Controller;
use App\Models\Categoria;
use App\Models\Producto;
use App\Models\ProductosPresentacion;
use App\Models\Region;
use App\Models\RegionContent;
use App\Models\RegionContentTranslation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\Encoders\WebpEncoder;
use Intervention\Image\ImageManager;

class CategoriasController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function indexCategorias()
    {
        $arrayRegiones = Region::where('slug', '!=', 'latin-es')
            ->orderBy('id')
            ->get();

        return view('backend.admin.categorias.vistacategorias', compact('arrayRegiones'));
    }


    public function tablaCategorias()
    {
        $arrayCategoria = Categoria::orderBy('posicion', 'ASC')->get();

        foreach ($arrayCategoria as $dato) {
            $nombreSV = "";
            if($infoRegion = RegionContent::where('key', $dato->content_key)
                ->where('region_id', 1)->first()){
                if($infoIdioma = RegionContentTranslation::where('content_id', $infoRegion->id)->first()){
                    $nombreSV = $infoIdioma->title;
                }
            }
            $dato->nombreSV = $nombreSV;
        }
        return view('backend.admin.categorias.tablacategorias', compact('arrayCategoria'));
    }


    public function actualizarPosicionCategorias(Request $request)
    {
        $tasks = Categoria::all();

        foreach ($tasks as $task) {
            $id = $task->id;

            foreach ($request->order as $order) {
                if ($order['id'] == $id) {
                    $task->update(['posicion' => $order['posicion']]);
                }
            }
        }
        return ['success' => 1];
    }


    public function nuevaCategoria(Request $request)
    {
        $regla = array(
            'key' => 'required',
        );

        $validar = Validator::make($request->all(), $regla);

        if ($validar->fails()){ return ['success' => 0];}

        DB::beginTransaction();

        try {
            $keySinEspacios = trim($request->key);

            // EVITAR KEY REPETIDAS
            if(RegionContent::where('key', $keySinEspacios)->first()){
                return ['success' => 1];
            }

            $nuevaPosicion = optional(Categoria::orderByDesc('posicion')->first())->posicion + 1 ?? 1;

            $gal = new Categoria();
            $gal->posicion    = $nuevaPosicion;
            $gal->activo      = 1;
            $gal->content_key = $keySinEspacios;
            $gal->save();

            $translations = $request->input('translations', []);
            $trES = $translations['es'] ?? null;       // 👈 base para ES

            // Trae regiones y crea RegionContent por cada una
            $regiones = Region::select('id','slug','locale')->get();

            foreach ($regiones as $region) {
                // Crea/obtiene el contenedor por region_id + key
                $content = RegionContent::firstOrCreate(
                    ['region_id' => $region->id, 'key' => $keySinEspacios],
                    []
                );

                // Traducción a aplicar para esta región:
                // - Si la región es 'sv' (El Salvador) o 'latin-es', usa SIEMPRE la misma (ES)
                // - Para otras, usa lo que venga por su locale.
                if ($region->slug === 'sv' || $region->slug === 'latin-es') {
                    if ($trES) {
                        RegionContentTranslation::updateOrCreate(
                            ['content_id' => $content->id, 'locale' => 'es'],
                            ['title' => $trES['title'] ?? '']
                        );
                    }
                } else {
                    $tr = $translations[$region->locale] ?? null;
                    if ($tr) {
                        RegionContentTranslation::updateOrCreate(
                            ['content_id' => $content->id, 'locale' => $region->locale],
                            ['title' => $tr['title'] ?? '']
                        );
                    }
                }
            }

            DB::commit();
            return ['success' => 1];

        } catch (\Throwable $e) {
            DB::rollBack();
            report($e);
            return ['success' => 0, 'message' => 'Excepción al guardar'];
        }
    }



    public function desactivarCategoria(Request $request)
    {
        $regla = array(
            'id' => 'required',
        );

        $validar = Validator::make($request->all(), $regla);

        if ($validar->fails()){ return ['success' => 0];}

        Categoria::where('id', $request->id)
            ->update([
                'activo' => 0,
            ]);

        return ['success' => 1];
    }


    public function activarCategoria(Request $request)
    {
        $regla = array(
            'id' => 'required',
        );

        $validar = Validator::make($request->all(), $regla);

        if ($validar->fails()){ return ['success' => 0];}

        Categoria::where('id', $request->id)
            ->update([
                'activo' => 1,
            ]);

        return ['success' => 1];
    }

    public function informacionCategoria(Request $request)
    {
        $regla = ['id' => 'required|exists:categorias,id'];
        $validar = Validator::make($request->all(), $regla);
        if ($validar->fails()) { return ['success' => 0]; }

        $galeria = Categoria::find($request->id);
        if (!$galeria) { return ['success' => 2]; }

        // ⚠️ Excluye latinoamérica (latin-es)
        $regiones = Region::select('id','name','locale','slug')
            ->where('slug','!=','latin-es')
            ->orderBy('id')
            ->get();

        $traducciones = collect();
        if ($galeria->content_key) {
            $traducciones = DB::table('region_contents as rc')
                ->join('region_content_translation as rct', 'rct.content_id', '=', 'rc.id')
                ->join('regions as r', 'r.id', '=', 'rc.region_id')
                ->where('rc.key', $galeria->content_key)
                ->select(
                    'r.id as region_id',
                    'r.name as region_name',
                    'r.locale as region_locale',
                    'rct.title'
                )
                ->get()
                ->keyBy('region_locale');
        }

        $langs = $regiones->map(function ($region) use ($traducciones) {
            $data = $traducciones->get($region->locale);
            return [
                'region_id' => $region->id,
                'name'      => $region->name,
                'locale'    => $region->locale,
                'title'      => $data->title ?? '',
            ];
        })->values();

        return [
            'success' => 1,
            'info' => [
                'id'          => $galeria->id,
                'content_key' => $galeria->content_key,
            ],
            'langs' => $langs,
        ];
    }


    public function editarCategoria(Request $request)
    {

        // 1) Validación
        $reglas = [
            'id'        => 'required',
        ];
        $validar = Validator::make($request->all(), $reglas);
        if ($validar->fails()) {
            return response()->json([
                'success' => 0,
                'errors'  => $validar->errors()
            ], 422);
        }

        $categoria = Categoria::find($request->id);
        if (!$categoria) {
            return ['success' => 2]; // borrada/no existe
        }

        DB::beginTransaction();
        try {

            // 4) Garantiza content_key
            if (empty($categoria->content_key)) {
                $categoria->content_key = $categoria->id;
            }

            $categoria->save();

            // 5) Upsert de contenidos por región y traducciones por locale
            $titles = $request->input('title', []);   // ej: ['es' => '...', 'en'=>'...']

            // Para cada región del sistema, garantizamos region_contents (region_id + key)
            $regiones = Region::select('id', 'locale', 'name')->get();

            foreach ($regiones as $region) {
                // Crea/obtiene el row base por región+key
                $content = RegionContent::firstOrCreate(
                    ['region_id' => $region->id, 'key' => $categoria->content_key],
                    [] // no hay más columnas en region_contents
                );

                // Usamos el locale de la región para tomar los campos del request
                $loc  = $region->locale; // p.ej 'es', 'en', 'ko'...
                $tit  = $titles[$loc] ?? null;

                // Si no hay nada para este locale, puedes saltar o limpiar. Aquí upsert si hay algo.
                if ($tit !== null) {
                    RegionContentTranslation::updateOrCreate(
                        ['content_id' => $content->id, 'locale' => $loc],
                        ['title' => $tit]
                    );
                }
            }

            DB::commit();
            return ['success' => 1];
        } catch (\Throwable $e) {
            DB::rollBack();
            // \Log::error($e->getMessage());
            return ['success' => 0, 'message' => 'Error al actualizar'];
        }
    }







    // ================ PRODUCTOS  ===================================

    public function indexProductos($idcategoria)
    {
        $arrayRegiones = Region::where('slug', '!=', 'latin-es')
            ->orderBy('id')
            ->get();

        return view('backend.admin.categorias.productos.vistaproductos', compact('arrayRegiones', 'idcategoria'));
    }


    public function tablaProductos($idcategoria)
    {
        $arrayProducto = Producto::where('id_categorias', $idcategoria)
            ->orderBy('posicion', 'ASC')
            ->get();

        foreach ($arrayProducto as $dato) {
            $nombreSV = "";
            if($infoRegion = RegionContent::where('key', $dato->content_key)
                ->where('region_id', 1)->first()){
                if($infoIdioma = RegionContentTranslation::where('content_id', $infoRegion->id)->first()){
                    $nombreSV = $infoIdioma->title;
                }
            }
            $dato->nombreSV = $nombreSV;
        }
        return view('backend.admin.categorias.productos.tablaproductos', compact('arrayProducto'));
    }




    public function nuevoProducto(Request $request)
    {
        DB::beginTransaction();
        try {
            $keySinEspacios = trim($request->key);

            // EVITAR KEY REPETIDAS
            if(RegionContent::where('key', $keySinEspacios)->first()){
                return ['success' => 1];
            }

            // ===== 1) Imagen =====
            $nombreBase  = Str::slug(Str::random(15) . '-' . microtime(true), '_');
            $manager = new ImageManager(new Driver());
            $img     = $manager->read($request->file('imagen')->getPathname());
            if ($img->width() > 1200) { $img->scale(width: 1200); }

            $encoded   = $img->encode(new WebpEncoder(quality: 82));
            $nombreOut = $nombreBase . '.webp';
            if (!Storage::disk('archivos')->put($nombreOut, $encoded)) {
                return ['success' => 99, 'message' => 'No se pudo guardar la imagen'];
            }

            // ===== 2) Galería =====
            $nuevaPosicion = optional(Producto::orderByDesc('posicion')->first())->posicion + 1 ?? 1;

            $gal = new Producto();
            $gal->id_categorias = $request->idcategoria;
            $gal->imagen      = $nombreOut;
            $gal->posicion    = $nuevaPosicion;
            $gal->activo      = 0; // CREAR LA PRESENTACION
            $gal->content_key = $keySinEspacios;     // ← única
            $gal->save();

            // ===== 3) i18n =====
            // Espera formato: translations[<locale>][title|body]
            $translations = $request->input('translations', []);
            $trES = $translations['es'] ?? null;       // 👈 base para ES

            // Trae regiones y crea RegionContent por cada una
            $regiones = Region::select('id','slug','locale')->get();

            foreach ($regiones as $region) {
                // Crea/obtiene el contenedor por region_id + key
                $content = RegionContent::firstOrCreate(
                    ['region_id' => $region->id, 'key' => $keySinEspacios],
                    []
                );

                // Decide el slug que vas a usar en ESTA región:
                $slugFuente = ($region->slug === 'sv' || $region->slug === 'latin-es')
                    ? ($trES['slug'] ?? '')
                    : (($translations[$region->locale]['slug'] ?? '') ?: ($trES['slug'] ?? ''));

                $slugVerificar = Str::slug(trim($slugFuente), '-');

                // 1) Validar duplicado por REGIÓN, excluyendo el propio content_id
                $duplicado = RegionContentTranslation::where('slug', $slugVerificar)
                    ->where('content_id', '!=', $content->id)              // no me compares contra mí
                    ->whereHas('content', function ($q) use ($region) {    // solo en esta región
                        $q->where('region_id', $region->id);
                    })
                    ->exists();

                if ($duplicado) {
                    return [
                        'success' => 1,
                        'message' => "El slug '{$slugVerificar}' ya existe en la región '{$region->slug}'.",
                    ];
                }

                // 2) Guardar/actualizar la traducción
                if ($region->slug === 'sv' || $region->slug === 'latin-es') {
                    if ($trES) {
                        RegionContentTranslation::updateOrCreate(
                            ['content_id' => $content->id, 'locale' => 'es'],
                            [
                                'title' => $trES['title'] ?? '',
                                'body'  => $trES['body']  ?? '',
                                'slug'  => $slugVerificar,
                            ]
                        );
                    }
                } else {
                    $tr = $translations[$region->locale] ?? null;
                    if ($tr) {
                        RegionContentTranslation::updateOrCreate(
                            ['content_id' => $content->id, 'locale' => $region->locale],
                            [
                                'title' => $tr['title'] ?? '',
                                'body'  => $tr['body']  ?? '',
                                'slug'  => $slugVerificar,
                            ]
                        );
                    }
                }
            }

            DB::commit();
            return ['success' => 2];

        } catch (\Throwable $e) {
            DB::rollBack();
            report($e);
            return ['success' => 0, 'message' => 'Excepción al guardar'];
        }
    }


    public function actualizarPosicionProducto(Request $request)
    {
        $tasks = Producto::all();

        foreach ($tasks as $task) {
            $id = $task->id;

            foreach ($request->order as $order) {
                if ($order['id'] == $id) {
                    $task->update(['posicion' => $order['posicion']]);
                }
            }
        }
        return ['success' => 1];
    }

    public function desactivarProducto(Request $request)
    {
        $regla = array(
            'id' => 'required',
        );

        $validar = Validator::make($request->all(), $regla);

        if ($validar->fails()){ return ['success' => 0];}

        Producto::where('id', $request->id)
            ->update([
                'activo' => 0,
            ]);

        return ['success' => 1];
    }


    public function activarProducto(Request $request)
    {
        $regla = array(
            'id' => 'required',
        );

        $validar = Validator::make($request->all(), $regla);

        if ($validar->fails()){ return ['success' => 0];}

        if(ProductosPresentacion::where('id_productos', $request->id)->first()){
            Producto::where('id', $request->id)
                ->update([
                    'activo' => 1,
                ]);

            return ['success' => 1];
        }else{
            return ['success' => 2];
        }
    }


    public function informacionProducto(Request $request)
    {

        $producto = Producto::find($request->id);
        if (!$producto) { return ['success' => 2]; }

        // ⚠️ Excluye latinoamérica (latin-es)
        $regiones = Region::select('id','name','locale','slug')
            ->where('slug','!=','latin-es')
            ->orderBy('id')
            ->get();

        $traducciones = collect();
        if ($producto->content_key) {
            $traducciones = DB::table('region_contents as rc')
                ->join('region_content_translation as rct', 'rct.content_id', '=', 'rc.id')
                ->join('regions as r', 'r.id', '=', 'rc.region_id')
                ->where('rc.key', $producto->content_key)
                ->select(
                    'r.id as region_id',
                    'r.name as region_name',
                    'r.locale as region_locale',
                    'rct.title',
                    'rct.body',
                    'rct.slug',
                )
                ->get()
                ->keyBy('region_locale');
        }

        $langs = $regiones->map(function ($region) use ($traducciones) {
            $data = $traducciones->get($region->locale);
            return [
                'region_id' => $region->id,
                'name'      => $region->name,
                'locale'    => $region->locale,
                'title'      => $data->title ?? '',
                'body'      => $data->body ?? '',
                'slug'      => $data->slug ?? '',
            ];
        })->values();

        return [
            'success' => 1,
            'info' => [
                'id'          => $producto->id,
                'content_key' => $producto->content_key,
            ],
            'langs' => $langs,
        ];
    }


    public function editarProducto(Request $request)
    {
        $regla = array(
            'id' => 'required'
        );

        $validar = Validator::make($request->all(), $regla);

        if ($validar->fails()){ return ['success' => 0];}

        $producto = Producto::find($request->id);
        if (!$producto) {
            return ['success' => 2]; // borrada/no existe
        }

        DB::beginTransaction();
        try {

            // VERIFICAR QUE SLUG NO ESTE REPETIDO PRIMERO

            $bodies = $request->input('body',  []);   // ej: ['es' => '...', 'en'=>'...']
            $title = $request->input('title', []);   // ej: ['es' => '...', 'en'=>'...']
            $slug = $request->input('slug', []);   // ej: ['es' => '...', 'en'=>'...']


            // Para cada región del sistema, garantizamos region_contents (region_id + key)
            $regiones = Region::select('id', 'locale', 'name')->get();

            foreach ($regiones as $region) {
                // 1) Asegurar RegionContent por región + key
                $content = RegionContent::firstOrCreate(
                    ['region_id' => $region->id, 'key' => $producto->content_key],
                    []
                );
                $loc = $region->locale; // 'es', 'en', 'ko', etc.
                $slu = $slug[$loc] ?? null;

                $slugVerificar = Str::slug(trim($slu), '-');

                // 1) Validar duplicado por REGIÓN, excluyendo el propio content_id
                $duplicado = RegionContentTranslation::where('slug', $slugVerificar)
                    ->where('content_id', '!=', $content->id)              // no me compares contra mí
                    ->whereHas('content', function ($q) use ($region) {    // solo en esta región
                        $q->where('region_id', $region->id);
                    })
                    ->exists();

                if ($duplicado) {
                    return [
                        'success' => 10,
                        'message' => "El slug '{$slugVerificar}' ya existe en la región '{$region->slug}'.",
                    ];
                }
            }

            // YA VERIFICADO SLUG, PROCEDER A GUARDAR

            // Imagen opcional → WebP
            if ($request->hasFile('imagen')) {
                $old = $producto->imagen;

                $manager = new ImageManager(new Driver());
                $img     = $manager->read($request->file('imagen')->getPathname());

                if ($img->width() > 1200) {
                    $img->scale(width: 1200);
                }

                $nombreBase = Str::slug(Str::random(15) . '-' . microtime(true), '_');
                $nombreOut  = $nombreBase . '.webp';
                $encoded    = $img->encode(new WebpEncoder(quality: 82));

                if (!Storage::disk('archivos')->put($nombreOut, $encoded)) {
                    DB::rollBack();
                    return ['success' => 99, 'message' => 'No se pudo guardar la imagen'];
                }

                $producto->imagen = $nombreOut;

                if ($old && Storage::disk('archivos')->exists($old)) {
                    Storage::disk('archivos')->delete($old);
                }
            }

            // 4) Garantiza content_key
            if (empty($producto->content_key)) {
                $producto->content_key = $producto->id;
            }

            $producto->save();


            foreach ($regiones as $region) {
                // 1) Asegurar RegionContent por región + key
                $content = RegionContent::firstOrCreate(
                    ['region_id' => $region->id, 'key' => $producto->content_key],
                    []
                );

                $loc = $region->locale; // 'es', 'en', 'ko', etc.
                $tit = $title[$loc] ?? null;
                $bod = $bodies[$loc] ?? null;
                $slu = $slug[$loc] ?? null;

                // 4) (Opcional) Evitar guardar slug vacío; si querés forzarlo, corta aquí
                if ($tit !== null && trim($tit) === '') {
                    DB::rollBack();
                    return [
                        'success' => 4,
                        'message' => "El título no puede estar vacío para el idioma {$loc}.",
                        'locale'  => $loc,
                    ];
                }

                if ($slu !== null && trim($slu) === '') {
                    DB::rollBack();
                    return [
                        'success' => 4,
                        'message' => "El slug no puede estar vacío para el idioma {$loc}.",
                        'locale'  => $loc,
                    ];
                }

                // 5) Upsert final (usa slug normalizado)
                RegionContentTranslation::updateOrCreate(
                    ['content_id' => $content->id, 'locale' => $loc],
                    [
                        'title' => $tit ?? ($trActual->title ?? ''),
                        'body'  => $bod ?? ($trActual->body ?? ''),
                        'slug'  => $slu ?? ($trActual->slug ?? null),
                    ]
                );
            }

            DB::commit();
            return ['success' => 1];
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            return ['success' => 0, 'message' => 'Error al actualizar'];
        }
    }





    // ========= PRESENTACION DE PRODUCTO =======

    public function indexProductosPresentacion($idproducto)
    {
        return view('backend.admin.categorias.productos.presentacion.vistapresentacion');
    }



    public function tablaProductosPresentacion($idproducto)
    {
        return view('backend.admin.categorias.productos.presentacion.tablapresentacion');
    }



}
