<?php

namespace Modules\Chat\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Chat extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'type',
        'image',
        'status'
    ];

    function chatMessages()
    {
        return $this->hasMany(ChatMessage::class);
    }

    function chatAttendees()
    {
        return $this->hasMany(ChatAttendee::class);
    }

    function chatGroup()
    {
        return $this->hasOne(ChatGroup::class);
    }

    protected static function newFactory()
    {
        return \Modules\Chat\Database\factories\ChatFactory::new();
    }
}
