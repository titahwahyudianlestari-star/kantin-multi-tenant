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
        'name_snapshot',
        'price_snapshot',
        'group_snapshot',
        'quantity',
        'line_total',
    ];

    protected $casts = [
        'group_snapshot' => 'array',
    ];

    public function tenantOrder(): BelongsTo
    {
        return $this->belongsTo(TenantOrder::class);
    }

    public function menu(): BelongsTo
    {
        return $this->belongsTo(Menu::class);
    }

    public function modifiers(): HasMany
    {
        return $this->hasMany(OrderItemModifier::class);
    }

    public function stockMovements(): HasMany
    {
        return $this->hasMany(MenuStockMovement::class);
    }
}
