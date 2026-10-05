<?php

namespace Modules\Student\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StudentIntakeCourseCompetence extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_intake_course_id',
        'award_status',
        'certificate_type',
        'parchment_issue_date',
        'parchment_no',
        'status'
    ];
    
    protected static function newFactory()
    {
        return \Modules\Student\Database\factories\StudentIntakeCourseCompetenceFactory::new();
    }
}
