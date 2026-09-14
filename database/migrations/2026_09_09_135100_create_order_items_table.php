<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();

            $table->foreignId('tenant_order_id')
                ->constrained('tenant_orders')
                ->restrictOnDelete();

            $table->foreignId('menu_id')
                ->constrained('menus')
                ->restrictOnDelete();

            $table->string('name_snapshot');
            $table->unsignedBigInteger('price_snapshot');
            $table->string('group_snapshot')->nullable();
            $table->unsignedInteger('quantity')->default(1);
            $table->unsignedBigInteger('line_total')->default(0);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
