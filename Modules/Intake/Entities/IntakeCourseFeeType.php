<?php

namespace Modules\Intake\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class IntakeCourseFeeType extends Model
{
    use HasFactory;

    protected $fillable = [
        'intake_course_fee_id',
        'key',
        'value',
        'type',
        'status'
    ];

    function intakeCourseFee()
    {
        return $this->belongsTo(intakeCourseFee::class);
    }
    
    protected static function newFactory()
    {
        return \Modules\Intake\Database\factories\IntakeCourseFeeTypeFactory::new();
    }
}
