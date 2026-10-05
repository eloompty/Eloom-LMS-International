<?php

namespace Modules\Assignment\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AssignmentQuestion extends Model
{
    use HasFactory;

    protected $fillable = ['assignment_id', 'question', 'status'];

    function assignment()
    {
        return $this->belongsTo(Assignment::class);
    }

    function choice()
    {
        return $this->hasMany(AssignmentChoice::class);
    }

    function answer()
    {
        return $this->hasMany(AssignmentAnswer::class);
    }
    
    protected static function newFactory()
    {
        return \Modules\Assignment\Database\factories\AssignmentQuestionFactory::new();
    }
}
