<?php

namespace Modules\Assignment\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AssignmentSubmissionFile extends Model
{
    use HasFactory;

    protected $fillable = [
        'assignment_submission_id',
        'file',
        'graded_file',
        'show_to_student',
        'status'
    ];

    function assignmentSubmission()
    {
        return $this->belongsTo(AssignmentSubmission::class);
    }

    protected static function newFactory()
    {
        return \Modules\Assignment\Database\factories\AssignmentSubmissionFileFactory::new();
    }
}
