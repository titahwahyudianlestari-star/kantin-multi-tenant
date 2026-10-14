<?php

namespace App\Modules\Admin\Services;

use App\Models\DiningTable;
use App\Models\TableQrToken;
use App\Support\Tokens\OpaqueToken;
use Carbon\CarbonInterface;
use Illuminate\Support\Facades\DB;

final class QrTokenService
{
    public function __construct(
        private AuditLogger $audit
    ) {
    }

    public function issue(
        DiningTable $table,
        ?CarbonInterface $expiresAt = null
    ): string {
        return DB::transaction(function () use ($table, $expiresAt): string {
            $locked = DiningTable::query()
                ->lockForUpdate()
                ->findOrFail($table->id);

            TableQrToken::query()
                ->where('dining_table_id', $locked->id)
                ->where('status', 'active')
                ->whereNull('revoked_at')
                ->update([
                    'status' => 'revoked',
                    'revoked_at' => now(),
                ]);

            $token = OpaqueToken::issue(32);

            $qr = new TableQrToken();

            $qr->forceFill([
                'dining_table_id' => $locked->id,
                'token_hash' => $token['hash'],
                'status' => 'active',
                'issued_at' => now(),
                'expires_at' => $expiresAt,
                'revoked_at' => null,
            ])->save();

            $this->audit->record(
                'table_qr_token.issued',
                null,
                null,
                [
                    'canteen_id' => $locked->canteen_id,
                    'dining_table_id' => $locked->id,
                    'table_qr_token_id' => $qr->id,
                ]
            );

            return $token['plain'];
        });
    }

    public function rotate(DiningTable $table): string
    {
        return $this->issue($table);
    }
}