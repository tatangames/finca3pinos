<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;
use App\Models\Usuario;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class GoogleController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            // Busca o crea usuario
            $user = Usuario::updateOrCreate(
                ['email' => $googleUser->getEmail()],
                [
                    'nombre'          => $googleUser->getName(),
                    'google_id'       => $googleUser->getId(),
                    'password'        => bcrypt(Str::random(16)),
                    'fecha_registro' => now('America/El_Salvador'),
                ]
            );

            Auth::login($user);

            // Redirige a la ruta que mencionaste
            return redirect()->route('user.index');

        } catch (\Exception $e) {
            Log::error('Error al iniciar sesión con Google: '.$e->getMessage());
            return redirect('/login')->with('error', 'Error al iniciar sesión con Google.');
        }
    }
}
