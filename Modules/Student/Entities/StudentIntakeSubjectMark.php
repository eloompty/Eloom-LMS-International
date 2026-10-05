<?php

namespace Modules\Student\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StudentIntakeSubjectMark extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_intake_subject_id',
        'name',
        'full_marks',
        'pass_marks',
        'obtain_marks',
        'user_type',
        'user_id',
        'status'
    ];

    function studentIntakeSubject()
    {
        return $this->belongsTo(StudentIntakeSubject::class);
    }

    protected static function newFactory()
    {
        return \Modules\Student\Database\factories\StudentIntakeSubjectMarkFactory::new();
    }
}
