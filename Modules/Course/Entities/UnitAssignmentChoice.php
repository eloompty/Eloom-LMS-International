<?php

namespace Modules\Course\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UnitAssignmentChoice extends Model
{
    use HasFactory;

    protected $fillable = ['unit_assignment_question_id', 'choice', 'is_correct', 'status'];

    function unitAssignmentQuestion()
    {
        return $this->belongsTo(UnitAssignmentQuestion::class);
    }
    
    protected static function newFactory()
    {
        return \Modules\Course\Database\factories\UnitAssignmentChoiceFactory::new();
    }
}
