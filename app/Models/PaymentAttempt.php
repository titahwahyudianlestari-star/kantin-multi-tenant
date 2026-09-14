<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PaymentAttempt extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'payment_id',
        'gateway',
        'provider_reference',
        'amount',
        'status',
    ];

    public function payment(): BelongsTo
    {
        return $this->belongsTo(Payment::class);
    }

    public function events(): HasMany
    {
        return $this->hasMany(PaymentEvent::class, 'attempt_id');
    }
}
