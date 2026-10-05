<?php

namespace Modules\Gradebook\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\Student\Entities\Student;
use Modules\Student\Entities\StudentIntakeSubjectMark;
use Modules\Student\Entities\StudentIntakeUnitMark;
use Modules\Trainer\Entities\Trainer;

class GradeAppeal extends Model
{
    protected $fillable = [
        'student_id',
        'mark_type',
        'mark_id',
        'reason',
        'status',
        'trainer_response',
        'trainer_id',
        'admin_notes',
        'admin_user_id',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function trainer()
    {
        return $this->belongsTo(Trainer::class);
    }

    public function subjectMark()
    {
        return $this->belongsTo(StudentIntakeSubjectMark::class, 'mark_id');
    }

    public function unitMark()
    {
        return $this->belongsTo(StudentIntakeUnitMark::class, 'mark_id');
    }

    // Resolves the underlying mark record dynamically based on mark_type
    public function getMark()
    {
        if ($this->mark_type === 'subject') {
            return \Modules\Student\Entities\StudentIntakeSubjectMark::find($this->mark_id);
        }
        return \Modules\Student\Entities\StudentIntakeUnitMark::find($this->mark_id);
    }
}
