<?php

namespace Modules\Email\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Email extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'mailer',
        'host',
        'port',
        'username',
        'password',
        'encryption',
        'from_address',
        'from_name',
        'reply_to',
        'cc',
        'bcc',
        'status'
    ];

    protected static function newFactory()
    {
        return \Modules\Email\Database\factories\EmailFactory::new();
    }
}
