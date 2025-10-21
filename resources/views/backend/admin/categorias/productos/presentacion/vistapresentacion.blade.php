@extends('backend.menus.superior')

@section('content-admin-css')
    <link href="{{ asset('css/adminlte.min.css') }}" type="text/css" rel="stylesheet" />
    <link href="{{ asset('css/dataTables.bootstrap4.css') }}" type="text/css" rel="stylesheet" />
    <link href="{{ asset('css/toastr.min.css') }}" type="text/css" rel="stylesheet" />
    <link href="{{ asset('css/select2.min.css') }}" type="text/css" rel="stylesheet">
    <link href="{{ asset('css/select2-bootstrap-5-theme.min.css') }}" type="text/css" rel="stylesheet">
@stop

<style>
    table{
        /*Ajustar tablas*/
        table-layout:fixed;
    }


</style>

<div id="divcontenedor" style="display: none">

    <section class="content-header">
        <div class="row mb-2">
            <div class="col-sm-6">
                <button type="button" onclick="modalAgregar()" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus-square"></i>
                    Nuevo Presentación
                </button>
            </div>

            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item">Presentación</li>
                    <li class="breadcrumb-item active">Listado de Presentaciones</li>
                </ol>
            </div>

        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="card card-gray-dark">
                <div class="card-header">
                    <h3 class="card-title">Presentación</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div id="tablaDatatable">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <div class="modal fade" id="modalAgregar">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Registro</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formulario-nuevo">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">

                                    <!-- Key global -->
                                    <div class="form-group">
                                        <label>Key para todos los idiomas (NO ESPACIOS)</label>
                                        <input type="text" maxlength="200" id="key" class="form-control"
                                               placeholder="Ej: about.history">
                                    </div>

                                    <!-- Campos por idioma -->
                                    @foreach($arrayRegiones as $region)

                                        <div class="card mt-3 border">
                                            <div class="card-header" style="background:#f4f4f4;">
                                                <strong>{{ $region->name }}</strong> ({{ strtoupper($region->locale) }})
                                            </div>
                                            <div class="card-body">
                                                <div class="form-group">

                                                    <p>Título de Presentación</p>
                                                    <textarea name="title_{{ $region->locale }}"
                                                              id="title_{{ $region->locale }}"
                                                              rows="4"
                                                              class="form-control"
                                                              required
                                                              placeholder="<p>Texto o HTML...</p>"></textarea>
                                                    <br>

                                                </div>
                                            </div>
                                        </div>
                                    @endforeach

                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-primary" onclick="nuevo()">Guardar</button>
                </div>
            </div>
        </div>
    </div>



    <div class="modal fade" id="modalEditar">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Actualizar</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formulario-editar">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">

                                    <div class="form-group">
                                        <input type="hidden" id="id-editar">
                                    </div>

                                    <!-- Campos dinámicos por idioma -->
                                    <div id="langs-editar"></div>

                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-primary" onclick="editar()">Guardar</button>
                </div>
            </div>
        </div>
    </div>



