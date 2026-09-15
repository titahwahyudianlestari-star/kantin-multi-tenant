<?php

namespace App\Models;

use App\Modules\Tenant\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TenantOrder extends Model
{
    use BelongsToTenant;

    protected $fillable = [
        'order_id',
        'commission_id',
        'status',
        'subtotal',
        'commission_rate',
        'commission_amount',
        'total_snapshot',
    ];

    protected $casts = [
        'commission_rate' => 'decimal:2',
    ];

    /** @return BelongsTo<Tenant, $this> */
    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    /** @return BelongsTo<Order, $this> */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /** @return BelongsTo<CommissionScheme, $this> */
    public function commission(): BelongsTo
    {
        return $this->belongsTo(CommissionScheme::class, 'commission_id');
    }

    /** @return HasMany<OrderItem, $this> */
    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    /** @return HasMany<NotificationDelivery, $this> */
    public function notifications(): HasMany
    {
        return $this->hasMany(NotificationDelivery::class);
    }
}
