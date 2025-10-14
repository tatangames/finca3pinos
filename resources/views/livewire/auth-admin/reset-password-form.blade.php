<form wire:submit.prevent="resetPassword">
    <input type="hidden" wire:model="token">
    <div class="form-group">
        <label>Correo electrónico</label>
        <input type="email" wire:model="email" class="form-control">
    </div>

    <div class="form-group">
        <label>Nueva contraseña</label>
        <input type="password" wire:model="password" class="form-control">
    </div>

    <div class="form-group">
        <label>Confirmar contraseña</label>
        <input type="password" wire:model="password_confirmation" class="form-control">
    </div>

    <button type="submit" class="btn btn-primary">Restablecer contraseña</button>
</form>
