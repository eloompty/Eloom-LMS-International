<?php

namespace Modules\Email\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EmailTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'subject',
        'content',
        'regards_name',
        'regards_position',
        'status'
    ];
    
    protected static function newFactory()
    {
        return \Modules\Email\Database\factories\EmailTemplateFactory::new();
    }
}
