<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('modifier_options', function (Blueprint $table) {
            $table->id();

            $table->foreignId('tenant_id')
                ->constrained('tenants')
                ->restrictOnDelete();

            $table->unsignedBigInteger('group_id');

            $table->string('name');
            $table->unsignedBigInteger('price_delta')->default(0);
            $table->unsignedInteger('stock_qty')->default(0);
            $table->boolean('is_available')->default(true);

            $table->unique(['tenant_id', 'id']);

            $table->foreign(['tenant_id', 'group_id'])
                ->references(['tenant_id', 'id'])
                ->on('modifier_groups')
                ->restrictOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('modifier_options');
    }
};
