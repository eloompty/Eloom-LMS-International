<?php

namespace Modules\Student\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Agent\Entities\Agent;
use Modules\Agent\Entities\AgentBranch;
use Modules\AgentBranchUser\Entities\AgentBranchUser;

class StudentAgent extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'agent_id',
        'branch_id',
        'user_id',
        'status'
    ];

    function student()
    {
        return $this->belongsTo(Student::class);
    }

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
        return \Modules\Student\Database\factories\StudentAgentFactory::new();
    }
}
