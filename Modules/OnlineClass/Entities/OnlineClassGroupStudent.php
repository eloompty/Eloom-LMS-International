<?php

namespace Modules\OnlineClass\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Student\Entities\Student;

class OnlineClassGroupStudent extends Model
{
    use HasFactory;

    protected $fillable = ['online_class_group_id', 'student_id', 'status'];

    function group()
    {
        return $this->belongsTo(OnlineClassGroup::class, 'online_class_group_id', 'id');
    }

    function student()
    {
        return $this->belongsTo(Student::class, 'student_id', 'id');
    }
    
    protected static function newFactory()
    {
        return \Modules\OnlineClass\Database\factories\OnlineClassGroupStudentFactory::new();
    }
}
