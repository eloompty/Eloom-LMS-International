<?php

namespace Modules\Student\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StudentIntakeCourseFeeInstallmentPaymentCommission extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_intake_course_fee_installment_payment_id',
        'agent_id',
        'paid_date',
        'payment_mode',
        'receipt',
        'remarks',
        'status'
    ];

    protected static function newFactory()
    {
        return \Modules\Student\Database\factories\StudentIntakeCourseFeeInstallmentPaymentCommissionFactory::new();
    }
}
