<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ModifierGroup extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'tenant_id',
        'min_select',
        'max_select',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /** @return BelongsTo<Tenant, $this> */
    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    /** @return HasMany<ModifierOption, $this> */
    public function options(): HasMany
    {
        return $this->hasMany(ModifierOption::class, 'group_id');
    }

    /** @return HasMany<MenuModifierGroup, $this> */
    public function menuModifierGroups(): HasMany
    {
        return $this->hasMany(MenuModifierGroup::class);
    }
}
