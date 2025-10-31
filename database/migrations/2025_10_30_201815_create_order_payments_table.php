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
        Schema::create('order_payments', function (Blueprint $table) {
            $table->id();

            $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete();
            $table->string('gateway', 30)->index();           // 'pagadito'
            $table->string('gateway_env', 15)->default('sandbox');
            $table->string('method', 30)->nullable();         // card, cod...
            $table->string('brand', 30)->nullable();          // VISA/MC si aplica
            $table->string('last4', 4)->nullable();
            $table->string('token', 120)->nullable();         // session/id inicial
            $table->string('transaction_id', 120)->nullable()->index(); // id de Pagadito
            $table->string('status', 30)->default('initiated')->index(); // initiated|pending|approved|failed
            $table->decimal('amount', 12, 2)->default(0);
            $table->string('currency', 3)->default('USD');
            $table->json('request_payload')->nullable();      // lo que enviaste a la API
            $table->json('response_payload')->nullable();     // respuesta de create/execute
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_payments');
    }
};
