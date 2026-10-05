<?php

namespace Modules\Course\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_name',
        'details',
        'course_code',
        'cricos_code',
        'entry_requirements',
        'study_mode',
        'study_location',
        'work_placement',
        'hours_per_week',
        'holiday_breaks',
        'pathways',
        'reference_name',
        'delivery_mode',
        'internal',
        'predominant_delivery_mode',
        'duration',
        'study_period',
        'study_break',
        'hours',
        'fee',
        'fee_initial',
        'fee_installment',
        'onshore_fee',
        'onshore_initial',
        'onshore_installment',
        'enrollment_fee',
        'material_fee',
        'total_units',
        'registered',
        'status',
    ];

    function unit() {
        return $this->hasMany(Unit::class);
    }

    function deliverSite()
    {
        return $this->hasOne(CourseDeliverySite::class);
    }

    function semester()
    {
        return $this->hasMany(Semester::class);
    }
    
    protected static function newFactory()
    {
        return \Modules\Course\Database\factories\CourseFactory::new();
    }
}
