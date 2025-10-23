<?php

namespace App\Http\Controllers\Frontend\Sistema;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UsuarioAuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:web')->except(['showLoginFormUsuario', 'loginUsuario']);
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

        return response()->json([
            'success' => 0,
            'message' => __('meta.unknown_error'),
        ]);






        $credentials = $request->only('email', 'password');

        // Guard admin (usa el provider 'admin' del auth.php)
        if (Auth::guard('web')->attempt($credentials)) {

            // Regenera la sesión por seguridad
            $request->session()->regenerate();

            // Puedes redirigir o devolver JSON
            return response()->json([
                'success' => 1,
                'ruta' => route('user.dashboard'),
                'admin' => Auth::guard('web')->user(),
            ]);
        }

        return response()->json(['success' => 2, 'message' => 'Credenciales incorrectas']);
    }

    public function logoutUsuario(Request $request){
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('');
    }
}
