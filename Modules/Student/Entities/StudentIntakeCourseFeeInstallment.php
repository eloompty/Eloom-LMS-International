<?php

namespace Modules\Student\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StudentIntakeCourseFeeInstallment extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_intake_course_fee_id',
        'name',
        'amount',
        'due_date',
        'parent_id',
        'status'
    ];

    function studentIntakeCourseFee()
    {
        return $this->belongsTo(StudentIntakeCourseFee::class);
    }

    function studentCourseFeeInstallments()
    {
        return $this->hasMany(StudentIntakeCourseFeeInstallment::class, 'parent_id', 'id');
    }

    function studentIntakeCourseFeePayment()
    {
        return $this->hasOne(StudentIntakeCourseFeeInstallmentPayment::class);
    }

    protected static function newFactory()
    {
        return \Modules\Student\Database\factories\StudentIntakeCourseFeeInstallmentFactory::new();
    }
}
