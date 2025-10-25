<?php

namespace App\Http\Controllers\Frontend\Sistema;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class PasswordResetController extends Controller
{

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token'    => 'required',
            'email'    => 'required|email:rfc',
            'password' => [
                'required', 'confirmed', 'min:8',
                // 'regex:/^(?=.*[A-Z])(?=.*\d).+$/', // opcional: fuerza
            ],
        ]);

        $status = Password::broker('users')->reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user) use ($request) {
                $user->forceFill([
                    'password' => Hash::make($request->password),
                    'remember_token' => Str::random(60),
                ])->save();
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return response()->json([
                'success' => 1,
                'message' => '', // “Tu contraseña ha sido restablecida.”
                'ruta'    => route('user.login'),
            ]);
        }

        return response()->json([
            'success' => 0,
            'message' => __($status),
        ], 422);
    }
}
