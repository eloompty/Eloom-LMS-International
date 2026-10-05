<?php

namespace Modules\Intake\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Course\Entities\Semester;

class IntakeSemester extends Model
{
    use HasFactory;

    protected $fillable = [
        'intake_course_id',
        'semester_id',
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

    function semester()
    {
        return $this->belongsTo(Semester::class);
    }

    function intakeSubject()
    {
        return $this->hasMany(IntakeSubject::class);
    }

    protected static function newFactory()
    {
        return \Modules\Intake\Database\factories\IntakeSemesterFactory::new();
    }
}
