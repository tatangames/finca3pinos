<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * GUARDAR REGISTRO DE LOS FORMULARIOS DE CONTACTO
     */
    public function up(): void
    {
        Schema::create('registro_contactos', function (Blueprint $table) {
            $table->id();
            $table->dateTime('fecha');

            $table->text('nombre')->nullable();
            $table->text('correo')->nullable();
            $table->text('mensaje')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('registro_contactos');
    }
};
