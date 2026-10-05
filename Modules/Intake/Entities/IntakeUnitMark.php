<?php

namespace Modules\Intake\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class IntakeUnitMark extends Model
{
    use HasFactory;

    protected $fillable = [
        'intake_unit_id',
        'name',
        'full_marks',
        'pass_marks',
        'user_type',
        'user_id',
        'status'
    ];

    function intakeUnit()
    {
        return $this->belongsTo(IntakeUnit::class);
    }
    
    protected static function newFactory()
    {
        return \Modules\Intake\Database\factories\IntakeUnitMarkFactory::new();
    }
}
