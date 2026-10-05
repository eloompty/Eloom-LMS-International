<?php

namespace Modules\Scholarship\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Student\Entities\Student;
use Modules\Student\Entities\StudentIntakeCourseFee;
use Modules\Intake\Entities\IntakeCourse;

class ScholarshipApplication extends Model
{
    use HasFactory;

    protected $fillable = [
        'scholarship_id',
        'student_id',
        'intake_course_id',
        'student_intake_course_fee_id',
        'justification',
        'applied_by',
        'reviewed_by',
        'reviewed_at',
        'review_notes',
        'status',
    ];

    protected $casts = [
        'reviewed_at' => 'datetime',
    ];

    public function scholarship()
    {
        return $this->belongsTo(Scholarship::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function intakeCourse()
    {
        return $this->belongsTo(IntakeCourse::class);
    }

    public function studentIntakeCourseFee()
    {
        return $this->belongsTo(StudentIntakeCourseFee::class);
    }

    public function feeDiscount()
    {
        return $this->hasOne(FeeDiscount::class);
    }

    public function disbursements()
    {
        return $this->hasMany(ScholarshipDisbursement::class);
    }

    public function getStatusLabelAttribute()
    {
        return match($this->status) {
            0 => 'Pending',
            1 => 'Approved',
            2 => 'Rejected',
            3 => 'Revoked',
            default => 'Unknown',
        };
    }

    public function getStatusClassAttribute()
    {
        return match($this->status) {
            0 => 'warning',
            1 => 'success',
            2 => 'danger',
            3 => 'dark',
            default => 'secondary',
        };
    }
}
