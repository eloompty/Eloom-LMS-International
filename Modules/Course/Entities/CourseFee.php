<?php

namespace Modules\Course\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CourseFee extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id',
        'name',
        'fee',
        'installments',
        'status'
    ];

    function course()
    {
        return $this->belongsTo(Course::class);
    }

    function courseFeeType()
    {
        return $this->hasMany(CourseFeeType::class);
    }
    
    protected static function newFactory()
    {
        return \Modules\Course\Database\factories\CourseFeeFactory::new();
    }
}
