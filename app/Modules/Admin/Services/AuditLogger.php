<?php

namespace App\Modules\Admin\Services;

use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;

class AuditLogger
{
    public function record(
        string $action,
        ?int $tenantId,
        ?array $before = null,
        ?array $after = null
    ): AuditLog {
        return AuditLog::create([
            'tenant_id' => $tenantId,
            'actor_id' => Auth::id(),
            'action' => $action,
            'occurred_at' => now(),
            'before_metadata' => $this->sanitize($before),
            'after_metadata' => $this->sanitize($after),
        ]);
    }

    private function sanitize(?array $data): ?array
    {
        if ($data === null) {
            return null;
        }

        unset(
            $data['account_number'],
            $data['account_number_cipher']
        );

        return $data;
    }
}
