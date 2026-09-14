<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notification_deliveries', function (Blueprint $table) {
            $table->id();

            $table->foreignId('order_id')
                ->nullable()
                ->constrained('orders')
                ->restrictOnDelete();

            $table->uuid('customer_session_id')
                ->nullable();

            $table->foreign('customer_session_id')
                ->references('id')
                ->on('customer_sessions')
                ->restrictOnDelete();

            $table->foreignId('tenant_order_id')
                ->nullable()
                ->constrained('tenant_orders')
                ->restrictOnDelete();

            $table->string('event_key');
            $table->string('channel');
            $table->string('status')->default('pending');
            $table->unsignedInteger('attempts')->default(0);
            $table->json('payload')->nullable();

            $table->unique(['event_key', 'channel']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notification_deliveries');
    }
};
