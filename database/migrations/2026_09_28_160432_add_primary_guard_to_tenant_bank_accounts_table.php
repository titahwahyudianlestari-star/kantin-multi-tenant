<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tenant_bank_accounts', function (Blueprint $table) {
            $table->unsignedBigInteger('primary_tenant_guard')
                ->nullable()
                ->storedAs('IF(is_primary = 1, tenant_id, NULL)');

            $table->unique(
                'primary_tenant_guard',
                'uq_bank_account_primary_per_tenant'
            );
        });
    }

    public function down(): void
    {
        Schema::table('tenant_bank_accounts', function (Blueprint $table) {
            $table->dropUnique('uq_bank_account_primary_per_tenant');
            $table->dropColumn('primary_tenant_guard');
        });
    }
};
