<?php

namespace Modules\Student\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StudentDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'name',
        'path',
        'status'
    ];

    function student()
    {
        return $this->belongsTo(Student::class);
    }

    protected static function newFactory()
    {
        return \Modules\Student\Database\factories\StudentDocumentFactory::new();
    }
}
