<?php

namespace Modules\Scholarship\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Student\Entities\Student;
use Modules\Student\Entities\StudentIntakeCourseFee;

class FeeDiscount extends Model
{
    use HasFactory;

    protected $fillable = [
        'scholarship_application_id',
        'student_id',
        'student_intake_course_fee_id',
        'type',
        'value_type',
        'value',
        'discount_amount',
        'justification',
        'agent_id',
        'approved_by',
        'approved_at',
        'status',
    ];

    protected $casts = [
        'approved_at' => 'datetime',
    ];

    public function scholarshipApplication()
    {
        return $this->belongsTo(ScholarshipApplication::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function studentIntakeCourseFee()
    {
        return $this->belongsTo(StudentIntakeCourseFee::class);
    }

    public function getTypeLabelAttribute()
    {
        $labels = [
            'merit'             => 'Merit',
            'need_based'        => 'Need Based',
            'staff'             => 'Staff',
            'early_enrollment'  => 'Early Enrollment',
            'agent_negotiated'  => 'Agent Negotiated',
            'manual'            => 'Manual',
        ];
        return $labels[$this->type] ?? ucfirst($this->type);
    }
}
