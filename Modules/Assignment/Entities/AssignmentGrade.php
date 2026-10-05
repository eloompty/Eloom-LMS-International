<?php

namespace Modules\Assignment\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AssignmentGrade extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'status'];
    
    protected static function newFactory()
    {
        return \Modules\Assignment\Database\factories\AssignmentGradeFactory::new();
    }
}
