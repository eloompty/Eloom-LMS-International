<?php

namespace Modules\Student\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Intake\Entities\IntakeSemester;

class StudentIntakeSemester extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_intake_course_id',
        'intake_semester_id',
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

    function intakeSemester()
    {
        return $this->belongsTo(IntakeSemester::class, 'intake_semester_id', 'id');
    }

    function studnetIntakeSubject()
    {
        return $this->hasMany(StudentIntakeSubject::class);
    }

    protected static function newFactory()
    {
        return \Modules\Student\Database\factories\StudentIntakeSemesterFactory::new();
    }
}
