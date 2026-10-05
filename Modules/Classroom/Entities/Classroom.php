<?php

namespace Modules\Classroom\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Classroom extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'status'
    ];

    function classroomTrainer()
    {
        return $this->hasOne(ClassroomTrainer::class);
    }

    function classroomStudents()
    {
        return $this->hasMany(ClassroomStudent::class);
    }

    function classroomTimeTable()
    {
        return $this->hasMany(ClassroomTimeTable::class);
    }

    protected static function newFactory()
    {
        return \Modules\Classroom\Database\factories\ClassroomFactory::new();
    }
}
