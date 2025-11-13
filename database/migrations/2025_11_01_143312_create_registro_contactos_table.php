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
            $table->bigInteger('')->unsigned()->nullable();

            $table->text('nombre')->nullable();
            $table->text('correo')->nullable();
            $table->text('telefono')->nullable();
            $table->text('mensaje')->nullable();

            // 0: de contacto
            // 1 de cotizacion

            $table->integer('tipo_formulario');

            $table->foreign('id_paises')->references('id')->on('paises');
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
