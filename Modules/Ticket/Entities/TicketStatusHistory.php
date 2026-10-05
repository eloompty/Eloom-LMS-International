<?php

namespace Modules\Ticket\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TicketStatusHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'ticket_id',
        'user_id',
        'user_type',
        'status'
    ];

    protected static function newFactory()
    {
        return \Modules\Ticket\Database\factories\TicketStatusHistoryFactory::new();
    }
}
