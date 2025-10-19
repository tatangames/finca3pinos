<?php

namespace App\Http\Controllers\Backend\Sistema;

use App\Http\Controllers\Controller;
use App\Models\Categoria;
use App\Models\Producto;
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











}
