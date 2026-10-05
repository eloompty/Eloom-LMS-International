<?php

namespace Modules\Student\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StudentNote extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'notes',
        'user_id', // Admin Id
        'user_type', // Admin
        'status'
    ];

    protected static function newFactory()
    {
        return \Modules\Student\Database\factories\StudentNoteFactory::new();
    }
}
