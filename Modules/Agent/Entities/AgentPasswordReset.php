<?php

namespace Modules\Agent\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AgentPasswordReset extends Model
{
    use HasFactory;

    protected $fillable = ['email', 'code', 'status'];
    
    protected static function newFactory()
    {
        return \Modules\Agent\Database\factories\AgentPasswordResetFactory::new();
    }
}
