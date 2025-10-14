<div class="demo-container">
    <div class="container">
        <div class="row" style="margin-top: 60px">
            <div class="col-lg-6 col-12 mx-auto">
                <div class="p-5 bg-white rounded shadow-lg">
                    <div class="text-center mb-3">
                        <img src="{{ asset('images/logoindex.png') }}" alt="Logo" style="width:120px;height:auto;">
                    </div>

                    <p class="text-center lead" style="font-weight: bold">Restablecer contraseña</p>

                    {{-- Formulario Livewire --}}
                    <form id="resetPwdForm" wire:submit.prevent="resetPassword" novalidate>

                        {{-- NUEVA CONTRASEÑA --}}
                        <div class="input-group form-group" style="margin-top: 25px">
                            <div class="input-group-prepend">
                                <span class="input-group-text" style="background:#D2AA6DFF;color:#fff;border:none;">
                                    <i class="fas fa-lock"></i>
                                </span>
                            </div>
                            <input
                                id="new-password"
                                type="password"
                                wire:model.defer="password"
                                class="form-control"
                                placeholder="Nueva contraseña"
                                autocomplete="new-password"
                                required
                                minlength="8"
                                maxlength="25"
                            >
                            <div class="input-group-append" wire:ignore>
                                <button type="button" id="toggle-new-password"
                                        class="input-group-text"
                                        style="cursor:pointer; background:#D2AA6DFF; color:#fff; border:none; user-select:none;"
                                        aria-label="Mostrar contraseña" aria-pressed="false" title="Mostrar/Ocultar contraseña">
                                    <!-- Ojo abierto -->
                                    <svg data-eye="show" width="20" height="20" viewBox="0 0 24 24" fill="none"
                                         xmlns="http://www.w3.org/2000/svg" stroke="#fff" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M2 12s4-7 10-7 10 7 10 7-4 7-10 7S2 12 2 12Z"/>
                                        <circle cx="12" cy="12" r="3"/>
                                    </svg>
                                    <!-- Ojo cerrado -->
                                    <svg data-eye="hide" class="d-none" width="20" height="20" viewBox="0 0 24 24" fill="none"
                                         xmlns="http://www.w3.org/2000/svg" stroke="#fff" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M15.6487 5.39489C14.4859 4.95254 13.2582 4.72021 12 4.72021C8.46997 4.72021 5.17997 6.54885 2.88997 9.71381C1.98997 10.9534 1.98997 13.037 2.88997 14.2766C3.34474 14.9051 3.83895 15.481 4.36664 16.0002M19.3248 7.69653C19.9692 8.28964 20.5676 8.96425 21.11 9.71381C22.01 10.9534 22.01 13.037 21.11 14.2766C18.82 17.4416 15.53 19.2702 12 19.2702C10.6143 19.2702 9.26561 18.9884 7.99988 18.4547"/>
                                        <path d="M15 12C15 13.6592 13.6592 15 12 15M14.0996 9.85541C13.5589 9.32599 12.8181 9 12 9C10.3408 9 9 10.3408 9 12C9 12.7293 9.25906 13.3971 9.69035 13.9166"/>
                                        <path d="M2 21.0002L22 2.7002"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                        <small id="pwdClientError" class="text-danger d-block mt-1" style="display:none;"></small>
                        @error('password')
                        <small id="pwdServerError" class="text-danger d-block mt-1">{{ $message }}</small>
                        @enderror

                        {{-- CONFIRMAR CONTRASEÑA --}}
                        <div class="input-group form-group" style="margin-top: 25px">
                            <div class="input-group-prepend">
                                <span class="input-group-text" style="background:#D2AA6DFF;color:#fff;border:none;">
                                    <i class="fas fa-lock"></i>
                                </span>
                            </div>
                            <input
                                id="confirm-password"
                                type="password"
                                wire:model.defer="password_confirmation"
                                class="form-control"
                                placeholder="Confirmar contraseña"
                                autocomplete="new-password"
                                required
                                minlength="8"
                                maxlength="25"
                            >
                            <div class="input-group-append" wire:ignore>
                                <button type="button" id="toggle-confirm-password"
                                        class="input-group-text"
                                        style="cursor:pointer; background:#D2AA6DFF; color:#fff; border:none; user-select:none;"
                                        aria-label="Mostrar contraseña" aria-pressed="false" title="Mostrar/Ocultar contraseña">
                                    <!-- Ojo abierto -->
                                    <svg data-eye="show" width="20" height="20" viewBox="0 0 24 24" fill="none"
                                         xmlns="http://www.w3.org/2000/svg" stroke="#fff" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M2 12s4-7 10-7 10 7 10 7-4 7-10 7S2 12 2 12Z"/>
                                        <circle cx="12" cy="12" r="3"/>
                                    </svg>
                                    <!-- Ojo cerrado -->
                                    <svg data-eye="hide" class="d-none" width="20" height="20" viewBox="0 0 24 24" fill="none"
                                         xmlns="http://www.w3.org/2000/svg" stroke="#fff" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M15.6487 5.39489C14.4859 4.95254 13.2582 4.72021 12 4.72021C8.46997 4.72021 5.17997 6.54885 2.88997 9.71381C1.98997 10.9534 1.98997 13.037 2.88997 14.2766C3.34474 14.9051 3.83895 15.481 4.36664 16.0002M19.3248 7.69653C19.9692 8.28964 20.5676 8.96425 21.11 9.71381C22.01 10.9534 22.01 13.037 21.11 14.2766C18.82 17.4416 15.53 19.2702 12 19.2702C10.6143 19.2702 9.26561 18.9884 7.99988 18.4547"/>
                                        <path d="M15 12C15 13.6592 13.6592 15 12 15M14.0996 9.85541C13.5589 9.32599 12.8181 9 12 9C10.3408 9 9 10.3408 9 12C9 12.7293 9.25906 13.3971 9.69035 13.9166"/>
                                        <path d="M2 21.0002L22 2.7002"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                        <small id="confirmClientError" class="text-danger d-block mt-1" style="display:none;"></small>
                        @error('password_confirmation')
                        <small id="confirmServerError" class="text-danger d-block mt-1">{{ $message }}</small>
                        @enderror

                        <br>
                        <div class="form-group text-center">
                            <button id="resetPwdBtn" type="submit"
                                    class="btn btn-lg w-100 shadow-lg"
                                    style="background:#D2AA6DFF;color:#fff;border:none;"
                                    wire:loading.attr="disabled">
                                <span wire:loading.remove wire:target="resetPassword">RESTABLECER CONTRASEÑA</span>
                                <span wire:loading wire:target="resetPassword">Procesando…</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>



