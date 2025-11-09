<?php

namespace App\Http\Controllers\Backend\Sistema;

use App\Http\Controllers\Controller;
use App\Models\Usuario;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AdminClienteController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function vistaClientes(){
        return view('backend.admin.clientes.vistaclientes');
    }

    public function tablaClientes()
    {
        $clientes = Usuario::select('nombre', 'email', 'fecha_registro')
            ->orderBy('fecha_registro', 'desc')
            ->get()
            ->map(function ($c) {
                $fecha = $c->fecha_registro
                    ? Carbon::parse($c->fecha_registro)
                    : null;

                return [
                    'nombre'         => $c->nombre,
                    'email'          => $c->email,
                    'fecha_registro' => $fecha ? $fecha->format('d-m-Y h:i A') : '',
                    'fecha_orden'    => $fecha ? $fecha->timestamp : 0, // para ordenar bien
                ];
            });

        return view('backend.admin.clientes.tablaclientes', compact('clientes'));
    }

}
