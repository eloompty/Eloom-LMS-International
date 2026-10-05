<?php

namespace Modules\Classroom\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Intake\Entities\IntakeUnit;

class ClassroomIntakeUnit extends Model
{
    use HasFactory;

    protected $fillable = [
        'classroom_id',
        'intake_unit_id',
        'status'
    ];

    function classroom()
    {
        return $this->belongsTo(Classroom::class);
    }

    function intakeUnit()
    {
        return $this->belongsTo(IntakeUnit::class);
    }
    
    protected static function newFactory()
    {
        return \Modules\Classroom\Database\factories\ClassroomIntakeUnitFactory::new();
    }
}
