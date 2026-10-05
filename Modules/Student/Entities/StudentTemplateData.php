<?php

namespace Modules\Student\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StudentTemplateData extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_template_id',
        'key',
        'value',
        'status'
    ];

    function studentTemplate()
    {
        return $this->belongsTo(StudentTemplate::class);
    }

    protected static function newFactory()
    {
        return \Modules\Student\Database\factories\StudentTemplateDataFactory::new();
    }
}
