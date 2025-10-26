<?php

namespace App\Http\Controllers\Backend\Sistema;

use App\Http\Controllers\Controller;
use App\Models\Departamento;
use App\Models\Municipio;
use App\Models\Pais;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class PaisesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }


    public function vistaPaises()
    {
        return view('backend.admin.configuracion.pais.vistapais');
    }

    public function tablaPaises()
    {
        $arrayPais = Pais::orderBy('nombre', 'ASC')->get()->map(function ($item) {
            $item->precioFormat = '$' . number_format((float)$item->precio_envio, 2, '.', '');
            return $item;
        });



        return view('backend.admin.configuracion.pais.tablapais', compact('arrayPais')  );
    }


    public function informacionPais(Request $request)
    {
        $rules = array(
            'id' => 'required'
        );

        $validator = Validator::make($request->all(), $rules);

        if ( $validator->fails()){return ['success' => 0];}

        if($infoPais = Pais::where('id', $request->id)->first()){

            return['success' => 1, 'info' => $infoPais];
        }else{
            return['success' => 2];
        }
    }

    public function registrarNuevoPais(Request $request)
    {
        $regla = array(
            'nombre' => 'required',
            'precio' => 'required',
        );

        $validar = Validator::make($request->all(), $regla);

        if ($validar->fails()){return ['success' => 0]; }

        try {
            DB::beginTransaction();

            $item = new Pais();
            $item->nombre = $request->nombre;
            $item->precio_envio = $request->precio;
            $item->activo = 0;
            $item->disponible = 0;
            $item->save();

            DB::commit();
            return ['success' => 1];
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::info($e->getMessage());
            // error generico
            return ['success' => 99];
        }
    }


    public function editarPais(Request $request)
    {
        $regla = array(
            'id' => 'required',
            'nombre' => 'required',
            'precio' => 'required',
            'activo' => 'required',
            'disponible' => 'required',
        );

        $validar = Validator::make($request->all(), $regla);

        if ($validar->fails()){return ['success' => 0]; }

        try {
            DB::beginTransaction();

            Pais::where('id', $request->id)
                ->update([
                    'nombre' => $request->nombre,
                    'precio_envio' => $request->precio,
                    'activo' => $request->activo,
                    'disponible' => $request->disponible,
                ]);

            DB::commit();
            return ['success' => 1];
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::info($e->getMessage());
            // error generico
            return ['success' => 99];
        }
    }


    // ==================== DEPARTAMENTOS ==========================================

    public function vistaDepartamentos($idpais){
        return view('backend.admin.configuracion.departamentos.vistadepartamentos',compact('idpais'));
    }


    public function tablaDepartamentos($idpais){

        $arrayDepartamentos = Departamento::where('id_paises', $idpais)
            ->orderBy('nombre', 'ASC')->get();

        return view('backend.admin.configuracion.departamentos.tabladepartamentos',compact('arrayDepartamentos'));
    }

    public function informacionDepartamento(Request $request)
    {
        $rules = array(
            'id' => 'required' // id departamento
        );

        $validator = Validator::make($request->all(), $rules);

        if ( $validator->fails()){return ['success' => 0];}

        if($infoPais = Departamento::where('id', $request->id)->first()){

            return['success' => 1, 'info' => $infoPais];
        }else{
            return['success' => 2];
        }
    }

    public function registrarNuevoDepartamento(Request $request)
    {
        $regla = array(
            'idpais' => 'required',
            'nombre' => 'required',
        );

        $validar = Validator::make($request->all(), $regla);

        if ($validar->fails()){return ['success' => 0]; }

        try {
            DB::beginTransaction();

            $item = new Departamento();
            $item->id_paises = $request->idpais;
            $item->nombre = $request->nombre;
            $item->activo = 0;
            $item->disponible = 0;
            $item->save();

            DB::commit();
            return ['success' => 1];
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::info($e->getMessage());
            // error generico
            return ['success' => 99];
        }
    }

    public function editarDepartamento(Request $request)
    {
        $regla = array(
            'id' => 'required',
            'nombre' => 'required',
            'activo' => 'required',
            'disponible' => 'required',
        );

        $validar = Validator::make($request->all(), $regla);

        if ($validar->fails()){return ['success' => 0]; }

        try {
            DB::beginTransaction();

            Departamento::where('id', $request->id)
                ->update([
                    'nombre' => $request->nombre,
                    'activo' => $request->activo,
                    'disponible' => $request->disponible,
                ]);

            DB::commit();
            return ['success' => 1];
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::info($e->getMessage());
            // error generico
            return ['success' => 99];
        }
    }





    /// ========= MUNICIPIOS =========================

    public function vistaMunicipios($iddepa){
        return view('backend.admin.configuracion.municipios.vistamunicipios',compact('iddepa'));
    }


    public function tablaMunicipios($iddepa){

        $arrayMunicipios = Municipio::where('id_departamentos', $iddepa)
            ->orderBy('nombre', 'ASC')->get()->map(function($item){
                $item->precioFormat = '$' . number_format((float)$item->precio_envio, 2, '.', '');
                return $item;
            });

        return view('backend.admin.configuracion.municipios.tablamunicipios',compact('arrayMunicipios'));
    }

    public function informacionMunicipio(Request $request)
    {
        $rules = array(
            'id' => 'required' // id municipio
        );

        $validator = Validator::make($request->all(), $rules);

        if ( $validator->fails()){return ['success' => 0];}

        if($infoPais = Municipio::where('id', $request->id)->first()){

            return['success' => 1, 'info' => $infoPais];
        }else{
            return['success' => 2];
        }
    }

    public function registrarNuevoMunicipio(Request $request)
    {
        $regla = array(
            'iddepa' => 'required',
            'nombre' => 'required',
            'precio' => 'required',
        );

        $validar = Validator::make($request->all(), $regla);

        if ($validar->fails()){return ['success' => 0]; }

        try {
            DB::beginTransaction();

            $item = new Municipio();
            $item->id_departamentos = $request->iddepa;
            $item->nombre = $request->nombre;
            $item->precio_envio = $request->precio;
            $item->activo = 0;
            $item->disponible = 0;
            $item->save();

            DB::commit();
            return ['success' => 1];
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::info($e->getMessage());
            // error generico
            return ['success' => 99];
        }
    }

    public function editarMunicipio(Request $request)
    {
        $regla = array(
            'id' => 'required',
            'nombre' => 'required',
            'precio' => 'required',
            'activo' => 'required',
            'disponible' => 'required',
        );

        $validar = Validator::make($request->all(), $regla);

        if ($validar->fails()){return ['success' => 0]; }

        try {
            DB::beginTransaction();

            Municipio::where('id', $request->id)
                ->update([
                    'nombre' => $request->nombre,
                    'precio_envio' => $request->precio,
                    'activo' => $request->activo,
                    'disponible' => $request->disponible,
                ]);

            DB::commit();
            return ['success' => 1];
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::info($e->getMessage());
            // error generico
            return ['success' => 99];
        }
    }









}
