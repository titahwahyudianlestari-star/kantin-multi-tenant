<?php

namespace App\Modules\Admin\Services;

use App\Models\Tenant;

class UpdateTenant
{
    public function handle(Tenant $tenant, array $data): Tenant
    {
        $tenant->update([
            'canteen_id' => $data['canteen_id'],
            'code' => $data['code'],
            'slug' => $data['slug'],
            'status' => $data['status'],
        ]);

        return $tenant->fresh();
    }
}
