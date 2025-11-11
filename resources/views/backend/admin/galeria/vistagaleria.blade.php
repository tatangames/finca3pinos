@extends('backend.menus.superior')

@section('content-admin-css')
    <link href="{{ asset('css/adminlte.min.css') }}" type="text/css" rel="stylesheet" />
    <link href="{{ asset('css/dataTables.bootstrap4.css') }}" type="text/css" rel="stylesheet" />
    <link href="{{ asset('css/toastr.min.css') }}" type="text/css" rel="stylesheet" />
    <link href="{{ asset('css/select2.min.css') }}" type="text/css" rel="stylesheet">
    <link href="{{ asset('css/select2-bootstrap-5-theme.min.css') }}" type="text/css" rel="stylesheet">
@stop
<meta name="csrf-token" content="{{ csrf_token() }}">

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
                                        <label>Key para todos los idiomas (NO ESPACIOS)</label>
                                        <input type="text" maxlength="300" id="key" class="form-control"
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

                                    <!-- Tipo de contenido -->
                                    <div class="form-group mt-4">
                                        <label>Tipo de contenido</label>
                                        <select id="tipo-contenido" class="form-control">
                                            <option value="0">Imagen</option>
                                            <option value="1">Video (URL)</option>
                                        </select>
                                    </div>

                                    <!-- Imagen -->
                                    <div class="form-group mt-3" id="grupo-imagen">
                                        <label>Imagen</label>
                                        <input type="file" id="imagen-nuevo" class="form-control" accept="image/jpeg,image/png">
                                    </div>

                                    <!-- URL Video -->
                                    <div class="form-group mt-3 d-none" id="grupo-video">
                                        <label>URL del video (YouTube, Vimeo, etc.)</label>
                                        <input type="url" id="url-video" maxlength="100" class="form-control" placeholder="https://www.youtube.com/watch?v=xxxxxx">
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


                                    <!-- Tipo de contenido -->
                                    <div class="form-group mt-4">
                                        <label>Tipo de contenido</label>
                                        <select id="tipo-contenido-editar" class="form-control">
                                            <option value="0">Imagen</option>
                                            <option value="1">Video (URL)</option>
                                        </select>
                                    </div>

                                    <!-- Grupo Imagen -->
                                    <div class="form-group" id="grupo-imagen-editar">
                                        <div>
                                            <label>Actualizar Imagen</label>
                                        </div>
                                        <br>
                                        <div class="col-md-10">
                                            <input type="file"
                                                   style="color:#191818"
                                                   id="imagen-editar"
                                                   accept="image/jpeg, image/jpg, image/png" />
                                        </div>
                                    </div>

                                    <!-- Grupo Video -->
                                    <div class="form-group mt-3 d-none" id="grupo-video-editar">
                                        <label>URL del video (YouTube, Vimeo, etc.)</label>
                                        <input type="url"
                                               id="url-video-editar"
                                               maxlength="200"
                                               class="form-control"
                                               placeholder="https://www.youtube.com/watch?v=xxxxxx">
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

