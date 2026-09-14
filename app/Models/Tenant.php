<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tenant extends Model
{
    use SoftDeletes;

    public $timestamps = false;

    protected $fillable = [
        'canteen_id',
        'code',
        'slug',
        'status',
    ];

    /** @return BelongsTo<Canteen, $this> */
    public function canteen(): BelongsTo
    {
        return $this->belongsTo(Canteen::class);
    }

    /** @return HasOne<TenantBalance, $this> */
    public function balance(): HasOne
    {
        return $this->hasOne(TenantBalance::class);
    }

    /** @return HasMany<UserTenantRole, $this> */
    public function userTenantRoles(): HasMany
    {
        return $this->hasMany(UserTenantRole::class);
    }

    /** @return HasMany<TenantBankAccount, $this> */
    public function bankAccounts(): HasMany
    {
        return $this->hasMany(TenantBankAccount::class);
    }

    /** @return HasMany<TenantOperatingHour, $this> */
    public function operatingHours(): HasMany
    {
        return $this->hasMany(TenantOperatingHour::class);
    }

    /** @return HasMany<CommissionScheme, $this> */
    public function commissionSchemes(): HasMany
    {
        return $this->hasMany(CommissionScheme::class);
    }

    /** @return HasMany<MenuCategory, $this> */
    public function menuCategories(): HasMany
    {
        return $this->hasMany(MenuCategory::class);
    }

    /** @return HasMany<Menu, $this> */
    public function menus(): HasMany
    {
        return $this->hasMany(Menu::class);
    }

    /** @return HasMany<TenantOrder, $this> */
    public function orders(): HasMany
    {
        return $this->hasMany(TenantOrder::class);
    }
}
