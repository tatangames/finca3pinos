<?php

namespace App\Livewire\AuthAdmin;

use Livewire\Component;
use Illuminate\Support\Facades\Password;

class ForgotPasswordForm extends Component
{
    public $email;

    protected $messages = [
        'email.required' => 'Ingresa tu correo electrónico.',
        'email.email'    => 'Formato de correo inválido.',
        'email.max'      => 'El correo no debe exceder 100 caracteres.',
    ];

    public function sendResetLink()
    {
        // 1) Validación básica del servidor
        $this->validate([
            'email' => 'required|email|max:100',
        ]);

        // 2) Intentar enviar
        $status = Password::broker('admin')->sendResetLink([
            'email' => $this->email,
        ]);

        // 3) Respuestas claras según el status
        switch ($status) {
            case Password::RESET_LINK_SENT:
                // limpiar input y errores
                $this->reset('email');
                $this->resetErrorBag();
                session()->flash('message', 'Se ha enviado un enlace a tu correo.');
                break;

            case Password::INVALID_USER:
                // correo no existe en el broker 'admin'
                $this->addError('email', 'No encontramos una cuenta con ese correo.');
                break;

            case Password::RESET_THROTTLED:
                // demasiados intentos
                $this->addError('email', 'Has realizado varios intentos. Inténtalo de nuevo en unos minutos.');
                break;

            default:
                // fallback
                $this->addError('email', 'No se pudo enviar el enlace. Intenta más tarde.');
                break;
        }
    }

    public function updatedEmail()
    {
        $this->resetErrorBag('email'); // limpia errores del servidor
    }

    public function render()
    {
        return view('livewire.auth-admin.forgot-password-form');
    }
}
