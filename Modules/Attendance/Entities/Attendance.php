<?php

namespace Modules\Attendance\Entities;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Intake\Entities\IntakeUnit;
use Modules\Intake\Entities\IntakeSubject;
use Modules\Student\Entities\Student;
use Modules\Trainer\Entities\Trainer;

class Attendance extends Model
{
    use HasFactory;

    // Semantic attendance_status values (distinct from `status`, the 0/1 row-active flag).
    const PRESENT = 'present';
    const ABSENT  = 'absent';
    const LATE    = 'late';
    const EXCUSED = 'excused';

    // Statuses that count as "attended" for risk scoring / gradebook rate.
    const ATTENDED_STATUSES = [self::PRESENT, self::LATE, self::EXCUSED];

    protected $fillable = [
        'student_id',
        'date',
        'intake_unit_id',
        'intake_subject_id',
        'class_session_id',
        'user_id',
        'user_type',
        'status',
        'attendance_status',
        'left_early_at',
        'remarks',
        'feedback',
        'feedback_visible_to_student',
        'marked_via',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function intakeUnit()
    {
        return $this->belongsTo(IntakeUnit::class);
    }

    public function intakeSubject()
    {
        return $this->belongsTo(IntakeSubject::class);
    }

    public function classSession()
    {
        return $this->belongsTo(ClassSession::class);
    }

    protected static function newFactory()
    {
        return \Modules\Attendance\Database\factories\AttendanceFactory::new();
    }
}
