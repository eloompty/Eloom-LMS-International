<?php

namespace Modules\Ticket\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'subject',
        'description',
        'user_id',
        'user_type',
        'status'
    ];

    public function attachments()
    {
        return $this->hasMany(TicketAttachment::class);
    }

    public function replies()
    {
        return $this->hasMany(TicketReply::class);
    }

    public function statusHistories()
    {
        return $this->hasMany(TicketStatusHistory::class);
    }

    protected static function newFactory()
    {
        return \Modules\Ticket\Database\factories\TicketFactory::new();
    }
}
