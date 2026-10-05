<?php

namespace Modules\Chat\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ChatGroup extends Model
{
    use HasFactory;

    protected $fillable = [
        'chat_id',
        'type',
        'type_id',
        'parent_id',
        'status'
    ];

    function chat()
    {
        return $this->belongsTo(Chat::class);
    }

    protected static function newFactory()
    {
        return \Modules\Chat\Database\factories\ChatGroupFactory::new();
    }
}
