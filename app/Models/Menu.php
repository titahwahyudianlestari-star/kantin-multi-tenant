<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Menu extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'tenant_id',
        'category_id',
        'name',
        'slug',
        'price_amount',
        'stock_qty',
        'is_available',
        'prep_minutes',
    ];

    protected $casts = [
        'is_available' => 'boolean',
    ];

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(MenuCategory::class, 'category_id');
    }

    public function modifierGroups(): HasMany
    {
        return $this->hasMany(MenuModifierGroup::class);
    }

    public function stockMovements(): HasMany
    {
        return $this->hasMany(MenuStockMovement::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
}
