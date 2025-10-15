@extends('backend.layouts.app')

@push('styles')
    {{-- estilos específicos opcionales --}}

@endpush

@section('content')
    <div class="container-fluid">
        <section class="content-header">
            <div class="container-fluid">
                <h1>Dashboard</h1>
            </div>
        </section>

        <div class="card">
            <div class="card-body">
                Bienvenido 👋
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    {{-- scripts específicos opcionales --}}
@endpush
