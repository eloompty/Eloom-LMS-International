<?php

namespace Modules\Intake\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class IntakeUnitTime extends Model
{
    use HasFactory;

    protected $fillable = ['intake_course_time_id', 'intake_unit_id', 'day', 'from', 'to', 'classroom', 'status'];

    function intakeCourseTime()
    {
        return $this->belongsTo(intakeCourseTime::class);
    }

    function intakeUnit()
    {
        return $this->belongsTo(IntakeUnit::class);
    }
    
    protected static function newFactory()
    {
        return \Modules\Intake\Database\factories\IntakeUnitTimeFactory::new();
    }
}
