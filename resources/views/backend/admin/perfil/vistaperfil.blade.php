@extends('backend.menus.superior')

@section('content-admin-css')
    <link href="{{ asset('css/adminlte.min.css') }}" type="text/css" rel="stylesheet" />
    <link href="{{ asset('css/dataTables.bootstrap4.css') }}" type="text/css" rel="stylesheet" />
    <link href="{{ asset('css/toastr.min.css') }}" type="text/css" rel="stylesheet" />

    <style>
        :root{
            --brand:#D2AA6D;
            --brand-600:#b98a3b;
            --ink:#111827;
            --bg1:#0f0f0f;
            --bg2:#2a2a2a;
        }

        /* Fondo general del contenido */
        section.content {
            background: linear-gradient(135deg, var(--bg1) 0%, var(--bg2) 60%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            padding-top: 60px; /* mueve el card más arriba */
        }

        .content-header h1{
            font-weight:800;
            color:var(--brand);
            letter-spacing:.3px;
        }

        /* Card principal */
        .card.card-green{
            border:1px solid rgba(0,0,0,.06);
            border-top:4px solid var(--brand);
            border-radius:18px;
            overflow:hidden;
            box-shadow:0 10px 24px rgba(0,0,0,.25);
            background:#fff;
            width:100%;
            max-width:520px;
            animation: fadeInUp .5s ease;
        }

        @keyframes fadeInUp {
            from { opacity:0; transform: translateY(40px); }
            to { opacity:1; transform: translateY(0); }
        }

        .card-green .card-header{
            background:linear-gradient(135deg, #111 0%, #2b2b2b 60%);
            color:#fff;
            padding:18px 20px;
            text-align:center;
        }

        .card-green .card-title{
            font-weight:700;
            margin:0;
            letter-spacing:.5px;
        }

        .input-group-text{
            background:var(--brand);
            color:#fff;
            border:none;
        }

        .form-control:focus{
            border-color:var(--brand);
            box-shadow:0 0 0 .2rem rgba(210,170,109,.25);
        }

        .toggle-pass{
            cursor:pointer;
            background:#f3f4f6;
            color:#374151;
            border:none;
        }
        .toggle-pass:hover{
            background:#e5e7eb;
        }

        .card-footer{
            background:transparent;
            border-top:none;
            padding:16px 20px 20px;
        }

        .btn-success,
        .btn-success:hover,
        .btn-success:focus,
        .btn-success:active,
        .btn-success:disabled {
            background: var(--brand) !important;
            color: #fff !important;
            border: none !important;
            box-shadow: 0 10px 18px rgba(210,170,109,.22) !important;
            outline: none !important;
        }

        /* Evitar cambio por Bootstrap cuando se hace clic */
        .btn-success:not(:disabled):not(.disabled):active {
            background: var(--brand) !important;
            transform: scale(0.98);
        }

        .field-hint{
            font-size:12px;
            color:#6b7280;
            margin-top:6px;
        }

        /* Ajuste responsivo */
        @media (max-width: 576px){
            section.content { padding-top: 40px; }
            .card.card-green { margin: 0 12px; }
        }
    </style>

@stop

<section class="content">
    <div class="container-fluid d-flex justify-content-center align-items-start"
         style="min-height: calc(100vh - 120px); margin-top: 40px;">
        <div class="row justify-content-center w-100">
            <div class="col-md-7 col-lg-5">
                <div class="card card-green mx-auto">
                    <div class="card-header text-center">
                        <h3 class="card-title">Actualizar Perfil Administrador</h3>
                    </div>

                    <form onsubmit="event.preventDefault(); actualizar();">
                        <div class="card-body">

                            {{-- Correo --}}
                            <div class="form-group">
                                <label for="correo">Correo Electrónico</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                    </div>
                                    <input
                                        type="email"
                                        maxlength="100"
                                        id="correo"
                                        class="form-control"
                                        value="{{ $usuario->email }}"
                                        placeholder="tucorreo@dominio.com"
                                        autocomplete="email"
                                        required>
                                </div>
                                <div class="field-hint">Usa un correo al que tengas acceso.</div>
                            </div>

                            {{-- Nueva contraseña --}}
                            <div class="form-group">
                                <label for="password">Nueva Contraseña</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                    </div>
                                    <input
                                        type="password"
                                        maxlength="16"
                                        class="form-control"
                                        id="password"
                                        placeholder="Mínimo 4 y máximo 25 caracteres"
                                        autocomplete="new-password">
                                    <div class="input-group-append">
                                        <button class="input-group-text toggle-pass" type="button" data-toggle-pass="#password" title="Mostrar/Ocultar">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="field-hint">Déjalo vacío si no deseas cambiarla.</div>
                            </div>

                            {{-- Repetir contraseña --}}
                            <div class="form-group">
                                <label for="password1">Repetir Contraseña</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                    </div>
                                    <input
                                        type="password"
                                        maxlength="16"
                                        class="form-control"
                                        id="password1"
                                        placeholder="Repite la nueva contraseña"
                                        autocomplete="new-password">
                                    <div class="input-group-append">
                                        <button class="input-group-text toggle-pass" type="button" data-toggle-pass="#password1" title="Mostrar/Ocultar">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="card-footer d-flex justify-content-end">
                            <button type="button" class="btn btn-success" onclick="actualizar()">
                                <i class="fas fa-save mr-1"></i> Actualizar
                            </button>
                        </div>
                    </form>
                </div> <!-- /.card -->
            </div>
        </div>
    </div>
</section>


@extends('backend.menus.footerjs')
@section('archivos-js')

    <script src="{{ asset('js/jquery.dataTables.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/dataTables.bootstrap4.js') }}" type="text/javascript"></script>

    <script src="{{ asset('js/toastr.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/axios.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/sweetalert2.all.min.js') }}"></script>
    <script src="{{ asset('js/alertaPersonalizada.js') }}"></script>

    <script>
        // Mostrar / Ocultar contraseña (no toca tu función actualizar)
        document.addEventListener('click', function(e){
            const btn = e.target.closest('[data-toggle-pass]');
            if(!btn) return;
            const target = document.querySelector(btn.getAttribute('data-toggle-pass'));
            if(!target) return;
            target.type = (target.type === 'password') ? 'text' : 'password';
            const icon = btn.querySelector('i');
            icon.classList.toggle('fa-eye');
            icon.classList.toggle('fa-eye-slash');
        });

        // (Tu JS original queda intacto a continuación)

        function abrirModalAgregar(){
            document.getElementById("formulario-nuevo").reset();
            $('#modalAgregar').modal('show');
        }

        function actualizar(){
            var correo = document.getElementById('correo').value;
            var passwordNueva = document.getElementById('password').value;
            var passwordRepetida = document.getElementById('password1').value;

            if(correo === ''){
                toastr.error('Correo es requerido');
                return;
            }

            if(correo.length > 100){
                toastr.error('Correo máximo 100 caracteres');
                return;
            }

            // 🟡 NUEVO: Validar formato de correo electrónico
            var patronCorreo = /^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/;
            if (!patronCorreo.test(correo)) {
                toastr.error('Debe ingresar un correo válido');
                return;
            }

            var actualizarPass = 0;

            if(passwordNueva.length > 0 || passwordRepetida.length > 0){
                if(passwordNueva === ''){
                    toastr.error('Contraseña nueva es requerida');
                    return;
                }

                if(passwordRepetida === ''){
                    toastr.error('Contraseña repetida es requerida');
                    return;
                }

                if(passwordNueva.length > 25){
                    toastr.error('Máximo 25 caracteres para contraseña nueva');
                    return;
                }

                if(passwordNueva.length < 8){
                    toastr.error('Mínimo 8 caracteres para contraseña nueva');
                    return;
                }

                if(passwordRepetida.length > 25){
                    toastr.error('Máximo 25 caracteres para contraseña repetida');
                    return;
                }

                if(passwordRepetida.length < 8){
                    toastr.error('Mínimo 8 caracteres para contraseña repetida');
                    return;
                }

                if(passwordNueva !== passwordRepetida){
                    toastr.error('Las contraseñas no coinciden');
                    return;
                }

                actualizarPass = 1;
            }

            openLoading()
            var formData = new FormData();
            formData.append('password', passwordNueva);
            formData.append('correo', correo);
            formData.append('actualizarpass', actualizarPass);

            axios.post('/admin/perfil/actualizar/todo', formData, {})
                .then((response) => {
                    closeLoading()

                    if (response.data.success === 1) {
                        toastr.success('Contraseña Actualizada');
                        $('#modalEditar').modal('hide');
                        document.getElementById('password').value = '';
                        document.getElementById('password1').value = '';
                    }
                    else {
                        toastr.error('error al actualizar');
                    }
                })
                .catch((error) => {
                    closeLoading();
                    toastr.error('error al actualizar');
                });
        }
    </script>



@stop
