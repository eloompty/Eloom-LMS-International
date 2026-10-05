<?php

namespace Modules\Assignment\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Intake\Entities\IntakeSubject;
use Modules\Intake\Entities\IntakeUnit;
use Modules\Trainer\Entities\Trainer;
use Modules\User\Entities\User;

class Assignment extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'unit_assignment_id',
        'intake_subject_id',
        'intake_unit_id',
        'trainer_id',
        'type',
        'path',
        'due_date',
        'uploaded_by',
        'uploaded_user_id',
        'status',
    ];

    function intakeSubject()
    {
        return $this->belongsTo(IntakeSubject::class);
    }

    function intakeUnit()
    {
        return $this->belongsTo(IntakeUnit::class);
    }

    function trainer()
    {
        return $this->belongsTo(Trainer::class);
    }

    function uploadedUser()
    {
        return $this->belongsTo(User::class, 'uploaded_user_id', 'id');
    }

    public function uploadedTrainer()
    {
        return $this->belongsTo(Trainer::class, 'uploaded_user_id', 'id');
    }

    public function submission()
    {
        return $this->hasMany(AssignmentSubmission::class);
    }

    function comment()
    {
        return $this->hasMany(AssignmentComment::class);
    }

    protected static function newFactory()
    {
        return \Modules\Assignment\Database\factories\AssignmentFactory::new();
    }
}
