<?php

namespace Modules\Chat\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ChatMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'chat_id',
        'chat_attendee_id',
        'message_type',
        'message',
        'status'
    ];

    function chat()
    {
        return $this->belongsTo(Chat::class);
    }

    function chatAttendee()
    {
        return $this->belongsTo(ChatAttendee::class);
    }

    function chatAttachments()
    {
        return $this->hasMany(ChatAttachment::class);
    }

    protected static function newFactory()
    {
        return \Modules\Chat\Database\factories\ChatMessageFactory::new();
    }
}
