<?php

namespace Modules\Chat\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ChatAttachment extends Model
{
    use HasFactory;

    protected $fillable = [
        'chat_message_id',
        'type',
        'path',
        'is_public',
        'status'
    ];

    function chatMessage()
    {
        return $this->belongsTo(ChatMessage::class);
    }

    protected static function newFactory()
    {
        return \Modules\Chat\Database\factories\ChatAttachmentFactory::new();
    }
}
