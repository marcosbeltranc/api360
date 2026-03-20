<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SystemFaq extends Model
{
    use SoftDeletes;

    protected $table = 'system_faqs';

    protected $fillable = [
        'system_id',
        'question',
        'answer',
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