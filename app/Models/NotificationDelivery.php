<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NotificationDelivery extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'order_id',
        'customer_session_id',
        'tenant_order_id',
        'event_key',
        'channel',
        'status',
        'attempts',
        'payload',
    ];

    protected $casts = [
        'payload' => 'array',
    ];

    /** @return BelongsTo<Order, $this> */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /** @return BelongsTo<CustomerSession, $this> */
    public function customerSession(): BelongsTo
    {
        return $this->belongsTo(CustomerSession::class);
    }

    /** @return BelongsTo<TenantOrder, $this> */
    public function tenantOrder(): BelongsTo
    {
        return $this->belongsTo(TenantOrder::class);
    }
}
