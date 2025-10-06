<?php

namespace App\Http\Controllers\Backend\Sistema;

use App\Http\Controllers\Controller;
use App\Models\Administrador;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
class PerfilController extends Controller
{
    public function __construct(){
        $this->middleware('auth:admin');
    }


    public function indexEditarPerfil(){
        $usuario = auth()->user();

        return view('backend.admin.perfil.vistaperfil', compact('usuario'));
    }

    // editar contraseña del usuario
    public function editarUsuario(Request $request){

        $regla = array(
            'correo' => 'required',
            'actualizarpass' => 'required'
        );

        // password

        $validar = Validator::make($request->all(), $regla);

        if ($validar->fails()){
            return ['success' => 0];
        }

        $usuario = auth()->user();


        if($request->actualizarpass == 1){

            Administrador::where('id', $usuario->id)
                ->update([
                    'email' => $request->correo,
                    'password' => Hash::make($request->password)]);
        }else{

            Administrador::where('id', $usuario->id)
                ->update([
                    'email' => $request->correo]);
        }

        return ['success' => 1];
    }
}
