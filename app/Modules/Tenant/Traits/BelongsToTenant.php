<?php

namespace App\Modules\Tenant\Traits;

use App\Models\Tenant;
use App\Modules\Tenant\TenantContext;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use RuntimeException;

trait BelongsToTenant
{
    protected static function bootBelongsToTenant(): void
    {
        static::addGlobalScope('tenant', function (Builder $builder): void {
            $tenantContext = app(TenantContext::class);

            if (! $tenantContext->has()) {
                throw new RuntimeException('Tenant context belum ditentukan.');
            }

            $builder->where(
                $builder->getModel()->getTable().'.tenant_id',
                $tenantContext->id(),
            );
        });

        static::creating(function (Model $model): void {
            $tenantContext = app(TenantContext::class);

            if (! $tenantContext->has()) {
                throw new RuntimeException('Tenant context belum ditentukan.');
            }

            $model->tenant_id = $tenantContext->id();
        });
    }

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }
}
