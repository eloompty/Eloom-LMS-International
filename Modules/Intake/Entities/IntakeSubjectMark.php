<?php

namespace Modules\Intake\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class IntakeSubjectMark extends Model
{
    use HasFactory;

    protected $fillable = [
        'intake_subject_id',
        'name',
        'full_marks',
        'pass_marks',
        'user_type',
        'user_id',
        'status'
    ];

    function intakeSubject()
    {
        return $this->belongsTo(IntakeSubject::class);
    }

    protected static function newFactory()
    {
        return \Modules\Intake\Database\factories\IntakeSubjectMarkFactory::new();
    }
}
