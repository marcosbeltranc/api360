<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class System extends Model
{
    use SoftDeletes;

    protected $table = 'system';

    protected $fillable = [
        'server_device_id', 'name', 'description', 'status_id', 'priority_id', 
        'area_id', 'responsible_id', 'api_doc_url', 'db_engine', 'db_name', 
        'db_host', 'db_port', 'repository_url', 'last_update', 'technical_notes', 'url'
    ];

    protected $casts = [
        'last_update' => 'datetime:Y-m-d',
        'created_at'  => 'datetime:Y-m-d H:i:s',
    ];

    public function server(): BelongsTo { return $this->belongsTo(ServerDevice::class, 'server_device_id'); }
    public function status(): BelongsTo { return $this->belongsTo(OptionList::class, 'status_id'); }
    public function priority(): BelongsTo { return $this->belongsTo(OptionList::class, 'priority_id'); }
    public function area(): BelongsTo { return $this->belongsTo(OptionList::class, 'area_id'); }
    public function responsible(): BelongsTo { return $this->belongsTo(User::class, 'responsible_id'); }
}