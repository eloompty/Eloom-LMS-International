<?php

namespace Modules\Student\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StudentIntakeUnitMark extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_intake_unit_id',
        'name',
        'full_marks',
        'pass_marks',
        'obtain_marks',
        'user_type',
        'user_id',
        'status'
    ];

    function studentIntakeUnit()
    {
        return $this->belongsTo(StudentIntakeUnit::class);
    }
    
    protected static function newFactory()
    {
        return \Modules\Student\Database\factories\StudentIntakeUnitMarkFactory::new();
    }
}
