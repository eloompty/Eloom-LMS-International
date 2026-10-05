<?php

namespace Modules\Assignment\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AssignmentResubmission extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'assignment_id',
        'user_id', // Admin or Trainer Id
        'user_type', // Admin or Trainer
        'approved_date',
        'remarks',
        'due_date',
        'status', // 0 => Requested, 1 => Approved, 2 => Rejected, 3 => Resubmitted
    ];

    function assignment()
    {
        return $this->belongsTo(Assignment::class);
    }

    protected static function newFactory()
    {
        return \Modules\Assignment\Database\factories\AssignmentResubmissionFactory::new();
    }
}
