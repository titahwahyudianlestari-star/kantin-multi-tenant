<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DiningTable extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'canteen_id',
        'code',
        'label',
        'zone',
        'status',
    ];

    /**
     * @return BelongsTo<Canteen, $this>
     */
    public function canteen(): BelongsTo
    {
        return $this->belongsTo(Canteen::class);
    }

    /**
     * @return HasMany<TableQrToken, $this>
     */
    public function qrTokens(): HasMany
    {
        return $this->hasMany(TableQrToken::class);
    }

    /**
     * @return HasMany<CustomerSession, $this>
     */
    public function customerSessions(): HasMany
    {
        return $this->hasMany(CustomerSession::class);
    }
}
