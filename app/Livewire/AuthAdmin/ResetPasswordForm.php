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
    }

    public function resetPassword()
    {
        $this->validate([
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);

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

        if ($status == Password::PASSWORD_RESET) {
            session()->flash('message', 'Contraseña restablecida correctamente.');
            return redirect()->route('admin.login');
        } else {
            session()->flash('error', 'Token inválido o expirado.');
        }
    }

    public function render()
    {
        return view('livewire.auth-admin.reset-password-form');
    }
}
