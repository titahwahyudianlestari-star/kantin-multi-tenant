<?php

namespace App\Modules\Admin\Services;

use App\Models\Tenant;
use App\Models\User;
use App\Models\UserTenantRole;
use DomainException;

class AssignTenantRole
{
    public function assign(
        Tenant $tenant,
        User $user,
        string $role
    ): UserTenantRole {
        return UserTenantRole::updateOrCreate(
            [
                'user_id' => $user->id,
                'tenant_id' => $tenant->id,
            ],
            [
                'role' => $role,
            ]
        );
    }

    public function remove(
        Tenant $tenant,
        User $user,
        string $role
    ): void {
        if ($role === 'owner') {
            $owners = UserTenantRole::query()
                ->where('tenant_id', $tenant->id)
                ->where('role', 'owner')
                ->count();

            if ($owners <= 1) {
                throw new DomainException(
                    'Tidak boleh menghapus owner terakhir tenant.'
                );
            }
        }

        UserTenantRole::query()
            ->where('user_id', $user->id)
            ->where('tenant_id', $tenant->id)
            ->where('role', $role)
            ->delete();
    }
}
