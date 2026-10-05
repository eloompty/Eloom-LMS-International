<?php

namespace Modules\Student\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Intake\Entities\IntakeCourse;

class StudentPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'intake_course_id',
        'taxable_amount',
        'total_amount',
        'agent_commission_percent',
        'agent_commission_amount',
        'gst_percent',
        'gst',
        'gst_waiver',
        'paid_to_agent',
        'branch_commission_percent',
        'branch_commission_amount',
        'student_discount',
        'payment_type',
        'paid_amount',
        'remaining_amount',
        'paid_date',
        'received_date',
        'receipt',
        'comment',
        'status'
    ];

    function student()
    {
        return $this->belongsTo(Student::class);
    }

    function intakeCourse()
    {
        return $this->belongsTo(IntakeCourse::class);
    }

    function agentCommission()
    {
        return $this->hasOne(StudentPaymentCommission::class);
    }

    protected static function newFactory()
    {
        return \Modules\Student\Database\factories\StudentPaymentFactory::new();
    }
}
