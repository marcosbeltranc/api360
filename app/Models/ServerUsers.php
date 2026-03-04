<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ServerUsers extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'server_device_id',
        'name',
        'password',
        'description',
        'type',
    ];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
        'deleted_at' => 'datetime:Y-m-d H:i:s',
    ];

    public function server()
    {
        return $this->belongsTo(ServerDevice::class, 'server_device_id');
    }
}
