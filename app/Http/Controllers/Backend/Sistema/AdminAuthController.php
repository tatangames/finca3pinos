<?php

namespace App\Http\Controllers\Backend\Sistema;

use App\Http\Controllers\Controller;
use App\Models\Galeria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        return view('backend.admin.galeria.vistagaleria');
    }


    public function tablaGaleria()
    {
        $arrayGaleria = Galeria::orderBy('posicion', 'ASC')->get();

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
        if ($request->hasFile('imagen')) {

            // Nombre único
            $nombreBase  = Str::slug(Str::random(15) . '-' . microtime(true), '_');

            // Lee archivo con Intervention v3
            $manager = new ImageManager(new Driver());
            $img     = $manager->read($request->file('imagen')->getPathname());

            // (Opcional) Redimensiona si es muy grande: ancho máx 1600px manteniendo proporción
            if ($img->width() > 1600) {
                $img->scale(width: 1600); // altura se ajusta automáticamente manteniendo aspecto
            }

            // ── Opción A: guarda como JPEG optimizado ────────────────────────────
            //$encoded   = $img->encode(new JpegEncoder(quality: 80)); // 70–85 suele bien
            //$nombreOut = $nombreBase . '.jpg';

            // ── Opción B (recomendada para web): guarda como WebP ───────────────
             $encoded   = $img->encode(new WebpEncoder(quality: 82));
             $nombreOut = $nombreBase . '.webp';

            // Guarda al disco 'archivos' (config/filesystems.php)
            if(Storage::disk('archivos')->put($nombreOut, $encoded)){

                // Posición
                $nuevaPosicion = optional(\App\Models\Galeria::orderByDesc('posicion')->first())->posicion + 1 ?? 1;


                $nuevo = new Galeria();
                $nuevo->nombre = $request->nombre;
                $nuevo->imagen = $nombreOut;
                $nuevo->posicion = $nuevaPosicion;
                $nuevo->activo = 1;
                $nuevo->alt_seo = $request->altseo;
                $nuevo->save();

                return ['success' => 1];

            } else {
                // error al subir imagen
                return ['success' => 99, 'message' => 'No se pudo guardar la imagen'];
            }
        } else {
            // imagen no encontrada
            return ['success' => 99, 'message' => 'No se pudo guardar la imagen'];
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
        $regla = array(
            'id' => 'required',
        );

        $validar = Validator::make($request->all(), $regla);

        if ($validar->fails()){ return ['success' => 0];}

        if($info = Galeria::where('id', $request->id)->first()){

            $imagenOld = $info->imagen;

            if(Storage::disk('archivos')->exists($imagenOld)){
                Storage::disk('archivos')->delete($imagenOld);
            }

            Galeria::where('id', $info->id)->delete();

            // fue borrada
            return ['success' => 1];
        }else{
            // decir que fue borrado
            return ['success' => 1];
        }
    }


    public function informacionGaleria(Request $request)
    {
        $regla = array(
            'id' => 'required',
        );

        $validar = Validator::make($request->all(), $regla);

        if ($validar->fails()){ return ['success' => 0];}

        if($info = Galeria::where('id', $request->id)->first()){

            return ['success' => 1, 'info' => $info];
        }else{
            // decir que fue borrado
            return ['success' => 2];
        }
    }

    public function editarGaleria(Request $request)
    {
        $regla = array(
            'id' => 'required',
        );

        $validar = Validator::make($request->all(), $regla);

        if ($validar->fails()){ return ['success' => 0];}

        if ($request->hasFile('imagen')) {

            $infoGaleria = Galeria::where('id', $request->id)->first();
            $imagenOld = $infoGaleria->imagen;

            // Nombre único
            $nombreBase  = Str::slug(Str::random(15) . '-' . microtime(true), '_');

            // Lee archivo con Intervention v3
            $manager = new ImageManager(new Driver());
            $img     = $manager->read($request->file('imagen')->getPathname());

            // (Opcional) Redimensiona si es muy grande: ancho máx 1600px manteniendo proporción
            if ($img->width() > 1600) {
                $img->scale(width: 1600); // altura se ajusta automáticamente manteniendo aspecto
            }

            // ── Opción A: guarda como JPEG optimizado ────────────────────────────
            //$encoded   = $img->encode(new JpegEncoder(quality: 80)); // 70–85 suele bien
            //$nombreOut = $nombreBase . '.jpg';

            // ── Opción B (recomendada para web): guarda como WebP ───────────────
            $encoded   = $img->encode(new WebpEncoder(quality: 82));
            $nombreOut = $nombreBase . '.webp';

            // Guarda al disco 'archivos' (config/filesystems.php)
            if(Storage::disk('archivos')->put($nombreOut, $encoded)){

                Galeria::where('id', $request->id)
                    ->update([
                        'nombre' => $request->nombre,
                        'alt_seo' => $request->altseo,
                        'imagen' => $nombreOut,
                    ]);

                if(Storage::disk('archivos')->exists($imagenOld)){
                    Storage::disk('archivos')->delete($imagenOld);
                }

                return ['success' => 1];
            } else {
                // error al subir imagen
                return ['success' => 99];
            }
        } else {
            Galeria::where('id', $request->id)
                ->update([
                    'nombre' => $request->nombre,
                    'alt_seo' => $request->altseo
                ]);

            return ['success' => 1];
        }
    }










}
