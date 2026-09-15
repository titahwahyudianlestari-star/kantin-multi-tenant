<?php

namespace App\Policies;

use App\Models\Menu;
use App\Models\User;

class MenuPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole('tenant');
    }

    public function view(User $user, Menu $menu): bool
    {
        return $this->belongsToUserTenant($user, $menu->tenant_id);
    }

    public function create(User $user): bool
    {
        return $user->hasRole('tenant');
    }

    public function update(User $user, Menu $menu): bool
    {
        return $this->belongsToUserTenant($user, $menu->tenant_id);
    }

    public function delete(User $user, Menu $menu): bool
    {
        return $this->belongsToUserTenant($user, $menu->tenant_id);
    }

    private function belongsToUserTenant(User $user, int $tenantId): bool
    {
        return $user->tenants()->whereKey($tenantId)->exists();
    }
}
