<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SystemFile extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'system_id',
        'name',
        'file_path',
        'file_type',
        'size',
        'tags',
        'order',
        'is_active'
    ];

    protected $casts = [
        'tags' => 'array',
        'is_active' => 'boolean'
    ];

    public function system()
    {
        return $this->belongsTo(System::class);
    }
}