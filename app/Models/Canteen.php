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

    public function tenants(): HasMany
    {
        return $this->hasMany(Tenant::class);
    }

    public function diningTables(): HasMany
    {
        return $this->hasMany(DiningTable::class);
    }

    public function userCanteenRoles(): HasMany
    {
        return $this->hasMany(UserCanteenRole::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }
}
