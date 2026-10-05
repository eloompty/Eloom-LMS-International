<?php

namespace Modules\Student\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Intake\Entities\IntakeUnit;

class StudentIntakeUnitFee extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'intake_unit_id',
        'name',
        'fee',
        'due_date',
        'status'
    ];

    function intakeUnit()
    {
        return $this->belongsTo(IntakeUnit::class);
    }

    function student()
    {
        return $this->belongsTo(Student::class);
    }

    protected static function newFactory()
    {
        return \Modules\Student\Database\factories\StudentIntakeUnitFeeFactory::new();
    }
}

