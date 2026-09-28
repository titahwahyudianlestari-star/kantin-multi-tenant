<?php

namespace App\Modules\Admin\Services;

use App\Models\Commission;
use App\Models\Tenant;
use DomainException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class CommissionService
{
    public function getByTenant(Tenant $tenant): Collection
    {
        return Commission::query()
            ->where('tenant_id', $tenant->id)
            ->orderByDesc('valid_from')
            ->get();
    }

    public function create(
        Tenant $tenant,
        array $data
    ): Commission {
        return DB::transaction(function () use ($tenant, $data): Commission {
            $validFrom = $data['valid_from'];
            $validTo = $data['valid_to'] ?? null;

            $activeCommission = Commission::query()
                ->where('tenant_id', $tenant->id)
                ->whereNull('valid_to')
                ->orderByDesc('valid_from')
                ->first();

            if ($activeCommission !== null) {
                if ($validFrom <= $activeCommission->valid_from->format('Y-m-d')) {
                    throw new DomainException(
                        'Commission baru harus berlaku setelah commission aktif saat ini.'
                    );
                }

                $activeCommission->update([
                    'valid_to' => date(
                        'Y-m-d',
                        strtotime($validFrom.' -1 day')
                    ),
                ]);
            }

            $this->ensureNoOverlap(
                $tenant,
                $validFrom,
                $validTo
            );

            return Commission::create([
                'tenant_id' => $tenant->id,
                'commission_rate' => $data['commission_rate'],
                'valid_from' => $validFrom,
                'valid_to' => $validTo,
            ]);
        });
    }

    public function update(
        Commission $commission,
        array $data
    ): Commission {
        return DB::transaction(function () use ($commission, $data): Commission {
            $this->ensureNoOverlap(
                $commission->tenant,
                $data['valid_from'],
                $data['valid_to'] ?? null,
                $commission->id
            );

            $commission->update([
                'commission_rate' => $data['commission_rate'],
                'valid_from' => $data['valid_from'],
                'valid_to' => $data['valid_to'] ?? null,
            ]);

            return $commission->fresh();
        });
    }

    public function getActive(
        Tenant $tenant,
        string $date
    ): ?Commission {
        return Commission::query()
            ->where('tenant_id', $tenant->id)
            ->whereDate('valid_from', '<=', $date)
            ->where(function ($query) use ($date): void {
                $query
                    ->whereNull('valid_to')
                    ->orWhereDate('valid_to', '>=', $date);
            })
            ->orderByDesc('valid_from')
            ->first();
    }

    private function ensureNoOverlap(
        Tenant $tenant,
        string $validFrom,
        ?string $validTo,
        ?int $ignoreCommissionId = null
    ): void {
        $query = Commission::query()
            ->where('tenant_id', $tenant->id)
            ->whereDate(
                'valid_from',
                '<=',
                $validTo ?? '9999-12-31'
            )
            ->where(function ($query) use ($validFrom): void {
                $query
                    ->whereNull('valid_to')
                    ->orWhereDate('valid_to', '>=', $validFrom);
            });

        if ($ignoreCommissionId !== null) {
            $query->where('id', '!=', $ignoreCommissionId);
        }

        if ($query->exists()) {
            throw new DomainException(
                'Periode commission tidak boleh overlap dengan commission yang sudah ada.'
            );
        }
    }
}
