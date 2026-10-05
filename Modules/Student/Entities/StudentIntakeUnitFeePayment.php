<?php

namespace Modules\Student\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StudentIntakeUnitFeePayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_intake_unit_fee_id',
        'fee_amount',
        'paid_date',
        'receipt',
        'remarks',
        'payment_type',
        'status'
    ];

    function studentIntakeUnitFee()
    {
        return $this->belongsTo(StudentIntakeCourseFee::class);
    }
    
    protected static function newFactory()
    {
        return \Modules\Student\Database\factories\StudentIntakeUnitFeePaymentFactory::new();
    }
}
