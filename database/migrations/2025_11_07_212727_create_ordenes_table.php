<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * ORDENES REALIZADAS
     */
    public function up(): void
    {
        Schema::create('ordenes', function (Blueprint $table) {
            $table->id();
            // Usuario (usa tu tabla usuarios)
            $table->foreignId('id_usuario')->constrained('usuarios');


            // REFERENCIA DE PAGADITO
            $table->string('ern', 100)->unique();

            // Fecha de registro
            $table->dateTime('fecha');

            // COPIA DATOS DE DIRECCION DEL USUARIO

            $table->foreignId('id_paises')->constrained('paises'); // PAIS

            $table->foreignId('id_departamentos') // DEPARTAMENTOS
            ->nullable()
                ->constrained('departamentos')
                ->nullOnDelete();

            $table->foreignId('id_municipios') // MUNICIPIOS
            ->nullable()
                ->constrained('municipios')
                ->nullOnDelete();
            $table->string('shipping_nombre', 50)->nullable(); //*
            $table->string('shipping_direccion', 100)->nullable(); //*
            $table->string('shipping_ciudad', 50)->nullable(); //*
            $table->string('shipping_direccion_opc', 100)->nullable(); //*
            $table->string('shipping_estado', 50)->nullable(); //*
            $table->string('shipping_zipcode', 20)->nullable(); //*
            $table->string('shipping_telefono', 20)->nullable(); //*


            // COPIA DATOS DE FACTURACION
            $table->foreignId('billing_idpaises')->constrained('paises'); // PAIS
            $table->string('billing_nombre', 50)->nullable();
            $table->string('billing_direccion', 100)->nullable();
            $table->string('billing_ciudad', 50)->nullable();
            $table->string('billing_estado', 50)->nullable();
            $table->string('billing_zipcode', 20)->nullable();
            $table->string('billing_telefono', 20)->nullable();

            // Totales
            $table->decimal('subtotal', 10, 2)->default(0);
            $table->decimal('shipping_cost', 10, 2)->default(0);
            $table->decimal('total', 10, 2)->default(0);

           /*
                1 => 'pending',
                2 => 'paid',
                3 => 'failed',
                4 => 'canceled',
                5 => 'refunded',
          */

            // Estado de la orden interna
            $table->tinyInteger('status_id')->default(1);

            // Datos Pagadito
            $table->string('pagadito_token', 150)->nullable();
            $table->string('pagadito_ref', 150)->nullable();
            $table->string('pagadito_status', 50)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ordenes');
    }
};
