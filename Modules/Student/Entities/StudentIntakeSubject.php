<?php

namespace Modules\Student\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Intake\Entities\IntakeSubject;

class StudentIntakeSubject extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_intake_course_id',
        'student_intake_semester_id',
        'intake_subject_id',
        'duration',
        'starting_date',
        'ending_date',
        'due_date',
        'sequence',
        'is_complete',
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

    function intakeSubject()
    {
        return $this->belongsTo(IntakeSubject::class);
    }

    function studentIntakeUnits()
    {
        return $this->hasMany(StudentIntakeUnit::class);
    }

    protected static function newFactory()
    {
        return \Modules\Student\Database\factories\StudentIntakeSubjectFactory::new();
    }
}
