<?php

namespace App\Modules\Catalog\Queries;

use App\Models\Menu;
use Illuminate\Database\Eloquent\Collection;

class PublicCatalogQuery
{
    /**
     * Mengambil menu yang tersedia untuk tenant tertentu.
     */
    public function forTenant(int $tenantId): Collection
    {
        return Menu::withoutGlobalScopes()
            ->where('tenant_id', $tenantId)
            ->where('is_available', true)
            ->orderBy('name')
            ->get();
    }
}
