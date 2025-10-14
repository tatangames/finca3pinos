<div class="demo-container">
    <div class="container">
        <div class="row" style="margin-top: 60px">
            <div class="col-lg-6 col-12 mx-auto">
                <div class="p-5 bg-white rounded shadow-lg">
                    <div class="text-center mb-3">
                        <img src="{{ asset('images/logoindex.png') }}" alt="Logo" style="width:120px;height:auto;">
                    </div>

                    <p class="text-center lead" style="font-weight: bold">Recuperar contraseña</p>

                    {{-- Formulario Livewire --}}
                    <form id="resetForm" wire:submit.prevent="sendResetLink" novalidate>
                        <div class="input-group form-group" style="margin-top: 25px">
                            <div class="input-group-prepend">
                            <span class="input-group-text" style="background:#D2AA6DFF;color:#fff;border:none;">
                                <i class="fas fa-envelope"></i>
                            </span>
                            </div>
                            <input
                                id="email"
                                name="email"
                                wire:model.defer="email"
                                type="email"
                                maxlength="100"
                                class="form-control"
                                placeholder="Correo electrónico"
                                autocomplete="email"
                                required
                            >
                        </div>
                        <small id="emailError" class="text-danger d-block mt-1" style="display:none;"></small>
                        {{-- Error del SERVIDOR (Livewire) --}}
                        @error('email')
                        <small id="emailServerError" class="text-danger d-block mt-1">{{ $message }}</small>
                        @enderror

                        <br>
                        <div class="form-group text-center">
                            <button
                                id="submitBtn"
                                type="submit"
                                class="btn btn-lg w-100 shadow-lg"
                                style="background:#D2AA6DFF;color:#fff;border:none;"
                                wire:loading.attr="disabled">
                                <span wire:loading.remove wire:target="sendResetLink">ENVIAR ENLACE</span>
                                <span wire:loading wire:target="sendResetLink">Procesando…</span>
                            </button>

                            {{-- Mensaje de éxito --}}
                            @if (session()->has('message'))
                                <div
                                    x-data="{ show: true }"
                                    x-init="setTimeout(() => show = false, 4000)"
                                    x-show="show"
                                    x-transition
                                    class="alert alert-success mt-3 text-center"
                                    style="background:#e9f7ef;border:1px solid #cfe9d8;color:#1e7e34;border-radius:10px;padding:12px;">
                                    {{ session('message') }}
                                </div>
                            @endif
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>

{{-- ==== VALIDACIÓN FRONTEND ==== --}}
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const form = document.getElementById('resetForm');
        const email = document.getElementById('email');
        const err  = document.getElementById('emailError');
        const serverErr = document.getElementById('emailServerError');
        const btn  = document.getElementById('submitBtn');

        // 🔸 limpiar errores cuando el usuario escribe
        email.addEventListener('input', () => {
            err.style.display = 'none';
            if (serverErr) serverErr.style.display = 'none';
            email.classList.remove('is-invalid', 'is-valid');
        });

        // 🔸 validación antes de enviar
        form.addEventListener('submit', (e) => {
            const value = email.value.trim();
            let ok = true;

            err.style.display = 'none';
            if (serverErr) serverErr.style.display = 'none';
            email.classList.remove('is-invalid','is-valid');

            if (value === '') {
                show('Por favor, ingrese su correo electrónico.');
                ok = false;
            } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value)) {
                show('Ingrese un correo electrónico válido.');
                ok = false;
            }

            if (!ok) {
                e.preventDefault();
                e.stopPropagation();
                if (e.stopImmediatePropagation) e.stopImmediatePropagation();
                email.classList.add('is-invalid');
                email.focus();
                return;
            }

            email.classList.add('is-valid');
            btn.disabled = true;
        }, true);

        function show(message){
            err.textContent = message;
            err.style.display = 'block';
        }
    });
</script>
