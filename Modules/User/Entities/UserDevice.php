<?php

namespace Modules\User\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserDevice extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'device_type', // Android or iOS
        'device_name', // Name of device
        'device_token', // FCM token
        'status'
    ];

    function user()
    {
        return $this->belongsTo(User::class);
    }
    
    protected static function newFactory()
    {
        return \Modules\User\Database\factories\UserDeviceFactory::new();
    }
}
