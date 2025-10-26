@extends('backend.menus.superior')

@section('content-admin-css')
    <link href="{{ asset('css/adminlte.min.css') }}" type="text/css" rel="stylesheet" />
    <link href="{{ asset('css/dataTables.bootstrap4.css') }}" type="text/css" rel="stylesheet" />
    <link href="{{ asset('css/toastr.min.css') }}" type="text/css" rel="stylesheet" />
    <link href="{{ asset('css/estiloToggle.css') }}" type="text/css" rel="stylesheet" />

@stop

<section class="content-header">
    <div class="container-fluid">
        <div class="col-sm-12">
            <h1>Listado de Departamentos</h1>
        </div>
        <button type="button" onclick="modalNuevo()" class="btn btn-primary btn-sm">
            <i class="fas fa-plus-square"></i>
            Nuevo Departamento
        </button>
    </div>
</section>

<section class="content">
    <div class="container-fluid">
        <div class="card card-gray-dark">
            <div class="card-header">
                <h3 class="card-title">Departamentos</h3>
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
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Nuevo Departamento</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formulario-nuevo">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">

                                <div class="form-group">
                                    <label>Nombre</label>
                                    <input type="text" maxlength="100" class="form-control" autocomplete="off" id="nombre-nuevo" placeholder="Nombre">
                                </div>

                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-success" onclick="guardarRegistro()">Guardar</button>
            </div>
        </div>
    </div>
</div>



<!-- modal editar -->
<div class="modal fade" id="modalEditar">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Información</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formulario-editar">
                    <div class="card-body">


                            <div class="form-group">
                                <input type="hidden" id="id-editar">
                            </div>

                            <div class="form-group">
                                <label>Nombre</label>
                                <input type="text" maxlength="100" class="form-control" autocomplete="off" id="nombre-editar" placeholder="Nombre">
                            </div>

                            <div class="form-group" style="margin-left:20px">
                                <label>Activo</label><br>
                                <label class="switch" style="margin-top:10px">
                                    <input type="checkbox" id="toggle-activo">
                                    <div class="slider round">
                                        <span class="on">Activar</span>
                                        <span class="off">Desactivar</span>
                                    </div>
                                </label>
                            </div>

                            <div class="form-group" style="margin-left:20px">
                                <label>Disponibilidad</label><br>
                                <label class="switch" style="margin-top:10px">
                                    <input type="checkbox" id="toggle-disponible">
                                    <div class="slider round">
                                        <span class="on">Disponible</span>
                                        <span class="off">No disponible</span>
                                    </div>
                                </label>
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

    <script src="{{ asset('js/jquery.dataTables.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/dataTables.bootstrap4.js') }}" type="text/javascript"></script>

    <script src="{{ asset('js/toastr.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/axios.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/sweetalert2.all.min.js') }}"></script>
    <script src="{{ asset('js/alertaPersonalizada.js') }}"></script>

    <script type="text/javascript">
        $(document).ready(function(){

            var idpais = {{ $idpais }};

            var ruta = "{{ URL::to('admin/departamentos/tabla') }}/" + idpais;
            $('#tablaDatatable').load(ruta);
        });
    </script>

    <script>

        function recargar(){
            var idpais = {{ $idpais }};
            var ruta = "{{ URL::to('admin/departamentos/tabla') }}/" + idpais;
            $('#tablaDatatable').load(ruta);
        }

        function modalNuevo(){
            document.getElementById("formulario-nuevo").reset();
            $('#modalAgregar').modal('show');
        }

        function guardarRegistro(){
            var nombre = document.getElementById('nombre-nuevo').value;

            if(nombre === '') {
                toastr.error('Nombre es requerido');
                return;
            }

            var idpais = {{ $idpais }};

            openLoading();

            var formData = new FormData();
            formData.append('nombre', nombre);
            formData.append('idpais', idpais);

            axios.post('/admin/departamentos/nuevo', formData, {
            })
                .then((response) => {
                    closeLoading();
                    if (response.data.success === 1) {
                        $('#modalAgregar').modal('hide');
                        toastr.success('Registrado correctamente');
                        recargar();
                    }
                    else {
                        toastr.error('Error al guardar');
                    }
                })
                .catch((error) => {
                    closeLoading();
                    toastr.error('Error al guardar');
                });
        }


        function verInformacion(id){

            document.getElementById("formulario-editar").reset();
            openLoading();

            axios.post('/admin/departamentos/informacion',{
                'id': id
            })
                .then((response) => {
                    closeLoading();
                    if(response.data.success === 1){

                        $('#modalEditar').modal('show');
                        $('#id-editar').val(id);

                        var info = response.data.info

                        $('#nombre-editar').val(info.nombre);

                        if(info.activo === 0){
                            $("#toggle-activo").prop("checked", false);
                        }else{
                            $("#toggle-activo").prop("checked", true);
                        }

                        if(info.disponible === 0){
                            $("#toggle-disponible").prop("checked", false);
                        }else{
                            $("#toggle-disponible").prop("checked", true);
                        }

                    }else{
                        toastr.error('Información no encontrada');
                    }
                })
                .catch((error) => {
                    closeLoading();
                    toastr.error('Error del servidor');
                });
        }


        // editar
        function editar() {
            var id = document.getElementById('id-editar').value;
            var nombre = document.getElementById('nombre-editar').value;
            var cbactivo = document.getElementById('toggle-activo').checked;
            var cbdisponible = document.getElementById('toggle-disponible').checked;
            var toggleActivo = cbactivo ? 1 : 0;
            var toggleDisponible = cbdisponible ? 1 : 0;



            if(nombre === '') {
                toastr.error('Nombre es requerido');
                return;
            }

            let formData = new FormData();
            formData.append('id', id);
            formData.append('nombre', nombre);
            formData.append('activo', toggleActivo);
            formData.append('disponible', toggleDisponible);

            openLoading();

            axios.post('/admin/departamentos/editar', formData, {
            })
                .then((response) => {
                    closeLoading()

                    if (response.data.success === 1) {
                        toastr.success('Actualizado');
                        $('#modalEditar').modal('hide');
                        recargar();
                    }
                    else {
                        toastr.error('Error al guardar');
                    }
                })
                .catch((error) => {
                    closeLoading();
                    toastr.error('Error del servidor');
                });
        }


        function vistaMunicipios(id){
            window.location.href="{{ url('/admin/municipios/index') }}/"+id;
        }



    </script>


@endsection
