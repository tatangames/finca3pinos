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
        Schema::create('region_contents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('region_id')->constrained()->cascadeOnDelete();
            $table->string('key', 100);                  // ej: 'home.description', 'about.history'
            $table->string('title', 200)->nullable();    // opcional
            $table->longText('body');                    // HTML largo
            $table->string('status', 20)->default('published'); // draft/published
            $table->timestamp('published_at')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable(); // opcional (usuario admin)

            $table->unique(['region_id', 'key']);       // clave única por región+key
            $table->index('key');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('region_contents');
    }
};