</div>
@extends('backend.menus.footerjs')
@section('archivos-js')

    <script src="{{ asset('js/jquery-ui-drag.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/datatables-drag.min.js') }}" type="text/javascript"></script>

    <script src="{{ asset('js/toastr.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/axios.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/sweetalert2.all.min.js') }}"></script>
    <script src="{{ asset('js/alertaPersonalizada.js') }}"></script>



    <script>
        document.addEventListener('DOMContentLoaded', function () {

            var ruta = "{{ URL::to('/admin/galeria/index/tabla') }}";
            $('#tablaDatatable').load(ruta);

            document.getElementById("divcontenedor").style.display = "block";

            const selectTipo = document.getElementById('tipo-contenido');
            const grupoImagen = document.getElementById('grupo-imagen');
            const grupoVideo  = document.getElementById('grupo-video');

            if (selectTipo) {
                selectTipo.addEventListener('change', function () {
                    if (this.value === '1') {
                        // Mostrar campo de video
                        grupoVideo.classList.remove('d-none');
                        grupoImagen.classList.add('d-none');
                    } else {
                        // Mostrar campo de imagen
                        grupoImagen.classList.remove('d-none');
                        grupoVideo.classList.add('d-none');
                    }
                });
            }
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
            var selectTipo = document.getElementById('tipo-contenido').value;
            var urlVideo = document.getElementById('url-video').value;

            if (key === '') {
                toastr.error('Debe ingresar la key global');
                return;
            }


            if(selectTipo === '1'){ // video

                if(urlVideo === ''){
                    toastr.error('URL Video es Requerido')
                    return;
                }

            }else{ // imagen
                if(imagen.files && imagen.files[0]){ // si trae imagen
                    if (!imagen.files[0].type.match(/^image\/(jpeg|png)$/)) {
                        toastr.error('Formato de imagen permitido: .png .jpg .jpeg');
                        return;
                    }
                }else{
                    toastr.error('Imagen es Requerida')
                    return;
                }
            }

            // 🔹 Creamos el FormData
            let formData = new FormData();
            formData.append('key', key);
            formData.append('imagen', imagen.files[0]);
            formData.append('urlvideo', urlVideo);
            formData.append('tipo', selectTipo);

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

        function informacionEditar(id) {
            openLoading();
            document.getElementById("formulario-editar").reset();

            axios.post('/admin/galeria/informacion', { id })
                .then((response) => {
                    closeLoading();

                    if (response.data.success === 1) {
                        const { info, langs } = response.data;

                        $('#modalEditar').modal('show');
                        $('#id-editar').val(info.id);

                        // Referencias
                        const preview       = document.getElementById('preview-editar');
                        const tipoSelect    = document.getElementById('tipo-contenido-editar');
                        const grupoImagen   = document.getElementById('grupo-imagen-editar');
                        const grupoVideo    = document.getElementById('grupo-video-editar');
                        const urlVideoInput = document.getElementById('url-video-editar');
                        const inputFile     = document.getElementById('imagen-editar');

                        // Idiomas dinámicos
                        const cont = document.getElementById('langs-editar');
                        cont.innerHTML = langs.map(cardIdiomaHTML).join('');
                        cont.dataset.locales = langs.map(l => l.locale).join(',');

                        // Función para aplicar UI según tipo
                        function aplicarTipo(tipo) {
                            if (tipo == 1 || tipo === '1') {
                                // Video
                                tipoSelect.value = '1';

                                grupoVideo.classList.remove('d-none');
                                grupoImagen.classList.add('d-none');

                                // Mostrar URL video
                                urlVideoInput.value = info.urlvideo || '';

                                // Ocultar preview imagen
                                preview.style.display = 'none';

                                // Limpiar input file
                                if (inputFile) inputFile.value = '';
                            } else {
                                // Imagen
                                tipoSelect.value = '0';

                                grupoVideo.classList.add('d-none');
                                grupoImagen.classList.remove('d-none');

                                // Limpiar URL video
                                urlVideoInput.value = '';

                                if (info.imagen) {
                                    preview.src = '/storage/archivos/' + info.imagen;
                                    preview.style.display = 'block';
                                } else {
                                    preview.style.display = 'none';
                                }
                            }
                        }

                        // Aplicar según lo que viene de BD
                        aplicarTipo(info.tipo ?? 0);

                        // Cambiar en tiempo real si el usuario toca el select
                        tipoSelect.onchange = function (e) {
                            aplicarTipo(e.target.value);
                        };

                    } else {
                        toastr.error('Información no encontrada');
                    }
                })
                .catch(() => {
                    closeLoading();
                    toastr.error('Error al obtener la información');
                });
        }

        function editar() {
            const id       = document.getElementById('id-editar').value;
            const tipo     = document.getElementById('tipo-contenido-editar').value; // 0=imagen,1=video
            const urlVideo = document.getElementById('url-video-editar').value.trim();
            const imagen   = document.getElementById('imagen-editar');

            // Validaciones según tipo
            if (tipo === '1') {
                // Video
                if (!urlVideo) {
                    toastr.error('Debe ingresar la URL del video');
                    return;
                }
            } else {
                // Imagen
                if (imagen.files && imagen.files[0]) {
                    if (!imagen.files[0].type.match(/^image\/(jpeg|png)$/)) {
                        toastr.error('Formato de imagen permitido: .png .jpg .jpeg');
                        return;
                    }
                }
            }

            // Recolectar traducciones dinámicas
            const cont     = document.getElementById('langs-editar');
            const locales  = (cont.dataset.locales || '').split(',').filter(Boolean);

            const formData = new FormData();
            formData.append('id', id);
            formData.append('tipo', tipo);

            if (tipo === '1') {
                // Mandamos URL video; no mandamos imagen nueva
                formData.append('urlvideo', urlVideo);
            } else {
                // Imagen
                formData.append('urlvideo', ''); // limpiar si antes era video
                if (imagen.files && imagen.files[0]) {
                    formData.append('imagen', imagen.files[0]);
                }
            }

            // body[loc], altseo[loc]
            for (const loc of locales) {
                const b = document.getElementById(`body_${loc}_editar`)?.value.trim() ?? '';
                const s = document.getElementById(`altseo_${loc}_editar`)?.value.trim() ?? '';

                formData.append(`body[${loc}]`, b);

                if (!s) {
                    toastr.error(`ALT SEO es requerido para ${loc.toUpperCase()}`);
                    return;
                }

                formData.append(`altseo[${loc}]`, s);
            }

            openLoading();

            axios.post('/admin/galeria/editar', formData, {
                headers: { 'Content-Type': 'multipart/form-data' }
            })
                .then((response) => {
                    closeLoading();

                    if (response.data.success === 1) {
                        toastr.success('Actualizado correctamente');
                        $('#modalEditar').modal('hide');
                        recargar();
                    } else if (response.data.success === 3) {
                        toastr.error(response.data.message || 'Debe ingresar la URL del video');
                    } else {
                        toastr.error(response.data.message || 'Error al actualizar');
                    }
                })
                .catch(() => {
                    closeLoading();
                    toastr.error('Error al actualizar');
                });
        }










    </script>


@endsection
