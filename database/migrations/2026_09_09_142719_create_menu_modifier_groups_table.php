<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('menu_modifier_groups', function (Blueprint $table) {
            $table->id();

            $table->foreignId('tenant_id')
                ->constrained('tenants')
                ->restrictOnDelete();

            $table->unsignedBigInteger('menu_id');
            $table->unsignedBigInteger('modifier_group_id');

            $table->unsignedInteger('sort_order')->default(0);

            $table->unique(['menu_id', 'modifier_group_id']);

            $table->index(['tenant_id', 'menu_id']);
            $table->index(['tenant_id', 'modifier_group_id']);

            $table->foreign(['tenant_id', 'menu_id'])
                ->references(['tenant_id', 'id'])
                ->on('menus')
                ->restrictOnDelete();

            $table->foreign(['tenant_id', 'modifier_group_id'])
                ->references(['tenant_id', 'id'])
                ->on('modifier_groups')
                ->restrictOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('menu_modifier_groups');
    }
};
