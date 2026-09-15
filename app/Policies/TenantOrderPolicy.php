<?php

namespace App\Policies;

use App\Models\TenantOrder;
use App\Models\User;

class TenantOrderPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole('tenant');
    }

    public function view(User $user, TenantOrder $tenantOrder): bool
    {
        return $this->belongsToUserTenant($user, $tenantOrder->tenant_id);
    }

    public function create(User $user): bool
    {
        return $user->hasRole('tenant');
    }

    public function update(User $user, TenantOrder $tenantOrder): bool
    {
        return $this->belongsToUserTenant($user, $tenantOrder->tenant_id);
    }

    public function delete(User $user, TenantOrder $tenantOrder): bool
    {
        return $this->belongsToUserTenant($user, $tenantOrder->tenant_id);
    }

    private function belongsToUserTenant(User $user, int $tenantId): bool
    {
        return $user->tenants()->whereKey($tenantId)->exists();
    }
}
