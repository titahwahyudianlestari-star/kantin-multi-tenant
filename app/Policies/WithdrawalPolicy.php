<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Withdrawal;

class WithdrawalPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole('tenant');
    }

    public function view(User $user, Withdrawal $withdrawal): bool
    {
        return $this->belongsToUserTenant($user, $withdrawal->tenant_id);
    }

    public function create(User $user): bool
    {
        return $user->hasRole('tenant');
    }

    public function update(User $user, Withdrawal $withdrawal): bool
    {
        return $this->belongsToUserTenant($user, $withdrawal->tenant_id);
    }

    public function delete(User $user, Withdrawal $withdrawal): bool
    {
        return $this->belongsToUserTenant($user, $withdrawal->tenant_id);
    }

    private function belongsToUserTenant(User $user, int $tenantId): bool
    {
        return $user->tenants()->whereKey($tenantId)->exists();
    }
}
