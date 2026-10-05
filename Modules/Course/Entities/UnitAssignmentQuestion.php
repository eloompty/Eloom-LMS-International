<?php

namespace Modules\Course\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UnitAssignmentQuestion extends Model
{
    use HasFactory;

    protected $fillable = ['unit_assignment_id', 'question', 'status'];

    function unitAssignment()
    {
        return $this->belongsTo(UnitAssignment::class);
    }

    function unitAssignmentChoices()
    {
        return $this->hasMany(UnitAssignmentChoice::class);
    }
    
    protected static function newFactory()
    {
        return \Modules\Course\Database\factories\UnitAssignmentQuestionFactory::new();
    }
}
