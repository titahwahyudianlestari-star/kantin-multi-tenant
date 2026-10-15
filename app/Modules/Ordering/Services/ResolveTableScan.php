<?php

namespace App\Modules\Ordering\Services;

use App\Models\Canteen;
use App\Models\CustomerSession;
use App\Models\DiningTable;
use App\Models\TableQrToken;
use App\Modules\Catalog\Services\TenantOpeningHours;
use App\Support\Tokens\OpaqueToken;
use Illuminate\Support\Str;

final class ResolveTableScan
{
    public function __construct(
        private TenantOpeningHours $hours
    ) {}

    /**
     * @return array{
     *     status: 'ok',
     *     session: CustomerSession,
     *     plain: string,
     *     canteen: Canteen,
     *     table: DiningTable
     * }|array{
     *     status: 'closed',
     *     canteen: Canteen,
     *     table: DiningTable
     * }|null
     */
    public function resolve(string $plainToken): ?array
    {
        $qr = TableQrToken::query()
            ->where(
                'token_hash',
                OpaqueToken::hash($plainToken)
            )
            ->with('diningTable.canteen')
            ->first();

        if ($qr === null || ! $qr->isActive()) {
            return null;
        }

        $table = $qr->diningTable;

        if (
            ! $table instanceof DiningTable
            || ! $table->isActive()
        ) {
            return null;
        }

        $canteen = $table->canteen;

        if (
            ! $canteen instanceof Canteen
            || $canteen->status !== 'active'
        ) {
            return null;
        }

        /*
         * UC-02 alur 3b:
         * QR valid, tetapi kantin sedang di luar
         * jam operasional.
         *
         * Jangan membuat CustomerSession.
         */
        if (! $this->hours->canteenIsOpen($canteen)) {
            return [
                'status' => 'closed',
                'canteen' => $canteen,
                'table' => $table,
            ];
        }

        $sessionToken = OpaqueToken::issue(32);

        $session = new CustomerSession;

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
            'status' => 'ok',
            'session' => $session,
            'plain' => $sessionToken['plain'],
            'canteen' => $canteen,
            'table' => $table,
        ];
    }
}
