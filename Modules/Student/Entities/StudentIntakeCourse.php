<?php

namespace Modules\Student\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Intake\Entities\IntakeCourse;

class StudentIntakeCourse extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'intake_course_id',
        'starting_date',
        'ending_date',
        'duration',
        'study_mode',
        'study_location',
        'work_placement',
        'hours_per_week',
        'holiday_breaks',
        'entry_requirements',
        'is_enrolled',
        'status'
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    function intakeCourse()
    {
        return $this->belongsTo(IntakeCourse::class);
    }

    public function studentIntakeSemester()
    {
        return $this->hasMany(StudentIntakeSemester::class);
    }

    public function studentIntakeUnit()
    {
        return $this->hasMany(StudentIntakeUnit::class);
    }

    public function studentIntakeCourseCompetence()
    {
        return $this->hasOne(StudentIntakeCourseCompetence::class);
    }
    
    protected static function newFactory()
    {
        return \Modules\Student\Database\factories\StudentIntakeCourseFactory::new();
    }
}
