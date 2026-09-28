<?php

namespace App\Models;

use App\Modules\Tenant\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;

class MenuModifierGroup extends Model
{
    use BelongsToTenant;

    protected $table = 'menu_modifier_groups';

    public $timestamps = false;

    protected $fillable = [
        'menu_id',
        'modifier_group_id',
        'tenant_id',
        'sort_order',
    ];
}
