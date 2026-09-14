<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PaymentAttempt extends Model
{
    protected $fillable = [
        'payment_id',
        'provider',
        'provider_reference',
        'amount',
        'status',
        'attempted_at',
        'response_payload',
    ];

    protected $casts = [
        'attempted_at' => 'datetime',
        'response_payload' => 'array',
    ];

    /** @return BelongsTo<Payment, $this> */
    public function payment(): BelongsTo
    {
        return $this->belongsTo(Payment::class);
    }

    /** @return HasMany<PaymentEvent, $this> */
    public function events(): HasMany
    {
        return $this->hasMany(PaymentEvent::class);
    }
}
