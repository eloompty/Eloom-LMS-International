<?php

namespace Modules\Student\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StudentIntakeUnitFeePaymentStripe extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_intake_unit_fee_payment_id',
        'stripe_id',
        'status'
    ];
    
    function studentIntakeUnitFeePayment()
    {
        return $this->belongsTo(StudentIntakeUnitFeePayment::class);
    }

    protected static function newFactory()
    {
        return \Modules\Student\Database\factories\StudentIntakeUnitFeePaymentStripeFactory::new();
    }
}
