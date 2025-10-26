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
        Schema::create('municipios', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('id_departamentos')->unsigned();

            $table->string('nombre', 100);

            // UNICAMENTE TOMADO PARA EL SALVADOR
            $table->decimal('precio_envio', 8, 2);

            $table->boolean('activo')->default(1); // oculta
            $table->boolean('disponible')->default(1); // no disponible actualmente para envio

            $table->foreign('id_departamentos')->references('id')->on('departamentos');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('municipios');
    }
};
