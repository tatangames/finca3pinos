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
use Illuminate\Support\Facades\Mail;

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
        $ordenes = Ordenes::select('id', 'fecha', 'id_paises', 'shipping_nombre', 'subtotal', 'shipping_cost', 'total', 'status_id')
            ->orderBy('fecha', 'desc')
            ->get()
            ->map(function ($o) {
                $fecha = $o->fecha ? Carbon::parse($o->fecha) : null;
                $pais  = $o->id_paises
                    ? Pais::find($o->id_paises)
                    : null;

                // ===== Mapeo de estado =====
                $statusNombre = match ($o->status_id) {
                    1       => 'Pendiente',
                    2       => 'Pagado',
                    3       => 'Fallo',
                    4       => 'Cancelado',
                    5       => 'Reembolso',
                    default => 'Desconocido',
                };

                // Color opcional para mostrar badge (si lo usas en DataTables)
                $statusColor = match ($o->status_id) {
                    1       => 'warning',
                    2       => 'success',
                    3       => 'danger',
                    4       => 'secondary',
                    5       => 'info',
                    default => 'dark',
                };

                return [
                    'id'            => $o->id,
                    'fecha_formato' => $fecha ? $fecha->format('d-m-Y h:i A') : '',
                    'fecha_orden'   => $fecha ? $fecha->timestamp : 0, // para ordenar
                    'pais'          => $pais?->nombre ?? '—',
                    'nombre'        => $o->shipping_nombre ?? '—',
                    'subtotal'      => '$' . number_format((float) $o->subtotal, 2, '.', ','),
                    'envio'         => '$' . number_format((float) $o->shipping_cost, 2, '.', ','),
                    'total'         => '$' . number_format((float) $o->total, 2, '.', ','),
                    'status'        => $statusNombre,
                    'status_badge'  => "<span class='badge badge-{$statusColor}'>{$statusNombre}</span>",
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
            'visible_cliente' => (int)($orden->visible_cliente ?? 1),

            // 👇 seguimiento (para inputs date usamos Y-m-d)
            'estado_pedido_1' => (int)($orden->estado_pedido_1 ?? 0),
            'fecha_pedido_1'  => $orden->fecha_pedido_1
                ? Carbon::parse($orden->fecha_pedido_1)->format('Y-m-d')
                : '',
            'estado_pedido_2' => (int)($orden->estado_pedido_2 ?? 0),
            'fecha_pedido_2'  => $orden->fecha_pedido_2
                ? Carbon::parse($orden->fecha_pedido_2)->format('Y-m-d')
                : '',

            'seguimiento' => $orden->seguimiento,

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



    public function actualizarEstadoVisibleOrden(Request $request)
    {
        try {
            $data = $request->validate([
                'orden_id'        => ['required', 'integer', 'exists:ordenes,id'],
                'visible_cliente' => ['required', 'in:0,1'],
            ]);

            $orden = Ordenes::findOrFail($data['orden_id']);
            $orden->visible_cliente = (int)$data['visible_cliente'];
            $orden->save();

            Log::info('Visibilidad de orden actualizada', [
                'orden_id'        => $orden->id,
                'visible_cliente' => $orden->visible_cliente,
                'user_id'         => auth()->id(),
            ]);

            return response()->json([
                'success'          => true,
                'visible_cliente'  => (int)$orden->visible_cliente,
            ]);

        } catch (\Throwable $e) {
            Log::error('Error al actualizar visibilidad de orden', [
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar la visibilidad de la orden.',
            ], 500);
        }
    }



    public function actualizarSeguimientoOrden(Request $request)
    {
        $data = $request->validate([
            'orden_id'        => ['required', 'integer', 'exists:ordenes,id'],
            'estado_pedido_1' => ['required', 'in:0,1'],
            'fecha_pedido_1'  => ['nullable', 'date'],
            'estado_pedido_2' => ['required', 'in:0,1'],
            'fecha_pedido_2'  => ['nullable', 'date'],
        ]);

        // Validaciones: si estado = 1, fecha obligatoria
        if ($data['estado_pedido_1'] == 1 && empty($data['fecha_pedido_1'])) {
            return response()->json([
                'success' => false,
                'message' => 'La fecha de "Preparando orden" es requerida.',
            ], 422);
        }

        if ($data['estado_pedido_2'] == 1 && empty($data['fecha_pedido_2'])) {
            return response()->json([
                'success' => false,
                'message' => 'La fecha de "Orden enviada" es requerida.',
            ], 422);
        }

        $orden = Ordenes::findOrFail($data['orden_id']);

        $orden->estado_pedido_1 = (int)$data['estado_pedido_1'];
        $orden->fecha_pedido_1  = $data['estado_pedido_1'] == 1
            ? $data['fecha_pedido_1']
            : null;

        $orden->estado_pedido_2 = (int)$data['estado_pedido_2'];
        $orden->fecha_pedido_2  = $data['estado_pedido_2'] == 1
            ? $data['fecha_pedido_2']
            : null;

        $orden->save();

        return response()->json([
            'success' => true,
        ]);
    }

    public function actualizarSeguimientoOrdenCkEditor(Request $request)
    {
        $data = $request->validate([
            'orden_id'    => ['required', 'integer', 'exists:ordenes,id'],
            'seguimiento' => ['nullable', 'string'],
        ]);

        $orden = Ordenes::findOrFail($data['orden_id']);
        $orden->seguimiento = $data['seguimiento'] ?? null;
        $orden->save();

        return response()->json([
            'success' => true,
            'message' => 'Notas de seguimiento guardadas correctamente.',
        ]);
    }



    public function enviarCorreoPreparando(Request $request)
    {
        $orden = Ordenes::with('usuario')->findOrFail($request->orden_id);

        // ===== Validar usuario =====
        if (!$orden->usuario || !$orden->usuario->email) {
            return response()->json([
                'success' => false,
                'message' => 'El cliente no tiene correo registrado.',
            ]);
        }

        // ===== Validar estado y fecha =====
        if ($orden->estado_pedido_1 == 0 || empty($orden->fecha_pedido_1)) {
            return response()->json([
                'success' => false,
                'message' => 'No se puede enviar el correo: la orden aún no está marcada como "Preparando" o no tiene fecha registrada.',
            ]);
        }

        // ===== Mapeo de idioma por país =====
        $locale = match ($orden->id_paises) {
            1       => 'sv', // El Salvador
            2       => 'en', // USA
            3       => 'ko', // Corea
            default => 'sv', // fallback
        };

        // ===== Formato de fecha según país =====
        $formatoFecha = $orden->id_paises == 1 ? 'd-m-Y' : 'm-d-Y';
        $fechaPreparacion = $orden->fecha_pedido_1
            ? \Carbon\Carbon::parse($orden->fecha_pedido_1)->format($formatoFecha)
            : null;

        // ===== Guardar locale actual y forzar idioma =====
        $previousLocale = app()->getLocale();
        app()->setLocale($locale);

        // ===== Envío del correo =====
        Mail::send('mail.orden_preparando', [
            'orden'            => $orden,
            'locale'           => $locale,
            'fechaPreparacion' => $fechaPreparacion,
        ], function ($m) use ($orden) {
            $m->to($orden->usuario->email, $orden->usuario->nombre ?? 'Cliente')
                ->subject(__('meta.your_order_is_being_prepared'));
        });

        // ===== Restaurar idioma original =====
        app()->setLocale($previousLocale);

        return response()->json(['success' => true]);
    }






    public function enviarCorreoSeguimiento(Request $request)
    {
        $orden = Ordenes::with('usuario')->findOrFail($request->orden_id);

        // ===== Validar usuario =====
        if (!$orden->usuario || !$orden->usuario->email) {
            return response()->json([
                'success' => false,
                'message' => 'El cliente no tiene correo registrado.',
            ]);
        }

        // ===== Validar estado y fecha =====
        if ($orden->estado_pedido_2 == 0 || empty($orden->fecha_pedido_2)) {
            return response()->json([
                'success' => false,
                'message' => 'No se puede enviar el correo: la orden aún no está marcada como "Enviada" o no tiene fecha de envío registrada.',
            ]);
        }

        // ====== Mapeo de idioma por país ======
        $locale = match ($orden->id_paises) {
            1       => 'sv', // El Salvador
            2       => 'en', // Estados Unidos
            3       => 'ko', // Corea
            default => 'sv', // fallback
        };

        // ====== Formato de fecha según país ======
        $formatoFecha = $orden->id_paises == 1 ? 'd-m-Y' : 'm-d-Y';
        $fechaEnvio   = $orden->fecha_pedido_2
            ? \Carbon\Carbon::parse($orden->fecha_pedido_2)->format($formatoFecha)
            : null;

        // ===== Guardar idioma actual =====
        $previousLocale = app()->getLocale();

        // ===== Forzar idioma del correo =====
        app()->setLocale($locale);

        // ===== Enviar correo =====
        Mail::send('mail.orden_seguimiento', [
            'orden'      => $orden,
            'locale'     => $locale,
            'fechaEnvio' => $fechaEnvio,
        ], function ($m) use ($orden) {
            $m->to($orden->usuario->email, $orden->usuario->nombre ?? 'Cliente')
                ->subject(__('meta.your_order_has_been_shipped'));
        });

        // ===== Restaurar idioma original =====
        app()->setLocale($previousLocale);

        return response()->json(['success' => true]);
    }







}
