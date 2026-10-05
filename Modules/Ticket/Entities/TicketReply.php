<?php

namespace Modules\Ticket\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Student\Entities\Student;
use Modules\User\Entities\User;

class TicketReply extends Model
{
    use HasFactory;

    protected $fillable = [
        'ticket_id',
        'user_id',
        'user_type',
        'message'
    ];

    function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    function attachments()
    {
        return $this->hasMany(TicketAttachment::class);
    }

    function student()
    {
        return $this->belongsTo(Student::class, 'user_id', 'id');
    }

    function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    protected static function newFactory()
    {
        return \Modules\Ticket\Database\factories\TicketReplyFactory::new();
    }
}
