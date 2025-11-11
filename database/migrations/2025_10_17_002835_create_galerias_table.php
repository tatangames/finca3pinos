<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * GALERIAS
     */
    public function up(): void
    {
        Schema::create('galerias', function (Blueprint $table) {
            $table->id();

            $table->date('fecha');
            $table->string('imagen', 100)->nullable();
            $table->string('urlvideo', 100)->nullable();
            $table->integer('posicion');
            $table->boolean('activo');

            $table->boolean('tipo'); // 0: imagen 1: video url

            // Campos requeridos
            $table->string('content_key', 300)->unique();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('galerias');
    }
};
