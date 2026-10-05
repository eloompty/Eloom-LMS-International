<?php

namespace Modules\Student\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StudentIntakeCourseFeeInstallmentPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_intake_course_fee_installment_id',
        'taxable_amount',
        'total_amount',
        'student_discount',
        'payment_type',
        'paid_amount',
        'remaining_amount',
        'paid_date',
        'received_date',
        'receipt',
        'status'
    ];

    function studentIntakeCourseFeeInstallment()
    {
        return $this->belongsTo(StudentIntakeCourseFeeInstallment::class);
    }

    function paymentnotes()
    {
        return $this->hasMany(StudentIntakeCourseFeeInstallmentPaymentNote::class);
    }

    function paymentRefund()
    {
        return $this->hasOne(StudentIntakeCourseFeeInstallmentPaymentRefund::class);
    }

    function agentCommissionPayment()
    {
        return $this->hasOne(StudentIntakeCourseFeeInstallmentPaymentCommission::class);
    }

    protected static function newFactory()
    {
        return \Modules\Student\Database\factories\StudentIntakeCourseFeeInstallmentPaymentFactory::new();
    }
}
