<?php

namespace Modules\Agent\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\AgentBranchUser\Entities\AgentBranchUser;

class AgentStudent extends Model
{
    use HasFactory;

    protected $fillable = [
        'agent_id',
        'branch_id',
        'user_id',
        'salutation',
        'first_name',
        'family_name',
        'date_of_birth',
        'passport_no',
        'citizenship',
        'phone',
        'mobile',
        'email',
        'image',
        'address',
        'emergency_contact_person',
        'emergency_contact_number',
        'emergency_contact_relation',
        'status'
    ];

    function agent()
    {
        return $this->belongsTo(Agent::class);
    }

    function branch()
    {
        return $this->belongsTo(AgentBranch::class);
    }

    function user()
    {
        return $this->belongsTo(AgentBranchUser::class);
    }

    protected static function newFactory()
    {
        return \Modules\Agent\Database\factories\AgentStudentFactory::new();
    }
}
