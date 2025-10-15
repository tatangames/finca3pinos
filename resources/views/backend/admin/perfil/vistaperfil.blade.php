@extends('backend.layouts.app')
@section('body-class','page-vistaperfil')

@push('styles')
    <style id="page-vistaperfil-css">

        /* ===========================================================
           ESTILOS ESPECÍFICOS PARA LA VISTA PERFIL ADMINISTRADOR
           =========================================================== */

        /* Fondo gris claro para esta página */
        .page-vistaperfil .content-wrapper {
            background: #f2f2f2 !important;
        }

        /* Quita el padding del contenido principal */
        .page-vistaperfil .content-wrapper > .content {
            padding: 0 !important;
        }

        /* Contenedor principal */
        #perfil-page {
            background: #ffffff;
            min-height: 100vh;
            width: 100%;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            padding: 40px 24px;
        }

        /* Card principal */
        #perfil-page .card.card-green {
            border: 1px solid rgba(0, 0, 0, .06);
            border-top: 4px solid var(--brand, #D2AA6D);
            border-radius: 18px;
            overflow: hidden;
            box-shadow: 0 10px 24px rgba(0, 0, 0, .25);
            background: #fff;
            animation: fadeInUp .5s ease;
        }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(40px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        #perfil-page .card-green .card-header {
            background: linear-gradient(135deg, #111 0%, #2b2b2b 60%);
            color: #fff;
            padding: 18px 20px;
            text-align: center;
        }

        #perfil-page .card-green .card-title {
            font-weight: 700;
            margin: 0;
            letter-spacing: .5px;
        }

        /* Inputs */
        #perfil-page .input-group-text {
            background: var(--brand, #D2AA6D);
            color: #fff;
            border: none;
        }

        #perfil-page .form-control:focus {
            border-color: var(--brand, #D2AA6D);
            box-shadow: 0 0 0 .2rem rgba(210, 170, 109, .25);
        }

        #perfil-page .toggle-pass {
            cursor: pointer;
            background: #f3f4f6;
            color: #374151;
            border: none;
        }

        #perfil-page .toggle-pass:hover {
            background: #e5e7eb;
        }

        #perfil-page .field-hint {
            font-size: 0.85rem;
            color: #6b7280;
            margin-top: 4px;
        }

        /* Botón principal */
        #perfil-page .btn-brand,
        #perfil-page .btn-brand:hover,
        #perfil-page .btn-brand:focus,
        #perfil-page .btn-brand:active,
        #perfil-page .btn-brand:disabled {
            background: var(--brand, #D2AA6D) !important;
            color: #fff !important;
            border: none !important;
            box-shadow: 0 10px 18px rgba(210, 170, 109, .22) !important;
            outline: none !important;
        }

        #perfil-page .btn-brand:not(:disabled):active {
            transform: scale(0.98);
        }

        @media (max-width: 576px) {
            #perfil-page { padding-top: 40px; }
            #perfil-page .card.card-green { margin: 0 12px; }
        }

    </style>
@endpush

{{-- 👇 Este mismo CSS también como OOB para navegación HTMX --}}
<style id="page-vistaperfil-css" hx-swap-oob="true">
    /* … EL MISMO CSS … */

    /* ===========================================================
           ESTILOS ESPECÍFICOS PARA LA VISTA PERFIL ADMINISTRADOR
           =========================================================== */

    /* Fondo gris claro para esta página */
    .page-vistaperfil .content-wrapper {
        background: #f2f2f2 !important;
    }

    /* Quita el padding del contenido principal */
    .page-vistaperfil .content-wrapper > .content {
        padding: 0 !important;
    }

    /* Contenedor principal */
    #perfil-page {
        background: #ffffff;
        min-height: 100vh;
        width: 100%;
        display: flex;
        justify-content: center;
        align-items: flex-start;
        padding: 40px 24px;
    }

    /* Card principal */
    #perfil-page .card.card-green {
        border: 1px solid rgba(0, 0, 0, .06);
        border-top: 4px solid var(--brand, #D2AA6D);
        border-radius: 18px;
        overflow: hidden;
        box-shadow: 0 10px 24px rgba(0, 0, 0, .25);
        background: #fff;
        animation: fadeInUp .5s ease;
    }

    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(40px); }
        to   { opacity: 1; transform: translateY(0); }
    }

    #perfil-page .card-green .card-header {
        background: linear-gradient(135deg, #111 0%, #2b2b2b 60%);
        color: #fff;
        padding: 18px 20px;
        text-align: center;
    }

    #perfil-page .card-green .card-title {
        font-weight: 700;
        margin: 0;
        letter-spacing: .5px;
    }

    /* Inputs */
    #perfil-page .input-group-text {
        background: var(--brand, #D2AA6D);
        color: #fff;
        border: none;
    }

    #perfil-page .form-control:focus {
        border-color: var(--brand, #D2AA6D);
        box-shadow: 0 0 0 .2rem rgba(210, 170, 109, .25);
    }

    #perfil-page .toggle-pass {
        cursor: pointer;
        background: #f3f4f6;
        color: #374151;
        border: none;
    }

    #perfil-page .toggle-pass:hover {
        background: #e5e7eb;
    }

    #perfil-page .field-hint {
        font-size: 0.85rem;
        color: #6b7280;
        margin-top: 4px;
    }

    /* Botón principal */
    #perfil-page .btn-brand,
    #perfil-page .btn-brand:hover,
    #perfil-page .btn-brand:focus,
    #perfil-page .btn-brand:active,
    #perfil-page .btn-brand:disabled {
        background: var(--brand, #D2AA6D) !important;
        color: #fff !important;
        border: none !important;
        box-shadow: 0 10px 18px rgba(210, 170, 109, .22) !important;
        outline: none !important;
    }

    #perfil-page .btn-brand:not(:disabled):active {
        transform: scale(0.98);
    }

    @media (max-width: 576px) {
        #perfil-page { padding-top: 40px; }
        #perfil-page .card.card-green { margin: 0 12px; }
    }

