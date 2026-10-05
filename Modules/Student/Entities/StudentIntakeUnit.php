<?php

namespace Modules\Student\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Intake\Entities\IntakeUnit;

class StudentIntakeUnit extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_intake_course_id',
        'student_intake_semester_id',
        'student_intake_subject_id',
        'intake_unit_id',
        'duration',
        'starting_date',
        'ending_date',
        'due_date',
        'sequence',
        'is_complete',
        'outcome',
        'status'
    ];

    function studentIntakeCourse()
    {
        return $this->belongsTo(StudentIntakeCourse::class);
    }

    function studentIntakeSemester()
    {
        return $this->belongsTo(StudentIntakeSemester::class);
    }

    function studentIntakeSubject()
    {
        return $this->belongsTo(StudentIntakeSubject::class);
    }

    function intakeUnit()
    {
        return $this->belongsTo(IntakeUnit::class);
    }

    protected static function newFactory()
    {
        return \Modules\Student\Database\factories\StudentIntakeUnitFactory::new();
    }
}
