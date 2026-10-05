<?php

namespace Modules\Log\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Log extends Model
{
    use HasFactory;

    protected $fillable = ['user_type', 'user_id', 'action', 'status'];
    
    protected static function newFactory()
    {
        return \Modules\Log\Database\factories\LogFactory::new();
    }
}
