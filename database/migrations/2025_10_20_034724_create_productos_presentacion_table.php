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
        Schema::create('productos_presentacion', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('id_productos')->unsigned();
            $table->string('content_key', 300)->unique();

            $table->boolean('activo');

            // NOMBRE POR IDIOMA

            $table->foreign('id_productos')->references('id')->on('productos');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('productos_presentacion');
    }
};
