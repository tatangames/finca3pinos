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
            return redirect()->route('user.dashboard');
        }

        return view('frontend.login.vistaloginusuario');
    }

    public function loginUsuario(Request $request){
        $rules = [
            'email' => 'required|email',
            'password' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(['success' => 0, 'errors' => $validator->errors()]);
        }

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
