@extends('backend.menus.superior')

@section('content-admin-css')
    <link href="{{ asset('css/adminlte.min.css') }}" type="text/css" rel="stylesheet" />
    <link href="{{ asset('css/dataTables.bootstrap4.css') }}" type="text/css" rel="stylesheet" />
    <link href="{{ asset('css/toastr.min.css') }}" type="text/css" rel="stylesheet" />
    <link href="{{ asset('css/select2.min.css') }}" type="text/css" rel="stylesheet">
    <link href="{{ asset('css/select2-bootstrap-5-theme.min.css') }}" type="text/css" rel="stylesheet">
@stop

<style>
    .order-label { font-weight: 600; color: #555; }
    .order-value { color: #222; }
    .card-header { font-weight: 600; }
    .table td, .table th { vertical-align: middle; }
    .content-wrapper { background-color: #f4f6f9; }
    .content-wrapper .content .container-fluid { background-color: transparent; }
    .block-separator { margin-bottom: 18px; }
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
                {{-- Columna izquierda --}}
                <div class="col-md-6 mb-3">

                    {{-- Info Orden --}}
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

                    {{-- Estado de la Orden --}}
                    <div class="card">
                        <div class="card-header">
                            Estado de la Orden
                        </div>
                        <div class="card-body">
                            {{-- Select estado --}}
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
                                    class="btn btn-primary btn-sm mb-3"
                                    onclick="actualizarEstadoOrden()">
                                Actualizar estado
                            </button>

                            <hr>

                            {{-- Visibilidad cliente --}}
                            <div class="form-group mb-3">
                                <label for="selectVisibleCliente" class="order-label">Visibilidad para el cliente</label>
                                <select id="selectVisibleCliente" class="form-control">
                                    <option value="1" {{ ($ordenData['visible_cliente'] ?? 1) == 1 ? 'selected' : '' }}>Visible en su panel</option>
                                    <option value="0" {{ ($ordenData['visible_cliente'] ?? 1) == 0 ? 'selected' : '' }}>Ocultar al cliente</option>
                                </select>
                            </div>

                            <button type="button"
                                    class="btn btn-secondary btn-sm"
                                    onclick="actualizarVisibleCliente()">
                                Actualizar visibilidad
                            </button>

                            <small class="text-muted d-block mt-2">
                                Controla si esta orden será visible o no en el panel del cliente.
                            </small>
                        </div>
                    </div>

                    {{-- ==== SEGUIMIENTO (estados + fechas) ==== --}}
                    <div class="card mb-4 mt-3">
                        <div class="card-header bg-light">
                            Seguimiento de envío
                        </div>
                        <div class="card-body">
                            <div class="row">
                                {{-- Preparando orden --}}
                                <div class="col-md-6 mb-3">
                                    <label class="order-label d-block mb-1">Preparando orden</label>
                                    <select id="estadoPedido1" class="form-control mb-2">
                                        <option value="0" {{ ($ordenData['estado_pedido_1'] ?? 0) == 0 ? 'selected' : '' }}>Pendiente</option>
                                        <option value="1" {{ ($ordenData['estado_pedido_1'] ?? 0) == 1 ? 'selected' : '' }}>Preparando orden</option>
                                    </select>
                                    <input type="date"
                                           id="fechaPedido1"
                                           class="form-control"
                                           value="{{ $ordenData['fecha_pedido_1'] ?? '' }}">
                                    <small class="text-muted">
                                        Si marcas "Preparando orden", debes indicar la fecha.
                                    </small>
                                </div>

                                {{-- Orden enviada --}}
                                <div class="col-md-6 mb-3">
                                    <label class="order-label d-block mb-1">Orden enviada</label>
                                    <select id="estadoPedido2" class="form-control mb-2">
                                        <option value="0" {{ ($ordenData['estado_pedido_2'] ?? 0) == 0 ? 'selected' : '' }}>Pendiente</option>
                                        <option value="1" {{ ($ordenData['estado_pedido_2'] ?? 0) == 1 ? 'selected' : '' }}>Orden enviada</option>
                                    </select>
                                    <input type="date"
                                           id="fechaPedido2"
                                           class="form-control"
                                           value="{{ $ordenData['fecha_pedido_2'] ?? '' }}">
                                    <small class="text-muted">
                                        Si marcas "Orden enviada", debes indicar la fecha.
                                    </small>
                                </div>
                            </div>

                            <button type="button"
                                    class="btn btn-info btn-sm"
                                    onclick="actualizarSeguimiento()">
                                Guardar seguimiento
                            </button>
                        </div>
                    </div>

                    {{-- ==== NOTIFICACIONES AL CLIENTE ==== --}}
                    <div class="card mb-3">
                        <div class="card-header bg-light">
                            Notificaciones al Cliente
                        </div>
                        <div class="card-body">
                            <p class="text-muted">
                                Usa los siguientes botones para enviar correos automáticos al cliente según el estado de la orden.
                            </p>

                            <div class="d-flex flex-wrap gap-2">
                                <button type="button"
                                        class="btn btn-warning btn-sm mr-2"
                                        onclick="enviarCorreoPreparando()">
                                    ✉️ Enviar correo: Preparando orden
                                </button>

                                <button type="button"
                                        class="btn btn-info btn-sm"
                                        onclick="enviarCorreoSeguimiento()">
                                    ✉️ Enviar correo: Seguimiento orden
                                </button>
                            </div>
                        </div>
                    </div>

                    {{-- ==== SEGUIMIENTO (TEXTO CON CKEDITOR) ==== --}}
                    <div class="card mb-4">
                        <div class="card-header bg-light">
                            Notas / Seguimiento interno
                        </div>
                        <div class="card-body">
                            <textarea id="seguimiento"
                                      name="seguimiento"
                                      rows="5"
                                      class="form-control">{{ $ordenData['seguimiento'] ?? '' }}</textarea>
                            <small class="text-muted d-block mt-2">
                                Aquí puedes guardar notas internas, enlaces de tracking, URLs de guía, etc.
                                Solo es visible en el panel admin (según cómo lo manejes en backend).
                            </small>

                            <button type="button"
                                    class="btn btn-success btn-sm mt-2"
                                    onclick="guardarSeguimientoTexto()">
                                Guardar notas de seguimiento
                            </button>
                        </div>
                    </div>

                </div>

                {{-- Columna derecha: envío + facturación --}}
                <div class="col-md-6 mb-3">
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

            {{-- Referencia Pagadito --}}
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

            {{-- Productos --}}
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

    {{-- CKEditor (incluye herramienta de links) --}}
    <script src="https://cdn.ckeditor.com/ckeditor5/41.2.1/classic/ckeditor.js"></script>
    <script>
        // Inicializar CKEditor en textarea seguimiento
        ClassicEditor
            .create(document.querySelector('#seguimiento'), {
                toolbar: ['bold','italic','link','bulletedList','numberedList','undo','redo']
            })
            .then(editor => { window.editor = editor; })
            .catch(error => console.error(error));

        function actualizarEstadoOrden() {
            const estadoId = document.getElementById('selectEstadoOrden').value;
            let formData = new FormData();
            formData.append('orden_id', '{{ $ordenData['id'] }}');
            formData.append('status_id', estadoId);

            axios.post("{{ route('admin.ordenes.estado.update') }}", formData, {
                headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'}
            }).then(r => {
                if (r.data && r.data.success) {
                    toastr.success('Actualizado');
                    setTimeout(() => location.reload(), 600);
                } else toastr.error('No se pudo actualizar el estado');
            }).catch(() => toastr.error('No se pudo actualizar el estado'));
        }

        function actualizarVisibleCliente() {
            const visible = document.getElementById('selectVisibleCliente').value;
            let formData = new FormData();
            formData.append('orden_id', '{{ $ordenData['id'] }}');
            formData.append('visible_cliente', visible);

            axios.post("{{ route('admin.ordenes.estado.visible.update') }}", formData, {
                headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'}
            }).then(r => {
                if (r.data && r.data.success) {
                    toastr.success('Visibilidad actualizada');
                } else toastr.error(r.data.message || 'No se pudo actualizar la visibilidad');
            }).catch(() => toastr.error('No se pudo actualizar la visibilidad'));
        }

        function actualizarSeguimiento() {
            const estado1 = document.getElementById('estadoPedido1').value;
            const fecha1  = document.getElementById('fechaPedido1').value;
            const estado2 = document.getElementById('estadoPedido2').value;
            const fecha2  = document.getElementById('fechaPedido2').value;

            if (estado1 === '1' && !fecha1) {
                toastr.error('La fecha de "Preparando orden" es requerida.');
                return;
            }
            if (estado2 === '1' && !fecha2) {
                toastr.error('La fecha de "Orden enviada" es requerida.');
                return;
            }

            let formData = new FormData();
            formData.append('orden_id', '{{ $ordenData['id'] }}');
            formData.append('estado_pedido_1', estado1);
            formData.append('fecha_pedido_1', fecha1);
            formData.append('estado_pedido_2', estado2);
            formData.append('fecha_pedido_2', fecha2);

            // también mandamos el texto de seguimiento si quieres guardarlo junto aquí
            if (CKEDITOR.instances.seguimiento) {
                formData.append('seguimiento', CKEDITOR.instances.seguimiento.getData());
            }

            axios.post("{{ route('admin.ordenes.seguimiento.update') }}", formData, {
                headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'}
            }).then(r => {
                if (r.data && r.data.success) {
                    toastr.success('Seguimiento actualizado');
                } else toastr.error(r.data.message || 'No se pudo actualizar el seguimiento');
            }).catch(() => toastr.error('No se pudo actualizar el seguimiento'));
        }

        function guardarSeguimientoTexto() {
            let formData = new FormData();
            formData.append('orden_id', '{{ $ordenData['id'] }}');
            formData.append('seguimiento', CKEDITOR.instances.seguimiento.getData());

            axios.post("{{ route('admin.ordenes.seguimiento.ckeditor.update') }}", formData, {
                headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'}
            }).then(r => {
                if (r.data && r.data.success) {
                    toastr.success('Notas de seguimiento guardadas');
                } else toastr.error(r.data.message || 'No se pudo guardar el seguimiento');
            }).catch(() => toastr.error('No se pudo guardar el seguimiento'));
        }

        function enviarCorreoPreparando() {
            Swal.fire({
                title: '¿Enviar correo al cliente?',
                text: 'Se notificará que la orden está en preparación.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Sí, enviar',
                cancelButtonText: 'Cancelar',
            }).then(result => {
                if (!result.isConfirmed) return;
                let formData = new FormData();
                formData.append('orden_id', '{{ $ordenData['id'] }}');

                axios.post("{{ route('admin.ordenes.email.preparando') }}", formData, {
                    headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'}
                }).then(r => {
                    if (r.data && r.data.success) toastr.success('Correo enviado correctamente');
                    else toastr.error(r.data.message || 'No se pudo enviar el correo');
                }).catch(() => toastr.error('Error al enviar el correo'));
            });
        }

        function enviarCorreoSeguimiento() {
            Swal.fire({
                title: '¿Enviar correo al cliente?',
                text: 'Se notificará que la orden ha sido enviada.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Sí, enviar',
                cancelButtonText: 'Cancelar',
            }).then(result => {
                if (!result.isConfirmed) return;
                let formData = new FormData();
                formData.append('orden_id', '{{ $ordenData['id'] }}');

                axios.post("{{ route('admin.ordenes.email.seguimiento') }}", formData, {
                    headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'}
                }).then(r => {
                    if (r.data && r.data.success) toastr.success('Correo enviado correctamente');
                    else toastr.error(r.data.message || 'No se pudo enviar el correo');
                }).catch(() => toastr.error('Error al enviar el correo'));
            });
        }
    </script>
@endsection
