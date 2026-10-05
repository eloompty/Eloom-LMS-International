<?php

namespace Modules\Report\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\Student\Entities\Student;
use Modules\Student\Entities\StudentIntakeCourse;

class StudentRiskScoreTrend extends Model
{
    protected $table = 'student_risk_score_trends';

    protected $fillable = [
        'student_id',
        'student_intake_course_id',
        'overall_score',
        'risk_level',
        'attendance_score',
        'assignment_score',
        'grade_score',
        'fee_score',
        'engagement_score',
        'analyzed_at',
    ];

    protected $casts = [
        'analyzed_at' => 'date',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function studentIntakeCourse()
    {
        return $this->belongsTo(StudentIntakeCourse::class);
    }
}
