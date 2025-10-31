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
        Schema::create('order_addresses', function (Blueprint $table) {
            $table->id();

            $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete();
            $table->enum('type', ['shipping','billing'])->index();
            $table->string('country', 80)->nullable();         // nombre
            $table->string('country_code', 2)->nullable();     // ISO
            $table->string('state', 80)->nullable();           // departamento/estado
            $table->string('city', 80)->nullable();
            $table->string('zipcode', 20)->nullable();
            $table->string('address_line', 180)->nullable();
            $table->string('name', 120)->nullable();           // nombre/razón social
            $table->string('phone', 30)->nullable();
            $table->json('meta')->nullable();                  // depto/municipio ids si te sirven
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_addresses');
    }
};
