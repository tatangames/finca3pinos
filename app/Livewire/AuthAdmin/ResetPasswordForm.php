<?php

namespace App\Livewire\AuthAdmin;

use Livewire\Component;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;

class ResetPasswordForm extends Component
{

    public $token, $email, $password, $password_confirmation;


    public function mount($token)
    {
        $this->token = $token;
        $this->email = request()->query('email'); // si el email viene en la URL
    }

    public function resetPassword()
    {
        $messages = [
            'email.required'    => 'El campo correo electrónico es obligatorio.',
            'email.email'       => 'Debe ingresar un correo electrónico válido.',
            'password.required' => 'Debe ingresar una nueva contraseña.',
            'password.min'      => 'La contraseña debe tener al menos 8 caracteres.',
            'password.confirmed'=> 'Las contraseñas no coinciden.',
        ];

        $this->validate([
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ], $messages);

        $status = Password::broker('admin')->reset(
            [
                'email' => $this->email,
                'password' => $this->password,
                'password_confirmation' => $this->password_confirmation,
                'token' => $this->token,
            ],
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                ])->save();
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            session()->flash('message', 'Contraseña restablecida correctamente.');
            return redirect()->route('admin.login');
        } else {
            session()->flash('error', 'El enlace para restablecer la contraseña ha expirado o no es válido.');
        }
    }


    public function render()
    {
        return view('livewire.auth-admin.reset-password-form');
    }
}
