@extends('backend.menus.superior')

@section('content-admin-css')
    <link href="{{ asset('css/adminlte.min.css') }}" type="text/css" rel="stylesheet" />
    <link href="{{ asset('css/dataTables.bootstrap4.css') }}" type="text/css" rel="stylesheet" />
    <link href="{{ asset('css/toastr.min.css') }}" type="text/css" rel="stylesheet" />

    <style>
        :root{
            --brand:#D2AA6D;
        }


        #perfil-page{
            background: linear-gradient(135deg, #1b1b1b 0%, #2f2233 100%);
            min-height:100vh;
            width:100%;
            display:flex;
            justify-content:center;
            align-items:flex-start;
            padding:40px 24px;
        }

        #perfil-page .card.card-green{
            border:1px solid rgba(0,0,0,.06);
            border-top:4px solid var(--brand,#D2AA6D);
            border-radius:18px;
            overflow:hidden;
            box-shadow:0 10px 24px rgba(0,0,0,.25);
            background:#fff;
            animation:fadeInUp .5s ease;
        }
        @keyframes fadeInUp{
            from{opacity:0;transform:translateY(40px)}
            to{opacity:1;transform:translateY(0)}
        }

        #perfil-page .card-green .card-header{
            background:linear-gradient(135deg,#111 0%,#2b2b2b 60%);
            color:#fff;
            padding:18px 20px;
            text-align:center;
        }
        #perfil-page .card-green .card-title{
            font-weight:700;
            margin:0;
            letter-spacing:.5px;
        }

        #perfil-page .input-group-text{
            background:var(--brand,#D2AA6D);
            color:#fff;
            border:none;
        }
        #perfil-page .form-control:focus{
            border-color:var(--brand,#D2AA6D);
            box-shadow:0 0 0 .2rem rgba(210,170,109,.25);
        }

        #perfil-page .toggle-pass{
            cursor:pointer;
            background:#f3f4f6;
            color:#374151;
            border:none;
            display:flex;
            align-items:center;
            justify-content:center;
            padding:0 10px;
        }
        #perfil-page .toggle-pass:hover{
            background:#e5e7eb;
        }

        #perfil-page .icon-eye{
            width:20px;
            height:20px;
            stroke:currentColor;
        }

        #perfil-page .btn-brand,
        #perfil-page .btn-brand:hover,
        #perfil-page .btn-brand:focus,
        #perfil-page .btn-brand:active,
        #perfil-page .btn-brand:disabled{
            background: #28a745;
            color:#fff!important;
            border:none!important;
            box-shadow:0 10px 18px rgba(210,170,109,.22)!important;
            outline:none!important;
        }
        #perfil-page .btn-brand:not(:disabled):active{
            transform:scale(0.98);
        }

        @media (max-width:576px){
            #perfil-page{ padding-top:40px; }
            #perfil-page .card.card-green{ margin:0 12px; }
        }
    </style>
@stop


