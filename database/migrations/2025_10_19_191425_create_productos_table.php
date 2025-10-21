<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * PRODUCTOS
     */
    public function up(): void
    {
        Schema::create('productos', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('id_categorias')->unsigned();
            $table->string('content_key', 300)->unique();

            $table->string('imagen', 100);
            $table->integer('posicion')->default(0);
            $table->boolean('activo');

            $table->decimal('precio', 8, 2)->default(0);

            // titulo y Descripcion por Idioma

            $table->foreign('id_categorias')->references('id')->on('categorias');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('productos');
    }
};
