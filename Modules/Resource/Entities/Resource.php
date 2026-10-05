<?php

namespace Modules\Resource\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Course\Entities\Course;
use Modules\Course\Entities\Semester;
use Modules\Course\Entities\Subject;
use Modules\Course\Entities\Unit;
use Modules\Trainer\Entities\Trainer;
use Modules\User\Entities\User;

class Resource extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'resource_type',
        'path',
        'user_type',
        'resource_category_id',
        'course_id',
        'semester_id',
        'subject_id',
        'unit_id',
        'uploaded_by',
        'uploaded_user_id',
        'status',
    ];

    function category()
    {
        return $this->belongsTo(ResourceCategory::class, 'resource_category_id', 'id');
    }

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

    function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'uploaded_user_id', 'id');
    }

    function trainer()
    {
        return $this->belongsTo(Trainer::class, 'uploaded_user_id', 'id');
    }
    
    protected static function newFactory()
    {
        return \Modules\Resource\Database\factories\ResourceFactory::new();
    }
}
