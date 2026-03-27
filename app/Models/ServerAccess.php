<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class ServerAccess extends Model
{
    use SoftDeletes;

    protected $table = 'server_access';

    protected $fillable = [
        'name', 'description', 'access_id', 'access_ip', 'password', 'port', 'server_device_id'
    ];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
        'deleted_at' => 'datetime:Y-m-d H:i:s',
    ];

    protected $hidden = ['password'];

    public function makeVisibleIfAuthorized()
    {
        $user = request()->user();

        if ($user && in_array($user->level, [0, 2])) {
            $this->makeVisible(['password']);
        }

        return $this;
    }

    public function server()
    {
        return $this->belongsTo(ServerDevice::class, 'server_device_id');
    }
}
