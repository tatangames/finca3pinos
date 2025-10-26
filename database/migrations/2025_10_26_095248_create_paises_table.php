<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * PAISES
     */
    public function up(): void
    {
        Schema::create('paises', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 100);
            $table->decimal('precio_envio', 8,2);

            $table->boolean('activo')->default(1); // oculta
            $table->boolean('disponible')->default(1); // no disponible actualmente para envio
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('paises');
    }
};
