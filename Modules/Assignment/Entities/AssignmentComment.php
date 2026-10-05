<?php

namespace Modules\Assignment\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Student\Entities\Student;
use Modules\Trainer\Entities\Trainer;

class AssignmentComment extends Model
{
    use HasFactory;

    protected $fillable = ['assignment_id', 'user_id', 'user_type','comment', 'status'];

    function assignment()
    {
        return $this->belongsTo(Assignment::class);
    }

    function trainer()
    {
        return $this->belongsTo(Trainer::class, 'user_id', 'id');
    }

    function student()
    {
        return $this->belongsTo(Student::class, 'user_id', 'id');
    }

    protected static function newFactory()
    {
        return \Modules\Assignment\Database\factories\AssignmentCommentFactory::new();
    }
}
