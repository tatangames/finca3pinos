<div class="demo-container">
    <div class="container">
        <div class="row" style="margin-top: 60px">
            <div class="col-lg-6 col-12 mx-auto">
                <div class="p-5 bg-white rounded shadow-lg">
                    <div class="text-center mb-3">
                        <img src="{{ asset('images/logoindex.png') }}" alt="Logo" style="width:120px;height:auto;">
                    </div>

                    <p class="text-center lead" style="font-weight: bold">Recuperar contraseña</p>

                    {{-- Form Livewire --}}
                    <form wire:submit.prevent="sendResetLink" class="validate-form">

                        {{-- EMAIL --}}
                        <div class="input-group form-group" style="margin-top: 25px">
                            <div class="input-group-prepend">
                                <span class="input-group-text" style="background:#D2AA6DFF;color:#fff;border:none;">
                                    <i class="fas fa-envelope"></i>
                                </span>
                            </div>
                            <input
                                wire:model.debounce.500ms="email"
                                type="email"
                                maxlength="100"
                                class="form-control @error('email') is-invalid @enderror"
                                placeholder="Correo electrónico"
                                autocomplete="off"
                                aria-invalid="@error('email') true @else false @enderror"
                            >
                        </div>
                        @error('email')
                        <small class="text-danger d-block mt-1">{{ $message }}</small>
                        @enderror

                        <br>
                        <div class="form-group text-center">
                            <button
                                type="submit"
                                class="btn btn-lg w-100 shadow-lg"
                                style="background:#D2AA6DFF;color:#fff;border:none;"
                                wire:loading.attr="disabled">
                                <span wire:loading.remove wire:target="sendResetLink">ENVIAR ENLACE</span>
                                <span wire:loading wire:target="sendResetLink">Procesando…</span>
                            </button>

                            {{-- Mensaje de éxito con desaparición automática --}}
                            @if (session()->has('message'))
                                <div
                                    x-data="{ show: true }"
                                    x-init="setTimeout(() => show = false, 4000)"
                                    x-show="show"
                                    x-transition
                                    class="alert alert-success mt-3 text-center"
                                    style="background:#e9f7ef;border:1px solid #cfe9d8;color:#1e7e34;
                   border-radius:10px;padding:12px;">
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
