<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class AdminResetPassword extends Notification implements ShouldQueue
{
    use Queueable;

    public string $token;

    public function __construct(string $token)
    {
        $this->token = $token;
    }

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        // Arma la URL (ruta propia para admins)
        $url = url(route('admin.password.reset.form', [
            'token' => $this->token,
            'email' => $notifiable->email,
        ], false));

        // Datos extra para la vista
        $data = [
            'nombre' => $notifiable->nombre ?? 'Administrador',
            'email'  => $notifiable->email,
            'url'    => $url,
            'marca'  => 'Finca 3 Pinos',
        ];

        return (new MailMessage)
            ->subject('Restablecer tu contraseña | Finca 3 Pinos')
            ->markdown('mail.admin.reset', $data);
    }
}
