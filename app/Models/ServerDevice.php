<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ServerDevice extends Model
{
    use SoftDeletes;

    protected $table = 'server_devices';

    protected $fillable = [
        'name', 'status_id', 'device_type_id', 'location_id', 'responsible_id', 'server_type_id',
        'sku', 'model', 'brand', 'serial_number', 'notes',
        'ip_address', 'secondary_ip', 'mac_address', 'network_card',
        'processor', 'ram', 'storage', 'gpu', 'raid_controller', 'power_supply',
        'os', 'last_maintenance', 'last_update', 'maintenance_notes'
    ];

    protected $casts = [
        'last_maintenance' => 'date:Y-m-d',
        'last_update'      => 'datetime:Y-m-d H:i:s',
        'created_at'       => 'datetime:Y-m-d H:i:s',
    ];

    public function status(): BelongsTo { return $this->belongsTo(OptionList::class, 'status_id'); }
    public function deviceType(): BelongsTo { return $this->belongsTo(OptionList::class, 'device_type_id'); }
    public function location(): BelongsTo { return $this->belongsTo(OptionList::class, 'location_id'); }
    public function responsible(): BelongsTo { return $this->belongsTo(User::class, 'responsible_id'); }
    public function serverType(): BelongsTo { return $this->belongsTo(OptionList::class, 'server_type_id'); }
    public function serverAccess(): HasMany 
    { 
        return $this->hasMany(ServerAccess::class, 'server_device_id'); 
    }
    public function serverUsers(): HasMany 
    { 
        return $this->hasMany(ServerUsers::class, 'server_device_id'); 
    }
    public function system(): HasMany
    {
        return $this->hasMany(System::class, 'server_device_id')
        ->with([
            'status',
        ]);
    }
}