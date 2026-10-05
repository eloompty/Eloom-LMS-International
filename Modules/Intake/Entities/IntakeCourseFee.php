<?php

namespace Modules\Intake\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class IntakeCourseFee extends Model
{
    use HasFactory;

    protected $fillable = [
        'intake_course_id',
        'name',
        'fee',
        'extra_fee',
        'installments',
        'due_date',
        'status'
    ];

    function intakeCourse()
    {
        return $this->belongsTo(IntakeCourse::class);
    }

    function fee_installments()
    {
        return $this->hasMany(IntakeCourseFeeInstallment::class)->where('parent_id', 0);
    }
    
    function intakeCourseFeeTypes()
    {
        return $this->hasMany(IntakeCourseFeeType::class);
    }

    protected static function newFactory()
    {
        return \Modules\Intake\Database\factories\IntakeCourseFeeFactory::new();
    }
}
