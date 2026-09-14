<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TableQrToken extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'dining_table_id',
        'token_hash',
        'status',
        'issued_at',
        'expires_at',
        'revoked_at',
    ];

    protected $casts = [
        'issued_at' => 'datetime',
        'expires_at' => 'datetime',
        'revoked_at' => 'datetime',
    ];

    public function diningTable(): BelongsTo
    {
        return $this->belongsTo(DiningTable::class);
    }

    public function customerSessions(): HasMany
    {
        return $this->hasMany(CustomerSession::class);
    }
}
