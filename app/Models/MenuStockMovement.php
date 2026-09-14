<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MenuStockMovement extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'menu_id',
        'order_item_id',
        'idempotency_key',
        'type',
        'quantity_delta',
    ];

    public function menu(): BelongsTo
    {
        return $this->belongsTo(Menu::class);
    }

    public function orderItem(): BelongsTo
    {
        return $this->belongsTo(OrderItem::class);
    }
}
