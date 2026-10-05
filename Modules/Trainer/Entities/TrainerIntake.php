<?php

namespace Modules\Trainer\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Intake\Entities\IntakeCourse;
use Modules\Intake\Entities\IntakeSemester;
use Modules\Intake\Entities\IntakeSubject;
use Modules\Intake\Entities\IntakeUnit;

class TrainerIntake extends Model
{
    use HasFactory;

    protected $fillable = [
        'trainer_id',
        'intake_course_id',
        'intake_semester_id',
        'intake_subject_id',
        'intake_unit_id',
        'duration',
        'starting_date',
        'sequence',
        'status'
    ];

    function trainer()
    {
        return $this->belongsTo(Trainer::class);
    }

    function intakeCourse()
    {
        return $this->belongsTo(IntakeCourse::class, 'intake_course_id', 'id');
    }

    function intakeSemester()
    {
        return $this->belongsTo(IntakeSemester::class, 'intake_semester_id', 'id');
    }

    function intakeSubject()
    {
        return $this->belongsTo(IntakeSubject::class, 'intake_subject_id', 'id');
    }

    function intakeUnit()
    {
        return $this->belongsTo(IntakeUnit::class, 'intake_unit_id', 'id');
    }
    
    protected static function newFactory()
    {
        return \Modules\Trainer\Database\factories\TrainerIntakeFactory::new();
    }
}
