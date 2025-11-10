@extends('backend.menus.superior')

@section('content-admin-css')
    <link href="{{ asset('css/adminlte.min.css') }}" type="text/css" rel="stylesheet" />
    <link href="{{ asset('css/dataTables.bootstrap4.css') }}" type="text/css" rel="stylesheet" />
    <link href="{{ asset('css/toastr.min.css') }}" type="text/css" rel="stylesheet" />
    <link href="{{ asset('css/select2.min.css') }}" type="text/css" rel="stylesheet">
    <link href="{{ asset('css/select2-bootstrap-5-theme.min.css') }}" type="text/css" rel="stylesheet">
@stop

<style>
    .order-label {
        font-weight: 600;
        color: #555;
    }
    .order-value {
        color: #222;
    }
    .card-header {
        font-weight: 600;
    }
    .table td, .table th {
        vertical-align: middle;
    }
    .content-wrapper {
        background-color: #f4f6f9;
    }
    .content-wrapper .content .container-fluid {
        background-color: transparent;
    }
    .block-separator {
        margin-bottom: 18px;
    }
</style>

<div class="content-wrapper">

    {{-- ENCABEZADO --}}
    <section class="content-header">
        <div class="container-fluid d-flex justify-content-between align-items-center flex-wrap">
            <div>
                <h4 class="mb-1">
                    Detalle de la Orden #{{ $ordenData['id'] }}
                </h4>
                <small class="text-muted">ERN: {{ $ordenData['ern'] }}</small>
            </div>

            @php
                $statusColors = [
                    'Pendiente' => 'warning',
                    'Pagado'    => 'success',
                    'Fallo'     => 'danger',
                    'Cancelado' => 'secondary',
                    'Reembolso' => 'info',
                ];
                $badge = $statusColors[$ordenData['status']] ?? 'dark';
            @endphp

            <div class="mt-2 mt-sm-0">
                <span class="badge badge-{{ $badge }}" style="font-size: 0.9rem;">
                    {{ $ordenData['status'] }}
                </span>
            </div>
        </div>
    </section>

    {{-- CONTENIDO --}}
    <section class="content">
        <div class="container-fluid">

            {{-- ==== INFORMACIÓN DE LA ORDEN Y DATOS ==== --}}
            <div class="row block-separator">
                {{-- Columna izquierda: info orden + estado --}}
                <div class="col-md-6 mb-3">

                    {{-- Información de la Orden --}}
                    <div class="card mb-3">
                        <div class="card-header">
                            Información de la Orden
                        </div>
                        <div class="card-body">
                            <p><span class="order-label">Fecha:</span>
                                <span class="order-value">{{ $ordenData['fecha'] ?? '—' }}</span>
                            </p>
                            <p><span class="order-label">Estado:</span>
                                <span class="badge badge-{{ $badge }}" style="font-size: 0.9rem;">
                                   {{ $ordenData['status'] }}
                                </span>
                            </p>
                            <p><span class="order-label">Subtotal:</span>
                                <span class="order-value">${{ $ordenData['subtotal'] }}</span>
                            </p>
                            <p><span class="order-label">Envío:</span>
                                <span class="order-value">${{ $ordenData['envio'] }}</span>
                            </p>
                            <p><span class="order-label">Total:</span>
                                <span class="order-value">${{ $ordenData['total'] }}</span>
                            </p>
                        </div>
                    </div>

                    {{-- NUEVO BLOQUE: Estado de la Orden --}}
                    <div class="card">
                        <div class="card-header">
                            Estado de la Orden
                        </div>
                        <div class="card-body">
                            <div class="form-group mb-3">
                                <label for="selectEstadoOrden" class="order-label">Cambiar estado</label>
                                <select id="selectEstadoOrden" class="form-control">
                                    <option value="1" {{ ($ordenData['status_id'] ?? null) == 1 ? 'selected' : '' }}>Pendiente</option>
                                    <option value="2" {{ ($ordenData['status_id'] ?? null) == 2 ? 'selected' : '' }}>Pagado</option>
                                    <option value="3" {{ ($ordenData['status_id'] ?? null) == 3 ? 'selected' : '' }}>Fallo</option>
                                    <option value="4" {{ ($ordenData['status_id'] ?? null) == 4 ? 'selected' : '' }}>Cancelado</option>
                                    <option value="5" {{ ($ordenData['status_id'] ?? null) == 5 ? 'selected' : '' }}>Reembolso</option>
                                </select>
                            </div>

                            <button type="button"
                                    class="btn btn-primary btn-sm"
                                    onclick="actualizarEstadoOrden()">
                                Actualizar estado
                            </button>

                            <small class="text-muted d-block mt-2">
                                Usa este control para actualizar manualmente el estado interno de la orden.
                            </small>
                        </div>
                    </div>

                </div>

                {{-- Columna derecha: envío + facturación --}}
                <div class="col-md-6 mb-3">
                    {{-- Datos de envío --}}
                    <div class="card mb-3">
                        <div class="card-header">
                            Datos de Envío
                        </div>
                        <div class="card-body">
                            <p><strong>País:</strong> {{ $ordenData['shipping']['pais'] }}</p>

                            @if(!empty($ordenData['shipping']['departamento']))
                                <p><strong>Departamento:</strong> {{ $ordenData['shipping']['departamento'] }}</p>
                            @endif

                            @if(!empty($ordenData['shipping']['municipio']))
                                <p><strong>Municipio:</strong> {{ $ordenData['shipping']['municipio'] }}</p>
                            @endif

                            <p><strong>Nombre:</strong> {{ $ordenData['shipping']['nombre'] }}</p>
                            <p><strong>Dirección:</strong> {{ $ordenData['shipping']['direccion'] }}</p>
                            <p><strong>Ciudad:</strong> {{ $ordenData['shipping']['ciudad'] }}</p>
                            <p><strong>Estado/Provincia:</strong> {{ $ordenData['shipping']['estado'] }}</p>
                            <p><strong>Código Postal:</strong> {{ $ordenData['shipping']['zipcode'] }}</p>
                            <p><strong>Teléfono:</strong> {{ $ordenData['shipping']['telefono'] }}</p>
                        </div>
                    </div>

                    {{-- Datos de facturación --}}
                    <div class="card">
                        <div class="card-header">
                            Datos de Facturación
                        </div>
                        <div class="card-body">
                            <p><span class="order-label">País:</span>
                                <span class="order-value">{{ $ordenData['billing']['pais'] ?? '—' }}</span>
                            </p>
                            <p><span class="order-label">Nombre:</span>
                                <span class="order-value">{{ $ordenData['billing']['nombre'] ?? '—' }}</span>
                            </p>
                            <p><span class="order-label">Dirección:</span>
                                <span class="order-value">{{ $ordenData['billing']['direccion'] ?? '—' }}</span>
                            </p>
                            <p><span class="order-label">Ciudad:</span>
                                <span class="order-value">{{ $ordenData['billing']['ciudad'] ?? '—' }}</span>
                            </p>
                            <p><span class="order-label">Estado/Provincia:</span>
                                <span class="order-value">{{ $ordenData['billing']['estado'] ?? '—' }}</span>
                            </p>
                            <p><span class="order-label">Código Postal:</span>
                                <span class="order-value">{{ $ordenData['billing']['zipcode'] ?? '—' }}</span>
                            </p>
                            <p><span class="order-label">Teléfono:</span>
                                <span class="order-value">{{ $ordenData['billing']['telefono'] ?? '—' }}</span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ==== REFERENCIA PAGADITO ==== --}}
            <div class="card mb-4">
                <div class="card-header bg-light">
                    Referencia Pagadito
                </div>
                <div class="card-body">
                    <p><span class="order-label">Referencia:</span>
                        <span class="order-value">{{ $ordenData['pagadito_ref'] ?? '—' }}</span>
                    </p>
                    <p><span class="order-label">Estado en Pagadito:</span>
                        <span class="order-value">{{ $ordenData['pagadito_status'] ?? '—' }}</span>
                    </p>
                    <p><span class="order-label">Token / ID transacción:</span>
                        <span class="order-value">{{ $ordenData['pagadito_token'] ?? '—' }}</span>
                    </p>
                </div>
            </div>

            {{-- ==== PRODUCTOS DE LA ORDEN ==== --}}
            <div class="card">
                <div class="card-header">
                    Productos de la orden
                </div>
                <div class="card-body p-0">
                    <table class="table table-striped mb-0">
                        <thead>
                        <tr>
                            <th style="width: 60px;">#</th>
                            <th>Nombre Producto</th>
                            <th>Nombre Presentación</th>
                            <th class="text-right" style="width: 120px;">Precio</th>
                            <th class="text-center" style="width: 100px;">Cantidad</th>
                            <th class="text-right" style="width: 140px;">Subtotal</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($ordenData['items'] as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $item['nombre_producto'] }}</td>
                                <td>{{ $item['nombre_presentacion'] }}</td>
                                <td class="text-right">${{ $item['precio'] }}</td>
                                <td class="text-center">{{ $item['cantidad'] }}</td>
                                <td class="text-right">${{ $item['subtotal'] }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-3">
                                    No hay productos asociados a esta orden.
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </section>

    <br><br>
</div>

@extends('backend.menus.footerjs')
@section('archivos-js')
    <script src="{{ asset('js/jquery.dataTables.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/dataTables.bootstrap4.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/toastr.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/axios.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/sweetalert2.all.min.js') }}"></script>
    <script src="{{ asset('js/alertaPersonalizada.js') }}"></script>

    <script>
        function actualizarEstadoOrden() {
            const estadoId = document.getElementById('selectEstadoOrden').value;

            let formData = new FormData();
            formData.append('orden_id', '{{ $ordenData['id'] }}');
            formData.append('status_id', estadoId);

            axios.post("{{ route('admin.ordenes.estado.update') }}", formData, {
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
                .then(function (response) {
                    if (response.data && response.data.success) {
                        toastr.success('Actualizado');
                        setTimeout(function () {
                            window.location.reload();
                        }, 600);
                    } else {
                        toastr.error('No se pudo actualizar el estado');
                    }
                })
                .catch(function () {
                    toastr.error('No se pudo actualizar el estado');
                });
        }
    </script>
@endsection
