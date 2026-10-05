<?php

namespace Modules\Course\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Semester extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id',
        'name',
        'credits',
        'status'
    ];

    function course()
    {
        return $this->belongsTo(Course::class);
    }

    function subjects()
    {
        return $this->hasMany(Subject::class);
    }

    function units()
    {
        return $this->hasMany(Unit::class);
    }
    
    protected static function newFactory()
    {
        return \Modules\Course\Database\factories\SemesterFactory::new();
    }
}
