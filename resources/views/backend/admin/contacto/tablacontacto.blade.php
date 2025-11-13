<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <table id="tabla" class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th>Fecha registro</th>
                                <th>Tipo Formulario</th>
                                <th>País</th>
                                <th>Nombre</th>
                                <th>Correo electrónico</th>
                                <th>Teléfono</th>
                                <th>Mensaje</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($arrayContacto as $c)
                                <tr>
                                    {{-- Para ordenar por fecha real --}}
                                    <td data-order="{{ strtotime($c->fecha) }}">
                                        {{ \Carbon\Carbon::parse($c->fecha)->format('d/m/Y h:i A') }}
                                    </td>
                                    <td>
                                        @if($c->tipo_formulario == 0)
                                            <span class="badge badge-info">Contacto</span>
                                        @else
                                            <span class="badge badge-success">Cotización</span>
                                        @endif
                                    </td>
                                    <td>{{ $c->nombrePais ?? '—' }}</td>
                                    <td>{{ $c->nombre ?? '—' }}</td>
                                    <td>{{ $c->correo ?? '—' }}</td>
                                    <td>{{ $c->telefono ?? '—' }}</td>
                                    <td>{{ $c->mensaje ?? '—' }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    $(function () {
        $('#tabla').DataTable({
            paging: true,
            lengthChange: true,
            searching: true,
            ordering: true,
            order: [[0, 'desc']], // orden por fecha_registro
            info: true,
            autoWidth: false,
            pagingType: 'full_numbers',
            lengthMenu: [[50, 100, -1], [50, 100, 'Todo']],
            language: {
                sProcessing:     'Procesando...',
                sLengthMenu:     'Mostrar _MENU_ registros',
                sZeroRecords:    'No se encontraron resultados',
                sEmptyTable:     'Ningún dato disponible en esta tabla',
                sInfo:           'Mostrando _START_ a _END_ de _TOTAL_ registros',
                sInfoEmpty:      'Mostrando 0 de 0 registros',
                sInfoFiltered:   '(filtrado de _MAX_ registros)',
                sSearch:         'Buscar:',
                oPaginate: {
                    sFirst:    'Primero',
                    sLast:     'Último',
                    sNext:     'Siguiente',
                    sPrevious: 'Anterior'
                }
            },
            responsive: true
        });
    });
</script>







