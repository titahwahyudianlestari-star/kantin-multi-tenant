<?php

namespace App\Models;

use App\Modules\Tenant\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Menu extends Model
{
    use BelongsToTenant;

    public $timestamps = false;

    protected $fillable = [
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

    /**
     * @return BelongsTo<Tenant, $this>
     */
    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    /**
     * @return BelongsTo<MenuCategory, $this>
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(MenuCategory::class, 'category_id');
    }

    /**
     * @return HasMany<MenuModifierGroup, $this>
     */
    public function modifierGroups(): HasMany
    {
        return $this->hasMany(MenuModifierGroup::class);
    }

    /**
     * @return HasMany<MenuStockMovement, $this>
     */
    public function stockMovements(): HasMany
    {
        return $this->hasMany(MenuStockMovement::class);
    }

    /**
     * @return HasMany<OrderItem, $this>
     */
    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
}
