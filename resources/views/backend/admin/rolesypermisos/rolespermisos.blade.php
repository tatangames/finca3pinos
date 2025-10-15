@extends('backend.layouts.app')
@section('title','Lista de Permisos')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/dataTables.bootstrap4.css') }}">
    <link rel="stylesheet" href="{{ asset('css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/select2-bootstrap-5-theme.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/buttons_estilo.css') }}">
    <style> table{table-layout:fixed;} </style>
@endpush

@section('content')
    <div class="container-fluid">
        <section class="content-header">
            <h1>Lista de Permisos</h1>
            <button type="button" class="btn btn-success btn-sm font-weight-bold">
                <i class="fas fa-pencil-alt"></i> Agregar Permiso
            </button>
        </section>

        <div class="card">
            <div class="card-body">
                <table id="permisos" class="table table-striped w-100">
                    {{-- tu thead/tbody --}}
                </table>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('js/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('js/dataTables.bootstrap4.js') }}"></script>
    <script src="{{ asset('js/toastr.min.js') }}"></script>
    <script src="{{ asset('js/axios.min.js') }}"></script>
    <script src="{{ asset('js/sweetalert2.all.min.js') }}"></script>
    <script src="{{ asset('js/select2.min.js') }}"></script>

    <script>
        function initPermisos() {
            $('#permisos').DataTable({
                // opciones…
            });
            $('.select2').select2({theme:'bootstrap-5'});
        }
        document.addEventListener('DOMContentLoaded', initPermisos);
        document.addEventListener('htmx:afterSettle', function (e) {
            if (e.target.id === 'content') initPermisos(); // re-inicializa tras navegación parcial
        });
    </script>
@endpush
