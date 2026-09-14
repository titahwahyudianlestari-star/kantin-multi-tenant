<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class OrderItem extends Model
{
    protected $fillable = [
        'tenant_order_id',
        'menu_id',
        'quantity',
        'unit_price',
        'subtotal',
        'menu_snapshot',
    ];

    protected $casts = [
        'menu_snapshot' => 'array',
    ];

    /** @return BelongsTo<TenantOrder, $this> */
    public function tenantOrder(): BelongsTo
    {
        return $this->belongsTo(TenantOrder::class);
    }

    /** @return BelongsTo<Menu, $this> */
    public function menu(): BelongsTo
    {
        return $this->belongsTo(Menu::class);
    }

    /** @return HasMany<OrderItemModifier, $this> */
    public function modifiers(): HasMany
    {
        return $this->hasMany(OrderItemModifier::class);
    }

    /** @return HasMany<MenuStockMovement, $this> */
    public function stockMovements(): HasMany
    {
        return $this->hasMany(MenuStockMovement::class);
    }
}
