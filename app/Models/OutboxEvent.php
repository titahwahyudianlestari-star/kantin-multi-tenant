<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OutboxEvent extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'order_id',
        'tenant_order_id',
        'event_type',
        'payload',
        'status',
        'available_at',
        'processed_at',
    ];

    protected $casts = [
        'payload' => 'array',
        'available_at' => 'datetime',
        'processed_at' => 'datetime',
    ];

    /** @return BelongsTo<Order, $this> */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /** @return BelongsTo<TenantOrder, $this> */
    public function tenantOrder(): BelongsTo
    {
        return $this->belongsTo(TenantOrder::class);
    }
}
