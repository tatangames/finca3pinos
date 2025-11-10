<?php

namespace App\Http\Controllers\Backend\Sistema;

use App\Http\Controllers\Controller;
use App\Models\Departamento;
use App\Models\Municipio;
use App\Models\Ordenes;
use App\Models\Pais;
use App\Models\RegionContent;
use App\Models\RegionContentTranslation;
use App\Models\Usuario;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

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
        // ====== Constante STATUS ======
        $STATUS = [
            1 => 'Pendiente',
            2 => 'Pagado',
            3 => 'Fallo',
            4 => 'Cancelado',
            5 => 'Reembolso',
        ];

        // ====== Traer la orden con items + producto + presentacion ======
        $orden = Ordenes::with([
            'items.producto',
            'items.presentacion',
        ])->findOrFail($idorden);

        // ====== Nombre de estado ======
        $statusNombre = $STATUS[$orden->status_id] ?? 'Desconocido';

        // ====== Ubicación envío ======
        $paisEnvio  = Pais::find($orden->id_paises);
        $deptoEnvio = $orden->id_departamentos
            ? Departamento::find($orden->id_departamentos)
            : null;
        $muniEnvio  = $orden->id_municipios
            ? Municipio::find($orden->id_municipios)
            : null;

        // ====== Ubicación facturación ======
        $paisFactura = $orden->billing_idpaises
            ? Pais::find($orden->billing_idpaises)
            : null;

        // ====== Helper: obtener nombre traducido desde RegionContent (SV) ======
        $getNombreSV = function (?string $contentKey, ?string $fallback = null) {
            if (!$contentKey) {
                return $fallback ?: '—';
            }

            $nombre = '';

            if ($infoRegion = RegionContent::where('key', $contentKey)
                ->where('region_id', 1) // 1 = SV
                ->first()
            ) {
                if ($infoIdioma = RegionContentTranslation::where('content_id', $infoRegion->id)->first()) {
                    $nombre = $infoIdioma->title ?: '';
                }
            }

            return $nombre !== '' ? $nombre : ($fallback ?: $contentKey);
        };

        // ====== Armar datos de la orden ======
        $ordenData = collect([
            'id'       => $orden->id,
            'ern'      => $orden->ern,
            'fecha'    => $orden->fecha
                ? Carbon::parse($orden->fecha)->format('d-m-Y h:i A')
                : null,
            'status_id'  => $orden->status_id,
            'status'   => $statusNombre,
            'subtotal' => number_format($orden->subtotal, 2, '.', ','),
            'envio'    => number_format($orden->shipping_cost, 2, '.', ','),
            'total'    => number_format($orden->total, 2, '.', ','),

            // ==== Referencia Pagadito (top-level) ====
            // Si aún sólo usas 'ern' como referencia de Pagadito:
            'pagadito_ref'    => $orden->pagadito_ref ?? $orden->ern ?? null,
            'pagadito_status' => $orden->pagadito_status ?? null,
            'pagadito_token'  => $orden->pagadito_token ?? null,

            'shipping' => [
                'pais'         => $paisEnvio->nombre ?? '—',
                'departamento' => $deptoEnvio->nombre ?? null,
                'municipio'    => $muniEnvio->nombre ?? null,
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

            'items' => $orden->items->map(function ($item) use ($getNombreSV) {
                $producto     = $item->producto;
                $presentacion = $item->presentacion;

                $nombreProducto = $producto
                    ? $getNombreSV($producto->content_key ?? null, $producto->nombre ?? null)
                    : '—';

                $nombrePresentacion = $presentacion
                    ? $getNombreSV($presentacion->content_key ?? null, $presentacion->nombre ?? null)
                    : '';

                $nombreCompleto = trim(
                    $nombreProducto .
                    ($nombrePresentacion ? ' — ' . $nombrePresentacion : '')
                );

                return [
                    'nombre_producto'     => $nombreProducto,
                    'nombre_presentacion' => $nombrePresentacion,
                    'nombre'              => $nombreCompleto,
                    'precio'              => number_format($item->precio, 2, '.', ','),
                    'cantidad'            => $item->cantidad,
                    'subtotal'            => number_format($item->subtotal, 2, '.', ','),
                ];
            }),
        ]);

        return view('backend.admin.ordenes.detalle.vistaordendetalle', compact('ordenData'));
    }



    public function actualizarEstadoOrden(Request $request)
    {
        try {
            // Validar datos
            $data = $request->validate([
                'orden_id'  => ['required', 'integer', 'exists:ordenes,id'],
                'status_id' => ['required', 'integer', 'in:1,2,3,4,5'],
            ]);

            // Buscar orden
            $orden = Ordenes::findOrFail($data['orden_id']);

            // Si el estado ya es el mismo, devolvemos ok sin tocar DB
            if ((int)$orden->status_id === (int)$data['status_id']) {
                return response()->json([
                    'success'      => true,
                    'no_changes'   => true,
                    'status_id'    => $orden->status_id,
                    'status_label' => $this->getStatusLabel($orden->status_id),
                ]);
            }

            // Actualizar estado
            $orden->status_id = $data['status_id'];
            $orden->save();

            // Opcional: log
            Log::info('Estado de orden actualizado manualmente', [
                'orden_id'   => $orden->id,
                'nuevo_estado_id' => $orden->status_id,
                'nuevo_estado'    => $this->getStatusLabel($orden->status_id),
                'user_id'    => auth()->id(),
            ]);

            return response()->json([
                'success'      => true,
                'status_id'    => $orden->status_id,
                'status_label' => $this->getStatusLabel($orden->status_id),
            ]);

        } catch (\Throwable $e) {

            Log::error('Error al actualizar estado de orden', [
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar el estado de la orden.',
            ], 500);
        }
    }


    private function getStatusLabel(int $statusId): string
    {
        $STATUS = [
            1 => 'Pendiente',
            2 => 'Pagado',
            3 => 'Fallo',
            4 => 'Cancelado',
            5 => 'Reembolso',
        ];

        return $STATUS[$statusId] ?? 'Desconocido';
    }


}
