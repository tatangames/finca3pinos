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
        Schema::create('orders_items', function (Blueprint $table) {
            $table->id();

            $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete();
            $table->nullableMorphs('purchasable');      // product_id / type (si usas variaciones)
            $table->string('sku', 60)->nullable();
            $table->string('name', 160);                // snapshot del nombre
            $table->string('presentation', 100)->nullable(); // ej. “Grano 340g”
            $table->unsignedInteger('qty')->default(1);
            $table->decimal('unit_price', 12, 2)->default(0);  // antes de descuentos/impuestos
            $table->decimal('discount', 12, 2)->default(0);
            $table->decimal('tax', 12, 2)->default(0);
            $table->decimal('total', 12, 2)->default(0);       // (unit - disc + tax) * qty
            $table->json('meta')->nullable(); // {attributes: {...}}
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders_items');
    }
};
