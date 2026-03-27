<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ServerMetric extends Model
{
    use SoftDeletes;

    protected $table = 'device_metrics';

    protected $fillable = ['name', 'stats'];

    protected $casts = [
        'stats' => 'array',
    ];
}