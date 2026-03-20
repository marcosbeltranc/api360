<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

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
        'technical_notes' => 'array',
        'last_update' => 'datetime:Y-m-d',
        'created_at'  => 'datetime:Y-m-d H:i:s',
    ];

    public function server(): BelongsTo { return $this->belongsTo(ServerDevice::class, 'server_device_id'); }
    public function status(): BelongsTo { return $this->belongsTo(OptionList::class, 'status_id'); }
    public function priority(): BelongsTo { return $this->belongsTo(OptionList::class, 'priority_id'); }
    public function area(): BelongsTo { return $this->belongsTo(OptionList::class, 'area_id'); }
    public function responsible(): BelongsTo { return $this->belongsTo(User::class, 'responsible_id'); }
    public function areas() { return $this->belongsToMany( OptionList::class, 'system_area', 'system_id', 'area_id')->where('type', 'departments'); }
    public function faqs() { return $this->hasMany(SystemFaq::class)->orderBy('order'); }
    public function files() { return $this->hasMany(SystemFile::class); }
}