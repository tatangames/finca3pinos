<?php

namespace Database\Seeders;

use App\Models\Pais;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PaisesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Pais::create([
            'nombre' => 'El Salvador',
            'precio_envio' => '0.00',
            'activo' => 1,
            'disponible' => 1,
        ]);

        Pais::create([
            'nombre' => 'United States',
            'precio_envio' => '0.00',
            'activo' => 1,
            'disponible' => 1,
        ]);

        Pais::create([
            'nombre' => 'South Korea',
            'precio_envio' => '0.00',
            'activo' => 1,
            'disponible' => 1,
        ]);
    }
}
