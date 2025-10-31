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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('code', 40)->unique();               // ej. ORD-20251030-0001
            $table->foreignId('user_id')->nullable()->constrained('usuarios')->nullOnDelete();

            // Moneda y totales
            $table->string('currency', 3)->default('USD');      // ISO 4217
            $table->decimal('fx_rate', 12, 6)->default(1);      // tipo de cambio si facturas en otra
            $table->decimal('sub_total', 12, 2)->default(0);
            $table->decimal('shipping_total', 12, 2)->default(0);
            $table->decimal('grand_total', 12, 2)->default(0);

            // Envío
            $table->unsignedBigInteger('shipping_address_id')->nullable(); // referencia a snapshot
            $table->string('shipping_service')->nullable();                 // nombre del carrier/tarifa
            $table->string('shipping_country_code', 2)->nullable();         // ES, US, etc.

            // Facturación (snapshot referenciado)
            $table->unsignedBigInteger('billing_address_id')->nullable();

            // Cupón / promos
            $table->string('coupon_code', 60)->nullable();
            $table->decimal('coupon_amount', 12, 2)->default(0);

            // Estado del pedido
            // draft -> payment_pending -> paid -> fulfilled -> closed | failed | cancelled
            $table->string('status', 30)->default('payment_pending')->index();

            // Método de pago declarado por el cliente (card, cod, etc.)
            $table->string('pay_method', 30)->nullable();
            $table->string('pay_gateway', 30)->nullable()->index(); // 'pagadito'
            $table->string('pay_token', 120)->nullable();           // token/sesión inicial
            $table->timestamp('paid_at')->nullable();

            // Meta varios
            $table->json('meta')->nullable(); // {notes, source, device, ip, etc.}

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
