<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();

            $table->foreignId('canteen_id')
                ->constrained('canteens')
                ->restrictOnDelete();

            $table->uuid('customer_session_id')
                ->nullable();

            $table->foreign('customer_session_id')
                ->references('id')
                ->on('customer_sessions')
                ->restrictOnDelete();

            $table->string('order_number', 50)->unique();
            $table->binary('checkout_key')->nullable();
            $table->binary('tracking_token_hash')->nullable();
            $table->string('status')->default('pending');
            $table->unsignedBigInteger('subtotal_amount')->default(0);
            $table->unsignedBigInteger('total_amount')->default(0);
            $table->json('customer_snapshot')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
