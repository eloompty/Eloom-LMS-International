<?php

namespace Modules\Ticket\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TicketAttachment extends Model
{
    use HasFactory;

    protected $fillable = [
        'ticket_id',
        'ticket_reply_id',
        'path'
    ];

    protected static function newFactory()
    {
        return \Modules\Ticket\Database\factories\TicketAttachmentFactory::new();
    }
}
