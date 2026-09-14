<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    protected $fillable = [
        'canteen_id',
        'customer_session_id',
        'order_number',
        'checkout_key',
        'tracking_token_hash',
        'status',
        'subtotal_amount',
        'total_amount',
        'customer_snapshot',
    ];

    protected $casts = [
        'customer_snapshot' => 'array',
    ];

    /** @return BelongsTo<Canteen, $this> */
    public function canteen(): BelongsTo
    {
        return $this->belongsTo(Canteen::class);
    }

    /** @return BelongsTo<CustomerSession, $this> */
    public function customerSession(): BelongsTo
    {
        return $this->belongsTo(CustomerSession::class);
    }

    /** @return HasMany<TenantOrder, $this> */
    public function tenantOrders(): HasMany
    {
        return $this->hasMany(TenantOrder::class);
    }

    /** @return HasMany<OrderItem, $this> */
    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    /** @return HasMany<Payment, $this> */
    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    /** @return HasMany<NotificationDelivery, $this> */
    public function notificationDeliveries(): HasMany
    {
        return $this->hasMany(NotificationDelivery::class);
    }

    /** @return HasMany<OutboxEvent, $this> */
    public function outboxEvents(): HasMany
    {
        return $this->hasMany(OutboxEvent::class);
    }

    /** @return HasMany<AuditLog, $this> */
    public function auditLogs(): HasMany
    {
        return $this->hasMany(AuditLog::class);
    }
}
