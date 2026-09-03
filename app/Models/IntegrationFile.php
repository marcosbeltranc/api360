<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class IntegrationFile extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'integration_id',
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

    public function integration(): BelongsTo
    {
        return $this->belongsTo(Integration::class);
    }
}