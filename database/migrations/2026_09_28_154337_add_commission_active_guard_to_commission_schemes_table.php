<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('commission_schemes', function (Blueprint $table) {
            $table->unsignedBigInteger('active_tenant_guard')
                ->nullable()
                ->storedAs('IF(valid_to IS NULL, tenant_id, NULL)');

            $table->unique(
                'active_tenant_guard',
                'uq_commission_active_per_tenant'
            );
        });
    }

    public function down(): void
    {
        Schema::table('commission_schemes', function (Blueprint $table) {
            $table->dropUnique('uq_commission_active_per_tenant');
            $table->dropColumn('active_tenant_guard');
        });
    }
};
