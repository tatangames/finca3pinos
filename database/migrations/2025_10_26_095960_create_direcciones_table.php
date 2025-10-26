<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * DIRECCIONES DE LOS USUARIOS
     */
    public function up(): void
    {
        Schema::create('direcciones', function (Blueprint $table) {
            $table->id();

            $table->bigInteger('id_paises')->unsigned();

            //
            $table->bigInteger('id_departamento')->unsigned()->nullable();
            $table->bigInteger('id_municipio')->unsigned()->nullable();

            $table->string('nombre', 50);

            $table->string('direccion', 60);
            $table->string('direccion_opcional', 60)->nullable();

            $table->string('ciudad', 50)->nullable();
            // estado / provincia / region
            $table->string('estado', 50)->nullable();

            // zip code
            $table->string('zipcode', 20)->nullable();

            // telefono
            $table->string('telefono', 20)->nullable();

            $table->foreign('id_paises')->references('id')->on('paises');
            $table->foreign('id_departamento')->references('id')->on('departamentos');
            $table->foreign('id_municipio')->references('id')->on('municipios');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('direcciones');
    }
};
