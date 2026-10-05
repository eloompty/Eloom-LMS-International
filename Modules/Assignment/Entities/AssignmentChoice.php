<?php

namespace Modules\Assignment\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AssignmentChoice extends Model
{
    use HasFactory;

    protected $fillable = ['assignment_question_id', 'choice', 'is_correct', 'status'];

    function assignmentQuestion()
    {
        return $this->belongsTo(AssignmentQuestion::class);
    }
    
    protected static function newFactory()
    {
        return \Modules\Assignment\Database\factories\AssignmentChoiceFactory::new();
    }
}
