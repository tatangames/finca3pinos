<?php

namespace App\Livewire\AuthAdmin;

use App\Models\Administrador;
use Livewire\Component;
use Illuminate\Support\Facades\Password;

class ForgotPasswordForm extends Component
{
    public $email;

    // 🔸 Mensajes de validación personalizados
    protected $messages = [
        'email.required' => 'Ingresa tu correo electrónico.',
        'email.email'    => 'Formato de correo inválido.',
        'email.max'      => 'El correo no debe exceder 100 caracteres.',
    ];

    public function sendResetLink()
    {
        $this->validate([
            'email' => 'required|email|max:100',
        ]);

        $status = Password::broker('admin')->sendResetLink(['email' => $this->email]);

        if ($status === Password::RESET_LINK_SENT) {
            // ✅ Limpiar input y errores
            $this->reset('email');
            $this->resetErrorBag();

            // ✅ Mensaje flash para mostrar en la vista
            session()->flash('message', 'Se ha enviado un enlace a tu correo.');
        } else {
            $this->addError('email', 'No se pudo enviar el enlace. Intenta más tarde.');
        }
    }


    public function render()
    {
        return view('livewire.auth-admin.forgot-password-form');
    }
}
