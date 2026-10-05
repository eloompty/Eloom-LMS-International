<?php

namespace Modules\Student\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StudentIntakeCourseFeeType extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_intake_course_fee_id',
        'key',
        'value',
        'type',
        'status'
    ];

    function studentIntakeCourseFee()
    {
        return $this->belongsTo(StudentIntakeCourseFee::class);
    }

    protected static function newFactory()
    {
        return \Modules\Student\Database\factories\StudentIntakeCourseFeeTypeFactory::new();
    }
}
