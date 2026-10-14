<?php

namespace App\Modules\Ordering\Services;

use App\Models\Canteen;
use App\Models\CustomerSession;
use App\Models\TableQrToken;
use App\Support\Tokens\OpaqueToken;
use Illuminate\Support\Str;

final class ResolveTableScan
{
    /**
     * @return array{
     *     session: CustomerSession,
     *     plain: string,
     *     canteen: Canteen
     * }|null
     */
    public function resolve(string $plainToken): ?array
    {
        $qr = TableQrToken::query()
            ->where('token_hash', OpaqueToken::hash($plainToken))
            ->with('diningTable.canteen')
            ->first();

        if ($qr === null || ! $qr->isActive()) {
            return null;
        }

        $table = $qr->diningTable;

        if ($table === null || ! $table->isActive()) {
            return null;
        }

        $canteen = $table->canteen;

        if (! $canteen instanceof Canteen || $canteen->status !== 'active') {
            return null;
        }

        $sessionToken = OpaqueToken::issue(32);

        $session = new CustomerSession();

        $session->forceFill([
            'id' => (string) Str::uuid(),
            'dining_table_id' => $table->id,
            'table_qr_token_id' => $qr->id,
            'customer_id' => null,
            'session_token_hash' => $sessionToken['hash'],
            'status' => 'active',
            'expires_at' => now()->addHours(4),
        ])->save();

        return [
            'session' => $session,
            'plain' => $sessionToken['plain'],
            'canteen' => $canteen,
        ];
    }
}