<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ModifierOption extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'tenant_id',
        'group_id',
        'name',
        'price_delta',
        'stock_qty',
        'is_available',
    ];

    protected $casts = [
        'is_available' => 'boolean',
    ];

    /** @return BelongsTo<Tenant, $this> */
    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    /** @return BelongsTo<ModifierGroup, $this> */
    public function group(): BelongsTo
    {
        return $this->belongsTo(ModifierGroup::class, 'group_id');
    }
}