</div>
@extends('backend.menus.footerjs')
@section('archivos-js')

    <script src="{{ asset('js/jquery-ui-drag.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/datatables-drag.min.js') }}" type="text/javascript"></script>

    <script src="{{ asset('js/toastr.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/axios.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/sweetalert2.all.min.js') }}"></script>
    <script src="{{ asset('js/alertaPersonalizada.js') }}"></script>


    <script type="text/javascript">
        $(document).ready(function(){

            var idproducto = {{ $idproducto }};
            var ruta = "{{ URL::to('/admin/producto/presentacion/index/tabla') }}/" + idproducto;
            $('#tablaDatatable').load(ruta);

            document.getElementById("divcontenedor").style.display = "block";
        });
    </script>

    <script>

        // recarga tabla
        function recargar(){
            var idproducto = {{ $idproducto }};
            var ruta = "{{ URL::to('/admin/producto/presentacion/index/tabla') }}/" + idproducto;
            $('#tablaDatatable').load(ruta);
        }


        function modalAgregar(){
            document.getElementById("formulario-nuevo").reset();
            $('#modalAgregar').modal('show');
        }

        function nuevo(){
            var key = document.getElementById('key').value.trim();

            if (key === '') {
                toastr.error('Debe ingresar la key global');
                return;
            }

            var idproducto = {{ $idproducto }};

            // 🔹 Creamos el FormData
            let formData = new FormData();
            formData.append('idproducto', idproducto);
            formData.append('key', key);

            // 🔹 Recorremos las regiones en un array JSON generado por Blade
            let regiones = @json($arrayRegiones);

            const error = regiones.some(region => {
                let locale = region.locale;
                let title = document.getElementById(`title_${locale}`).value.trim();

                if (!title) {
                    toastr.error(`Título es requerido para ${locale.toUpperCase()}`);
                    return true; // detiene el ciclo
                }

                formData.append(`translations[${locale}][title]`, title);
                return false;
            });


            if (error) return;

            openLoading()

            axios.post('/admin/producto/presentacion/nuevo', formData, {
            })
                .then((response) => {
                    closeLoading();

                    if(response.data.success === 1){
                        // KEY repetida
                        toastr.error("LA KEY esta repetida");
                    }
                    else if(response.data.success === 2){
                        toastr.success('Registrado correctamente');
                        $('#modalAgregar').modal('hide');
                        recargar();
                    }
                    else {
                        toastr.error('Error al registrar');
                    }
                })
                .catch((error) => {
                    toastr.error('Error al registrar');
                    closeLoading();
                });
        }

        function modalDesactivar(idfila){
            Swal.fire({
                title: '¿Desactivar?',
                text: "",
                icon: 'info',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#d33',
                cancelButtonText: 'Cancelar',
                confirmButtonText: 'Si'
            }).then((result) => {
                if (result.isConfirmed) {
                    desactivarFila(idfila);
                }
            })
        }

        function desactivarFila(idfila){
            openLoading();

            axios.post('/admin/producto/presentacion/desactivar',{
                'id': idfila
            })
                .then((response) => {
                    closeLoading();
                    if(response.data.success === 1){
                        toastr.success('Actualizado');
                        recargar();
                    }else{
                        toastr.error('Error al actualizar');
                    }
                })
                .catch((error) => {
                    toastr.error('Error al actualizar');
                    closeLoading();
                });
        }

        function modalActivar(idfila){
            Swal.fire({
                title: '¿Activar?',
                text: "",
                icon: 'info',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#d33',
                cancelButtonText: 'Cancelar',
                confirmButtonText: 'Si'
            }).then((result) => {
                if (result.isConfirmed) {
                    activarFila(idfila);
                }
            })
        }

        function activarFila(idfila){
            openLoading();

            axios.post('/admin/producto/presentacion/activar',{
                'id': idfila
            })
                .then((response) => {
                    closeLoading();
                    if(response.data.success === 1){
                        toastr.success('Actualizado');
                        recargar();
                    }
                    else{
                        toastr.error('Error al actualizar');
                    }
                })
                .catch((error) => {
                    toastr.error('Error al actualizar');
                    closeLoading();
                });
        }



        function cardIdiomaHTML(item) {
            // item = { name, locale, title }
            const loc = item.locale;
            return `
              <div class="card mt-3 border">
                <div class="card-header" style="background:#f4f4f4;">
                  <strong>${item.name}</strong> (${loc.toUpperCase()})
                </div>
                <div class="card-body">
                  <div class="form-group">


                    <label>Título (${loc})</label>
                    <textarea id="title_${loc}_editar"
                              rows="4"
                              class="form-control"
                              placeholder="<p>Texto o HTML...</p>">${item.title || ''}</textarea>

                  </div>
                </div>
              </div>
    `;
        }

        function informacionEditar(id){
            openLoading();
            document.getElementById("formulario-editar").reset();

            axios.post('/admin/producto/presentacion/informacion', { id })
                .then((response) => {
                    closeLoading();
                    if (response.data.success === 1) {
                        const { info, langs } = response.data;

                        $('#modalEditar').modal('show');
                        $('#id-editar').val(info.id);

                        // ✅ Idiomas dinámicos
                        const cont = document.getElementById('langs-editar');
                        cont.innerHTML = langs.map(cardIdiomaHTML).join('');
                        cont.dataset.locales = langs.map(l => l.locale).join(',');
                    } else {
                        toastr.error('Información no encontrada');
                    }
                })
                .catch(() => {
                    closeLoading();
                    toastr.error('Error al obtener la información');
                });
        }



        function editar(){
            const id     = document.getElementById('id-editar').value;

            // Recolectar traducciones dinámicas
            const cont = document.getElementById('langs-editar');
            const locales = (cont.dataset.locales || '').split(',').filter(Boolean);

            const formData = new FormData();
            formData.append('id', id);

            let valido = true;
            // Enviar como arrays: title[en], title[sv], title[sv], ...
            locales.forEach(loc => {
                const title = document.getElementById(`title_${loc}_editar`)?.value ?? '';

                if (!title) {
                    valido = false;
                }

                formData.append(`title[${loc}]`,  title);
            });

            if (!valido) {
                toastr.error('Debes completar el título en todos los idiomas.');
                return;
            }

            openLoading();

            axios.post('/admin/producto/presentacion/editar', formData)
                .then((response) => {
                    closeLoading();

                    if (response.data.success === 1) {
                        toastr.success('Actualizado correctamente');
                        $('#modalEditar').modal('hide');
                        recargar();
                    }
                    else{
                        toastr.error('Error al actualizar');
                    }
                })
                .catch((error) => {
                    closeLoading();
                    toastr.error('Error al actualizar');
                });
        }







    </script>


@endsection
