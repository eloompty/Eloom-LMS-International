<?php

namespace Modules\Agent\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\AgentBranchUser\Entities\AgentBranchUser;
use Modules\Country\Entities\Country;

class AgentBranch extends Model
{
    use HasFactory;

    protected $fillable = [
        'agent_id',
        'name',
        'country_id',
        'city',
        'address',
        'phone',
        'rate',
        'status'
    ];

    function agent()
    {
        return $this->belongsTo(Agent::class);
    }

    function country()
    {
        return $this->belongsTo(Country::class);
    }

    function user()
    {
        return $this->hasMany(AgentBranchUser::class);
    }

    protected static function newFactory()
    {
        return \Modules\Agent\Database\factories\AgentBranchFactory::new();
    }
}
