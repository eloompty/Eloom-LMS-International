<?php

namespace Modules\Student\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StudentIntakeCourseFeeInstallmentPaymentNote extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_intake_course_fee_installment_payment_id',
        'notes',
        'status'
    ];
    
    protected static function newFactory()
    {
        return \Modules\Student\Database\factories\StudentIntakeCourseFeeInstallmentPaymentNoteFactory::new();
    }
}
