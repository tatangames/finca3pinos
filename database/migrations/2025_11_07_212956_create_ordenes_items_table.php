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
        Schema::create('ordenes_items', function (Blueprint $table) {
            $table->id();
            // Relación con la orden
            $table->unsignedBigInteger('id_orden');

            // Relación opcional con productos (si tienes tabla productos)
            $table->unsignedBigInteger('id_producto')->nullable();

            // Relación opcional con productos (si tienes tabla productos)
            $table->unsignedBigInteger('id_presentacion')->nullable();

            // Snapshot del producto en el momento de la compra
            $table->string('nombre', 150);
            $table->decimal('precio', 10, 2);      // precio unitario
            $table->integer('cantidad');
            $table->decimal('subtotal', 10, 2);    // precio * cantidad

            $table->timestamps();

            // FKs
            $table->foreign('id_orden')
                ->references('id')->on('ordenes')
                ->onDelete('cascade');

            $table->foreign('id_producto')
                ->references('id')->on('productos')
                ->onDelete('set null');

            $table->foreign('id_presentacion')
                ->references('id')->on('productos_presentacion')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ordenes_items');
    }
};
