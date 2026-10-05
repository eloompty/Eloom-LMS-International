<?php

namespace Modules\Assignment\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Student\Entities\Student;

class AssignmentSubmission extends Model
{
    use HasFactory;

    protected $fillable = [
        'assignment_id',
        'student_id',
        'path',
        'assignment_grade_id',
        'remarks',
        'credits',
        'graded_date',
        'assignment_resubmission_id',
        'status',
    ];

    function assignment()
    {
        return $this->belongsTo(Assignment::class);
    }

    function student()
    {
        return $this->belongsTo(Student::class);
    }

    function assignmentGrade()
    {
        return $this->belongsTo(AssignmentGrade::class);
    }

    function assignmentSubmissionGrade()
    {
        return $this->hasOne(AssignmentSubmissionGrade::class);
    }

    protected static function newFactory()
    {
        return \Modules\Assignment\Database\factories\AssignmentSubmissionFactory::new();
    }
}
