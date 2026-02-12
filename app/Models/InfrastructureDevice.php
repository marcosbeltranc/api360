<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InfrastructureDevice extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name', 'description', 'device_type_id', 'sub_type_id', 'location_id', 
        'status_id', 'cpu', 'ram', 'storage', 'ip_address', 'raid_type', 
        'folders_count', 'brand', 'model', 'last_maintenance', 'next_maintenance'
    ];

    protected $casts = [
        'last_maintenance' => 'date:Y-m-d',
        'next_maintenance' => 'date:Y-m-d',
        'folders_count' => 'integer',
    ];

    // Relaciones con la tabla option_lists
    public function deviceType(): BelongsTo { return $this->belongsTo(OptionList::class, 'device_type_id'); }
    public function subType(): BelongsTo { return $this->belongsTo(OptionList::class, 'sub_type_id'); }
    public function location(): BelongsTo { return $this->belongsTo(OptionList::class, 'location_id'); }
    public function status(): BelongsTo { return $this->belongsTo(OptionList::class, 'status_id'); }
}