</style>


@section('content')
    <section id="perfil-page" class="content">
        <div class="container-fluid d-flex justify-content-center align-items-start">
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
                                        <input type="email"
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
                                        <input type="password"
                                               maxlength="25"
                                               class="form-control"
                                               id="password"
                                               placeholder="Mínimo 8 y máximo 25 caracteres"
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
                                        <input type="password"
                                               maxlength="25"
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
                                <button type="button" class="btn btn-brand" onclick="actualizar()">
                                    <i class="fas fa-save mr-1"></i> Actualizar
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection


@push('scripts')
    <script src="{{ asset('js/toastr.min.js') }}"></script>
    <script src="{{ asset('js/axios.min.js') }}"></script>
    <script src="{{ asset('js/sweetalert2.all.min.js') }}"></script>
    <script src="{{ asset('js/alertaPersonalizada.js') }}"></script>

    <script>
        // Mostrar / Ocultar contraseña
        document.addEventListener('click', function (e) {
            const btn = e.target.closest('[data-toggle-pass]');
            if (!btn) return;
            const target = document.querySelector(btn.getAttribute('data-toggle-pass'));
            if (!target) return;
            target.type = (target.type === 'password') ? 'text' : 'password';
            const icon = btn.querySelector('i');
            icon.classList.toggle('fa-eye');
            icon.classList.toggle('fa-eye-slash');
        });

        // Actualizar perfil
        function actualizar() {
            var correo = document.getElementById('correo').value.trim();
            var passwordNueva = document.getElementById('password').value.trim();
            var passwordRepetida = document.getElementById('password1').value.trim();

            if (correo === '') {
                toastr.error('Correo es requerido');
                return;
            }

            if (correo.length > 100) {
                toastr.error('Correo máximo 100 caracteres');
                return;
            }

            var patronCorreo = /^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/;
            if (!patronCorreo.test(correo)) {
                toastr.error('Debe ingresar un correo válido');
                return;
            }

            var actualizarPass = 0;

            if (passwordNueva || passwordRepetida) {
                if (passwordNueva.length < 8 || passwordNueva.length > 25) {
                    toastr.error('Contraseña nueva: mínimo 8 y máximo 25 caracteres');
                    return;
                }
                if (passwordRepetida.length < 8 || passwordRepetida.length > 25) {
                    toastr.error('Contraseña repetida: mínimo 8 y máximo 25 caracteres');
                    return;
                }
                if (passwordNueva !== passwordRepetida) {
                    toastr.error('Las contraseñas no coinciden');
                    return;
                }
                actualizarPass = 1;
            }

            openLoading();
            var formData = new FormData();
            formData.append('correo', correo);
            formData.append('password', passwordNueva);
            formData.append('actualizarpass', actualizarPass);

            axios.post('/admin/perfil/actualizar/todo', formData)
                .then(response => {
                    closeLoading();

                    if (response.data.success === 1) {
                        toastr.success('Perfil actualizado correctamente');
                        document.getElementById('password').value = '';
                        document.getElementById('password1').value = '';
                    } else {
                        toastr.error('Error al actualizar');
                    }
                })
                .catch(() => {
                    closeLoading();
                    toastr.error('Error al actualizar');
                });
        }
    </script>
@endpush
