<?php

namespace Modules\Student\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StudentGuardian extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'name',
        'contact_no',
        'email',
        'occupation',
        'relation',
        'status'
    ];
    
    protected static function newFactory()
    {
        return \Modules\Student\Database\factories\StudentGuardianFactory::new();
    }
}
