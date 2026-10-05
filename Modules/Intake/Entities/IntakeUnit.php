<?php

namespace Modules\Intake\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Assignment\Entities\Assignment;
use Modules\Course\Entities\Unit;

class IntakeUnit extends Model
{
    use HasFactory;

    protected $fillable = [
        'intake_course_id',
        'intake_semester_id',
        'intake_subject_id',
        'unit_id',
        'starting_date',
        'ending_date',
        'due_date',
        'sequence',
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

    function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    function intakeUnitMarks()
    {
        return $this->hasMany(IntakeUnitMark::class);
    }
    
    protected static function newFactory()
    {
        return \Modules\Intake\Database\factories\IntakeUnitFactory::new();
    }
}
