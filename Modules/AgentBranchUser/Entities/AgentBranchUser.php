<?php

namespace Modules\AgentBranchUser\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

use Modules\Agent\Entities\AgentBranch;

class AgentBranchUser extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'branch_id',
        'name',
        'email',
        'phone',
        'password',
        'image',
        'status'
    ];

    function branch()
    {
        return $this->belongsTo(AgentBranch::class);
    }

    protected static function newFactory()
    {
        return \Modules\AgentBranchUser\Database\factories\AgentBranchUserFactory::new();
    }
}
