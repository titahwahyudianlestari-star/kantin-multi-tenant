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
        Schema::create('tenant_balances', function (Blueprint $table) {
            $table->foreignId('tenant_id')
                ->primary()
                ->constrained('tenants')
                ->restrictOnDelete();

            $table->unsignedBigInteger('available_amount')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tenant_balances');
    }
};
