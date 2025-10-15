<?php

namespace App\Http\Controllers\Backend\Sistema;

use App\Http\Controllers\Controller;
use App\Models\Galeria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

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
















}
