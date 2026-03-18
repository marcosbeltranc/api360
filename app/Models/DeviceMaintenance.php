<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DeviceMaintenance extends Model
{
    use SoftDeletes;

    protected $table = 'device_maintenances';

    protected $fillable = [
        'device_id',
        'device_type',
        'maintenance_type_id',
        'title',
        'responsible_id',
        'completion_date',
        'details',
        'validation_checklist'
    ];

    protected $casts = [
        'completion_date'      => 'date:Y-m-d',
        'validation_checklist' => 'array',
        'created_at'           => 'datetime:Y-m-d H:i:s',
    ];

    /**
     * Relación Polimórfica: Obtiene el modelo del dispositivo (Server, Nas, etc.)
     */
    public function device(): MorphTo
    {
        return $this->morphTo();
    }

    public function maintenanceType(): BelongsTo
    {
        return $this->belongsTo(OptionList::class, 'maintenance_type_id');
    }

    public function responsible(): BelongsTo
    {
        return $this->belongsTo(User::class, 'responsible_id');
    }
}