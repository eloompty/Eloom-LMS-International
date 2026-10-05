<?php

namespace Modules\Trainer\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TrainerDevice extends Model
{
    use HasFactory;

    protected $fillable = [
        'trainer_id',
        'device_type', // Android or iOS
        'device_name', // Name of device
        'device_token', // FCM token
        'status'
    ];

    function trainer()
    {
        return $this->belongsTo(Trainer::class);
    }
    
    protected static function newFactory()
    {
        return \Modules\Trainer\Database\factories\TrainerDeviceFactory::new();
    }
}
