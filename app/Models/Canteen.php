<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Canteen extends Model
{
    protected $fillable = [
        'code',
        'slug',
        'tax_rate',
        'service_fee_rate',
        'status',
    ];

    /**
     * @return HasMany<Tenant, $this>
     */
    public function tenants(): HasMany
    {
        return $this->hasMany(Tenant::class);
    }

    /**
     * @return HasMany<DiningTable, $this>
     */
    public function diningTables(): HasMany
    {
        return $this->hasMany(DiningTable::class);
    }

    /**
     * @return HasMany<UserCanteenRole, $this>
     */
    public function userCanteenRoles(): HasMany
    {
        return $this->hasMany(UserCanteenRole::class);
    }

    /**
     * @return HasMany<Order, $this>
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }
}
