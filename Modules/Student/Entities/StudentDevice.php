<?php

namespace Modules\Student\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StudentDevice extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'device_type', // Android or iOS
        'device_name', // Name of device
        'device_token', // FCM token
        'status'
    ];

    function student()
    {
        return $this->belongsTo(Student::class);
    }
    
    protected static function newFactory()
    {
        return \Modules\Student\Database\factories\StudentDeviceFactory::new();
    }
}
