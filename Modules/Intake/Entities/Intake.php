<?php

namespace Modules\Intake\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Intake extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'reference_name', 'orientation_date', 'starting_date', 'allow_submission_after_due_date', 'status'];

    function course()
    {
        return $this->hasMany(IntakeCourse::class);
    }

    function intakeCourses()
    {
        return $this->hasMany(IntakeCourse::class);
    }
    
    protected static function newFactory()
    {
        return \Modules\Intake\Database\factories\IntakeFactory::new();
    }
}
