<?php

namespace Modules\AgentBranchUser\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AgentBranchUserPasswordReset extends Model
{
    use HasFactory;

    protected $fillable = ['email', 'code', 'status'];
    
    protected static function newFactory()
    {
        return \Modules\AgentBranchUser\Database\factories\AgentBranchUserPasswordResetFactory::new();
    }
}
