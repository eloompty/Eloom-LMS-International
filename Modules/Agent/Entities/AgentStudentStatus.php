<?php

namespace Modules\Agent\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AgentStudentStatus extends Model
{
    use HasFactory;

    protected $fillable = [
        'agent_student_id',
        'status'
    ];
    
    protected static function newFactory()
    {
        return \Modules\Agent\Database\factories\AgentStudentStatusFactory::new();
    }
}
