<?php

namespace App\Http\Controllers\Frontend\Sistema;

use App\Http\Controllers\Controller;
use App\Mail\PasswordResetMail;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;


class UsuarioAuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:web')->except(['showLoginFormUsuario', 'loginUsuario', 'showIngresarCorreoForm',
        'solicitarCodigoCorreo', 'showResetPasswordForm', 'showtokenInvalid']);
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




















    // @Auth
    public function vistaDashboard()
    {
       return view('frontend.dashboard.vistadashboard');
    }












}
