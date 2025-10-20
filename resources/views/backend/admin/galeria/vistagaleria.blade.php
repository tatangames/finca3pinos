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
                    Nueva Imagen
                </button>
            </div>

            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item">Galería</li>
                    <li class="breadcrumb-item active">Listado de Imagenes</li>
                </ol>
            </div>

        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="card card-gray-dark">
                <div class="card-header">
                    <h3 class="card-title">Galería</h3>
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


                                                    <label>ALT SEO para imagen ({{ $region->locale }})</label>
                                                    <input name="altseo_{{ $region->locale }}"
                                                           id="altseo_{{ $region->locale }}"
                                                           class="form-control"
                                                           maxlength="300"
                                                           placeholder="ALT SEO">

                                                    <br>
                                                    <label>Descripción ({{ $region->locale }})</label>
                                                    <textarea name="body_{{ $region->locale }}"
                                                              id="body_{{ $region->locale }}"
                                                              rows="4"
                                                              class="form-control"
                                                              placeholder="<p>Texto o HTML...</p>"></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach

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

                                <input type="hidden" id="id-editar">
                                <!-- Campos dinámicos por idioma -->
                                <div id="langs-editar"></div>

                                <br>
                                <hr>

                                <!-- 👇 Vista previa centrada -->
                                <div class="mt-3"
                                     style="
                                    display:flex !important;
                                    flex-direction:column !important;
                                    justify-content:center !important;
                                    align-items:center !important;
                                    text-align:center !important;
                                    width:100% !important;
                                 ">

                                    <h6 style="
                                    font-weight:600 !important;
                                    color:#333 !important;
                                    margin-bottom:10px !important;
                                ">
                                        Vista previa de la imagen actual
                                    </h6>

                                    <img id="preview-editar"
                                         src=""
                                         alt="Vista previa"
                                         style="
                                        width: 190px !important;
                                        height: 176px !important;
                                        border-radius:12px !important;
                                        object-fit:cover;
                                        display:block !important;
                                        border:1px solid #ccc !important;
                                        margin:0 auto;
                                        box-shadow:0 2px 6px rgba(0,0,0,.2) !important;
                                     ">
                                </div>

                                <br>

                                <div class="form-group">
                                    <div>
                                        <label>Actualizar Imagen</label>
                                    </div>
                                    <br>
                                    <div class="col-md-10">
                                        <input type="file" style="color:#191818" id="imagen-editar" accept="image/jpeg, image/jpg, image/png"/>
                                    </div>
                                </div>

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

            var ruta = "{{ URL::to('/admin/galeria/index/tabla') }}";
            $('#tablaDatatable').load(ruta);

            document.getElementById("divcontenedor").style.display = "block";
        });
    </script>

    <script>

        // recarga tabla
        function recargar(){
            var ruta = "{{ URL::to('/admin/galeria/index/tabla') }}";
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


            // 🔹 Creamos el FormData
            let formData = new FormData();
            formData.append('key', key);
            formData.append('imagen', imagen.files[0]);

            // 🔹 Recorremos las regiones en un array JSON generado por Blade
            let regiones = @json($arrayRegiones);

            const error = regiones.some(region => {
                let locale = region.locale;

                let body = document.getElementById(`body_${locale}`).value.trim();
                let altseo = document.getElementById(`altseo_${locale}`).value.trim();
                if (!altseo) {
                    toastr.error('ALT SEO es requerido');
                    return true; // detiene el ciclo
                }

                formData.append(`translations[${locale}][body]`, body);
                formData.append(`translations[${locale}][altseo]`, altseo);
                return false;
            });

            if (error) return;

            openLoading()

            axios.post('/admin/galeria/nuevo', formData, {
                headers: { 'Content-Type': 'multipart/form-data' }
            })
                .then((response) => {
                    closeLoading();

                    if(response.data.success === 1) {
                        toastr.error('KEY repetida');
                    }else if(response.data.success === 2){
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

            axios.post('/admin/galeria/borrar',{
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

            axios.post('/admin/galeria/desactivar',{
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

            axios.post('/admin/galeria/activar',{
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

        function cardIdiomaHTML(item) {
            // item = { name, locale, body, altseo }
            const loc = item.locale;
            return `
      <div class="card mt-3 border">
        <div class="card-header" style="background:#f4f4f4;">
          <strong>${item.name}</strong> (${loc.toUpperCase()})
        </div>
        <div class="card-body">
          <div class="form-group">

            <label>ALT SEO (${loc})</label>
            <input id="altseo_${loc}_editar"
                      class="form-control"
                      value="${item.altseo || ''}"
                      maxlength="300"
                      placeholder="ALT SEO">

            <br>

            <label>Contenido (${loc})</label>
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

            axios.post('/admin/galeria/informacion', { id })
                .then((response) => {
                    closeLoading();
                    if (response.data.success === 1) {
                        const { info, langs } = response.data;

                        $('#modalEditar').modal('show');
                        $('#id-editar').val(info.id);

                        // ✅ Imagen
                        const imagenUrl = '/storage/archivos/' + info.imagen;
                        const preview = document.getElementById('preview-editar');
                        preview.src = imagenUrl;
                        preview.style.display = 'block';

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
            const imagen = document.getElementById('imagen-editar');

            // Validación de imagen (si hay)
            if (imagen.files && imagen.files[0]) {
                if (!imagen.files[0].type.match(/^image\/(jpeg|png)$/)) {
                    toastr.error('Formato de imagen permitido: .png .jpg .jpeg');
                    return;
                }
            }

            // Recolectar traducciones dinámicas
            const cont = document.getElementById('langs-editar');
            const locales = (cont.dataset.locales || '').split(',').filter(Boolean);

            const formData = new FormData();
            formData.append('id', id);

            if (imagen.files && imagen.files[0]) {
                formData.append('imagen', imagen.files[0]);
            }

            // Enviar como arrays: body[en], title[sv], body[sv], ...
            for (const loc of locales) {
                const b = document.getElementById(`body_${loc}_editar`)?.value.trim() ?? '';
                const s = document.getElementById(`altseo_${loc}_editar`)?.value.trim() ?? '';

                formData.append(`body[${loc}]`, b);

                if (!s) {
                    toastr.error(`ALT SEO es requerido para ${loc.toUpperCase()}`);
                    return; // 🔴 corta toda la función aquí
                }

                formData.append(`altseo[${loc}]`, s);
            }

            openLoading();

            axios.post('/admin/galeria/editar', formData)
                .then((response) => {
                    closeLoading();

                    if (response.data.success === 1) {
                        toastr.success('Actualizado correctamente');
                        $('#modalEditar').modal('hide');
                        recargar();
                        return;
                    }else{
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
