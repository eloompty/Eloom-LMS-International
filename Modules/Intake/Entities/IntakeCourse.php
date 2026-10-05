<?php

namespace Modules\Intake\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Course\Entities\Course;
use Modules\Student\Entities\StudentIntakeCourse;
use Modules\Trainer\Entities\Trainer;

class IntakeCourse extends Model
{
    use HasFactory;

    protected $fillable = [
        'intake_id',
        'course_id',
        'reference_name',
        'starting_date',
        'ending_date',
        'duration',
        'trainer_id',
        'marking_type',
        'study_mode',
        'study_location',
        'work_placement',
        'hours_per_week',
        'holiday_breaks',
        'entry_requirements',
        'status'
    ];

    function intake()
    {
        return $this->belongsTo(Intake::class);
    }

    function course()
    {
        return $this->belongsTo(Course::class);
    }

    function trainer()
    {
        return $this->belongsTo(Trainer::class);
    }

    function intakeSemester()
    {
        return $this->hasMany(IntakeSemester::class);
    }

    function intakeSubject()
    {
        return $this->hasMany(IntakeSubject::class);
    }

    function intakeUnit()
    {
        return $this->hasMany(IntakeUnit::class);
    }

    function studentIntake()
    {
        return $this->hasMany(StudentIntakeCourse::class);
    }

    protected static function newFactory()
    {
        return \Modules\Intake\Database\factories\IntakeCourseFactory::new();
    }
}
