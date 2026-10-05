<?php

namespace Modules\Assignment\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AssignmentAnswer extends Model
{
    use HasFactory;

    protected $fillable = ['assignment_question_id', 'answer', 'assignment_submission_id', 'remarks', 'status'];

    function assignmentQuestion()
    {
        return $this->belongsTo(AssignmentQuestion::class);
    }

    function assignmentSubmission()
    {
        return $this->belongsTo(AssignmentSubmission::class);
    }
    
    protected static function newFactory()
    {
        return \Modules\Assignment\Database\factories\AssignmentAnswerFactory::new();
    }
}
