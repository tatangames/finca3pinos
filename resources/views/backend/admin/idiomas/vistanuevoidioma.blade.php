@extends('backend.menus.superior')

@section('content-admin-css')
    <link href="{{ asset('css/adminlte.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/dataTables.bootstrap4.css') }}" rel="stylesheet">
    <link href="{{ asset('css/toastr.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/select2.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/select2-bootstrap-5-theme.min.css') }}" rel="stylesheet">
@stop

<style>
    .text-gold { color: #D2AA6D; }
    .card-body label { font-weight: 600; color: #555; }
    .border { border-color: #ddd !important; }
</style>

<div class="card mb-3">
    <div class="card-body">
        <form class="mb-3" method="GET" action="{{ route('admin.idiomas') }}">
            <label class="fw-bold me-2">Elegir región destino:</label>
            <select name="region_id" class="form-select d-inline-block" style="width:280px" onchange="this.form.submit()">
                @foreach($regiones as $r)
                    <option value="{{ $r->id }}" {{ $regionSeleccionada==$r->id ? 'selected':'' }}>
                        {{ $r->name }} ({{ $r->locale }}) — slug: {{ $r->slug }}
                    </option>
                @endforeach
            </select>
        </form>

        <h4 class="mb-3">Traducir a: <strong>{{ strtoupper($nuevoLocale) }}</strong></h4>

        <form id="form-crear-traducciones">
            @csrf
            <input type="hidden" name="locale" value="{{ $nuevoLocale }}">

            @forelse($faltantes as $row)
                <div class="mb-3 p-3 border rounded bg-light">
                    <div class="mb-2">
                        <span class="badge bg-dark">Key: {{ $row['key'] }}</span>
                    </div>

                    <label>Texto base (SV)</label>
                    <div class="form-control mb-2" style="background:#f7f7f7">{{ $row['sv_body'] ?: '—' }}</div>

                    <label>Traducción al {{ $regionNueva->name }}</label>
                    <textarea
                        name="traduccion[{{ $row['target_content_id'] }}][body]"
                        class="form-control"
                        rows="3"
                        placeholder="Escribe la traducción en {{ $regionNueva->name }} ({{ strtoupper($nuevoLocale) }})..."
                    ></textarea>

                    <input type="hidden"
                           name="traduccion[{{ $row['target_content_id'] }}][locale]"
                           value="{{ $nuevoLocale }}">
                </div>
            @empty
                <div class="alert alert-success mb-0">
                    No hay claves pendientes por traducir para <b>{{ $regionNueva->name }}</b>.
                </div>
            @endforelse

            @if(count($faltantes))
                <button type="submit" class="btn btn-dark mt-3">Guardar traducciones</button>
            @endif
        </form>
    </div>
</div>

@extends('backend.menus.footerjs')
@section('archivos-js')
    <script src="{{ asset('js/toastr.min.js') }}"></script>
    <script src="{{ asset('js/axios.min.js') }}"></script>
    <script src="{{ asset('js/sweetalert2.all.min.js') }}"></script>
    <script src="{{ asset('js/alertaPersonalizada.js') }}"></script>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const form = document.getElementById('form-crear-traducciones');

            form?.addEventListener('submit', async (e) => {
                e.preventDefault();

                const formData = new FormData(form);
                const url = "{{ route('admin.idiomas.guardar') }}";

                try {
                    const res = await axios.post(url, formData);
                    const data = res.data;

                    if (data.success === 1) {
                        toastr.success('Traducciones guardadas correctamente');
                        Swal.fire({
                            icon: 'success',
                            title: 'Guardado',
                            html: `
                            <b>${data.creados}</b> nuevas<br>
                            <b>${data.actualizados}</b> actualizadas<br>
                            <b>${data.omitidos}</b> omitidas
                        `,
                            timer: 2200,
                            showConfirmButton: false
                        });
                        form.querySelectorAll('textarea').forEach(t => t.value = '');
                        // Opcional: recargar la página para que desaparezcan los "faltantes"
                        // location.reload();
                    } else if (data.success === 0) {
                        toastr.error('Datos inválidos, revisa los campos');
                    } else {
                        toastr.error(data.msg || 'Error al guardar');
                    }
                } catch (err) {
                    console.error(err);
                    toastr.error('Error inesperado en el servidor');
                }
            });
        });
    </script>
@endsection
