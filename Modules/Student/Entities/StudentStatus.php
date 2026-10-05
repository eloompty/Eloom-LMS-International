<?php

namespace Modules\Student\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StudentStatus extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'status',
        'user_id',
        'user_type'
    ];
    
    protected static function newFactory()
    {
        return \Modules\Student\Database\factories\StudentStatusFactory::new();
    }
}
