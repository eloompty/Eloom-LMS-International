<?php

namespace Modules\Email\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Agent\Entities\Agent;
use Modules\Student\Entities\Student;
use Modules\Trainer\Entities\Trainer;

class EmailUser extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', // Student Id / Trainer Id / Agent Id
        'user_type', // Student / Trainer / Agent
        'email_id',
        'email_template_id',
        'email_subject',
        'email_content',
        'email_regards_name',
        'email_regards_position',
        'type_id', // Intake Id / Course Id etc
        'type', // Intake / Course etc
        'sender_id', // Admin Id / Trainer Id
        'sender_type' // Admin / Trainer
    ];

    function student()
    {
        return $this->belongsTo(Student::class, 'user_id', 'id');
    }

    function trainer()
    {
        return $this->belongsTo(Trainer::class, 'user_id', 'id');
    }

    function agent()
    {
        return $this->belongsTo(Agent::class, 'user_id', 'id');
    }

    function email()
    {
        return $this->belongsTo(Email::class);
    }

    function emailTemplate()
    {
        return $this->belongsTo(EmailTemplate::class);
    }
    
    protected static function newFactory()
    {
        return \Modules\Email\Database\factories\EmailUserFactory::new();
    }
}
