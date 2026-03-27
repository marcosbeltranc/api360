<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\ServerDevice;

class ServerAccessRequest extends Model
{
    protected $fillable = [
        'user_id',
        'server_id',
        'reason',
        'start_at',
        'end_at',
        'status',
    ];

    protected $casts = [
        'start_at' => 'datetime',
        'end_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function server()
    {
        return $this->belongsTo(ServerDevice::class);
    }
}
