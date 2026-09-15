<?php

namespace App\Models;

use App\Modules\Tenant\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;

class MenuModifierGroup extends Model
{
    use BelongsToTenant;

    protected $table = 'menu_modifier_groups';

    protected $fillable = [
        'menu_id',
        'modifier_group_id',
    ];
}
