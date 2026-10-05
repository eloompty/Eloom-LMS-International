<?php

namespace Modules\Notification\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Student\Entities\StudentIntakeUnit;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_type',
        'user_id',
        'sender_type',
        'sender_id',
        'title',
        'body',
        'type',
        'link',
        'status',
    ];
    
    // Resolves the correct assignment URL regardless of whether the notification
    // was created for a unit assignment (StudentIntakeUnit) or subject assignment (StudentIntakeSubject).
    public function getAssignmentUrlAttribute(): string
    {
        if ($this->type !== 'Assignment') {
            return '#';
        }
        return StudentIntakeUnit::find($this->link)
            ? route('student.assignment.index', $this->link)
            : route('student.subject.assignment.index', $this->link);
    }

    protected static function newFactory()
    {
        return \Modules\Notification\Database\factories\NotificationFactory::new();
    }
}
