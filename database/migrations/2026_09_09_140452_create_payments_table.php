<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();

            $table->foreignId('order_id')
                ->constrained('orders')
                ->restrictOnDelete();

            $table->unsignedBigInteger('amount');
            $table->string('status')->default('pending');
            $table->timestamp('settled_at')->nullable();
            $table->string('payment_reference')->unique();
            $table->string('idempotency_key');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
