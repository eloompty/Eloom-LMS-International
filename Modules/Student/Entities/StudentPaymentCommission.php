<?php

namespace Modules\Student\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Agent\Entities\Agent;

class StudentPaymentCommission extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_payment_id',
        'agent_id',
        'paid_date',
        'payment_mode',
        'receipt',
        'remarks',
        'status'
    ];

    function studentPayment()
    {
        return $this->belongsTo(StudentPayment::class);
    }

    function agent()
    {
        return $this->belongsTo(Agent::class);
    }

    protected static function newFactory()
    {
        return \Modules\Student\Database\factories\StudentPaymentCommissionFactory::new();
    }
}
