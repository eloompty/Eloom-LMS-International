<?php

namespace Modules\Student\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Intake\Entities\IntakeCourse;

class StudentTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'intake_course_id',
        'template_name',
        'template_content',
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

    function studentTemplateData()
    {
        return $this->hasMany(StudentTemplateData::class);
    }

    protected static function newFactory()
    {
        return \Modules\Student\Database\factories\StudentTemplateFactory::new();
    }
}
