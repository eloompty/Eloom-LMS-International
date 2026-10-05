<?php

namespace Modules\Assignment\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AssignmentSubmissionGrade extends Model
{
    use HasFactory;

    protected $fillable = [
        'assignment_submission_id',
        'path',
        'show_to_student',
        'status',
    ];
    
    protected static function newFactory()
    {
        return \Modules\Assignment\Database\factories\AssignmentSubmissionGradeFactory::new();
    }
}
