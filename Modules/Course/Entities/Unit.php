<?php

namespace Modules\Course\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Unit extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'type',
        'education_field',
        'hours',
        'duration',
        'due_date',
        'course_id',
        'semester_id',
        'subject_id',
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

    function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    function unitFees()
    {
        return $this->hasMany(UnitFee::class);
    }
    
    protected static function newFactory()
    {
        return \Modules\Course\Database\factories\UnitFactory::new();
    }
}
