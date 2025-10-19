<?php

namespace App\Http\Controllers\Backend\Sistema;

use App\Http\Controllers\Controller;
use App\Models\Galeria;
use App\Models\Region;
use App\Models\RegionContent;
use App\Models\RegionContentTranslation;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\Encoders\JpegEncoder;
use Intervention\Image\Encoders\WebpEncoder;
use Intervention\Image\ImageManager;

class AdminAuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin')->except([
            'showLoginFormAdmin',
            'showPasswordReset',
            'showResetForm',
            'linkInvalid'
        ]);
    }

    public function showLoginFormAdmin()
    {
        if (Auth::guard('admin')->check()) {
            return redirect()->route('admin.panel');
        }

        return view('backend.login.vistaloginadmin');
    }


    public function logoutAdmin(Request $request){
        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }

    public function showPasswordReset()
    {
        return view('backend.login.vistaingresarcorreo');
    }

    public function showResetForm($token)
    {
        $email  = request('email');
        $broker = Password::broker('admin'); // <-- tu broker para admins
        $user   = $broker->getUser(['email' => $email]);

        $tokenIsValid = $user && (
            method_exists($broker, 'tokenExists')
                ? $broker->tokenExists($user, $token)
                : $broker->getRepository()->exists($user, $token)
            );

        if (!$tokenIsValid) {
            return redirect()
                ->route('admin.password.invalid') // o la ruta de tu "Olvidé mi contraseña"
                ->with('error', 'El enlace para restablecer contraseña ha expirado o no es válido.');
        }

        return view('backend.login.vistareseteopassword', compact('token', 'email'));
    }

    public function linkInvalid()
    {
        return view('backend.login.vistaenlaceinvalido');
    }



    // ==== GALERIA ====

    public function indexGaleria()
    {
        $arrayRegiones = Region::where('slug', '!=', 'latin-es')
            ->orderBy('id')
            ->get();

        return view('backend.admin.galeria.vistagaleria', compact('arrayRegiones'));
    }


    public function tablaGaleria()
    {
        $arrayGaleria = Galeria::orderBy('posicion', 'ASC')->get();

        foreach ($arrayGaleria as $dato) {
            $nombreSV = "";
            if($infoRegion = RegionContent::where('key', $dato->content_key)
                ->where('region_id', 1)->first()){
                if($infoIdioma = RegionContentTranslation::where('content_id', $infoRegion->id)->first()){
                    $nombreSV = $infoIdioma->body;
                }
            }
            $dato->nombreSV = $nombreSV;
        }

        return view('backend.admin.galeria.tablagaleria', compact('arrayGaleria'));
    }

    public function actualizarPosicionGaleria(Request $request)
    {
        $tasks = Galeria::all();

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

    public function nuevaGaleria(Request $request)
    {
        $regla = array(
            'key' => 'required',
        );

        // imagen, altseo

        $validar = Validator::make($request->all(), $regla);

        if ($validar->fails()){ return ['success' => 0];}

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
            $nuevaPosicion = optional(Galeria::orderByDesc('posicion')->first())->posicion + 1 ?? 1;

            $gal = new Galeria();
            $gal->imagen      = $nombreOut;
            $gal->posicion    = $nuevaPosicion;
            $gal->activo      = 1;
            $gal->alt_seo = $request->altseo;
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

                // Traducción a aplicar para esta región:
                // - Si la región es 'sv' (El Salvador) o 'latin-es', usa SIEMPRE la misma (ES)
                // - Para otras, usa lo que venga por su locale.
                if ($region->slug === 'sv' || $region->slug === 'latin-es') {
                    if ($trES) {
                        RegionContentTranslation::updateOrCreate(
                            ['content_id' => $content->id, 'locale' => 'es'],
                            ['body' => $trES['body'] ?? '']
                        );
                    }
                } else {
                    $tr = $translations[$region->locale] ?? null;
                    if ($tr) {
                        RegionContentTranslation::updateOrCreate(
                            ['content_id' => $content->id, 'locale' => $region->locale],
                            ['body' => $tr['body'] ?? '']
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

    public function desactivarGaleria(Request $request)
    {
        $regla = array(
            'id' => 'required',
        );

        $validar = Validator::make($request->all(), $regla);

        if ($validar->fails()){ return ['success' => 0];}

        Galeria::where('id', $request->id)
            ->update([
                'activo' => 0,
            ]);

        return ['success' => 1];
    }

    public function activarGaleria(Request $request)
    {
        $regla = array(
            'id' => 'required',
        );

        $validar = Validator::make($request->all(), $regla);

        if ($validar->fails()){ return ['success' => 0];}

        Galeria::where('id', $request->id)
            ->update([
                'activo' => 1,
            ]);

        return ['success' => 1];
    }

    public function borrarGaleria(Request $request)
    {
        $regla = ['id' => 'required|exists:galerias,id'];
        $validar = Validator::make($request->all(), $regla);
        if ($validar->fails()) {
            return ['success' => 0];
        }

        DB::beginTransaction();
        try {
            $galeria = Galeria::find($request->id);
            if (!$galeria) {
                return ['success' => 1]; // ya borrada
            }

            $imagenOld = $galeria->imagen;
            $contentKey = $galeria->content_key;

            // 🧹 1) Eliminar imagen del disco
            if ($imagenOld && Storage::disk('archivos')->exists($imagenOld)) {
                Storage::disk('archivos')->delete($imagenOld);
            }

            // 🧹 2) Eliminar traducciones i18n asociadas
            if ($contentKey) {
                // Buscar todos los region_contents con esa key
                $contents = RegionContent::where('key', $contentKey)->get();

                foreach ($contents as $c) {
                    // Borrar sus traducciones
                    RegionContentTranslation::where('content_id', $c->id)->delete();
                    // Borrar el contenido base
                    $c->delete();
                }
            }

            // 🧹 3) Borrar galería
            $galeria->delete();

            DB::commit();
            return ['success' => 1];
        } catch (\Throwable $e) {
            DB::rollBack();
            // \Log::error($e->getMessage());
            return ['success' => 0, 'message' => 'Error al eliminar'];
        }
    }


    public function informacionGaleria(Request $request)
    {
        $regla = ['id' => 'required|exists:galerias,id'];
        $validar = Validator::make($request->all(), $regla);
        if ($validar->fails()) { return ['success' => 0]; }

        $galeria = Galeria::find($request->id);
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
                    'rct.body'
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
                'body'      => $data->body ?? '',
            ];
        })->values();

        return [
            'success' => 1,
            'info' => [
                'id'          => $galeria->id,
                'imagen'      => $galeria->imagen,
                'alt_seo'     => $galeria->alt_seo,
                'content_key' => $galeria->content_key,
            ],
            'langs' => $langs,
        ];
    }


    public function editarGaleria(Request $request)
    {
        // 1) Validación
        $reglas = [
            'id'        => 'require',
        ];

        $validar = Validator::make($request->all(), $reglas);

        if ($validar->fails()) {
            return response()->json([
                'success' => 0,
                'errors'  => $validar->errors()
            ], 422);
        }

        $galeria = Galeria::find($request->id);
        if (!$galeria) {
            return ['success' => 2]; // borrada/no existe
        }

        DB::beginTransaction();
        try {
            // 2) Actualiza ALT SEO
            $galeria->alt_seo = $request->input('alt_seo');

            // 3) Imagen opcional → WebP
            if ($request->hasFile('imagen')) {
                $old = $galeria->imagen;

                $manager = new ImageManager(new Driver());
                $img     = $manager->read($request->file('imagen')->getPathname());

                if ($img->width() > 1600) {
                    $img->scale(width: 1600);
                }

                $nombreBase = Str::slug(Str::random(15) . '-' . microtime(true), '_');
                $nombreOut  = $nombreBase . '.webp';
                $encoded    = $img->encode(new WebpEncoder(quality: 82));

                if (!Storage::disk('archivos')->put($nombreOut, $encoded)) {
                    DB::rollBack();
                    return ['success' => 99, 'message' => 'No se pudo guardar la imagen'];
                }

                $galeria->imagen = $nombreOut;

                if ($old && Storage::disk('archivos')->exists($old)) {
                    Storage::disk('archivos')->delete($old);
                }
            }

            // 4) Garantiza content_key
            if (empty($galeria->content_key)) {
                $galeria->content_key = $galeria->id;
            }

            $galeria->save();

            // 5) Upsert de contenidos por región y traducciones por locale
            $bodies = $request->input('body',  []);   // ej: ['es' => '...', 'en'=>'...']

            // Para cada región del sistema, garantizamos region_contents (region_id + key)
            $regiones = Region::select('id', 'locale', 'name')->get();

            foreach ($regiones as $region) {
                // Crea/obtiene el row base por región+key
                $content = RegionContent::firstOrCreate(
                    ['region_id' => $region->id, 'key' => $galeria->content_key],
                    [] // no hay más columnas en region_contents
                );

                // Usamos el locale de la región para tomar los campos del request
                $loc  = $region->locale; // p.ej 'es', 'en', 'ko'...
                $bod  = $bodies[$loc] ?? null;

                // Si no hay nada para este locale, puedes saltar o limpiar. Aquí upsert si hay algo.
                if ($bod !== null) {
                    RegionContentTranslation::updateOrCreate(
                        ['content_id' => $content->id, 'locale' => $loc],
                        ['body' => $bod]
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





    // === NUEVOS IDIOMAS ====

    public function indexIdiomas(Request $request, $regionParam = null)
    {
        $regionBaseSlug = 'sv';

        $regiones = Region::orderBy('name')->get();

        $regionNuevaId = $regionParam ?? $request->integer('region_id');
        if (!$regionNuevaId) {
            $regionNuevaId = optional($regiones->firstWhere('slug', '!=', $regionBaseSlug))->id;
        }

        $regionBase  = Region::where('slug', $regionBaseSlug)->firstOrFail();
        $regionNueva = Region::findOrFail($regionNuevaId);
        $nuevoLocale = $regionNueva->locale;

        // asegurar keys espejo en la región destino
        $keysBase = RegionContent::where('region_id', $regionBase->id)->pluck('key')->unique();
        foreach ($keysBase as $k) {
            RegionContent::firstOrCreate([
                'region_id' => $regionNueva->id,
                'key'       => $k,
            ]);
        }

        // armar faltantes
        $faltantes = [];
        foreach ($keysBase as $key) {
            $contentSVId = RegionContent::where('region_id', $regionBase->id)
                ->where('key', $key)->value('id');

            $svTexto = RegionContentTranslation::where('content_id', $contentSVId)
                ->where('locale', 'es')->value('body');

            $contentDest = RegionContent::where('region_id', $regionNueva->id)
                ->where('key', $key)->first();

            $existe = RegionContentTranslation::where('content_id', $contentDest->id)
                ->where('locale', $nuevoLocale)->exists();

            if (!$existe) {
                $faltantes[] = [
                    'key'               => $key,
                    'sv_body'           => $svTexto ?? '',
                    'target_content_id' => $contentDest->id,
                ];
            }
        }

        return view('backend.admin.idiomas.vistanuevoidioma', [
            'idiomaReferencia'  => $regionBaseSlug,
            'regionNueva'       => $regionNueva,
            'nuevoLocale'       => $nuevoLocale,
            'faltantes'         => $faltantes,
            'regiones'          => $regiones,
            'regionSeleccionada'=> $regionNuevaId,
        ]);
    }



    public function guardarIdiomas(Request $request)
    {
        // Estructura esperada: traduccion[content_id][body|title|locale]
        $reglas = [
            'traduccion'            => 'required|array',
            'traduccion.*.body'     => 'nullable|string',
            'traduccion.*.title'    => 'nullable|string|max:255',
            'traduccion.*.locale'   => 'nullable|string|size:2', // ej. 'pt'
            'locale'                => 'nullable|string|size:2', // fallback global opcional
        ];

        $val = \Validator::make($request->all(), $reglas);
        if ($val->fails()) {
            return ['success' => 0, 'msg' => 'Datos inválidos', 'errors' => $val->errors()];
        }

        $items        = $request->input('traduccion', []);
        $fallbackLoc  = $request->input('locale', 'pt'); // si no viene por-item, usa 'pt'

        DB::beginTransaction();
        try {
            $creados = 0;
            $actualizados = 0;
            $omitidos = 0;
            $invalidos = 0;

            foreach ($items as $contentId => $vals) {
                // Validar que el content exista
                $content = RegionContent::find($contentId);
                if (! $content) {
                    $invalidos++;
                    continue;
                }

                $locale = $vals['locale'] ?? $fallbackLoc;
                $title  = array_key_exists('title', $vals) ? trim((string)$vals['title']) : null;
                $body   = array_key_exists('body',  $vals) ? (string)$vals['body'] : null;

                // Si no enviaron nada (title ni body), omitir
                if (($title === null || $title === '') && ($body === null || $body === '')) {
                    $omitidos++;
                    continue;
                }

                // Crear/actualizar
                $existing = RegionContentTranslation::where('content_id', $contentId)
                    ->where('locale', $locale)
                    ->first();

                if ($existing) {
                    // Solo sobreescribe campos enviados (permite actualizaciones parciales)
                    if ($title !== null) $existing->title = $title;
                    if ($body  !== null) $existing->body  = $body;
                    $existing->save();
                    $actualizados++;
                } else {
                    RegionContentTranslation::create([
                        'content_id' => $contentId,
                        'locale'     => $locale,
                        'title'      => $title ?? '',
                        'body'       => $body  ?? '',
                    ]);
                    $creados++;
                }
            }

            DB::commit();

            return [
                'success'      => 1,
                'msg'          => 'Traducciones guardadas',
                'creados'      => $creados,
                'actualizados' => $actualizados,
                'omitidos'     => $omitidos,
                'invalidos'    => $invalidos,
            ];
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Error guardando traducciones: '.$e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return ['success' => 99, 'msg' => 'Error interno al guardar traducciones'];
        }
    }










}
