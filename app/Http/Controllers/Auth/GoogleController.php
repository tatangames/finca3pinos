<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;
use App\Models\Usuario;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization as L10n;

class GoogleController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $loginUrl = L10n::localizeURL(
                route('user.login', [], false) // sin duplicar idioma
            );
            return redirect()->to($loginUrl)
                ->with('error', __('meta.error_login_google'));

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

            Auth::guard('web')->login($user, true);

            // Redirige a la ruta que mencionaste
            return redirect()->route('user.index');

        } catch (\Exception $e) {
            Log::error('Error Google OAuth: '.$e->getMessage());

            // URL de login *localizada* (sin duplicar idioma)
            $loginUrl = LaravelLocalization::localizeURL(
                route('user.login', [], false)
            );

            return redirect()->to($loginUrl)
                ->with('error', __('meta.error_login_google'));
        }
    }
}
