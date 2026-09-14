<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TenantOrder extends Model
{
    protected $fillable = [
        'tenant_id',
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

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function commission(): BelongsTo
    {
        return $this->belongsTo(CommissionScheme::class, 'commission_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function notifications(): HasMany
    {
        return $this->hasMany(NotificationDelivery::class);
    }
}
