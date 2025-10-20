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
                    Nuevo Producto
                </button>
            </div>

            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item">Producto</li>
                    <li class="breadcrumb-item active">Listado de Productos</li>
                </ol>
            </div>

        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="card card-gray-dark">
                <div class="card-header">
                    <h3 class="card-title">Producto</h3>
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
                                        <label>Key para todos los idiomas</label>
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
                                                    <label>SLUG (RUTA WEB - no debe llevar espacios)</label>

                                                    <input name="slug_{{ $region->locale }}"
                                                              id="slug_{{ $region->locale }}"
                                                              class="form-control"
                                                              maxlength="300"
                                                              required
                                                              placeholder="Ruta WEB (Slug. No debe llevar espacios o simbolos)">
                                                    <br>
                                                    <p>Título del Producto</p>
                                                    <textarea name="title_{{ $region->locale }}"
                                                              id="title_{{ $region->locale }}"
                                                              rows="4"
                                                              class="form-control"
                                                              required
                                                              placeholder="<p>Texto o HTML...</p>"></textarea>
                                                    <br>
                                                    <p>Descripción del Producto</p>
                                                    <textarea name="body_{{ $region->locale }}"
                                                              id="body_{{ $region->locale }}"
                                                              rows="4"
                                                              class="form-control"
                                                              placeholder="<p>Texto o HTML...</p>"></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach

                                    <br>
                                    <hr>

                                    <!-- Imagen -->
                                    <div class="form-group mt-4">
                                        <label>Imagen</label>
                                        <input type="file" id="imagen-nuevo" class="form-control"
                                               accept="image/jpeg,image/png">
                                    </div>

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

            var idcategoria = {{ $idcategoria }};
            var ruta = "{{ URL::to('/admin/producto/index/tabla') }}/" + idcategoria;
            $('#tablaDatatable').load(ruta);

            document.getElementById("divcontenedor").style.display = "block";
        });
    </script>

    <script>

        // recarga tabla
        function recargar(){
            var idcategoria = {{ $idcategoria }};
            var ruta = "{{ URL::to('/admin/producto/index/tabla') }}/" + idcategoria;
            $('#tablaDatatable').load(ruta);
        }

        // abre modal para agregar nuevo pais
        function modalAgregar(){
            document.getElementById("formulario-nuevo").reset();
            $('#modalAgregar').modal('show');
        }

        function nuevo(){
            var key = document.getElementById('key').value.trim();
            var imagen = document.getElementById('imagen-nuevo');

            if (key === '') {
                toastr.error('Debe ingresar la key global');
                return;
            }

            if(imagen.files && imagen.files[0]){ // si trae imagen
                if (!imagen.files[0].type.match(/^image\/(jpeg|png)$/)) {
                    toastr.error('Formato de imagen permitido: .png .jpg .jpeg');
                    return;
                }
            }else{
                toastr.error('Imagen es Requerida')
                return;
            }
            var idcategoria = {{ $idcategoria }};

            // 🔹 Creamos el FormData
            let formData = new FormData();
            formData.append('idcategoria', idcategoria);
            formData.append('key', key);
            formData.append('imagen', imagen.files[0]);

            // 🔹 Recorremos las regiones en un array JSON generado por Blade
            let regiones = @json($arrayRegiones);

            const error = regiones.some(region => {
                let locale = region.locale;
                let slug = document.getElementById(`slug_${locale}`).value.trim().replace(/\s+/g, '');
                let title = document.getElementById(`title_${locale}`).value.trim();
                let body = document.getElementById(`body_${locale}`).value.trim();

                if (!slug) {
                    toastr.error(`SLUG es requerido para ${locale.toUpperCase()}`);
                    return true; // detiene el ciclo
                }

                if (!title) {
                    toastr.error(`Título es requerido para ${locale.toUpperCase()}`);
                    return true; // detiene el ciclo
                }

                formData.append(`translations[${locale}][slug]`, slug);
                formData.append(`translations[${locale}][body]`, body);
                formData.append(`translations[${locale}][title]`, title);
                return false;
            });


            if (error) return;

            openLoading()

            axios.post('/admin/producto/nuevo', formData, {
            })
                .then((response) => {
                    closeLoading();

                    console.log(response);

                    if(response.data.success === 1){
                        // KEY repetida
                        toastr.error(response.data.message);
                    }
                    else if(response.data.success === 2){
                        // SLUG ya existe
                        toastr.error(response.data.message);
                    }
                    else if(response.data.success === 3){
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


        function modalBorrar(idfila){
            Swal.fire({
                title: '¿Borrar?',
                text: "",
                icon: 'info',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#d33',
                cancelButtonText: 'Cancelar',
                confirmButtonText: 'Si'
            }).then((result) => {
                if (result.isConfirmed) {
                    solicitarBorrar(idfila);
                }
            })
        }

        function solicitarBorrar(idfila){

            openLoading();

            axios.post('/admin/producto/borrar',{
                'id': idfila
            })
                .then((response) => {
                    closeLoading();
                    if(response.data.success === 1){
                        toastr.success('Fila Borrada');
                        recargar();
                    }else{
                        toastr.error('Error al borrar');
                    }
                })
                .catch((error) => {
                    toastr.error('Error al borrar');
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

            axios.post('/admin/producto/desactivar',{
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

            axios.post('/admin/producto/activar',{
                'id': idfila
            })
                .then((response) => {
                    closeLoading();
                    if(response.data.success === 1){
                        toastr.success('Actualizado');
                        recargar();
                    }else if(response.data.success === 2){
                        toastr.error('Presentación es requerido para Activar');
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


                     <label>SLUG (${loc})</label>
                            <input id="slug_${loc}_editar"
                              maxlength="300"
                              class="form-control"
                              value="${item.slug || ''}">

                    <label>Título (${loc})</label>
                    <textarea id="title_${loc}_editar"
                              rows="4"
                              class="form-control"
                              placeholder="<p>Texto o HTML...</p>">${item.title || ''}</textarea>


                    <label>Descripción (${loc})</label>
                    <textarea id="body_${loc}_editar"
                              rows="4"
                              class="form-control"
                              placeholder="<p>Texto o HTML...</p>">${item.body || ''}</textarea>

                  </div>
                </div>
              </div>
    `;
        }

        function informacionEditar(id){
            openLoading();
            document.getElementById("formulario-editar").reset();

            axios.post('/admin/producto/informacion', { id })
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
                const body = document.getElementById(`body_${loc}_editar`)?.value ?? '';
                const slug = document.getElementById(`slug_${loc}_editar`)?.value ?? '';

                if (!title || !slug) {
                    valido = false;
                }

                formData.append(`title[${loc}]`,  title);
                formData.append(`body[${loc}]`,  body);
                formData.append(`slug[${loc}]`,  slug);
            });

            if (!valido) {
                toastr.error('Debes completar el título y slug en todos los idiomas.');
                return;
            }

            openLoading();

            axios.post('/admin/producto/editar', formData)
                .then((response) => {
                    closeLoading();

                    if (response.data.success === 1) {
                        toastr.success('Actualizado correctamente');
                        $('#modalEditar').modal('hide');
                        recargar();
                    }
                    else if (response.data.success === 3 || response.data.success === 4
                       ) {
                        toastr.error(response.data.message);
                        return
                    }
                    else if (response.data.success === 10) {
                        toastr.error(response.data.message);
                        return
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


        function vistaPresentacion(idproducto){
            window.location.href="{{ url('/admin/producto/presentacion') }}/" + idproducto;
        }





    </script>


@endsection
