<?php

namespace Modules\Intake\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class IntakeCourseTime extends Model
{
    use HasFactory;

    protected $fillable = ['intake_course_id', 'day', 'from', 'to', 'classroom', 'status'];

    function intakeCourse()
    {
        return $this->belongsTo(IntakeCourse::class);
    }
    
    protected static function newFactory()
    {
        return \Modules\Intake\Database\factories\IntakeCourseTimeFactory::new();
    }
}
