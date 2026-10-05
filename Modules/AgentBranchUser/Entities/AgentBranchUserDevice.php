<?php

namespace Modules\AgentBranchUser\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AgentBranchUserDevice extends Model
{
    use HasFactory;

    protected $fillable = [
        'agent_branch_user_id',
        'device_type', // Android or iOS
        'device_name', // Name of device
        'device_token', // FCM token
        'status'
    ];

    function agent_branch_user()
    {
        return $this->belongsTo(AgentBranchUser::class);
    }
    
    protected static function newFactory()
    {
        return \Modules\AgentBranchUser\Database\factories\AgentBranchUserDeviceFactory::new();
    }
}
