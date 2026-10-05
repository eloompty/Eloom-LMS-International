<?php

namespace Modules\Report\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\Student\Entities\Student;
use Modules\Student\Entities\StudentIntakeCourse;

class StudentRiskScore extends Model
{
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
        'details',
        'analyzed_at',
    ];

    protected $casts = [
        'details' => 'array',
        'analyzed_at' => 'datetime',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function studentIntakeCourse()
    {
        return $this->belongsTo(StudentIntakeCourse::class);
    }

    public function getRiskLevelColorAttribute()
    {
        return [
            'low' => 'success',
            'medium' => 'warning',
            'high' => 'orange',
            'critical' => 'danger',
        ][$this->risk_level] ?? 'secondary';
    }

    public function getRiskLevelLabelAttribute()
    {
        return ucfirst($this->risk_level);
    }

    public function scopeAtRisk($query)
    {
        return $query->where('overall_score', '>=', 61);
    }

    public function scopeOfLevel($query, $level)
    {
        return $query->where('risk_level', $level);
    }
}
