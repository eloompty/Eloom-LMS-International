<?php

namespace Modules\Agent\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AgentDevice extends Model
{
    use HasFactory;

    protected $fillable = [
        'agent_id',
        'device_type', // Android or iOS
        'device_name', // Name of device
        'device_token', // FCM token
        'status'
    ];

    function agent()
    {
        return $this->belongsTo(Agent::class);
    }
    
    protected static function newFactory()
    {
        return \Modules\Agent\Database\factories\AgentDeviceFactory::new();
    }
}
