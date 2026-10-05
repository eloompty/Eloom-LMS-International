<?php

namespace Modules\Course\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Subject extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id',
        'semester_id',
        'name',
        'code',
        'credits',
        'teaching_hours',
        'type',
        'full_marks',
        'theory',
        'practical',
        'internal',
        'status'
    ];

    function course()
    {
        return $this->belongsTo(Course::class);
    }

    function semester()
    {
        return $this->belongsTo(Semester::class);
    }

    function units()
    {
        return $this->hasMany(Unit::class);
    }

    protected static function newFactory()
    {
        return \Modules\Course\Database\factories\SubjectFactory::new();
    }
}
