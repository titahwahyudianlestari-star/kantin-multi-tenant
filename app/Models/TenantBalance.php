<?php

namespace App\Models;

use App\Modules\Tenant\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;

class TenantBalance extends Model
{
    use BelongsToTenant;

    protected $fillable = [
        'balance',
    ];

    protected $casts = [
        'balance' => 'decimal:2',
    ];
}
