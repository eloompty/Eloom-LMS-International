<?php

namespace Modules\Intake\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class IntakeTime extends Model
{
    use HasFactory;

    protected $fillable = [
        'intake_course_id',
        'intake_semester_id',
        'intake_subject_id',
        'intake_unit_id',
        'date',
        'day',
        'from',
        'to',
        'classroom',
        'status'
    ];

    function intakeCourse()
    {
        return $this->belongsTo(IntakeCourse::class);
    }

    function intakeSemester()
    {
        return $this->belongsTo(IntakeSemester::class);
    }

    function intakeSubject()
    {
        return $this->belongsTo(IntakeSubject::class);
    }

    function intakeUnit()
    {
        return $this->belongsTo(IntakeUnit::class);
    }

    protected static function newFactory()
    {
        return \Modules\Intake\Database\factories\IntakeTimeFactory::new();
    }
}
