<?php

namespace App\Http\Controllers\Controles;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ControlRolController extends Controller
{


    public function indexRedireccionamiento(){

        $user = Auth::user();

        // ADMINISTRADOR
        if($user->hasRole('admin')){
            $ruta = 'admin.dashboard';
        }
        else{
            $ruta = 'no.permisos.index';
        }

        return view('backend.layouts.app', compact( 'ruta'));
    }


    public function indexDashboard()
    {

        return view('backend.admin.dashboard');
    }

    public function indexSinPermiso(){
        return view('errors.403');
    }
}
