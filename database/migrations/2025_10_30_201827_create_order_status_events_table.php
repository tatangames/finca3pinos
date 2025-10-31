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
        Schema::create('order_status_events', function (Blueprint $table) {
            $table->id();

            $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete();
            $table->string('from', 30)->nullable();
            $table->string('to', 30);
            $table->string('context', 40)->nullable(); // 'webhook','admin','customer'
            $table->text('note')->nullable();
            $table->foreignId('user_id')->nullable()->constrained('usuarios')->nullOnDelete(); // si un admin cambia algo
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_status_events');
    }
};
