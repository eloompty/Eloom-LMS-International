<?php

namespace Modules\Student\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StudentIntakeCourseFeeInstallmentPaymentRefund extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_intake_course_fee_installment_payment_id',
        'refunded_amount',
        'comment',
        'receipt',
        'reinstate',
        'status'
    ];
    
    protected static function newFactory()
    {
        return \Modules\Student\Database\factories\StudentIntakeCourseFeeInstallmentPaymentRefundFactory::new();
    }
}
