<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;


class OptionGroup extends Model
{
    use SoftDeletes;

    protected $table = 'option_groups';

    protected $fillable = ['name', 'code', 'description', 'is_active'];

    public function options(): HasMany 
    { 
        return $this->hasMany(OptionList::class, 'option_group_id'); 
    }
}
