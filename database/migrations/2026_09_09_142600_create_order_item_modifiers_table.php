<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_item_modifiers', function (Blueprint $table) {
            $table->id();

            $table->foreignId('order_item_id')
                ->constrained('order_items')
                ->restrictOnDelete();

            $table->foreignId('modifier_group_id')
                ->constrained('modifier_groups')
                ->restrictOnDelete();

            $table->foreignId('modifier_option_id')
                ->constrained('modifier_options')
                ->restrictOnDelete();

            $table->json('group_snapshot')->nullable();
            $table->json('options_snapshot')->nullable();

            $table->unsignedBigInteger('price_delta_snapshot')->default(0);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_item_modifiers');
    }
};