{{-- ==== VALIDACIÓN FRONTEND + TOGGLE PASSWORD ==== --}}
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const form = document.getElementById('resetPwdForm');
        const pwd  = document.getElementById('new-password');
        const conf = document.getElementById('confirm-password');
        const btn  = document.getElementById('resetPwdBtn');

        const pwdErrClient  = document.getElementById('pwdClientError');
        const confErrClient = document.getElementById('confirmClientError');
        const pwdErrServer  = document.getElementById('pwdServerError');
        const confErrServer = document.getElementById('confirmServerError');

        const hide = el => { if(el){ el.textContent=''; el.style.display='none'; } };
        const show = (el,msg) => { if(el){ el.textContent=msg; el.style.display='block'; } };
        const clearVisual = i => i.classList.remove('is-invalid','is-valid');
        const okVisual = i => i.classList.add('is-valid');
        const badVisual = i => i.classList.add('is-invalid');

        function clearServerErrors(){
            hide(pwdErrServer);
            hide(confErrServer);
            form.querySelectorAll('.alert.alert-danger').forEach(n => n.style.display='none');
        }

        function setupToggle(btnId, inputId){
            const b = document.getElementById(btnId);
            const i = document.getElementById(inputId);
            if(!b || !i) return;
            b.addEventListener('click', e=>{
                e.preventDefault();
                const open = b.querySelector('[data-eye="show"]');
                const hideI = b.querySelector('[data-eye="hide"]');
                const showing = i.type === 'text';
                i.type = showing ? 'password' : 'text';
                open.classList.toggle('d-none', !showing);
                hideI.classList.toggle('d-none', showing);
                b.setAttribute('aria-pressed', (!showing).toString());
            });
        }
        setupToggle('toggle-new-password','new-password');
        setupToggle('toggle-confirm-password','confirm-password');

        function validate(){
            let ok=true;
            hide(pwdErrClient); hide(confErrClient);
            clearServerErrors();
            clearVisual(pwd); clearVisual(conf);

            const p=(pwd.value||'').trim();
            const c=(conf.value||'').trim();

            if(p===''){ show(pwdErrClient,'La nueva contraseña es obligatoria.'); badVisual(pwd); ok=false; }
            else if(p.length<8){ show(pwdErrClient,'La contraseña debe tener al menos 8 caracteres.'); badVisual(pwd); ok=false; }
            else okVisual(pwd);

            if(c===''){ show(confErrClient,'Confirma tu nueva contraseña.'); badVisual(conf); ok=false; }
            else if(p!=='' && c!==p){ show(confErrClient,'Las contraseñas no coinciden.'); badVisual(conf); ok=false; }
            else if(p.length>=8 && c===p) okVisual(conf);

            return ok;
        }

        pwd.addEventListener('input',()=>{ hide(pwdErrClient); clearServerErrors(); clearVisual(pwd); });
        conf.addEventListener('input',()=>{ hide(confErrClient); clearServerErrors(); clearVisual(conf); });

        form.addEventListener('submit',e=>{
            if(!validate()){ e.preventDefault(); e.stopPropagation(); return; }
            hide(pwdErrClient); hide(confErrClient); clearServerErrors();
            btn.disabled=true;
        },true);
    });
</script>
