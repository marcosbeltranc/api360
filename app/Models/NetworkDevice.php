<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NetworkDevice extends Model
{
    use SoftDeletes;

    protected $table = 'network_devices';

    protected $fillable = [
        'name', 'status_id', 'device_type_id', 'location_id',
        'sku', 'model', 'brand', 'serial_number', 'notes',
        'ip_address', 'last_maintenance', 'last_update'
    ];

    protected $casts = [
        'last_maintenance' => 'date:Y-m-d',
        'last_update'      => 'datetime:Y-m-d H:i:s',
        'created_at'       => 'datetime:Y-m-d H:i:s',
    ];

    public function status(): BelongsTo { return $this->belongsTo(OptionList::class, 'status_id'); }
    public function deviceType(): BelongsTo { return $this->belongsTo(OptionList::class, 'device_type_id'); }
    public function location(): BelongsTo { return $this->belongsTo(OptionList::class, 'location_id'); }
}