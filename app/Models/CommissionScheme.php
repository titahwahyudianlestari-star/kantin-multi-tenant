<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CommissionScheme extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'tenant_id',
        'commission_rate',
        'valid_from',
        'valid_to',
    ];

    protected $casts = [
        'commission_rate' => 'decimal:2',
        'valid_from' => 'date',
        'valid_to' => 'date',
    ];

    /**
     * @return BelongsTo<Tenant, $this>
     */
    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }
}
