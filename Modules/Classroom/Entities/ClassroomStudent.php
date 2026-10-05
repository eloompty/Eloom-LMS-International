<?php

namespace Modules\Classroom\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Student\Entities\Student;

class ClassroomStudent extends Model
{
    use HasFactory;

    protected $fillable = [
        'classroom_id',
        'student_id',
        'status'
    ];

    function classroom()
    {
        return $this->belongsTo(Classroom::class);
    }

    function student()
    {
        return $this->belongsTo(Student::class);
    }

    protected static function newFactory()
    {
        return \Modules\Classroom\Database\factories\ClassroomStudentFactory::new();
    }
}
