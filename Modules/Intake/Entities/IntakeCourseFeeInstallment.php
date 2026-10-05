<?php

namespace Modules\Intake\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class IntakeCourseFeeInstallment extends Model
{
    use HasFactory;

    protected $fillable = [
        'intake_course_fee_id',
        'name',
        'amount',
        'due_date',
        'parent_id',
        'status'
    ];

    function intakeCourseFee()
    {
        return $this->belongsTo(IntakeCourseFee::class, 'intake_course_fee_id', 'id');
    }

    function intakeCourseFeeInstallment()
    {
        return $this->belongsTo(IntakeCourseFeeInstallment::class, 'parent_id', 'id');
    }

    function intakeCourseFeeInstallments()
    {
        return $this->hasMany(IntakeCourseFeeInstallment::class, 'parent_id', 'id');
    }

    protected static function newFactory()
    {
        return \Modules\Intake\Database\factories\IntakeCourseFeeInstallmentFactory::new();
    }
}
