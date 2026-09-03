<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Integration extends Model
{
    use SoftDeletes;

    protected $table = 'integrations';

    protected $fillable = [
        'name',
        'description',
        'integration_type_id',
        'criticality_id',
        'status_id',
        'source_system_id',
        'destination_system_id',
        'responsible_id',
        'external_support_contact',
        'endpoint_url',
        'test_endpoint_url',
        'server_device_id',
        'authentication_method_id',
        'trigger_type_id',
        'frequency_detail',
        'repository_url',
        'technical_notes',
        'logs_location',
        'alerts_channel',
    ];

    protected $casts = [
        'technical_notes' => 'array',
        'created_at'      => 'datetime:Y-m-d H:i:s',
        'updated_at'      => 'datetime:Y-m-d H:i:s',
    ];

    public function server(): BelongsTo 
    { 
        return $this->belongsTo(ServerDevice::class, 'server_device_id'); 
    }

    public function sourceSystem(): BelongsTo 
    { 
        return $this->belongsTo(System::class, 'source_system_id'); 
    }

    public function destinationSystem(): BelongsTo 
    { 
        return $this->belongsTo(System::class, 'destination_system_id'); 
    }

    public function responsible(): BelongsTo 
    { 
        return $this->belongsTo(User::class, 'responsible_id'); 
    }

    // Relaciones con los catálogos usando OptionList
    public function integrationType(): BelongsTo 
    { 
        return $this->belongsTo(OptionList::class, 'integration_type_id'); 
    }

    public function criticality(): BelongsTo 
    { 
        return $this->belongsTo(OptionList::class, 'criticality_id'); 
    }

    public function status(): BelongsTo 
    { 
        return $this->belongsTo(OptionList::class, 'status_id'); 
    }

    public function authenticationMethod(): BelongsTo 
    { 
        return $this->belongsTo(OptionList::class, 'authentication_method_id'); 
    }

    public function triggerType(): BelongsTo 
    { 
        return $this->belongsTo(OptionList::class, 'trigger_type_id'); 
    }

    public function files()
    {
        return $this->hasMany(IntegrationFile::class);
    }
}