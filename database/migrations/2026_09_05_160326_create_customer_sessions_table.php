<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customer_sessions', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->foreignId('dining_table_id')
                ->constrained('dining_tables')
                ->restrictOnDelete();

            $table->foreignId('table_qr_token_id')
                ->constrained('table_qr_tokens')
                ->restrictOnDelete();

            $table->string('customer_id')->nullable();

            $table->binary('session_token_hash', 32)->unique();

            $table->string('status')->default('active');

            $table->timestamp('expires_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customer_sessions');
    }
};
