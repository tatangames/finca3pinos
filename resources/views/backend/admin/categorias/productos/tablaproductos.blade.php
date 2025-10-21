<style>
    .btn-activar {
        background-color: #007bff !important;     /* fondo blanco */
        color: #ffffff !important;             /* texto dorado */
        border: 1px solid #ffffff !important;  /* borde dorado */
        font-weight: 600;
    }

</style>

<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <table id="table" class="table table-bordered">
                        <thead>
                            <tr>
                                <th style="width: 5%">Posición</th>
                                <th style="width: 10%">Nombre (SV)</th>
                                <th style="width: 5%">Precio</th>
                                <th style="width: 10%">Imagen</th>
                                <th style="width: 6%">Estado</th>
                                <th style="width: 10%">Opciones</th>
                            </tr>
                        </thead>
                        <tbody id="tablecontents">
                        @foreach($arrayProducto as $dato)
                            <tr class="row1" data-id="{{ $dato->id }}">

                                <td style="font-weight: bold">{{ $dato->posicion }}</td>
                                <td>{{ $dato->nombreSV }}</td>
                                <td>{{ $dato->precioFormat }}</td>
                                <td>
                                    <img
                                        src="{{ url('storage/archivos/'.$dato->imagen) }}"
                                        alt="{{ $dato->alt_seo }}"
                                        width="190"
                                        height="176"
                                        style="border-radius:12px; object-fit:cover; display:block; margin:0 auto;"
                                    >
                                </td>
                                <td>
                                    @if($dato->activo == 1)
                                        <small class="badge badge-success"><i class="far fa-check"></i>Activo</small>
                                    @else
                                        <small class="badge badge-danger"><i class="far fa-close"></i>Desactivado</small>
                                    @endif
                                </td>
                                <td>
                                    <button type="button" class="btn btn-info btn-xs" onclick="informacionEditar({{ $dato->id }})">
                                        <i class="fas fa-edit" title="Editar"></i>&nbsp; Editar
                                    </button>

                                    @if($dato->activo == 1)
                                        <button type="button" style="margin: 2px" class="btn btn-warning btn-xs" onclick="modalDesactivar({{ $dato->id }})">
                                            <i class="fas fa-edit" title="Desactivar"></i>&nbsp; Desactivar
                                        </button>
                                    @else
                                        <button type="button" style="margin: 2px" class="btn btn-activar btn-xs" onclick="modalActivar({{ $dato->id }})">
                                            <i class="fas fa-edit" title="Activar"></i>&nbsp; Activar
                                        </button>
                                    @endif

                                    <button type="button" style="margin: 3px" class="btn btn-success btn-xs" onclick="modalImagen({{ $dato->id }})">
                                        <i class="fas fa-edit" title="Editar Imagen"></i>&nbsp; Editar Imagen
                                    </button>

                                    <button type="button" style="margin: 3px" class="btn btn-info btn-xs" onclick="vistaPresentacion({{ $dato->id }})">
                                        <i class="fas fa-edit" title="Presentacion"></i>&nbsp; Presentación
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
</section>

<script type="text/javascript">
    $(document).ready(function() {

        $( "#tablecontents" ).sortable({
            items: "tr",
            cursor: 'move',
            opacity: 0.6,
            update: function() {
                sendOrderToServer();
            }
        });

        function sendOrderToServer() {

            var order = [];
            $('tr.row1').each(function(index,element) {
                order.push({
                    id: $(this).attr('data-id'),
                    posicion: index+1
                });
            });

            openLoading();

            axios.post('/admin/producto/posicion',  {
                'order': order
            })
                .then((response) => {
                    closeLoading();
                    toastr.success('Actualizado correctamente');
                    recargar();
                })
                .catch((error) => {
                    closeLoading();
                    toastr.error('Error al actualizar');
                });
        }
    });

</script>
