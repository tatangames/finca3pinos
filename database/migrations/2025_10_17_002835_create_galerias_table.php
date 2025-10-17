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
        Schema::create('galerias', function (Blueprint $table) {
            $table->id();
            $table->string('imagen', 100);
            $table->integer('posicion');
            $table->boolean('activo');
            $table->string('alt_seo', 300)->nullable();
            $table->string('content_key', 300)->nullable();
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
