<?php

namespace Modules\Intake\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Course\Entities\Subject;

class IntakeSubject extends Model
{
    use HasFactory;

    protected $fillable = [
        'intake_course_id',
        'intake_semester_id',
        'subject_id',
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

    function subject() 
    {
        return $this->belongsTo(Subject::class);
    }

    function intakeUnit()
    {
        return $this->hasMany(IntakeUnit::class);
    }

    function intakeSubjectMarks()
    {
        return $this->hasMany(IntakeSubjectMark::class);
    }

    protected static function newFactory()
    {
        return \Modules\Intake\Database\factories\IntakeSubjectFactory::new();
    }
}
