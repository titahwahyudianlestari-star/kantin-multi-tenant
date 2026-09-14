<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TenantBankAccount extends Model
{
    protected $fillable = [
        'tenant_id',
        'verified_by',
        'bank_name',
        'account_number_cipher',
        'account_number_last4',
        'account_name',
        'status',
        'is_primary',
    ];

    protected $casts = [
        'account_number_cipher' => 'encrypted',
        'is_primary' => 'boolean',
    ];

    /** @return BelongsTo<Tenant, $this> */
    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    /** @return BelongsTo<User, $this> */
    public function verifier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }
}
