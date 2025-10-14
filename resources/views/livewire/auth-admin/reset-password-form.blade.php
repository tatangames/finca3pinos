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
                    <form wire:submit.prevent="resetPassword" novalidate>
                        {{-- token: puedes quitar este input si ya asignas $token en mount() --}}
                        {{-- <input type="hidden" wire:model.defer="token"> --}}

                        {{-- NUEVA CONTRASEÑA --}}
                        <div class="input-group form-group" style="margin-top: 25px">
                            <div class="input-group-prepend">
            <span class="input-group-text" style="background:#D2AA6DFF;color:#fff;border:none;">
                <i class="fas fa-lock"></i>
            </span>
                            </div>
                            <input
                                type="password"
                                wire:model.defer="password"
                                class="form-control @error('password') is-invalid @enderror"
                                placeholder="Nueva contraseña"
                                autocomplete="new-password"
                            >
                        </div>
                        @error('password')
                        <small class="text-danger d-block mt-1">{{ $message }}</small>
                        @enderror

                        {{-- CONFIRMAR CONTRASEÑA --}}
                        <div class="input-group form-group" style="margin-top: 25px">
                            <div class="input-group-prepend">
            <span class="input-group-text" style="background:#D2AA6DFF;color:#fff;border:none;">
                <i class="fas fa-lock"></i>
            </span>
                            </div>
                            <input
                                type="password"
                                wire:model.defer="password_confirmation"
                                class="form-control"
                                placeholder="Confirmar contraseña"
                                autocomplete="new-password"
                            >
                        </div>

                        <br>
                        <div class="form-group text-center">
                            <button
                                type="submit"
                                class="btn btn-lg w-100 shadow-lg"
                                style="background:#D2AA6DFF;color:#fff;border:none;"
                                wire:loading.attr="disabled">
                                <span wire:loading.remove wire:target="resetPassword">RESTABLECER CONTRASEÑA</span>
                                <span wire:loading wire:target="resetPassword">Procesando…</span>
                            </button>
                            {{-- … tus alerts … --}}
                        </div>
                    </form>


                </div>
            </div>
        </div>
    </div>
</div>
