<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * DEPARTAMENTOS
     */
    public function up(): void
    {
        Schema::create('departamentos', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('id_paises')->unsigned();

            $table->string('nombre', 100);
            $table->boolean('activo')->default(1); // oculta
            $table->boolean('disponible')->default(1); // no disponible actualmente para envio

            $table->foreign('id_paises')->references('id')->on('paises');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('departamentos');
    }
};
