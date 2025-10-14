<div class="demo-container">
    <div class="container">
        <div class="row" style="margin-top: 60px">
            <div class="col-lg-6 col-12 mx-auto">
                <div class="p-5 bg-white rounded shadow-lg">
                    <div class="text-center mb-3">
                        <img src="{{ asset('images/logoindex.png') }}" alt="Logo" style="width:120px;height:auto;">
                    </div>

                    <p class="text-center lead" style="font-weight: bold">Panel de Administración</p>

                    {{-- Formulario Livewire (POST como fallback) --}}
                    <form wire:submit.prevent="login" method="POST" action="#" novalidate>
                        @csrf

                        {{-- EMAIL --}}
                        <div class="input-group form-group" style="margin-top: 25px">
                            <div class="input-group-prepend">
                <span class="input-group-text" style="background:#D2AA6DFF;color:#fff;border:none;">
                  <i class="fas fa-user"></i>
                </span>
                            </div>
                            <input
                                id="login-email"
                                type="email"
                                maxlength="100"
                                class="form-control @error('email') is-invalid @enderror"
                                placeholder="Correo electrónico"
                                autocomplete="email"
                                required
                                wire:model.lazy="email"
                                wire:keydown.enter.prevent="login"
                            >
                        </div>
                        @error('email')
                        <small class="text-danger d-block mt-1">{{ $message }}</small>
                        @enderror

                        {{-- PASSWORD --}}
                        <div class="input-group form-group" style="margin-top:25px" wire:key="password-field">
                            <div class="input-group-prepend">
                <span class="input-group-text" style="background:#D2AA6DFF;color:#fff;border:none;">
                  <i class="fas fa-lock"></i>
                </span>
                            </div>

                            <input
                                id="login-password"
                                type="password"
                                maxlength="25"
                                class="form-control @error('password') is-invalid @enderror"
                                placeholder="Contraseña"
                                autocomplete="current-password"
                                required
                                wire:model.lazy="password"
                                wire:keydown.enter.prevent="login"
                            >

                            <div class="input-group-append" wire:ignore>
                                <button
                                    type="button"
                                    id="toggle-password"
                                    class="input-group-text"
                                    style="cursor:pointer; background:#fff; border-left:none; user-select:none; gap:6px;"
                                    aria-label="Mostrar contraseña"
                                    aria-pressed="false"
                                    title="Mostrar/Ocultar contraseña">

                                    <!-- Ojo abierto -->
                                    <svg data-eye="show" class="icon-eye" width="20" height="20" viewBox="0 0 24 24" fill="none"
                                         xmlns="http://www.w3.org/2000/svg" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M2 12s4-7 10-7 10 7 10 7-4 7-10 7S2 12 2 12Z"/>
                                        <circle cx="12" cy="12" r="3"/>
                                    </svg>

                                    <!-- Ojo cerrado (tu SVG) -->
                                    <svg data-eye="hide" class="icon-eye d-none" width="20" height="20" viewBox="0 0 24 24" fill="none"
                                         xmlns="http://www.w3.org/2000/svg" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M15.6487 5.39489C14.4859 4.95254 13.2582 4.72021 12 4.72021C8.46997 4.72021 5.17997 6.54885 2.88997 9.71381C1.98997 10.9534 1.98997 13.037 2.88997 14.2766C3.34474 14.9051 3.83895 15.481 4.36664 16.0002M19.3248 7.69653C19.9692 8.28964 20.5676 8.96425 21.11 9.71381C22.01 10.9534 22.01 13.037 21.11 14.2766C18.82 17.4416 15.53 19.2702 12 19.2702C10.6143 19.2702 9.26561 18.9884 7.99988 18.4547"/>
                                        <path d="M15 12C15 13.6592 13.6592 15 12 15M14.0996 9.85541C13.5589 9.32599 12.8181 9 12 9C10.3408 9 9 10.3408 9 12C9 12.7293 9.25906 13.3971 9.69035 13.9166"/>
                                        <path d="M2 21.0002L22 2.7002"/>
                                    </svg>

                                </button>
                            </div>
                        </div>

                        @error('password')
                        <small class="text-danger d-block mt-1">{{ $message }}</small>
                        @enderror

                        {{-- Mensaje general (credenciales) --}}
                        @if (session()->has('login_error'))
                            <div class="alert alert-danger mt-3">{{ session('login_error') }}</div>
                        @endif

                        <div class="d-flex justify-content-between align-items-center mt-2" style="float: right">
                            <a style="color:black;font-size:15px;" href="{{ url('/admin/contrasena-reset') }}">
                                ¿Contraseña olvidada?
                            </a>
                        </div>

                        <br>
                        <div class="form-group text-center" style="margin-top: 35px">
                            <button
                                type="submit"
                                class="btn btn-lg w-100 shadow-lg"
                                style="background:#D2AA6DFF;color:#fff;border:none;"
                                wire:click.prevent="login"
                                wire:target="login"
                                wire:loading.attr="disabled">
                                <span wire:loading.remove wire:target="login">ACCEDER</span>
                                <span wire:loading wire:target="login">Procesando…</span>
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>

{{-- Script: toggle de contraseña (robusto con Livewire) --}}
<script>
    document.addEventListener('mousedown', function (e) {
        const btn = e.target.closest('#toggle-password');
        if (btn) e.preventDefault(); // evita seleccionar texto y robar foco
    });

    document.addEventListener('click', function (e) {
        const btn = e.target.closest('#toggle-password');
        if (!btn) return;
        e.preventDefault();

        const pwd = document.getElementById('login-password');
        if (!pwd) return;

        const eyeOpen  = btn.querySelector('[data-eye="show"]');
        const eyeClose = btn.querySelector('[data-eye="hide"]');

        const selStart = pwd.selectionStart, selEnd = pwd.selectionEnd, scrollY = pwd.scrollTop;
        const showing = (pwd.type === 'text');
        pwd.type = showing ? 'password' : 'text';

        if (eyeOpen && eyeClose) {
            eyeOpen.classList.toggle('d-none', !showing);
            eyeClose.classList.toggle('d-none', showing);
        }

        btn.setAttribute('aria-pressed', (!showing).toString());
        btn.setAttribute('aria-label', showing ? 'Mostrar contraseña' : 'Ocultar contraseña');

        pwd.focus({ preventScroll: true });
        if (selStart !== null && selEnd !== null) { try { pwd.setSelectionRange(selStart, selEnd); } catch(_){} }
        pwd.scrollTop = scrollY;
    });
</script>
