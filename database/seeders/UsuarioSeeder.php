<?php

namespace Database\Seeders;

use App\Models\Usuario;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsuarioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Usuario::create([
            'nombre' => 'Jonathan',
            'password' => Hash::make('1234'),
            'email' => 'tatan@gmail.com',
            'token_correo' => null,
            'token_fecha' => null
        ]);
    }
}
