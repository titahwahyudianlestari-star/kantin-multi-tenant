<?php

namespace App\Modules\Admin\Services;

use App\Models\Tenant;
use Illuminate\Support\Facades\DB;

class CreateTenant
{
    public function handle(array $data): Tenant
    {
        return DB::transaction(function () use ($data): Tenant {
            return Tenant::create([
                'canteen_id' => $data['canteen_id'],
                'code' => $data['code'],
                'slug' => $data['slug'],
                'status' => $data['status'] ?? 'active',
            ]);
        });
    }
}
