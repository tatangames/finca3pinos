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
        Schema::create('region_content_translation', function (Blueprint $table) {
            $table->id();
            $table->foreignId('content_id')
                ->constrained('region_contents')
                ->cascadeOnDelete();

            // Idioma específico (ej: 'es', 'en', 'pt')
            $table->string('locale', 8);

            // Traducción del título y cuerpo
            $table->longText('body')->nullable();
            $table->longText('title')->nullable();

            // SLUG PRODUCTO UNICAMENTE
            $table->string('slug', 300)->nullable();
            $table->string('altseo', 300)->nullable();

            // Índices y unicidad
            $table->unique(['content_id', 'locale']); // una traducción por idioma
            $table->index('locale');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('region_content_translation');
    }
};
