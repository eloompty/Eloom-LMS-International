<?php

namespace Modules\Chat\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ChatAttendee extends Model
{
    use HasFactory;

    protected $fillable = [
        'chat_id',
        'user_id',
        'user_type',
        'is_owner',
        'status'
    ];

    function chat()
    {
        return $this->belongsTo(Chat::class);
    }

    protected static function newFactory()
    {
        return \Modules\Chat\Database\factories\ChatAttendeeFactory::new();
    }
}
