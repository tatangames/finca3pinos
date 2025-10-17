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

<div class="card mb-3">
    <div class="card-body">
        <form id="form-crear-traducciones" onsubmit="event.preventDefault(); crearTraducciones();">
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Región</label>
                    <select id="region_slug" class="form-control" required>
                        @foreach($regiones as $r)
                            <option value="{{ $r->slug }}">
                                {{ $r->name }} ({{ $r->slug }}) – locale base: {{ $r->locale }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Nuevo idioma (locale)</label>
                    <input id="new_locale"
                           class="form-control"
                           placeholder="ej: pt o es-MX"
                           pattern="^[a-z]{2}(-[A-Z]{2})?$"
                           required>
                    <small class="text-muted">Formato: es, en, pt, es-MX, en-GB…</small>
                </div>

                <div class="col-md-4">
                    <label class="form-label d-block">Modo</label>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="mode" id="mode_copy" value="copy" checked>
                        <label class="form-check-label" for="mode_copy">Copiar desde ES</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="mode" id="mode_empty" value="empty">
                        <label class="form-check-label" for="mode_empty">Crear vacías</label>
                    </div>
                </div>

                <div class="col-12 mt-2">
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-language"></i> Crear traducciones
                    </button>
                </div>
            </div>
        </form>

        <hr>

        <div id="resultado" class="mt-2 text-muted" style="display:none;"></div>
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
        document.addEventListener('DOMContentLoaded', () => {

            document.getElementById("divcontenedor").style.display = "block";

        });
    </script>

    <script>

        // recarga tabla
        function recargar(){
            var ruta = "{{ URL::to('/admin/idiomas/index/tabla') }}";
            $('#tablaDatatable').load(ruta);
        }


        // abre modal para agregar nuevo pais
        function modalAgregar(){
            document.getElementById("formulario-nuevo").reset();
            $('#modalAgregar').modal('show');
        }

        function nuevo(){

        }




    </script>


@endsection
