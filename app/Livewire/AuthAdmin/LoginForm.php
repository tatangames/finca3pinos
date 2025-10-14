<?php

namespace App\Livewire\AuthAdmin;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Validator;
use Livewire\Component;

class LoginForm extends Component
{
    public string $email = '';
    public string $password = '';
    public bool   $remember = false;

    // Controla si el botón puede habilitarse
    public bool $canSubmit = false;

    protected $rules = [
        'email'    => ['required','email','max:100'],
        'password' => ['required','max:25'],
    ];

    protected $messages = [
        'email.required'    => 'Ingresa tu correo electrónico.',
        'email.email'       => 'Formato de correo inválido.',
        'email.max'         => 'El correo no debe exceder 100 caracteres.',
        'password.required' => 'Ingresa tu contraseña.',
        'password.max'      => 'La contraseña no debe exceder 25 caracteres.',
    ];

    public function mount(): void
    {
        $this->recomputeCanSubmit();
    }

    /**
     * Limpia errores del campo editado, valida solo ese campo
     * y recalcula si el formulario está listo.
     */
    public function updated($property, $value): void
    {
        // Limpia errores SOLO del campo que cambió
        $this->resetErrorBag($property);
        $this->resetValidation($property);

        $this->validateOnly($property, $this->rules, $this->messages);
        $this->recomputeCanSubmit();
    }

    public function updatedEmail(): void
    {
        $this->validateOnly('email', $this->rules, $this->messages);
        $this->recomputeCanSubmit();
    }

    public function updatedPassword(): void
    {
        $this->validateOnly('password', $this->rules, $this->messages);
        $this->recomputeCanSubmit();
    }

    /**
     * Comprueba si el formulario cumple todas las reglas sin lanzar excepciones.
     */
    protected function recomputeCanSubmit(): void
    {
        $validator = Validator::make(
            ['email' => $this->email, 'password' => $this->password],
            $this->rules,
            $this->messages
        );

        $this->canSubmit = !$validator->fails();
    }

    public function login()
    {
        // 🔹 Limpia cualquier error previo ANTES de validar/enviar
        $this->resetErrorBag();
        $this->resetValidation();

        $this->validate();

        $key = 'login:'.strtolower($this->email).'|'.request()->ip();

        if (RateLimiter::tooManyAttempts($key, 5)) {
            $seconds = RateLimiter::availableIn($key);
            // Limpia password y recalcula disponibilidad
            $this->password = '';
            $this->recomputeCanSubmit();

            $this->dispatch('toast', type:'error', message:"Demasiados intentos. Intenta en {$seconds}s.");
            $this->addError('email', "Demasiados intentos. Intenta en {$seconds}s.");
            return;
        }

        // Guard específico
        if (!Auth::guard('admin')->attempt(
            ['email' => $this->email, 'password' => $this->password],
            $this->remember
        )) {
            RateLimiter::hit($key, 60);

            // 🔹 En fallo: limpiar password y errores anteriores
            $this->password = '';
            $this->resetErrorBag();
            $this->resetValidation();
            $this->addError('email', 'Credenciales inválidas.');
            $this->dispatch('toast', type:'error', message:'Correo o contraseña incorrectos.');
            $this->recomputeCanSubmit();
            return;
        }

        RateLimiter::clear($key);
        $this->resetErrorBag();
        $this->resetValidation();
        session()->regenerate();

        return redirect()->intended(route('admin.panel'));
    }

    public function render()
    {
        return view('livewire.auth-admin.login-form');
    }
}
