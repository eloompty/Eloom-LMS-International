<?php

namespace Modules\Attendance\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Intake\Entities\IntakeUnit;
use Modules\Intake\Entities\IntakeSubject;
use Modules\Trainer\Entities\Trainer;

class ClassSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'intake_time_id',
        'intake_unit_id',
        'intake_subject_id',
        'date',
        'starts_at',
        'ends_at',
        'title',
        'trainer_id',
        'status',
    ];

    public function intakeUnit()
    {
        return $this->belongsTo(IntakeUnit::class);
    }

    public function intakeSubject()
    {
        return $this->belongsTo(IntakeSubject::class);
    }

    public function trainer()
    {
        return $this->belongsTo(Trainer::class);
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    /**
     * Resolve the intake semester this session belongs to, via the subject or unit chain.
     * Feature 2's per-semester scholarship maintenance groups attendance by this.
     */
    public function intakeSemester()
    {
        if ($this->intake_subject_id && $this->intakeSubject) {
            return $this->intakeSubject->intakeSemester;
        }
        if ($this->intake_unit_id && $this->intakeUnit && $this->intakeUnit->intakeSubject) {
            return $this->intakeUnit->intakeSubject->intakeSemester;
        }
        return null;
    }
}
