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
        Schema::create('ordenes', function (Blueprint $table) {
            $table->id();
            // Usuario (usa tu tabla usuarios)
            $table->unsignedBigInteger('id_usuario')->nullable()->index();

            // Referencia única para Pagadito / sistema
            $table->string('ern', 100)->unique()
                ->comment('Referencia única de la transacción (F3P-USER-TIME)');

            $table->dateTime('fecha');

            // Snapshot ENVÍO (para no perder historial si el usuario edita su dirección después)
            $table->string('shipping_nombre', 100)->nullable();
            $table->string('shipping_telefono', 30)->nullable();
            $table->string('shipping_pais', 100)->nullable();
            $table->string('shipping_estado', 100)->nullable();
            $table->string('shipping_ciudad', 100)->nullable();
            $table->string('shipping_direccion', 255)->nullable();
            $table->string('shipping_zipcode', 20)->nullable();

            // Snapshot FACTURACIÓN
            $table->string('billing_nombre', 100)->nullable();
            $table->string('billing_direccion', 255)->nullable();
            $table->string('billing_ciudad', 100)->nullable();
            $table->string('billing_estado', 100)->nullable();
            $table->string('billing_zipcode', 20)->nullable();
            $table->string('billing_telefono', 30)->nullable();

            // Totales
            $table->decimal('subtotal', 10, 2)->default(0);
            $table->decimal('shipping_cost', 10, 2)->default(0);
            $table->decimal('total', 10, 2)->default(0);

            // Estado de la orden interna
            $table->tinyInteger('status_id')->default(1);

            // Datos Pagadito
            $table->string('pagadito_token', 150)->nullable();
            $table->string('pagadito_ref', 150)->nullable();
            $table->string('pagadito_status', 50)->nullable();

            // FKs
            $table->foreign('id_usuario')
                ->references('id')->on('usuarios')
                ->onDelete('set null');
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
