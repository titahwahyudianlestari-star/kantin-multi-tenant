<?php

namespace App\Modules\Catalog\Services;

use App\Models\Canteen;
use App\Models\Tenant;
use App\Models\TenantOperatingHour;
use Carbon\CarbonInterface;
use Illuminate\Support\Collection;

final class TenantOpeningHours
{
    public function isOpen(
        Tenant $tenant,
        ?CarbonInterface $at = null
    ): bool {
        $hours = $this->hoursFor($tenant);

        if ($hours->isEmpty()) {
            return true;
        }

        $local = $this->local($at);
        $day = strtolower($local->format('l'));

        $today = $hours->firstWhere('day', $day);

        if ($today === null) {
            return false;
        }

        $now = $local->format('H:i:s');
        $opens = (string) $today->opens_at;
        $closes = (string) $today->closes_at;

        if ($opens <= $closes) {
            return $now >= $opens && $now < $closes;
        }

        return $now >= $opens || $now < $closes;
    }

    public function todayLabel(
        Tenant $tenant,
        ?CarbonInterface $at = null
    ): ?string {
        $local = $this->local($at);
        $day = strtolower($local->format('l'));

        $today = $this->hoursFor($tenant)
            ->firstWhere('day', $day);

        if ($today === null) {
            return null;
        }

        return $this->fmt((string) $today->opens_at)
            .'–'
            .$this->fmt((string) $today->closes_at);
    }

    public function opensAtToday(
        Tenant $tenant,
        ?CarbonInterface $at = null
    ): ?string {
        $local = $this->local($at);
        $day = strtolower($local->format('l'));

        $today = $this->hoursFor($tenant)
            ->firstWhere('day', $day);

        if ($today === null) {
            return null;
        }

        return $this->fmt((string) $today->opens_at);
    }

    public function canteenIsOpen(
        Canteen $canteen,
        ?CarbonInterface $at = null
    ): bool {
        $tenants = $this->activeTenants($canteen);

        if ($tenants->isEmpty()) {
            return true;
        }

        $allUnconfigured = $tenants->every(
            fn (Tenant $tenant): bool => $tenant->operatingHours->isEmpty()
        );

        if ($allUnconfigured) {
            return true;
        }

        return $tenants->contains(
            fn (Tenant $tenant): bool => $this->isOpen($tenant, $at)
        );
    }

    /**
     * @return Collection<int, Tenant>
     */
    public function activeTenants(
        Canteen $canteen
    ): Collection {
        return Tenant::query()
            ->where('canteen_id', $canteen->id)
            ->where('status', 'active')
            ->with([
                'operatingHours' => fn ($query) => $query->withoutGlobalScope('tenant'),
            ])
            ->orderBy('code')
            ->get();
    }

    /**
     * @return Collection<int, TenantOperatingHour>
     */
    private function hoursFor(
        Tenant $tenant
    ): Collection {
        if ($tenant->relationLoaded('operatingHours')) {
            return $tenant->operatingHours;
        }

        return $tenant->operatingHours()
            ->withoutGlobalScope('tenant')
            ->get();
    }

    private function local(
        ?CarbonInterface $at
    ): CarbonInterface {
        return ($at ?? now())
            ->copy()
            ->setTimezone(
                (string) config(
                    'app.display_timezone',
                    'Asia/Jakarta'
                )
            );
    }

    private function fmt(string $time): string
    {
        return str_replace(
            ':',
            '.',
            substr($time, 0, 5)
        );
    }
}
