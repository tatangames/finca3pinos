<?php

namespace App\Http\Controllers\Backend\Sistema;

use App\Http\Controllers\Controller;
use App\Models\Departamento;
use App\Models\Municipio;
use App\Models\Ordenes;
use App\Models\Pais;
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


    public function vistaOrdenes(){
        return view('backend.admin.ordenes.vistaordenes');
    }

    public function tablaOrdenes()
    {
        $ordenes = Ordenes::select('id', 'fecha', 'id_paises', 'shipping_nombre', 'subtotal', 'shipping_cost', 'total')
            ->orderBy('fecha', 'desc')
            ->get()
            ->map(function ($o) {
                $fecha = $o->fecha ? Carbon::parse($o->fecha) : null;
                $pais  = $o->id_paises
                    ? Pais::find($o->id_paises)
                    : null;

                return [
                    'id'            => $o->id,
                    'fecha_formato' => $fecha ? $fecha->format('d-m-Y h:i A') : '',
                    'fecha_orden'   => $fecha ? $fecha->timestamp : 0, // para ordenar en DataTables
                    'pais'          => $pais?->nombre ?? '—',
                    'nombre'        => $o->shipping_nombre ?? '—',
                    'subtotal'      => '$' . number_format((float) $o->subtotal, 2, '.', ','),
                    'envio'         => '$' . number_format((float) $o->shipping_cost, 2, '.', ','),
                    'total'         => '$' . number_format((float) $o->total, 2, '.', ','),
                ];
            });

        return view('backend.admin.ordenes.tablaordenes', compact('ordenes'));
    }


    public function vistaOrdenDetalle($idorden)
    {
        // ====== Constante STATUS dentro del método ======
        $STATUS = [
            1 => 'Pendiente',
            2 => 'Pagado',
            3 => 'Fallo',
            4 => 'Cancelado',
            5 => 'Reembolso',
        ];

        // ====== Traer la orden y sus productos ======
        $orden = Ordenes::with('items')->findOrFail($idorden);

        // ====== Nombre de estado ======
        $statusNombre = $STATUS[$orden->status_id] ?? 'Desconocido';

        // ====== Ubicación envío (país siempre, depto/muni opcionales) ======
        $paisEnvio  = Pais::find($orden->id_paises);

        $deptoEnvio = $orden->id_departamentos
            ? Departamento::find($orden->id_departamentos)
            : null;
        $muniEnvio  = $orden->id_municipios
            ? Municipio::find($orden->id_municipios)
            : null;

        // ====== Ubicación facturación (si quieres también) ======
        $paisFactura = Pais::find($orden->billing_idpaises);

        // ====== Formatear datos ======
        $ordenData = collect([
            'id'       => $orden->id,
            'ern'      => $orden->ern,
            'fecha'    => $orden->fecha
                ? Carbon::parse($orden->fecha)->format('d-m-Y h:i A')
                : null,
            'status'   => $statusNombre,
            'subtotal' => number_format($orden->subtotal, 2, '.', ','),
            'envio'    => number_format($orden->shipping_cost, 2, '.', ','),
            'total'    => number_format($orden->total, 2, '.', ','),

            'shipping' => [
                'pais'         => $paisEnvio->nombre ?? '—',          // SIEMPRE
                'departamento' => $deptoEnvio->nombre ?? null,        // PUEDE SER NULL
                'municipio'    => $muniEnvio->nombre ?? null,         // PUEDE SER NULL
                'nombre'       => $orden->shipping_nombre,
                'direccion'    => $orden->shipping_direccion,
                'ciudad'       => $orden->shipping_ciudad,
                'estado'       => $orden->shipping_estado,
                'zipcode'      => $orden->shipping_zipcode,
                'telefono'     => $orden->shipping_telefono,
            ],

            'billing' => [
                'pais'      => $paisFactura->nombre ?? '—',
                'nombre'    => $orden->billing_nombre,
                'direccion' => $orden->billing_direccion,
                'ciudad'    => $orden->billing_ciudad,
                'estado'    => $orden->billing_estado,
                'zipcode'   => $orden->billing_zipcode,
                'telefono'  => $orden->billing_telefono,
            ],

            'items' => $orden->items->map(fn ($item) => [
                'nombre'   => $item->nombre,
                'precio'   => number_format($item->precio, 2, '.', ','),
                'cantidad' => $item->cantidad,
                'subtotal' => number_format($item->subtotal, 2, '.', ','),
            ]),
        ]);

        return view('backend.admin.ordenes.detalle.vistaordendetalle', compact('ordenData'));
    }






}
