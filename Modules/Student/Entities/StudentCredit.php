<?php

namespace Modules\Student\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Course\Entities\Unit;

class StudentCredit extends Model
{
    use HasFactory;

    protected $fillable = ['student_id' , 'unit_id', 'credits', 'status'];

    function student()
    {
        return $this->belongsTo(Student::class);
    }

    function unit()
    {
        return $this->belongsTo(Unit::class);
    }
    
    protected static function newFactory()
    {
        return \Modules\Student\Database\factories\StudentCreditFactory::new();
    }
}
