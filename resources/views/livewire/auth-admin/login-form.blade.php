<div class="demo-container">
    <div class="container">
        <div class="row" style="margin-top: 60px">
            <div class="col-lg-6 col-12 mx-auto">
                <div class="p-5 bg-white rounded shadow-lg">
                    <div class="text-center mb-3">
                        <img src="{{ asset('images/logoindex.png') }}" alt="Logo" style="width:120px;height:auto;">
                    </div>

                    <p class="text-center lead" style="font-weight: bold">Panel de Administración</p>

                    {{-- Form Livewire --}}
                    <form wire:submit.prevent="login" class="validate-form">

                        {{-- EMAIL --}}
                        <div class="input-group form-group" style="margin-top: 25px">
                            <div class="input-group-prepend">
            <span class="input-group-text" style="background:#D2AA6DFF;color:#fff;border:none;">
                <i class="fas fa-user"></i>
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

                        {{-- PASSWORD --}}
                        <div class="input-group form-group" style="margin-top: 25px">
                            <div class="input-group-prepend">
            <span class="input-group-text" style="background:#D2AA6DFF;color:#fff;border:none;">
                <i class="fas fa-key"></i>
            </span>
                            </div>
                            <input
                                wire:model.debounce.500ms="password"
                                type="password"
                                maxlength="25"
                                class="form-control @error('password') is-invalid @enderror"
                                placeholder="Contraseña"
                                autocomplete="off"
                                aria-invalid="@error('password') true @else false @enderror"
                            >
                        </div>
                        @error('password')
                        <small class="text-danger d-block mt-1">{{ $message }}</small>
                        @enderror

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
