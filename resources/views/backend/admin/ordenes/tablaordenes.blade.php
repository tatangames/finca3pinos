<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <table id="tabla" class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th>Fecha Creada</th>
                                <th>País</th>
                                <th>Nombre Cliente</th>
                                <th>Estado Orden</th>
                                <th>Sub Total</th>
                                <th>Envío</th>
                                <th>Total</th>
                                <th>Opciones</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($ordenes as $o)
                                <tr>
                                    <td data-order="{{ $o['fecha_orden'] }}">{{ $o['fecha_formato'] }}</td>
                                    <td>{{ $o['pais'] }}</td>
                                    <td>{{ $o['nombre'] }}</td>
                                    <td>{!! $o['status_badge'] !!}</td>
                                    <td>{{ $o['subtotal'] }}</td>
                                    <td>{{ $o['envio'] }}</td>
                                    <td><strong>{{ $o['total'] }}</strong></td>
                                    <td>
                                        <button type="button"
                                                class="btn btn-primary btn-xs"
                                                onclick="detalle({{ $o['id'] }})">
                                            <i class="fa fa-eye" title="Detalle"></i>&nbsp; Detalle
                                        </button>
                                    </td>
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
            order: [[0, 'desc']], // orden por fecha
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
