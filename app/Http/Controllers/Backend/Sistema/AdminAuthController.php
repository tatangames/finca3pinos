<?php

namespace App\Http\Controllers\Backend\Sistema;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AdminAuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin')->except(['showLoginFormAdmin', 'loginAdmin']);
    }

    public function showLoginFormAdmin()
    {
        if (Auth::guard('admin')->check()) {
            return redirect()->route('admin.panel');
        }

        return view('backend.login.vistaloginadmin');
    }

    public function loginAdmin(Request $request){
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
        if (Auth::guard('admin')->attempt($credentials)) {

            // Regenera la sesión por seguridad
            $request->session()->regenerate();

            // Puedes redirigir o devolver JSON
            return response()->json([
                'success' => 1,
                'ruta' => route('admin.panel'),
                'admin' => Auth::guard('admin')->user(),
            ]);
        }

        return response()->json(['success' => 2, 'message' => 'Credenciales incorrectas']);
    }

    public function logoutAdmin(Request $request){
        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }
}
