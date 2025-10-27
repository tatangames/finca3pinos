<?php

namespace App\Http\Controllers\Frontend\Sistema;

use App\Http\Controllers\Controller;
use App\Mail\PasswordResetMail;
use App\Models\Usuario;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;


class UsuarioAuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:web')->except(['showLoginFormUsuario', 'loginUsuario', 'showIngresarCorreoForm',
        'solicitarCodigoCorreo', 'showResetPasswordForm', 'showtokenInvalid', 'registroCliente']);
    }

    public function showLoginFormUsuario()
    {
        if (Auth::guard('web')->check()) {
            return redirect()->route('user.index');
        }

        return view('frontend.login.vistalogin');
    }

    public function loginUsuario(Request $request){

        $rules = [
            'email'    => ['required'],
            'password'   => ['required'],
        ];

        $attributes = [
            'email'   => __('meta.contact_v12'), // el correo es requerido
            'password' => __('meta.contact_v14'), // la contraseña es requerida
        ];

        $validator = Validator::make($request->all(), $rules, [], $attributes);

        if ($validator->fails()) {
            return response()->json([
                'success' => 0,
                'message' => __('meta.unknown_error'),
            ]);
        }

        $credentials = $request->only('email', 'password');

        // Guard admin (usa el provider 'admin' del auth.php)
        if (Auth::guard('web')->attempt($credentials)) {

            // Regenera la sesión por seguridad
            $request->session()->regenerate();

            // Puedes redirigir o devolver JSON
            return response()->json([
                'success' => 1,
                'ruta' => route('user.index'),
                'admin' => Auth::guard('web')->user(),
            ]);
        }

        return ['success' => 2, 'message' => __('meta.incorrect_data')];
    }

    public function logoutUsuario(Request $request){
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('user.index');
    }


    public function showIngresarCorreoForm()
    {
        return view('frontend.login.vistaingresarcorreo');
    }

    public function solicitarCodigoCorreo(Request $request)
    {
        $rules = [
            'email' => ['required', 'email:rfc,dns'],
        ];
        $attributes = [
            'email' => __('meta.contact_v12'),
        ];

        $validator = Validator::make($request->all(), $rules, [], $attributes);

        if ($validator->fails()) {
            return response()->json([
                'success' => 0,
                'message' => __('meta.unknown_error'),
            ]);
        }

        // 2) (Opcional) Rate limit por IP + email para evitar abuso
        $key = 'pwd-reset:' . Str::lower($request->input('email')) . '|' . $request->ip();
        if (RateLimiter::tooManyAttempts($key, 25)) {
            $seconds = RateLimiter::availableIn($key);
            return response()->json([
                'success' => 1, // muchos intentos
                'message' => __('meta.too_many_attempts', ['seconds' => $seconds]), // crea esta key si quieres
            ]);
        }

        // 🔍 Buscar el usuario
        $user = Usuario::where('email', $request->email)->first();

        if (! $user) {
            RateLimiter::hit($key, 60);
            return response()->json([
                'success' => 2, // correo no encontrado
                'message' => __('meta.email_not_found'),
            ]);
        }

        // 🧩 Crear token de recuperación
        $token = Password::broker('users')->createToken($user);

        // 🔗 Generar URL personalizada de reseteo
        $resetUrl = route('user.password.reset.form', ['token' => $token, 'email' => $user->email]);

        // ✉️ Enviar correo personalizado
        Mail::to($user->email)->send(new PasswordResetMail($user, $resetUrl));

        return response()->json([
            'success' => 3,
            'message' => __('meta.reset_link_sent'), // enviado
        ]);
    }


    public function showResetPasswordForm(Request $request, $token)
    {

        $email  = request('email');
        $broker = Password::broker('users'); // <-- tu broker para admins
        $user   = $broker->getUser(['email' => $email]);

        $tokenIsValid = $user && (
            method_exists($broker, 'tokenExists')
                ? $broker->tokenExists($user, $token)
                : $broker->getRepository()->exists($user, $token)
            );

        if (!$tokenIsValid) {
            return redirect()
                ->route('user.token.novalid');
        }

        return view('frontend.login.vistaresetpassword', compact('token', 'email'));
    }


    public function showtokenInvalid()
    {
        return view('frontend.login.vistatokennovalido');
    }

    public function registroCliente(Request $request)
    {
        $regla = array(
            'name' => 'required',
            'email' => 'required',
            'password' => 'required',
        );

        $validar = Validator::make($request->all(), $regla);

        if ($validar->fails()){ return ['success' => 0];}

        // 2) Normalizar datos
        $name  = trim($request->input('name'));
        $email = trim(mb_strtolower($request->input('email')));
        $pass  = $request->input('password');

        // 3) Crear usuario + login dentro de transacción
        try {
            DB::beginTransaction();

            if(Usuario::where('email', $email)->exists()){
                return ['success' => 1];
            }

            $fechaActual = Carbon::now('America/El_Salvador');

            $user = Usuario::create([
                'nombre'     => $name,
                'email'    => $email,
                'password' => Hash::make($pass),
                'fecha_registro' => $fechaActual
            ]);

            Auth::guard('web')->login($user);
            $request->session()->regenerate();

            DB::commit();

            // 4) Redirección (elige tu destino)
            // Opción dashboard:
            $ruta = route('user.index');

            return response()->json([
                'success' => 2,
                'ruta'    => $ruta,
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::info($e->getMessage());
            // error generico
            return ['success' => 99];
        }
    }


















    // @Auth
    public function vistaMisOrdenes()
    {
       return view('frontend.dashboard.vistaordenes');
    }

    public function vistaMisDirecciones()
    {
        // Países activos y disponibles
        $paises = DB::table('paises')
            ->where('activo', 1)
            ->where('disponible', 1)
            ->select('id', 'nombre')
            ->get();

        // Departamentos solo de El Salvador y USA
        $departamentos = DB::table('departamentos')
            ->whereIn('id_paises', [1, 2])
            ->where('activo', 1)
            ->where('disponible', 1)
            ->select('id', 'id_paises', 'nombre')
            ->get();

        // Municipios solo de El Salvador
        $municipios = DB::table('municipios')
            ->where('activo', 1)
            ->where('disponible', 1)
            ->select('id', 'id_departamentos', 'nombre')
            ->get();

        return view('frontend.dashboard.vistamisdirecciones', [
            'paises' => $paises,
            'departamentos' => $departamentos,
            'municipios' => $municipios,
        ]);
    }

    // guardar nueva direccion
    public function guardarNuevaDireccion(Request $request)
    {
        $ruta = route('user.address');

        return response()->json([
            'success' => 1,
            'ruta'    => $ruta,
        ]);

        $regla = array(
            'pais' => 'required',
            'nombre' => 'required',
            'telefono' => 'required',
        );

        $validar = Validator::make($request->all(), $regla);

        if ($validar->fails()){ return ['success' => 0];}


      /*  [2025-10-27 14:56:55] local.INFO: array (
        '_token' => '4wnnvBhQZ1fVMxBnr0CL70DMts8zsKXLKxJzjvzk',
        'pais' => '2',
        'departamento' => '21',
        'municipio' => NULL,
        'nombre' => 'aaa',
        'direccion' => 'bbb',
        'direccion_opcional' => 'ccc',
        'telefono' => '2333',
        'ciudad' => 'miciudad',
        'provincia' => 'mi estado',
        'postal' => '503 postal',
    )*/





        return response()->json([
            'success' => 1,
            'ruta' => route('user.index'),
            'admin' => Auth::guard('web')->user(),
        ]);

        return ['success' => 1];

    }











}
