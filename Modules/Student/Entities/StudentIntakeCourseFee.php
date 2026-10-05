<?php

namespace Modules\Student\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Intake\Entities\IntakeCourse;

class StudentIntakeCourseFee extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'intake_course_id',
        'name',
        'fee',
        'extra_fee',
        'installments',
        'due_date',
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

    function fee_installments()
    {
        return $this->hasMany(StudentIntakeCourseFeeInstallment::class)->where('parent_id', 0)->orderBy('due_date');
    }

    function fee_types()
    {
        return $this->hasMany(StudentIntakeCourseFeeType::class);
    }

    public function feeDiscounts()
    {
        return $this->hasMany(\Modules\Scholarship\Entities\FeeDiscount::class)->where('status', 1);
    }

    protected static function newFactory()
    {
        return \Modules\Student\Database\factories\StudentIntakeCourseFeeFactory::new();
    }
}
