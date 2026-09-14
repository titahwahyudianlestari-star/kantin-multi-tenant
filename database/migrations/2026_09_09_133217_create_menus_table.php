<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('menus', function (Blueprint $table) {
            $table->id();

            $table->foreignId('tenant_id')
                ->constrained('tenants')
                ->restrictOnDelete();

            $table->unsignedBigInteger('category_id');

            $table->string('name', 120);
            $table->string('slug', 120);
            $table->unsignedBigInteger('price_amount');
            $table->unsignedInteger('stock_qty')->default(0);
            $table->boolean('is_available')->default(true);
            $table->unsignedInteger('prep_minutes')->default(0);

            $table->unique(['tenant_id', 'id']);
            $table->unique(['tenant_id', 'slug']);

            $table->foreign(['tenant_id', 'category_id'])
                ->references(['tenant_id', 'id'])
                ->on('menu_categories')
                ->restrictOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('menus');
    }
};
