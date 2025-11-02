<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('direcciones_facturacion', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('id_usuario')->unsigned();
            $table->bigInteger('id_paises')->unsigned();

            $table->string('nombre', 50)->nullable();
            $table->string('direccion', 100)->nullable();
            $table->string('ciudad',50)->nullable();
            $table->string('estado', 50)->nullable();
            $table->string('zipcode', 20)->nullable();
            $table->string('telefono', 20)->nullable();

            $table->foreign('id_usuario')->references('id')->on('usuarios');
            $table->foreign('id_paises')->references('id')->on('paises');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('direcciones_facturacion');
    }
};
