<?php

namespace Modules\Student\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StudentPasswordReset extends Model
{
    use HasFactory;

    protected $fillable = ['email', 'code', 'status'];
    
    protected static function newFactory()
    {
        return \Modules\Student\Database\factories\StudentPasswordResetFactory::new();
    }
}