<section id="perfil-page" class="content">
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-4">
                <div class="card card-green">
                    <div class="card-header">
                        <h3 class="card-title">Actualizar Perfil</h3>
                    </div>

                    <form>
                        <div class="card-body">

                            <div class="form-group">
                                <label>Correo Electrónico</label>
                                <input type="text" maxlength="100" id="correo"
                                       class="form-control" value="{{ $usuario->email }}">
                            </div>

                            <div class="form-group">
                                <label>Nueva Contraseña</label>
                                <div class="input-group">
                                    <input type="password" maxlength="25" class="form-control" id="password" placeholder="Contraseña">
                                    <div class="input-group-append">
                                        <button class="toggle-pass" type="button" onclick="togglePassword('password', this)">
                                            <!-- Ojo abierto -->
                                            <svg data-eye="show" class="icon-eye" viewBox="0 0 24 24" fill="none"
                                                 xmlns="http://www.w3.org/2000/svg" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M2 12s4-7 10-7 10 7 10 7-4 7-10 7S2 12 2 12Z"/>
                                                <circle cx="12" cy="12" r="3"/>
                                            </svg>

                                            <!-- Ojo cerrado -->
                                            <svg data-eye="hide" class="icon-eye d-none" viewBox="0 0 24 24" fill="none"
                                                 xmlns="http://www.w3.org/2000/svg" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M15.6487 5.39489C14.4859 4.95254 13.2582 4.72021 12 4.72021C8.46997 4.72021 5.17997 6.54885 2.88997 9.71381C1.98997 10.9534 1.98997 13.037 2.88997 14.2766C3.34474 14.9051 3.83895 15.481 4.36664 16.0002M19.3248 7.69653C19.9692 8.28964 20.5676 8.96425 21.11 9.71381C22.01 10.9534 22.01 13.037 21.11 14.2766C18.82 17.4416 15.53 19.2702 12 19.2702C10.6143 19.2702 9.26561 18.9884 7.99988 18.4547"/>
                                                <path d="M15 12C15 13.6592 13.6592 15 12 15M14.0996 9.85541C13.5589 9.32599 12.8181 9 12 9C10.3408 9 9 10.3408 9 12C9 12.7293 9.25906 13.3971 9.69035 13.9166"/>
                                                <path d="M2 21.0002L22 2.7002"/>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label>Repetir Contraseña</label>
                                <div class="input-group">
                                    <input type="password" maxlength="25" class="form-control" id="password1" placeholder="Contraseña">
                                    <div class="input-group-append">
                                        <button class="toggle-pass" type="button" onclick="togglePassword('password1', this)">
                                            <!-- Ojo abierto -->
                                            <svg data-eye="show" class="icon-eye" viewBox="0 0 24 24" fill="none"
                                                 xmlns="http://www.w3.org/2000/svg" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M2 12s4-7 10-7 10 7 10 7-4 7-10 7S2 12 2 12Z"/>
                                                <circle cx="12" cy="12" r="3"/>
                                            </svg>

                                            <!-- Ojo cerrado -->
                                            <svg data-eye="hide" class="icon-eye d-none" viewBox="0 0 24 24" fill="none"
                                                 xmlns="http://www.w3.org/2000/svg" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M15.6487 5.39489C14.4859 4.95254 13.2582 4.72021 12 4.72021C8.46997 4.72021 5.17997 6.54885 2.88997 9.71381C1.98997 10.9534 1.98997 13.037 2.88997 14.2766C3.34474 14.9051 3.83895 15.481 4.36664 16.0002M19.3248 7.69653C19.9692 8.28964 20.5676 8.96425 21.11 9.71381C22.01 10.9534 22.01 13.037 21.11 14.2766C18.82 17.4416 15.53 19.2702 12 19.2702C10.6143 19.2702 9.26561 18.9884 7.99988 18.4547"/>
                                                <path d="M15 12C15 13.6592 13.6592 15 12 15M14.0996 9.85541C13.5589 9.32599 12.8181 9 12 9C10.3408 9 9 10.3408 9 12C9 12.7293 9.25906 13.3971 9.69035 13.9166"/>
                                                <path d="M2 21.0002L22 2.7002"/>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="card-footer text-right">
                            <button type="button" class="btn btn-brand" onclick="actualizar()">
                                Actualizar
                            </button>
                        </div>
                    </form>

                </div>
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

        function togglePassword(id, btn) {
            const input = document.getElementById(id);
            const showIcon = btn.querySelector('[data-eye="show"]');
            const hideIcon = btn.querySelector('[data-eye="hide"]');

            const isHidden = input.type === 'password';
            input.type = isHidden ? 'text' : 'password';

            showIcon.classList.toggle('d-none', isHidden);
            hideIcon.classList.toggle('d-none', !isHidden);
        }

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

            // validación con expresión regular
            var regexCorreo = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

            if (!regexCorreo.test(correo)) {
                toastr.error('Ingrese un correo electrónico válido');
                return;
            }

            if(correo.length > 100){
                toastr.error('Correo máximo 100 caracteres');
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

            axios.post('/admin/perfil/actualizar/todo', formData, {
            })
                .then((response) => {
                    closeLoading()

                    console.log(response)

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
